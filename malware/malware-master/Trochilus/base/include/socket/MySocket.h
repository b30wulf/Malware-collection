#pragma once
#include <Winsock2.h>
#include "tstring.h"

class MySocket
{
public:
	MySocket(SOCKET socket, BOOL bOwn);
	MySocket();
	~MySocket();

	operator SOCKET() const;

	BOOL Create(BOOL bTcp = TRUE);
	BOOL Bind(LPCTSTR ip, UINT port = 0);
	BOOL Bind(UINT port);
	BOOL Accept(SOCKADDR_IN& addr,MySocket& socket);
	BOOL Connect(ULONG ip, UINT nHostPort, DWORD dwTimeoutSeconds = 4);
	BOOL Connect(LPCTSTR lpszHostAddress, UINT nHostPort, DWORD dwTimeoutSeconds = 4);

	int Send(LPCVOID lpBuf, int nBufLen);
	BOOL SendAll(LPCVOID lpBuf, int nBufLen);
	int SendTo(LPCVOID lpBuf, int nBufLen, UINT nHostPort, LPCTSTR lpszHostAddress);
	int SendTo(LPCVOID lpBuf, int nBufLen, const SOCKADDR_IN& addr);
	int RecvFrom(LPCVOID lpBuf, int nBufLen,const SOCKADDR_IN& addr,DWORD dwTimeoutSeconds, int* pLastError);
	int RecvFrom(LPCVOID lpBuf, int nBufLen, const SOCKADDR_IN& addr);
	int Receive(LPVOID lpBuf, int nBufLen);
	BOOL ReceiveAll(LPCVOID lpBuf,int nBufLen);
	int Receive(LPVOID lpBuf, int nBufLen, DWORD dwTimeoutSeconds, int* pLastError);
	BOOL SetKeepAlive(DWORD dwKeepAliveTimeMS = 5000, DWORD dwKeepAliveIntervalMS = 5000);
	void SetOwn(BOOL bOwn);

	void Close();

	static BOOL Hostname2IP(LPCTSTR hostname, tstring& ip);
	static BOOL IPOrHostname2IP(LPCTSTR hostnameOrIP, ULONG& ip);

private:
	SOCKET	m_socket;
	BOOL	m_bOwn;
};
