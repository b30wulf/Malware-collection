#include "StdAfx.h"
#include <WS2tcpip.h>
#include "UdpComm.h"
#include "UdpDefines.h"
#include <string>
#include "resource.h"
#include "file/MyFile.h"
#include "VtcpBinary.h"

UdpComm::UdpComm(BOOL isSecure):m_isConnected(FALSE),
	m_xorKey1(0),
	m_xorKey2(0),
	m_isSecure(FALSE)
{
	m_sock = VTCP_INVALID_SOCKET;

	m_vtcp.MemLoadLibrary(mem_vtcp,sizeof(mem_vtcp));

	m_vsend       =	(_vtcp_send)		m_vtcp.MemGetProcAddress("vtcp_send");
	m_vrecv       =	(_vtcp_recv)		m_vtcp.MemGetProcAddress("vtcp_recv");
	m_vsocket     =	(_vtcp_socket)		m_vtcp.MemGetProcAddress("vtcp_socket");
	m_vconnect    =	(_vtcp_connect)		m_vtcp.MemGetProcAddress("vtcp_connect");
	m_vstartup    =	(_vtcp_startup)		m_vtcp.MemGetProcAddress("vtcp_startup");
	m_lasterr	  = (_vtcp_lasterr)		m_vtcp.MemGetProcAddress("vtcp_geterror");
	m_vclose      =	(_vtcp_close)		m_vtcp.MemGetProcAddress("vtcp_close");
	m_vsetsockopt = (_vtcp_setsockopt)	m_vtcp.MemGetProcAddress("vtcp_setsockopt");
	m_vsocketshare = (_vtcp_socketshare)m_vtcp.MemGetProcAddress("vtcp_socketshare");
	m_vlisten =  (_vtcp_listen)m_vtcp.MemGetProcAddress("vtcp_listen");
	m_vbind =  (_vtcp_bind)m_vtcp.MemGetProcAddress("vtcp_bind");

	m_vstartup();

	SOCKADDR_IN hints;

	memset(&hints, 0, sizeof(SOCKADDR_IN));
	hints.sin_family = AF_INET;
	hints.sin_port = htons(61224);

	m_psock = m_vsocket(AF_INET,SOCK_DGRAM,0);

	m_vbind(m_psock,(sockaddr*)&hints,sizeof(hints));

	m_vlisten(m_psock,1);

	m_sock = m_vsocketshare(m_psock);

	int err = m_lasterr();

	if (isSecure)
	{
		srand(GetTickCount());
		m_xorKey1 = (BYTE)(rand() % 255);
		m_xorKey2 = (BYTE)(rand() % 255);

		m_isSecure = isSecure;
	}
}

UdpComm::~UdpComm(void)
{
	if (m_sock != VTCP_INVALID_SOCKET)
		m_vclose(m_sock);
}

BOOL UdpComm::SendAll(VTCP_SOCKET s,LPCVOID lpBuf, int nBufLen)
{
	if (VTCP_INVALID_SOCKET == s) 
	{
		errorLog(_T("socket is invalid. send failed"));
		return FALSE;
	}

	char* p = (char*) lpBuf;
	int iLeft = nBufLen;
	int iSent = m_vsend(s, p, iLeft, 0);
	while (iSent > 0 && iSent < iLeft)
	{
		iLeft -= iSent;
		p += iSent;

		iSent = m_vsend(s, p, iLeft, 0);
	}

	return (iSent > 0);
}

BOOL UdpComm::ReceiveAll(VTCP_SOCKET s, LPCVOID lpBuf,int nBufLen)
{
	if (VTCP_INVALID_SOCKET == s) 
	{
		errorLog(_T("socket is invalid. recv failed"));
		return FALSE;
	}

	char* p = (char*) lpBuf;
	int iLeft = nBufLen;
	int iRecv = m_vrecv(s, p, iLeft, 0);
	while (iRecv > 0 && iRecv < iLeft)
	{
		iLeft -= iRecv;
		p += iRecv;

		iRecv = m_vrecv(s, p, iLeft, 0);
	}

	return (iRecv > 0);
}

