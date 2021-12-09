#include "stdafx.h"
#include "TcpServer.h"

#pragma comment(lib, "ws2_32.lib")

TcpServer::TcpServer()
: m_bRunning(FALSE)
, m_hServerThread(NULL)
, m_hTimeCheckThread(NULL)
, m_hCompletionPort(NULL)
, m_dwSessionDataSize(0)
, m_sServer(INVALID_SOCKET)
, m_hExitEvent(NULL)
, m_dwSilenceTimeoutS(10)
, m_fnCallback(NULL)
, m_lpParameter(NULL)
, m_fnDisconnectNotify(NULL)
, m_lpDisconnectNotifyParameter(NULL)
, m_bIPSetAvailable(FALSE)
, m_bIsBlackIPSet(FALSE)
, m_dwMaxConnectionPerIP(INFINITE)
{
	::InitializeCriticalSection(&m_dataSection);
}

TcpServer::~TcpServer()
{
	::DeleteCriticalSection(&m_dataSection);

	if (m_hExitEvent != NULL)
	{
		::CloseHandle(m_hExitEvent);
		m_hExitEvent = NULL;
	}
}

void TcpServer::Init(FnTcpMsgHandler fnCallback, LPVOID lpParameter)
{
	m_fnCallback = fnCallback;
	m_lpParameter = lpParameter;
}

void TcpServer::SetBlackIPNameSet( const SingleIPOrNameSet& ipnameSet )
{
	SetIPOrNameForList(ipnameSet);
	m_bIsBlackIPSet = TRUE;
	m_bIPSetAvailable = TRUE;
}

void TcpServer::SetWhiteIPNameSet( const SingleIPOrNameSet& ipnameSet )
{
	SetIPOrNameForList(ipnameSet);
	m_bIsBlackIPSet = FALSE;
	m_bIPSetAvailable = TRUE;
}

void TcpServer::SetIPOrNameForList(const SingleIPOrNameSet& ipSet)
{
	m_ipSet.clear();
	m_nameSet.clear();

	SingleIPOrNameSet::const_iterator iter = ipSet.begin();
	for (; iter != ipSet.end(); iter++)
	{
		const CString& iporname = *iter;

		if (iporname.GetLength() > 0)
		{
			TCHAR firstChar = iporname[0];
			if (firstChar >= '0' && firstChar <= '9')
			{
				m_ipSet.insert(iporname);
			}
			else
			{
				m_nameSet.insert(iporname);
			}
		}
	}
}

void TcpServer::DisableBlackOrWhiteIPSet()
{
	m_bIPSetAvailable = FALSE;
}

void TcpServer::SetDisconnectNotify( FnTcpDisconnectNotify fnDisconnectNotify, LPVOID lpParameter )
{
	m_fnDisconnectNotify = fnDisconnectNotify;
	m_lpDisconnectNotifyParameter = lpParameter;
}

void TcpServer::SetMaxConnectionPerIP(DWORD dwMaxConnection)
{
	m_dwMaxConnectionPerIP = dwMaxConnection;
}

BOOL TcpServer::StartListening( UINT port, DWORD dwSessionDataSize, DWORD dwSilenceTimeoutS, DWORD dwWorkerThreadNumCpuMultipe )
{
	if (m_bRunning) return FALSE;

	m_dwWorkerThreadNumCpuMultipe = dwWorkerThreadNumCpuMultipe;

	if (m_hExitEvent == NULL)
	{
		m_hExitEvent = ::CreateEvent(NULL, FALSE, FALSE, NULL);
		if (m_hExitEvent == NULL)
		{
			errorLogE(_T("create exitevent for tcpserver failed."));
			return FALSE;
		}
	}
	::ResetEvent(m_hExitEvent);

	m_bRunning = TRUE;

	m_port = port;
	m_dwSilenceTimeoutS = dwSilenceTimeoutS;
	m_dwSessionDataSize = dwSessionDataSize;


	// 创建套接字
	m_sServer = ::socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
	if (INVALID_SOCKET == m_sServer)
	{
		errorLog(_T("create listen socket failed. WE:%u"), ::WSAGetLastError());
		m_bRunning = FALSE;
		goto END;
	}

	// 绑定套接字
	SOCKADDR_IN local;
	local.sin_addr.S_un.S_addr = htonl(INADDR_ANY);
	local.sin_family = AF_INET;
	local.sin_port = htons(m_port);
	if (0 != ::bind(m_sServer, (struct sockaddr *)&local, sizeof(SOCKADDR_IN)))
	{
		errorLog(_T("bind listen port failed. WE:%u"), ::WSAGetLastError());
		m_bRunning = FALSE;
		goto END;
	}

	// 开始监听
	if (0 != ::listen(m_sServer, SOMAXCONN))
	{
		errorLog(_T("start listening failed. WE%u"), ::WSAGetLastError());
		m_bRunning = FALSE;
		goto END;
	}

	m_hServerThread = CreateThread(NULL, 0, ServerThread, this, 0, NULL);
	if (NULL == m_hServerThread)
	{
		m_bRunning = FALSE;
	}

//	m_hTimeCheckThread = CreateThread(NULL, 0, TimeoutCheckThread, this, 0, NULL);
END:
	return m_bRunning;
}

