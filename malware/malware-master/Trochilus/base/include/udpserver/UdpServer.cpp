#include "stdafx.h"
#include <Winsock2.h>
#include "UdpServer.h"

#pragma comment(lib, "ws2_32.lib")

#define UDP_SOCKET_IOCP_KEY 10
#define EXIT_IOCP_KEY		0

UdpServer::UdpServer()
: m_bRunning(FALSE)
, m_hIocp(NULL)
, m_udpSocket(INVALID_SOCKET)
, m_fnUdpMsgHandler(NULL)
, m_lpParameter(NULL)
{

}

UdpServer::~UdpServer()
{
	if (! m_bRunning) return;
	Stop();
}

void UdpServer::Reset()
{
	m_bRunning = FALSE;
	m_hIocp = NULL;
	m_udpSocket = INVALID_SOCKET;
	m_iocpDataVector.clear();
	m_threadHandleVector.clear();
}

void UdpServer::Init(FnUdpMsgHandler fnUdpMsgHandler, LPVOID lpParameter)
{
	m_fnUdpMsgHandler = fnUdpMsgHandler;
	m_lpParameter = lpParameter;
}

BOOL UdpServer::Start(USHORT listenPort, DWORD dwWorkerThreadNumCpuMultipe)
{
	if (m_bRunning) return FALSE;

	::InterlockedExchange(&m_bRunning, TRUE);

	BOOL bSuccess = FALSE;
	do 
	{
		//创建IO完成端口
		m_hIocp = ::CreateIoCompletionPort(INVALID_HANDLE_VALUE, NULL, NULL, 0);
		if (! m_hIocp)
		{
			errorLogE(_T("create iocp failed for udpserver."));
			break;
		}

		//初始化udp socket
// 		WSADATA wsaData;
// 		if (::WSAStartup(MAKEWORD(2, 2), &wsaData) != 0)
// 		{
// 			errorLog(_T("WSAStartup failed. WE%d\r\n"), WSAGetLastError());
// 			break;
// 		}

		m_udpSocket = ::socket(AF_INET, SOCK_DGRAM, 0);
		if (SOCKET_ERROR == m_udpSocket)
		{
			errorLog(_T("create udp socket failed. WE:%d"), WSAGetLastError());
			break;
		}

		//绑定
		sockaddr_in localAddr = {0};
		localAddr.sin_family = AF_INET;
		localAddr.sin_addr.S_un.S_addr = INADDR_ANY;
		localAddr.sin_port = htons(listenPort);
		if (SOCKET_ERROR == ::bind(m_udpSocket, (sockaddr*)&localAddr, sizeof(sockaddr)))
		{
			errorLog(_T("bind udp failed. WE%d"), WSAGetLastError());
			break;
		}

		//和io完成端口绑定
		if(NULL == ::CreateIoCompletionPort((HANDLE)m_udpSocket, m_hIocp, UDP_SOCKET_IOCP_KEY, 0))
		{
			errorLog(_T("bind io and udp failed WE:%d"), WSAGetLastError());
			break;
		}

		//获取CPU个数
		SYSTEM_INFO sysInfo = {0};
		::GetSystemInfo(&sysInfo);	

		//工作者线程个数
		int dwNumberOfWorkThreads = (int)sysInfo.dwNumberOfProcessors * dwWorkerThreadNumCpuMultipe;

		//创建工作者线程
		for (int i = 0; i < dwNumberOfWorkThreads; i++)
		{
			HANDLE hThread = ::CreateThread(NULL, 0, UdpServer::WorkerThread, this, 0, NULL);
			if (! hThread)
			{
				errorLogE(_T("create udp worker thread failed."));
				dwNumberOfWorkThreads--;
			}
			else
			{
				m_threadHandleVector.push_back(hThread);
			}
		}
		if (dwNumberOfWorkThreads <= 0) 
		{
			errorLog(_T("create thread failed for udpserver. no threads is created"));
			break;
		}

		//pending
		for (int i = 0; i < dwNumberOfWorkThreads; i++)
		{
			PIOCP_DATA pIocpData = new IOCP_DATA;		
			m_iocpDataVector.push_back(pIocpData);

			if (::WSARecvFrom(m_udpSocket,
				&pIocpData->wsaBuf,
				1,
				NULL,
				&pIocpData->flags,
				(sockaddr*)&pIocpData->remoteAddr,
				&pIocpData->remoteAddrLen,
				(LPWSAOVERLAPPED)pIocpData,
				NULL) == SOCKET_ERROR)
			{
				int wsale = WSAGetLastError();
				if (wsale != WSA_IO_PENDING)
				{
					errorLog(_T("start udpserver failed:WSARecvFrom IO pending failed WE:%d"), wsale);
				}
			}
		}

		bSuccess = TRUE;
	} while (FALSE);

	if (! bSuccess)
	{
		if (NULL != m_hIocp) 
		{
			::CloseHandle(m_hIocp);
			m_hIocp = NULL;
		}

		if (INVALID_SOCKET != m_udpSocket)
		{
			::closesocket(m_udpSocket);
			m_udpSocket = INVALID_SOCKET;
		}
	}

	return bSuccess;
}

