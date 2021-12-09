#pragma once
#include <map>
#include <vector>
#include "../master/master.h"
#include "ICmd.h"

typedef std::vector<CLIENT_INFO> ClientInfoList;

class CommandManager
{
	DECLARE_SINGLETON(CommandManager);
public:
	BOOL Execute(LPCTSTR cmdline, tstring& replyText);
	void ListClientInfo(ClientInfoList& clientInfoList);

private:
	typedef std::map<tstring, ICmd*> CommandMap;
	typedef std::map<tstring, CLIENT_INFO> ClientInfoMap;

private:
	static void CALLBACK NotifyProc(UINT nMsg, LPVOID lpContext, LPVOID lpParameter);

private:
	CmdEnv		m_env;
	CommandMap	m_cmdMap;

	CriticalSection	m_mapSection;
	ClientInfoMap	m_infoMap;
};