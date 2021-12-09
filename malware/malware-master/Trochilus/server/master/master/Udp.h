#pragma once
#include "vtcp/vtcp.h"
#include "UdpDefines.h"
#include "rsa/librsa.h"

typedef BOOL (*udpHandler)(LPBYTE data,DWORD size,SOCKADDR_IN sin,ByteBuffer& toSender);

typedef struct
{
	VTCP_SOCKET s;
	SOCKADDR_IN sin;
	udpHandler handler;
	LPVOID lpParameter;

}UDP_ARGV,*PUDP_ARGV;

class CUdp
{
public:
	CUdp(BOOL isSecure = FALSE);
	~CUdp(void);
	
	typedef std::vector<VTCP_SOCKET> VecSocket;

	void Init();
	BOOL Start(int port, udpHandler handler);
	void Stop();

private:

	VTCP_SOCKET m_sock;

	udpHandler m_handler;

	static void Listen(LPVOID lpParameter);
	void ListenProc(UDP_ARGV *argv);

	static void Worker(LPVOID lpParameter);
	void WorkerProc(LPVOID lpParameter);

	CriticalSection m_cs;
	VecSocket m_vecSock;

private:
	RSA::RSA_PRIVATE_KEY m_myPriKey;
	RSA::RSA_PUBLIC_KEY m_myPubKey;

	BYTE m_xorKey1;
	BYTE m_xorKey2;

	BOOL m_isSecure;
};

