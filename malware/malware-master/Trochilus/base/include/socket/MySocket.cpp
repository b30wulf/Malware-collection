#include "stdafx.h"
#include <MSTCPiP.h>
#include "MySocket.h"

#pragma comment(lib, "Ws2_32.lib")

MySocket::MySocket()
: m_socket(INVALID_SOCKET)
, m_bOwn(TRUE)
{

}

MySocket::~MySocket()
{
}

MySocket::MySocket(SOCKET socket, BOOL bOwn)
: m_socket(socket)
, m_bOwn(bOwn)
{

}

MySocket::operator SOCKET() const
{
	return m_socket;
}

BOOL MySocket::Create(BOOL bTcp )
{
	if (INVALID_SOCKET != m_socket) 
	{
		errorLog(_T("socket is open. please close it first."));
		return FALSE;
	}

	if (bTcp)
	{
		m_socket = ::socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
	}
	else
	{
		m_socket = ::socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP);
	}

	if (INVALID_SOCKET == m_socket)
	{
		errorLog(_T("create socket failed E%u"), ::WSAGetLastError());
		return FALSE;
	}

	return TRUE;
}
BOOL MySocket::Accept( SOCKADDR_IN& addr,MySocket& socket )
{
	int nLen = sizeof(addr);
	SOCKET sClient;

	if (m_socket != INVALID_SOCKET)
	{
		sClient = accept(m_socket,(sockaddr*)&addr,&nLen);
		MySocket sock(sClient,TRUE);
		socket = sock;
	}
	
	return sClient != INVALID_SOCKET;
}
BOOL MySocket::Bind( LPCTSTR ip, UINT port )
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. bind failed"));
		return FALSE;
	}

	struct sockaddr_in local = {0};
	local.sin_addr.S_un.S_addr = inet_addr( t2a(ip) );
	local.sin_family = AF_INET;
	local.sin_port = htons(port);

	if (0 != ::bind(m_socket, (const sockaddr*)&local, sizeof(local)))
	{
		errorLog(_T("bind failed. E%u"), ::WSAGetLastError());
		return FALSE;
	}

	return TRUE;
}

BOOL MySocket::Bind(UINT port)
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. bind failed"));
		return FALSE;
	}

	struct sockaddr_in local = {0};
	local.sin_addr.S_un.S_addr = htonl(INADDR_ANY);
	local.sin_family = AF_INET;
	local.sin_port = htons(port);

	if (0 != ::bind(m_socket, (const sockaddr*)&local, sizeof(local)))
	{
		errorLog(_T("bind failed. E%u"), ::WSAGetLastError());
		return FALSE;
	}

	return TRUE;
}

BOOL MySocket::Connect( LPCTSTR lpszHostAddress, UINT nHostPort, DWORD dwTimeoutSeconds )
{
	ULONG targetIP = inet_addr( t2a(lpszHostAddress) );
	if (INADDR_NONE == targetIP)
	{
		tstring strip;
		if (! Hostname2IP(lpszHostAddress, strip))
		{
			errorLog(_T("invalid host [%s]"), lpszHostAddress);
			return -2;
		}

		targetIP = inet_addr(t2a(strip.c_str()));
	}

	return Connect(targetIP, nHostPort, dwTimeoutSeconds);
}

BOOL MySocket::Connect( ULONG ip, UINT nHostPort, DWORD dwTimeoutSeconds /*= 4*/ )
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. connect failed"));
		return FALSE;
	}

	sockaddr_in clientService; 
	clientService.sin_family = AF_INET;
	clientService.sin_addr.s_addr = ip;
	clientService.sin_port = htons( nHostPort );
	
	//设置为非阻塞模式
	ULONG ul = 1;
	::ioctlsocket(m_socket, FIONBIO, &ul);

	BOOL bRet = FALSE;

	if ( ::connect( m_socket, (SOCKADDR*) &clientService, sizeof(clientService) ) == SOCKET_ERROR
		&& ::WSAGetLastError() == WSAEWOULDBLOCK)
	{
		timeval tm;
		tm.tv_sec = dwTimeoutSeconds;
		tm.tv_usec = 0;

		fd_set set;
		FD_ZERO(&set);

		FD_SET(m_socket, &set);

		if( select((int)(m_socket + 1), NULL, &set, NULL, &tm) > 0)
		{
			DWORD dwError = 0;
			int len = (int) sizeof(dwError);
			int iRet = getsockopt(m_socket, SOL_SOCKET, SO_ERROR, (char*)&dwError, &len);
			if (0 == iRet
				&& dwError == 0)
			{
				bRet = TRUE;
			}
		}
	}

	//设置为阻塞模式
	ul = 0;
	::ioctlsocket(m_socket, FIONBIO, &ul);

	return bRet;
}

int MySocket::Send( LPCVOID lpBuf, int nBufLen )
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. send failed"));
		return -1;
	}

	return ::send( m_socket, (const char*)lpBuf, nBufLen, 0);
}
BOOL MySocket::ReceiveAll(LPCVOID lpBuf,int nBufLen)
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. recv failed"));
		return FALSE;
	}

	char* p = (char*) lpBuf;
	int iLeft = nBufLen;
	int iRecv = ::recv(m_socket, p, iLeft, 0);
	while (iRecv > 0 && iRecv < iLeft)
	{
		iLeft -= iRecv;
		p += iRecv;

		iRecv = ::recv(m_socket, p, iLeft, 0);
	}

	return (iRecv > 0);
}
BOOL MySocket::SendAll(LPCVOID lpBuf, int nBufLen)
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. send failed"));
		return FALSE;
	}

	const char* p = (const char*) lpBuf;
	int iLeft = nBufLen;
	int iSent = ::send(m_socket, p, iLeft, 0);
	while (iSent > 0 && iSent < iLeft)
	{
		iLeft -= iSent;
		p += iSent;

		iSent = ::send(m_socket, p, iLeft, 0);
	}
	
	return (iSent > 0);
}