void TcpServer::Stop()
{
	if (! m_bRunning) return;

	m_bRunning = FALSE;
	::SetEvent(m_hExitEvent);
	
	if (m_workerThreadList.size() > 0)
	{
		for (DWORD i = 0; i < (int)m_workerThreadList.size(); i++)
		{
			::PostQueuedCompletionStatus(m_hCompletionPort, 0xFFFFFFFF, 0, NULL); 
		}

		::WaitForMultipleObjects((DWORD)m_workerThreadList.size(), &m_workerThreadList[0], TRUE, INFINITE);
		m_workerThreadList.clear();
	}

	::EnterCriticalSection(&m_dataSection);
	{
		ClientSocketList::iterator it = m_clientSocketList.begin();
		for (; it != m_clientSocketList.end();it++)
		{
			closesocket(*it);
		}
		m_clientSocketList.clear();
	}
	::LeaveCriticalSection(&m_dataSection);

	::closesocket(m_sServer);
	m_sServer = INVALID_SOCKET;

	::WaitForSingleObject(m_hServerThread, INFINITE);
	m_hServerThread = NULL;

	::WaitForSingleObject(m_hTimeCheckThread, INFINITE);
	m_hTimeCheckThread = NULL;
}

DWORD WINAPI TcpServer::ServerThread( LPVOID lpParameter )
{
	TcpServer* pServer = (TcpServer*) lpParameter;
	pServer->ServerProc();
	return 0;
}

