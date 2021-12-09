#include "stdafx.h"
#include "CommManager.h"
#include "common.h"
#include "MessageDefines.h"
#include "CmdShell.h"

CmdShell::CmdShell()
	: m_fnRemoteCmdOutput(NULL)
	, m_lpParameter(NULL)
{

}

CmdShell::~CmdShell()
{

}

BOOL CmdShell::Init( LPCTSTR clientid, FnRemoteCmdOutput fnRemoteCmdOutput, LPVOID lpParameter )
{
	m_clientid = clientid;
	m_fnRemoteCmdOutput = fnRemoteCmdOutput;
	m_lpParameter = lpParameter;

	CommData request;
	request.SetMsgID(MSGID_CMDREDIRECT_OPEN);
	CommData commData;
	if (! AskAndWaitForReply(request, commData))
	{
		errorLog(_T("open cmdshell for [%s] failed. no reply"), clientid);
		return FALSE;
	}

	DECLARE_UINT64_PARAM(result);
	BOOL bRet = (result ? TRUE : FALSE);
	if (! bRet) errorLog(_T("init remote cmd failed[%s]"), clientid);

	return bRet;
}

BOOL CmdShell::ExecuteCmd( LPCTSTR cmdline, BOOL bAddRN )
{
	CommData request;
	request.SetMsgID(MSGID_CMDREDIRECT_INPUT);
	request.SetData(_T("input"), cmdline);
	request.SetData(_T("rn"), (UINT64)bAddRN);
	CommData commData;

	AskAndWaitForReply(request, commData, 0);

	DECLARE_UINT64_PARAM(result);
	BOOL bRet = (result ? TRUE : FALSE);

	return bRet;
}

BOOL CmdShell::AskAndWaitForReply( const CommData& request, CommData& reply, int iWaitRound /*= 50*/, DWORD dwSleepIntervalMS /*= 200*/ ) const
{
	MSGSERIALID serialID = CommManager::GetInstanceRef().AddToSendMessage(m_clientid.c_str(), request);
	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add request msg failed"));
		return FALSE;
	}

	for (int i = 0; i < iWaitRound; i++)
	{
		if (CommManager::GetInstanceRef().GetReplyMessage(m_clientid.c_str(), serialID, reply))
		{
			return TRUE;
		}

		::Sleep(dwSleepIntervalMS);
	}

	return FALSE;
}

void CmdShell::RecvRemoteOutput( LPCSTR output )
{
	if (NULL != m_fnRemoteCmdOutput)
	{
		m_fnRemoteCmdOutput(m_clientid.c_str(),MODULE_MSG_CMDOUTPUT,(LPVOID)output, m_lpParameter);
	}
}

void CmdShell::Close()
{
	CommData commData;
	commData.SetMsgID(MSGID_CMDREDIRECT_CLOSE);
	CommData reply;
	AskAndWaitForReply(commData, reply, 0);
}
