#pragma once
#include "socket/MySocket.h"
#include "MessageDefines.h"
#include "IComm.h"
#include "winhttp/http.h"

class HttpComm : public IComm
{
public:
	HttpComm(BOOL isSecure = FALSE);
	~HttpComm();
	//实现IComm接口
	virtual COMM_NAME GetName() { if (m_ssl) return COMMNAME_HTTPS; else COMMNAME_HTTP; };
	virtual BOOL Send( ULONG targetIP, const LPBYTE pData, DWORD dwSize );
	virtual BOOL SendAndRecv( ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize, LPBYTE* pRecvData, DWORD& dwRecvSize );

private:
	BOOL Connect(ULONG targetIP);
private:
	bool m_ssl;
	MySocket	m_sock;
	ctx::http*	m_http;
};