void TcpServer::ServerProc()
{
	debugLog(_T("tcpserver thread start."));
	// Initialize Windows Socket library
	//WSADATA wsaData = {0};
	//::WSAStartup(MAKEWORD(2, 2), &wsaData);

	do 
	{
		// 初始化完成端口
		m_hCompletionPort = ::CreateIoCompletionPort(INVALID_HANDLE_VALUE, NULL, 0, 0);
		if (NULL == m_hCompletionPort)
		{
			errorLogE(_T("CreateIoCompletionPort failed."));
			m_bRunning = FALSE;
			break;
		}

		// 有几个CPU就创建几个工作者线程
		SYSTEM_INFO systeminfo = {0};
		::GetSystemInfo(&systeminfo);
		for (DWORD i = 0; i < systeminfo.dwNumberOfProcessors * m_dwWorkerThreadNumCpuMultipe; i++)
		{
			HANDLE hThread = CreateThread(NULL, 0, WorkerThread, this, 0, NULL);
			m_workerThreadList.push_back(hThread);
		}

		//主进程的这个循环中循环等待客户端连接，若有连接，则将该客户套接字于完成端口绑定到一起
		//然后开始异步等待接收客户传来的数据。
		while (m_bRunning)
		{
			// 如果接到客户请求连接，则继续，否则等待。
			SOCKADDR_IN clientAddr;
			int iAddrSize = sizeof(SOCKADDR_IN);
			SOCKET sClient = ::accept(m_sServer, (struct sockaddr *)&clientAddr, &iAddrSize);
			if (INVALID_SOCKET == sClient)
			{
				errorLog(_T("accept socket failed. WE:%u"), ::WSAGetLastError());
				m_bRunning = FALSE;
				continue;
			}
			::EnterCriticalSection(&m_dataSection);
			{
				m_clientSocketList.push_back(sClient);
			}
			::LeaveCriticalSection(&m_dataSection);
			//设置接收缓冲区
			int iRecvBuf = TCPMSGSIZE;
			if ( 0 != setsockopt(sClient, SOL_SOCKET, SO_RCVBUF, (const char*)&iRecvBuf, sizeof(int)))
			{
				errorLog(_T("setsockopt socket failed. WE:%u"), ::WSAGetLastError());
			}

			//client中保存用户信息。
			debugLog(_T("Accepted client:%s:%d"), CString(inet_ntoa(clientAddr.sin_addr)), ntohs(clientAddr.sin_port));

			//检查是否是许可的IP
			if (! IsAllowedIP(clientAddr.sin_addr))
			{
				infoLog(_T("disconnect unallowed ip : %s"), CString(inet_ntoa(clientAddr.sin_addr)));
				::closesocket(sClient);
				continue;
			}

			//检查该ip的连接是否超过限制
			if (IsConnectionExceed(clientAddr.sin_addr))
			{
				errorLog(_T("connection exceed limit. ip : %s"), CString(inet_ntoa(clientAddr.sin_addr)));
				::closesocket(sClient);
				continue;
			}

			//将这个最新到来的客户套接字和完成端口绑定到一起。
			//第三个参数表示传递的参数，这里就传递的客户套接字地址。最后一个参数为0 表示有和CPU一样的进程数。即1个CPU一个线程
			if (NULL == CreateIoCompletionPort((HANDLE)sClient, m_hCompletionPort, (ULONG_PTR)sClient, 0))
			{
				errorLog(_T("bind socket to iocompletionport failed. WE:%u"), ::WSAGetLastError());
				DecreaseConnectionByIP(clientAddr.sin_addr);
				continue;
			}

			// 初始化结构体
			DWORD dwPerIODataSize = sizeof(PER_IO_OPERATION_DATA) + m_dwSessionDataSize;
			LPPER_IO_OPERATION_DATA lpPerIOData = (LPPER_IO_OPERATION_DATA)HeapAlloc(
				GetProcessHeap(),
				HEAP_ZERO_MEMORY,
				dwPerIODataSize);
//			ZeroMemory(lpPerIOData, dwPerIODataSize);
			lpPerIOData->Buffer.len = TCPMSGSIZE;
			lpPerIOData->Buffer.buf = lpPerIOData->szMessage; 
			lpPerIOData->socket = sClient;
			lpPerIOData->addr = clientAddr;
			_time64(&lpPerIOData->lastSpeekTime);

			AddData(lpPerIOData);

			::WSARecv(sClient,         //异步接收消息，立刻返回。
				&lpPerIOData->Buffer, //获得接收的数据
				1,       //The number of WSABUF structures in the lpBuffers array.
				&lpPerIOData->NumberOfBytesRecvd, //接收到的字节数，如果错误返回0
				&lpPerIOData->Flags,       //参数，先不管
				&lpPerIOData->overlap,     //输入这个结构体咯。
				NULL);
		}
	} while (FALSE);

	if (m_hCompletionPort != NULL)
	{
		::CloseHandle(m_hCompletionPort);
		m_hCompletionPort = NULL;
	}

	if (m_sServer != INVALID_SOCKET)
	{
		::closesocket(m_sServer);
	}

	//::WSACleanup();

	debugLog(_T("tcpserver thread end."));
}

DWORD WINAPI TcpServer::WorkerThread( LPVOID lpParameter )
{
	TcpServer* pServer = (TcpServer*) lpParameter;
	pServer->WorkerProc();
	return 0;
}

