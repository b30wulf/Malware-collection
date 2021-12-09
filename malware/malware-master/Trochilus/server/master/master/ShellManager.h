#pragma once
#include <map>
#include "CmdShell.h"

class ShellManager
{
	DECLARE_SINGLETON(ShellManager);
public:
	BOOL OpenShell(LPCTSTR clientid, FnRemoteCmdOutput fnRemoteCmdOutput, LPVOID lpParameter);
	void CloseShell(LPCTSTR clientid);
	CmdShell* GetShell(LPCTSTR clientid);

private:
	typedef std::map<tstring, CmdShell*> ShellMap;

private:
	void Clean();
	static BOOL MsgHandler_CmdOutput(MSGID msgid, const CommData& commData, LPVOID lpParameter);
	static BOOL MsgHandler_CmdInput(MSGID msgid, const CommData& commData, LPVOID lpParameter);
	static BOOL MsgHandler_CmdClose(MSGID msgid, const CommData& commData, LPVOID lpParameter);

private:
	ShellMap		m_shellMap;
	CriticalSection	m_mapSection;
};
