#pragma once
#include "UdpServerData.h"
#include <vector>

#ifndef IOCP_BUF_LEN
#	define IOCP_BUF_LEN (16 * 1024)
#endif

class UdpServer
{
public:
	UdpServer();
	~UdpServer();

	void Init(FnUdpMsgHandler fnUdpMsgHandler, LPVOID lpParameter);
	BOOL Start(USHORT listenPort, DWORD dwWorkerThreadNumCpuMultipe = 2);
	void Stop();

private:
	typedef std::vector<HANDLE> HandleVector;
	typedef struct iocp_data
	{
		WSAOVERLAPPED	overlapped;
		WSABUF			wsaBuf;
		CHAR			data[IOCP_BUF_LEN];
		DWORD			dataLength;

		sockaddr_in		remoteAddr;
		int				remoteAddrLen;
		DWORD			flags;
	
		iocp_data()
		{
			Reset();
		}

		void Reset()
		{
			flags = 0;
			wsaBuf.buf = data;
			wsaBuf.len = IOCP_BUF_LEN;
			remoteAddrLen = sizeof(remoteAddr);
			dataLength = 0;
			
			ZeroMemory(data, IOCP_BUF_LEN);
			ZeroMemory(&remoteAddr, sizeof(sockaddr_in));
			ZeroMemory(&overlapped, sizeof(WSAOVERLAPPED));
		}
	} IOCP_DATA, *PIOCP_DATA;
	typedef std::vector<PIOCP_DATA> IocpDataVector;

private:
	void Reset();
	static DWORD WINAPI WorkerThread(LPVOID lpParameter);
	void WorkerProc();
	void HandleMsg(const PIOCP_DATA pIocpData);

private:
	LONG			m_bRunning;
	HANDLE			m_hIocp;
	HandleVector	m_threadHandleVector;
	SOCKET			m_udpSocket;
	IocpDataVector	m_iocpDataVector;
	FnUdpMsgHandler	m_fnUdpMsgHandler;
	LPVOID			m_lpParameter;
};