void TcpServer::WorkerProc()
{
	debugLog(_T("tcpserver worker thread start."));

	DWORD dwBytesTransferred = 0;
	SOCKET sClient = INVALID_SOCKET;
	LPPER_IO_OPERATION_DATA lpPerIOData = NULL;

	while (m_bRunning)
	{
		DISCONNECT_REASON disconnectReason = DISCONNECTREASON_UNKNOWN;

		//遇到可以接收数据则返回，否则等待
		BOOL bRet = ::GetQueuedCompletionStatus( 
			m_hCompletionPort,
			&dwBytesTransferred, //返回的字数
			(PULONG_PTR)&sClient,           //是响应的哪个客户套接字？
			(LPOVERLAPPED*)&lpPerIOData, //得到该套接字保存的IO信息
			INFINITE);               //无限等待咯。不超时的那种。
		if (! bRet)
		{
			DWORD dwError = ::GetLastError();

			if (ERROR_OPERATION_ABORTED == dwError
				|| ERROR_CONNECTION_ABORTED == dwError)
			{
				disconnectReason = DISCONNECTREASON_OPERATION_ABORT;
				dwBytesTransferred = 0;
			}
			else
			{
				debugLog(_T("worker thread getqueuedcompletionstatus failed. E%u"), dwError);

				Sleep(200);
				continue;
			}
		}

		//准备退出
		if (dwBytesTransferred == 0xFFFFFFFF)
		{
			break;
		}

		// 连接被客户端关闭
		if (dwBytesTransferred == 0)
		{
			if (disconnectReason == DISCONNECTREASON_UNKNOWN) disconnectReason = DISCONNECTREASON_CLIENT_DISCONNECT;

			if (m_fnDisconnectNotify != NULL)
			{
				m_fnDisconnectNotify(lpPerIOData->addr, sClient, lpPerIOData->pSessionData, disconnectReason, m_lpDisconnectNotifyParameter);
			}

			RemoveData(lpPerIOData);
			DecreaseConnectionByIP(lpPerIOData->addr.sin_addr);
			if (sClient != INVALID_SOCKET) ::closesocket(sClient);
			::HeapFree(GetProcessHeap(), 0, lpPerIOData);        //释放结构体

			continue;
		}

		lpPerIOData->NumberOfBytesRecvd = dwBytesTransferred;

		//将时间戳设置为0，避免被超时任务清理掉
		lpPerIOData->lastSpeekTime = 0;

		//处理数据
		BOOL bKeepConnection = TRUE;
		if (m_fnCallback != NULL)
		{
			bKeepConnection = m_fnCallback(
				lpPerIOData->addr, 
				sClient,
				(const LPBYTE)lpPerIOData->szMessage, 
				lpPerIOData->NumberOfBytesRecvd, 
				lpPerIOData->pSessionData, 
				m_lpParameter);
		}
		if (! bKeepConnection)
		{
			infoLog(_T("handler cut the connection [%s:%d]"), 
				CString(inet_ntoa(lpPerIOData->addr.sin_addr)), ntohs(lpPerIOData->addr.sin_port));
			RemoveData(lpPerIOData);
			DecreaseConnectionByIP(lpPerIOData->addr.sin_addr);
			::closesocket(sClient);
			::HeapFree(GetProcessHeap(), 0, lpPerIOData);        //释放结构体

			continue;
		}

		//更新时间戳
		_time64(&lpPerIOData->lastSpeekTime);

		// Launch another asynchronous operation for sClient
		ZeroMemory(&lpPerIOData->overlap, sizeof(lpPerIOData->overlap));
		ZeroMemory(&lpPerIOData->Buffer, sizeof(lpPerIOData->Buffer));
		ZeroMemory(lpPerIOData->szMessage, sizeof(lpPerIOData->szMessage));
		lpPerIOData->NumberOfBytesRecvd = 0;
		lpPerIOData->Flags = 0;

		lpPerIOData->Buffer.len = TCPMSGSIZE;
		lpPerIOData->Buffer.buf = lpPerIOData->szMessage;

		::WSARecv(sClient,               //循环接收
			&lpPerIOData->Buffer,
			1,
			&lpPerIOData->NumberOfBytesRecvd,
			&lpPerIOData->Flags,
			&lpPerIOData->overlap,
			NULL);
	}

	debugLog(_T("tcpserver worker thread end."));
}

DWORD WINAPI TcpServer::TimeoutCheckThread( LPVOID lpParameter )
{
	TcpServer* pServer = (TcpServer*) lpParameter;
	pServer->TimeoutCheckProc();
	return 0;
}

void TcpServer::TimeoutCheckProc()
{
	debugLog(_T("tcpserver timeoutcheck thread start."));
	while (m_bRunning)
	{
		::WaitForSingleObject(m_hExitEvent, 1000);
		if (! m_bRunning) break;

		__time64_t now;
		_time64(&now);

		::EnterCriticalSection(&m_dataSection);
		{
			OperationDataSet::iterator iter = m_dataSet.begin();
			for (; iter != m_dataSet.end(); )
			{
				LPPER_IO_OPERATION_DATA lpData = *iter;
				if (lpData->lastSpeekTime != 0
					&& max(now, lpData->lastSpeekTime) - min(now, lpData->lastSpeekTime) > m_dwSilenceTimeoutS)
				{
					infoLog(_T("silence timeout. kick off [%s]"), CString(inet_ntoa(lpData->addr.sin_addr)));
					DecreaseConnectionByIP(lpData->addr.sin_addr, FALSE);
					iter = m_dataSet.erase(iter);
					//::CancelIoEx((HANDLE) lpData->socket, NULL);
					SOCKET sock = lpData->socket;
					lpData->socket = INVALID_SOCKET;
					closesocket(sock);
				}
				else
				{
					iter++;
				}
			}
		}
		::LeaveCriticalSection(&m_dataSection);
	}
	debugLog(_T("tcpserver timeoutcheck thread end."));
}

