#pragma once
#include <vector>
#include <set>
#include <map>
#include <list>
#include <Winsock2.h>
#include "TcpServerData.h"

//IP列表
typedef std::set<CString> SingleIPOrNameSet;

class TcpServer
{
public:
	TcpServer();
	~TcpServer();

	void Init(FnTcpMsgHandler fnCallback, LPVOID lpParameter);
	void SetBlackIPNameSet(const SingleIPOrNameSet& ipnameSet);
	void SetWhiteIPNameSet(const SingleIPOrNameSet& ipnameSet);
	void DisableBlackOrWhiteIPSet();
	void SetDisconnectNotify(FnTcpDisconnectNotify fnDisconnectNotify, LPVOID lpParameter);
	void SetMaxConnectionPerIP(DWORD dwMaxConnection);
	BOOL StartListening(UINT port, DWORD dwSessionDataSize, DWORD dwSilenceTimeoutS = 10, DWORD dwWorkerThreadNumCpuMultipe = 1);
	void Stop();

private:
	typedef struct
	{
		WSAOVERLAPPED   overlap;       
		WSABUF          Buffer;         
		char            szMessage[TCPMSGSIZE];
		DWORD           NumberOfBytesRecvd;
		DWORD           Flags;
		__time64_t		lastSpeekTime;
		SOCKADDR_IN		addr;
		SOCKET			socket;
		BYTE			pSessionData[1];
	} PER_IO_OPERATION_DATA, *LPPER_IO_OPERATION_DATA;    //定义一个结构体保存IO数据

	typedef std::vector<HANDLE> ThreadHandleList;

	typedef std::set<LPPER_IO_OPERATION_DATA> OperationDataSet;

	typedef std::map<ULONG, LONG> IPConnectionsMap;

	typedef std::list<SOCKET> ClientSocketList;
private:
	static DWORD WINAPI ServerThread(LPVOID lpParameter);
	void ServerProc();

	static DWORD WINAPI WorkerThread(LPVOID lpParameter);
	void WorkerProc();

	static DWORD WINAPI TimeoutCheckThread(LPVOID lpParameter);
	void TimeoutCheckProc();

	void SetIPOrNameForList(const SingleIPOrNameSet& ipSet);
	BOOL IsAllowedIP(IN_ADDR ip) const;
	BOOL IsConnectionExceed(IN_ADDR ip);
	void DecreaseConnectionByIP(IN_ADDR ip, BOOL bEnterCriticalSection = TRUE);
	BOOL Hostname2IP(LPCTSTR hostname, CString& ip) const;

	void AddData(LPPER_IO_OPERATION_DATA lpData);
	void RemoveData(LPPER_IO_OPERATION_DATA lpData);

private:
	BOOL				m_bRunning;				//运行状态
	UINT				m_port;					//监听端口
	HANDLE				m_hServerThread;		//服务线程
	HANDLE				m_hTimeCheckThread;		//沉默超时检查线程
	ThreadHandleList	m_workerThreadList;		//工作者线程句柄列表
	HANDLE				m_hCompletionPort;		//完成端口句柄
	DWORD				m_dwSessionDataSize;	//会话数据大小
	SOCKET				m_sServer;				//监听socket
	DWORD				m_dwSilenceTimeoutS;	//沉默超时时间（客户端连接上后沉默超过这个时间，就断开连接）
	DWORD				m_dwWorkerThreadNumCpuMultipe;	//工作车线程是cpu核数的多少倍
	DWORD				m_dwMaxConnectionPerIP;	//每IP最大连接数 INFINITE为不限制

	FnTcpMsgHandler		m_fnCallback;			//消息处理回调函数
	LPVOID				m_lpParameter;			//回调函数的参数

	FnTcpDisconnectNotify	m_fnDisconnectNotify;//连接断开通知回调函数
	LPVOID				m_lpDisconnectNotifyParameter;//连接断开通知回调函数的参数

	CRITICAL_SECTION	m_dataSection;			//数据访问临界区
	OperationDataSet	m_dataSet;				//数据set，用于存储overlapped数据
	IPConnectionsMap	m_ipConnectionsMap;		//每个ip已建立的连接数 映射表
	ClientSocketList	m_clientSocketList;		//客户端套接字集合
	HANDLE				m_hExitEvent;			//退出通知

	BOOL				m_bIPSetAvailable;		//黑名单或者白名单是否可用
	BOOL				m_bIsBlackIPSet;		//是否是黑名单
	SingleIPOrNameSet	m_ipSet;				//黑名单或者白名单IP列表
	SingleIPOrNameSet	m_nameSet;				//黑名单或者摆名单域名
};
