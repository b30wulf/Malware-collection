#include "StdAfx.h"
#include "Tcp.h"
#include "common.h"

CTcp::CTcp(BOOL isSecure):
m_isSecure(FALSE)
{
	if (isSecure)
	{
		RSA::GenRSAKey(&m_myPriKey,&m_myPubKey);

		m_isSecure = isSecure;

		srand(GetTickCount());
	}
}


CTcp::~CTcp(void)
{
}


void CTcp::Init()
{
}

void CTcp::Stop()
{
	m_sock.Close();

	m_cs.Enter();

	VecSocket::iterator it = m_vecSock.begin();

	for (; it != m_vecSock.end(); it++)
	{
		::closesocket(*it);
	}

	m_cs.Leave();

}

bool CTcp::Start(int port , tcpHandler handler)
{
	bool ret = FALSE;

	MySocket socket;

	socket.Create();

	if (socket.Bind(port))
	{
		m_sock = socket;

		ARGV_LIST* argv = new ARGV_LIST;

		argv->handler = handler;
		argv->s = socket;
		argv->lpParameter = this;

		listen(socket,25);
		_beginthread(Listen,0,argv);

		ret = TRUE;
	}

	return ret;


}
void CTcp::Worker(LPVOID lpParameter)
{
	ARGV_LIST *argv = (ARGV_LIST*)lpParameter;
	CTcp* tcp = (CTcp*)argv->lpParameter;

	tcp->WorkerProc(lpParameter);
}

void CTcp::WorkerProc(LPVOID lpParameter)
{
	ARGV_LIST *argv = (ARGV_LIST*)lpParameter;

	TCP_HEADER header;

	MySocket socket(argv->s,TRUE);

	BOOL ret = TRUE;
	
	ByteBuffer toSender;

	do 
	{
		if (m_isSecure)
		{
			int key1 = 0;
			int key2 = 0;

			int flag = TCP_FLAG;

			socket.ReceiveAll(&flag,sizeof(int));

			if(flag != TCP_FLAG )
				break;

			socket.SendAll(&m_myPubKey,sizeof(RSA::RSA_PUBLIC_KEY));
			socket.ReceiveAll(&key1,sizeof(int));
			socket.ReceiveAll(&key2,sizeof(int));

			RSA::RSADecrypt((char*)&m_xorKey1,&key1,m_myPriKey.e,m_myPriKey.n,1);
			RSA::RSADecrypt((char*)&m_xorKey2,&key2,m_myPriKey.e,m_myPriKey.n,1);
		}
		
		while(ret)
		{
			ret = socket.ReceiveAll(&header,sizeof(TCP_HEADER));
			if (ret && header.flag == TCP_FLAG)
			{
				LPBYTE lpData = (LPBYTE)malloc(header.nSize);

				ret = socket.ReceiveAll(lpData,header.nSize);

				if (m_isSecure)
					XorFibonacciCrypt(lpData,header.nSize,lpData,m_xorKey1,m_xorKey2);

				if ( ret )
				{
					if (argv->handler(lpData,header.nSize,argv->sin,toSender))
					{
						header.nSize = toSender.Size();
						socket.SendAll(&header,sizeof(TCP_HEADER));

						if (m_isSecure)
							XorFibonacciCrypt(toSender,toSender.Size(),toSender,m_xorKey1,m_xorKey2);

						socket.SendAll(toSender,toSender.Size());
					}

				}
				free(lpData);
			}
			else
			{
				break;
			}
		}

	} while (FALSE);

	socket.Close();

	delete lpParameter;
}

void CTcp::ListenProc( ARGV_LIST *argv )
{
	MySocket socket(argv->s,TRUE);

	SOCKADDR_IN sin;

	MySocket acc;

	while(socket.Accept(sin,acc))
	{
		m_cs.Enter();
		m_vecSock.push_back(acc);
		m_cs.Leave();

		ARGV_LIST * client_argv = new ARGV_LIST;

		client_argv->handler = argv->handler;
		client_argv->sin = sin;
		client_argv->s = acc;
		client_argv->lpParameter = argv->lpParameter;

		_beginthread(Worker,0,client_argv);
	}

	socket.Close();
	delete argv;
}

void CTcp::Listen(LPVOID lpParameter)
{
	ARGV_LIST *argv = (ARGV_LIST*)lpParameter;
	CTcp* tcp = (CTcp*)argv->lpParameter;

	return tcp->ListenProc((ARGV_LIST*)lpParameter);
}