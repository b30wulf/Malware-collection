#pragma once
#include <map>
#include "ClientInfoCallbacks.h"
#include "thread/RepeatTask.h"


//客户端基本信息
typedef std::map<tstring, MSGSERIALID> MsgSerialIDMap;

typedef struct  
{
	BOOL			bValid;

	ULONG			connectIP;	//和服务端连接的IP
	tstring			computerName;
	tstring			vercode;
	WIN_VER_DETAIL	windowsVersion;	//操作系统
	BOOL			bX64;	//是否x64平台
	__time64_t		installTime;	//客户端安装时间
	TStringVector	localIPList;

	__time64_t		reportTime;	//上报时间

	MSGSERIALID		requestSerialID;	//请求客户端报告数据的序列号id
	__time64_t		requestTime;	//请求上报的时间
	tstring			mods;	//客户端安装的模块列表
	tstring			groups; //分组名
	tstring			priv; //权限
	tstring			lang;
	COMM_NAME		commtype;
	int				cpunum;
	int				cpufrep;
	int				memsize;
	MsgSerialIDMap	installModMsgIDMap;	//进行模块安装的消息id映射
	BOOL bAdd;
} CLIENT_BASE_INFO;

typedef std::map<tstring, CLIENT_BASE_INFO> ClientBaseInfoMap;

class ClientInfoManager
{
	DECLARE_SINGLETON(ClientInfoManager);
public:
	BOOL StartWorking();
	BOOL GetClientBaseInfo(LPCTSTR clientid, CLIENT_BASE_INFO& baseInfo);
	void SetCallbacks(FnNotifyProc fnCallback, LPVOID lpParameter);
	void TransferInfo(LPCTSTR clientid, const CLIENT_BASE_INFO* pBaseInfo, CLIENT_INFO& info) const;

	void HandleInstalMsg(tstring clientid,CLIENT_BASE_INFO& info);

	static BOOL MsgHandler_OutputError( MSGID msgid, const CommData& commData, LPVOID lpParameter );
	static BOOL MsgHandler_ClientInfo( MSGID msgid, const CommData& commData, LPVOID lpParameter );

private:
	static DWORD WINAPI CheckInfoTask(LPVOID lpParameter);
	void CheckInfoProc();
	MSGSERIALID RequestReportInfo(LPCTSTR clientid) const;
	BOOL ParseData(const CommData& commData, CLIENT_BASE_INFO& baseInfo) const;

private:
	CriticalSection		m_infoMapSection;
	ClientBaseInfoMap	m_clientBaseInfoMap;
	RepeatTask			m_checkInfoTask;

	FnModuleNotifyProc m_fnModuleCallback;
	FnNotifyProc		m_fnCallback;
	LPVOID				m_lpParameter;
};
