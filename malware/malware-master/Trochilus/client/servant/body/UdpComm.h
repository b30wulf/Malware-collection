#pragma once
#include "IComm.h"
#include <Winsock2.h>
#include "memdll/MemLoadDll.h"
#include "MessageDefines.h"
#include "rsa/librsa.h"
#include "vtcp/vtcp.h"

class UdpComm: public IComm
{
public:
	UdpComm(BOOL isSecure = FALSE);
	~UdpComm(void);

public:
	typedef int (WINAPI *_vtcp_startup)();
	typedef int (WINAPI *_vtcp_lasterr)();
	typedef int (WINAPI *_vtcp_bind)(VTCP_SOCKET s, const struct sockaddr * sai, socklen_t sailen);
	typedef int (WINAPI *_vtcp_listen)(VTCP_SOCKET s, int backlog);
	typedef VTCP_SOCKET (WINAPI *_vtcp_socket) (int af, int itype, int protocol);
	typedef int (WINAPI *_vtcp_connect)(VTCP_SOCKET s, const struct sockaddr * sai, socklen_t sailen);
	typedef int (WINAPI *_vtcp_send)(VTCP_SOCKET s, char * buffer, int cb, int flag);
	typedef int (WINAPI *_vtcp_recv)(VTCP_SOCKET s, char * buffer, int cb, int flag);
	typedef int (WINAPI *_vtcp_close)(VTCP_SOCKET s);
	typedef int (WINAPI *_vtcp_setsockopt)(VTCP_SOCKET s, int level, int optname, char * optval, int   optlen);
	typedef VTCP_SOCKET (WINAPI *_vtcp_socketshare)(VTCP_SOCKET s);
private:
	_vtcp_startup m_vstartup;
	_vtcp_lasterr m_lasterr;
	_vtcp_socket m_vsocket;
	_vtcp_connect m_vconnect;
	_vtcp_send m_vsend;
	_vtcp_recv m_vrecv;
	_vtcp_close m_vclose;
	_vtcp_setsockopt m_vsetsockopt;
	_vtcp_socketshare m_vsocketshare;
	_vtcp_listen m_vlisten;
	_vtcp_bind m_vbind;

public:
	//实现IComm接口
	virtual COMM_NAME GetName() {return COMMNAME_UDP; };
	virtual BOOL Send( ULONG targetIP, const LPBYTE pData, DWORD dwSize );
	virtual BOOL SendAndRecv( ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize, LPBYTE* pRecvData, DWORD& dwRecvSize );

private:
	BOOL Connect(ULONG targetIP,int port);

	BOOL ReceiveAll(VTCP_SOCKET s,LPCVOID lpBuf,int nBufLen);
	BOOL SendAll(VTCP_SOCKET s,LPCVOID lpBuf, int nBufLen);

	VTCP_SOCKET m_sock;
	VTCP_SOCKET m_psock;

	BOOL m_isConnected;

	CMemLoadDll m_vtcp;

private:
	RSA::RSA_PUBLIC_KEY m_rsaKey;
	BYTE m_xorKey1;
	BYTE m_xorKey2;

	BOOL m_isSecure;
};