BOOL TcpServer::Hostname2IP( LPCTSTR hostname, CString& ip ) const
{
	if (NULL == hostname) return FALSE;

	hostent* pHostent = ::gethostbyname(CStringA(hostname)); 
	if (NULL == pHostent) return FALSE;

	ip.Format(_T("%d.%d.%d.%d"),
		(pHostent->h_addr_list[0][0] & 0x00ff),
		(pHostent->h_addr_list[0][1] & 0x00ff),
		(pHostent->h_addr_list[0][2] & 0x00ff),
		(pHostent->h_addr_list[0][3] & 0x00ff));

	return TRUE;
}

BOOL TcpServer::IsAllowedIP(IN_ADDR ip) const
{
	if (! m_bIPSetAvailable) return TRUE;

	CString ipstr((LPCSTR)inet_ntoa(ip));

	//在ip集合中进行查找
	BOOL bFound = (m_ipSet.find(ipstr) != m_ipSet.end());

	//如果在ip集合中找到了该ip则可以做出结论了
	if (bFound)
	{
		return (! m_bIsBlackIPSet);
	}
	
	//在域名集合中进行查找
	SingleIPOrNameSet::const_iterator iter = m_nameSet.begin();
	for (; iter != m_nameSet.end(); iter++)
	{
		const CString& name = *iter;
		CString ipOfCurName;
		if (Hostname2IP(name, ipOfCurName) && ipOfCurName == ipstr)
		{
			return (! m_bIsBlackIPSet);
		}
	}
	
	//没有找到匹配的ip或者域名
	return m_bIsBlackIPSet;
}

BOOL TcpServer::IsConnectionExceed(IN_ADDR ip)
{
	if (INFINITE == m_dwMaxConnectionPerIP) return FALSE;

	BOOL bRet = FALSE;
	::EnterCriticalSection(&m_dataSection);
	{
		ULONG ulIP = ip.S_un.S_addr;
		IPConnectionsMap::iterator icIter = m_ipConnectionsMap.find(ulIP);
		if (icIter == m_ipConnectionsMap.end())
		{
			m_ipConnectionsMap.insert(IPConnectionsMap::value_type(ulIP, 1));
		}
		else
		{
			if (icIter->second > (LONG)m_dwMaxConnectionPerIP)
			{
				bRet = TRUE;
			}
			else
			{
				icIter->second++;
			}
		}
	}
	::LeaveCriticalSection(&m_dataSection);

	return bRet;
}

void TcpServer::DecreaseConnectionByIP(IN_ADDR ip, BOOL bEnterCriticalSection)
{
	if (INFINITE == m_dwMaxConnectionPerIP) return;
	
	if (bEnterCriticalSection) ::EnterCriticalSection(&m_dataSection);
	{
		ULONG ulIP = ip.S_un.S_addr;
		IPConnectionsMap::iterator icIter = m_ipConnectionsMap.find(ulIP);
		if (icIter != m_ipConnectionsMap.end())
		{
			icIter->second--;
			if (icIter->second <= 0)
			{
				m_ipConnectionsMap.erase(icIter);
			}
		}
	}
	if (bEnterCriticalSection) ::LeaveCriticalSection(&m_dataSection);
}

void TcpServer::AddData(LPPER_IO_OPERATION_DATA lpData)
{
	::EnterCriticalSection(&m_dataSection);
	{
		m_dataSet.insert(lpData);
	}
	::LeaveCriticalSection(&m_dataSection);

	debugLog(_T("add data %x"), lpData);
}

void TcpServer::RemoveData(LPPER_IO_OPERATION_DATA lpData)
{
	::EnterCriticalSection(&m_dataSection);
	{
		m_dataSet.erase(lpData);
	}
	::LeaveCriticalSection(&m_dataSection);

	debugLog(_T("remove data %x"), lpData);
}
