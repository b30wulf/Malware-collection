#pragma once
#include "rsa/librsa.h"
#include "TcpDefines.h"
#include "socket/MySocket.h"
#include <WinSock2.h>
#pragma comment(lib, "ws2_32.lib")

typedef BOOL (*tcpHandler)(LPBYTE data,DWORD size,SOCKADDR_IN sin,ByteBuffer& toSender);

typedef struct
{
	SOCKET s;
	SOCKADDR_IN sin;
	tcpHandler handler;
	LPVOID lpParameter;

}ARGV_LIST,*PARGV_LIST;

class CTcp
{
public:
	CTcp(BOOL isSecure = FALSE);
	~CTcp(void);

	typedef std::vector<SOCKET> VecSocket;

	void Init();
	bool Start(int port , tcpHandler handler);
	void Stop();

private:

	CriticalSection m_cs;
	VecSocket m_vecSock;


	static void Listen(LPVOID lpParameter);
	void ListenProc(ARGV_LIST *argv);

	static void Worker(LPVOID lpParameter);
	void WorkerProc(LPVOID lpParameter);

	MySocket m_sock;

private:
	RSA::RSA_PRIVATE_KEY m_myPriKey;
	RSA::RSA_PUBLIC_KEY m_myPubKey;

	BYTE m_xorKey1;
	BYTE m_xorKey2;

	BOOL m_isSecure;
};

