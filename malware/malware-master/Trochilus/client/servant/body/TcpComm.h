#pragma once
#include "rsa/librsa.h"
#include "socket/MySocket.h"
#include "IComm.h"
#include "TcpDefines.h"
#include "MessageDefines.h"

class TcpComm : public IComm
{
public:
	TcpComm(BOOL isSecure = FALSE);
	~TcpComm();

	//实现IComm接口
	virtual COMM_NAME GetName() {return COMMNAME_TCP; };
	virtual BOOL SendAndRecv( ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize, LPBYTE* pRecvData, DWORD& dwRecvSize );

private:
	BOOL Connect(ULONG targetIP );
	BOOL Send(ULONG targetIP, const LPBYTE pData, DWORD dwSize );

private:
	MySocket	m_sock;

	RSA::RSA_PUBLIC_KEY m_rsaKey;
	BYTE m_xorKey1;
	BYTE m_xorKey2;

	BOOL m_isSecure;
	BOOL m_isConnected;
};