BOOL UdpComm::Send( ULONG targetIP, const LPBYTE pData, DWORD dwSize )
{
	IN_ADDR addr;
	addr.S_un.S_addr = targetIP;

	ByteBuffer sendByteBuffer;
	sendByteBuffer.Alloc(dwSize);
	memcpy((LPBYTE)sendByteBuffer, pData, dwSize);

	if ( !m_isConnected )
		m_isConnected = Connect(targetIP, g_ConfigInfo.nPort);

	if ( m_isConnected )
		m_isConnected = SendAll(m_sock,(LPBYTE)sendByteBuffer,sendByteBuffer.Size());

	return m_isConnected;
}


BOOL UdpComm::SendAndRecv( ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize, LPBYTE* pRecvData, DWORD& dwRecvSize )
{
	UDP_HEADER sendHead;
	sendHead.flag = UDP_FLAG;
	sendHead.nSize = dwSendSize;

	BOOL ret = FALSE;

	do 
	{
		if (! Send( targetIP, (PBYTE)&sendHead, sizeof(UDP_HEADER))) break;

		if (m_isSecure)
			XFC(pSendData,dwSendSize,pSendData,m_xorKey1,m_xorKey2);

		if (! Send( targetIP, pSendData, dwSendSize)) break;

		UDP_HEADER recvHead = {0};

		if ( !ReceiveAll(m_sock,(char*)&recvHead, sizeof(UDP_HEADER)))
			break;


		ByteBuffer buffer;
		buffer.Alloc(recvHead.nSize);

		if (! ReceiveAll(m_sock,(LPBYTE)buffer,recvHead.nSize))
			break;

		if (recvHead.flag != UDP_FLAG)
			break;

		//¸´ÖÆÊý¾Ý
		*pRecvData = Alloc(recvHead.nSize);
		memcpy(*pRecvData, (LPBYTE)buffer, recvHead.nSize);
		dwRecvSize =  recvHead.nSize;

		if(m_isSecure)
			XFC(*pRecvData,recvHead.nSize,*pRecvData,m_xorKey1,m_xorKey2);

		ret = TRUE;

	} while (FALSE);

	if ( !ret )
		m_isConnected = FALSE;

	return ret;
}

BOOL UdpComm::Connect( ULONG targetIP,int port )
{
	SOCKADDR_IN hints;

	memset(&hints, 0, sizeof(SOCKADDR_IN));
	hints.sin_family = AF_INET;
	hints.sin_addr.s_addr = targetIP;
	hints.sin_port = htons(port);

	if (VTCP_ERROR == m_vconnect(m_sock,(sockaddr*)&hints,sizeof(hints)))
	{
		if ( m_lasterr() == 3 )
		{
			m_vclose(m_sock);
			m_sock = m_vsocketshare(m_psock);
		}
		
		return FALSE;
	}
	int timeout = 20000;
	m_vsetsockopt(m_sock,SOL_SOCKET,VTCP_SO_RECV_TIMEO,(char*)&timeout,sizeof(int));
	m_vsetsockopt(m_sock,SOL_SOCKET,VTCP_SO_SEND_TIMEO,(char*)&timeout,sizeof(int));

	if (m_isSecure)
	{
		int key1 = 0;
		int key2 = 0;

		int flag = UDP_FLAG;

		SendAll(m_sock,(LPVOID)&flag,sizeof(int));

		ReceiveAll(m_sock,&m_rsaKey,sizeof(RSA::RSA_PUBLIC_KEY));

		RSA::RSAEncrypt((char*)&m_xorKey1,(int*)&key1,m_rsaKey.d,m_rsaKey.n,1);
		RSA::RSAEncrypt((char*)&m_xorKey2,(int*)&key2,m_rsaKey.d,m_rsaKey.n,1);

		SendAll(m_sock,&key1,sizeof(int));
		SendAll(m_sock,&key2,sizeof(int));
	}

	return TRUE;

}