void UdpServer::Stop()
{
	if (! m_bRunning) return;

	for (int i = 0; i < (int)m_threadHandleVector.size(); i++)
	{
		if (::PostQueuedCompletionStatus(m_hIocp, 0, EXIT_IOCP_KEY, NULL) == NULL)
		{
			errorLogE(_T("Post for stop IOCP failure."));
		}
	}

	::WaitForMultipleObjects((DWORD)m_threadHandleVector.size(), &m_threadHandleVector[0], TRUE, INFINITE);

	::closesocket(m_udpSocket);
	m_udpSocket = INVALID_SOCKET;
	CloseHandle(m_hIocp);

	IocpDataVector::iterator iter = m_iocpDataVector.begin();
	for (; iter != m_iocpDataVector.end(); iter++)
	{
		delete *iter;
	}
	
	//WSACleanup();

	Reset();
}

DWORD WINAPI UdpServer::WorkerThread(LPVOID lpParameter)
{
	UdpServer* pServer = (UdpServer*) lpParameter;
	pServer->WorkerProc();
	return 0;
}

void UdpServer::WorkerProc()
{
	debugLog(_T("udpserver worker thread begin working..."));

	while (TRUE)
	{
		DWORD dwNumOfBytesRecv = 0;
		LPOVERLAPPED lpOverlapped = NULL;
		ULONG_PTR iocpKey = 0;

		BOOL bGetOK = ::GetQueuedCompletionStatus(m_hIocp, &dwNumOfBytesRecv, &iocpKey, &lpOverlapped, INFINITE);
		if (NULL == lpOverlapped)
		{
			debugLog(_T("overlapped is null. exit worker thread."));
			break;
		}
		PIOCP_DATA pIocpData = (PIOCP_DATA) lpOverlapped;

		if (! bGetOK)
		{
			errorLogE(_T("Get iocp queued failed for udpserver."));
		}
		else
		{
			pIocpData->dataLength = dwNumOfBytesRecv;
			if (UDP_SOCKET_IOCP_KEY == iocpKey)
			{
				HandleMsg(pIocpData);
			}
			else if (EXIT_IOCP_KEY == iocpKey)
			{
				infoLog(_T("recv exitiocpkey. exit thread"));
				break;
			}
		}

		//pending
		pIocpData->Reset();
		if (::WSARecvFrom(
			m_udpSocket,
			&pIocpData->wsaBuf,
			1,
			NULL,
			&pIocpData->flags,
			(sockaddr*)&pIocpData->remoteAddr,
			&pIocpData->remoteAddrLen,
			(LPWSAOVERLAPPED)pIocpData,
			NULL) == SOCKET_ERROR)
		{
			int wela = WSAGetLastError();
			if (wela != WSA_IO_PENDING)
			{
				errorLog(_T("worker thread for udpserver WSARecvFrom failed. WE:%d\r\n"), wela);
			}
		}
	}

	debugLog(_T("udpserver worker thread end working."));
}

void UdpServer::HandleMsg(const PIOCP_DATA pIocpData)
{
	if (NULL != m_fnUdpMsgHandler)
	{
		m_fnUdpMsgHandler(pIocpData->remoteAddr, m_udpSocket, (const LPBYTE)pIocpData->data, pIocpData->dataLength, m_lpParameter);

		//todel
#ifdef DEBUG_TEST_TEST
		udpmsg.addr.sin_addr.S_un.S_addr = *(ULONG*)pIocpData->data;
#endif
	}
// 	InfoLog(_T("recv msg from [%s][%s]\r\n"), 
// 		(LPCTSTR)CString(inet_ntoa(pIocpData->remoteAddr.sin_addr)),
// 		CString(pIocpData->data));
}