int MySocket::SendTo(LPCVOID lpBuf, int nBufLen, UINT nHostPort, LPCTSTR lpszHostAddress)
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. sendto failed"));
		return -1;
	}

	struct sockaddr_in recvAddr;
	IPOrHostname2IP(lpszHostAddress, recvAddr.sin_addr.S_un.S_addr);
	recvAddr.sin_family = AF_INET;
	recvAddr.sin_port = htons(nHostPort);

	return ::sendto( m_socket, (const char*)lpBuf, nBufLen, 0, (SOCKADDR*)&recvAddr, sizeof(recvAddr));
}

int MySocket::SendTo(LPCVOID lpBuf, int nBufLen, const SOCKADDR_IN& addr)
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. sendto failed"));
		return -1;
	}

	return ::sendto( m_socket, (const char*)lpBuf, nBufLen, 0, (SOCKADDR*)&addr, sizeof(addr));
}

int MySocket::RecvFrom(LPCVOID lpBuf, int nBufLen, const SOCKADDR_IN& addr)
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. recvfrom failed"));
		return -1;
	}
	int nLen = sizeof(addr);
	return ::recvfrom( m_socket, (char*)lpBuf, nBufLen, 0, (SOCKADDR*)&addr, &nLen );
}

int MySocket::RecvFrom(LPCVOID lpBuf, int nBufLen,const SOCKADDR_IN& addr,DWORD dwTimeoutSeconds, int* pLastError )
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. receive failed"));
		return -1;
	}

	int iTimeOut = dwTimeoutSeconds * 1000;
	::setsockopt(m_socket, SOL_SOCKET, SO_RCVTIMEO, (char*)&iTimeOut, sizeof(iTimeOut));

	int nLen = sizeof(addr);

	int iRet = ::recvfrom(m_socket, (char*)lpBuf, nBufLen, 0, (SOCKADDR*)&addr, &nLen);
	if (NULL != pLastError)
	{
		*pLastError = ::WSAGetLastError();
	}
	return  iRet;
}

int MySocket::Receive( LPVOID lpBuf, int nBufLen )
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. receive failed"));
		return -1;
	}

	return ::recv(m_socket, (char*)lpBuf, nBufLen, 0);
}

int MySocket::Receive( LPVOID lpBuf, int nBufLen, DWORD dwTimeoutSeconds, int* pLastError )
{
	if (INVALID_SOCKET == m_socket) 
	{
		errorLog(_T("socket is invalid. receive failed"));
		return -1;
	}

	int iTimeOut = dwTimeoutSeconds * 1000;
	::setsockopt(m_socket, SOL_SOCKET, SO_RCVTIMEO, (char*)&iTimeOut, sizeof(iTimeOut));

	int iRet = ::recv(m_socket, (char*)lpBuf, nBufLen, 0);
	if (NULL != pLastError)
	{
		*pLastError = ::WSAGetLastError();
	}

	return iRet;
}

BOOL MySocket::SetKeepAlive(DWORD dwKeepAliveTimeMS, DWORD dwKeepAliveIntervalMS)
{
	if (INVALID_SOCKET == m_socket) return FALSE;

	tcp_keepalive sKA_Settings = {0};
	tcp_keepalive sReturned = {0};

	sKA_Settings.onoff = 1 ;
	sKA_Settings.keepalivetime = dwKeepAliveTimeMS;
	sKA_Settings.keepaliveinterval = dwKeepAliveIntervalMS;

	DWORD dwBytes = 0;
	if (::WSAIoctl(m_socket, SIO_KEEPALIVE_VALS, &sKA_Settings,
		sizeof(sKA_Settings), &sReturned, sizeof(sReturned), &dwBytes,
		NULL, NULL) != 0)
	{
		int iError = ::WSAGetLastError();
		errorLog(_T("set keep alive failed. WE%d"), iError);

		return FALSE;
	}

	return TRUE;
}

void MySocket::Close()
{
	if (! m_bOwn || INVALID_SOCKET == m_socket) return;

	::shutdown(m_socket, SD_SEND);

	int iRet = ::closesocket(m_socket);
	if (iRet != 0)
	{
		errorLog(_T("closesocket failed. E%d"), ::WSAGetLastError());
	}

	m_socket = INVALID_SOCKET;
}

BOOL MySocket::Hostname2IP( LPCTSTR hostname, tstring& ip )
{
	if (NULL == hostname) return FALSE;

	hostent* pHostent = ::gethostbyname(t2a(hostname)); 
	if (NULL == pHostent) 
	{
		errorLog(_T("get ip for[%s] failed. WE%d"), hostname, ::WSAGetLastError());
		return FALSE;
	}

	tostringstream toss;
	toss << (pHostent->h_addr_list[0][0] & 0x00ff) << '.'
		 << (pHostent->h_addr_list[0][1] & 0x00ff) << '.'
		 << (pHostent->h_addr_list[0][2] & 0x00ff) << '.'
		 << (pHostent->h_addr_list[0][3] & 0x00ff);
	ip = toss.str();

	return TRUE;
}

BOOL MySocket::IPOrHostname2IP( LPCTSTR hostnameOrIP, ULONG& ip )
{
	ip = inet_addr(t2a(hostnameOrIP));
	if (INADDR_NONE == ip)
	{
		tstring strip;
		MySocket::Hostname2IP(hostnameOrIP, strip);

		ip = inet_addr(t2a(strip.c_str()));
	}

	return (INADDR_NONE != ip);
}

void MySocket::SetOwn( BOOL bOwn )
{
	m_bOwn = bOwn;
}
