#pragma once
#include "CommData.h"
#include "CmdShellData.h"

class CmdShell
{
public:
	CmdShell();
	~CmdShell();

	BOOL Init(LPCTSTR clientid, FnRemoteCmdOutput fnRemoteCmdOutput, LPVOID lpParameter);
	void RecvRemoteOutput(LPCSTR output);
	BOOL ExecuteCmd(LPCTSTR cmdline, BOOL bAddRN);
	void Close();

private:
	BOOL AskAndWaitForReply(const CommData& request, CommData& reply, int iWaitRound = 50, DWORD dwSleepIntervalMS = 200) const;

private:
	tstring				m_clientid;
	FnRemoteCmdOutput	m_fnRemoteCmdOutput;
	LPVOID				m_lpParameter;
};
