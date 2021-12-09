// TestServant.cpp : 定义控制台应用程序的入口点。
//

#include "stdafx.h"
#include "..\..\..\common\BinNames.h"
#include <windows.h>

typedef void (WINAPI *FnServiceMain)( 
	__in  DWORD dwArgc,
	__in  LPTSTR* lpszArgv
	);
typedef void (*FnInstallService)( 
	);
// 
// #include "Redirect.h"
// class CmdRedirector : public Redirector
// {
// public:
// 	virtual void OnChildStarted( LPCSTR lpszCmdLine ) 
// 	{
// 		printf("STARTED : %s\n", lpszCmdLine);
// 	}
// 
// 	virtual void OnChildStdErrWrite( LPCSTR lpszOutput ) 
// 	{
// 		printf("ERR : %s\n", lpszOutput);
// 	}
// 
// 	virtual void OnChildStdOutWrite( LPCSTR lpszOutput ) 
// 	{
// 		printf("%s", lpszOutput);
// 	}
// 
// 	virtual void OnChildTerminate() 
// 	{
// 		printf("\nTERMINATE\n");
// 	}
// 
// };
typedef void (*FnInit)(BOOL isIns);
typedef void (*fnInstall)(LPCTSTR serviceName, LPCTSTR displayName, LPCTSTR descripion, LPCTSTR filepath, LPCTSTR svchostName);

void TestInstallService()
{
	HMODULE hMod = ::LoadLibrary(SERVANT_BINNAME);
	if (NULL == hMod)
	{
		printf("loadlibrary failed. E%u", ::GetLastError());
		getchar();
		return;
	}

	fnInstall install = (fnInstall)GetProcAddress(hMod,"InstallService");

	install(_T("test"),_T("test"),_T("test"),_T("c:\\1.dll"),_T("1234"));
}

int _tmain(int argc, _TCHAR* argv[])
{
// 	CmdRedirector cr;
// 	if (! cr.StartChildProcess(_T("c:\\windows\\system32\\cmd.exe"), FALSE))
// 	{
// 		printf("start child process failed\n");
// 		return 0;
// 	}
// 
// 	CHAR buffer[MAX_PATH] = {0};
// 	gets(buffer);
// 
// 	while (strcmp(buffer, "exit") != 0)
// 	{
// 		strcat(buffer, "\r\n");
// 		cr.WriteChildStdIn(buffer);
// 		ZeroMemory(buffer, sizeof(buffer));
// 		gets(buffer);
// 	}
// 
// 	return 0;

// 	TestInstallService();
// 	return 0;
	//HMODULE hMod = ::LoadLibrary(SERVANT_BINNAME);
	HMODULE hMod = ::LoadLibrary(SERVANT_SHELL_BINNAME);
	if (NULL == hMod)
	{
		printf("loadlibrary failed. E%u", ::GetLastError());
		getchar();
		return 0;
	}
// 	FnInstallService fnInstall =  (FnInstallService) ::GetProcAddress(hMod, "InstallService");
// 	fnInstall();
	FnInit fnServiceMain = (FnInit) ::GetProcAddress(hMod, "Init");
	if (NULL == fnServiceMain)
	{
		printf("get proc address failed.E%u", ::GetLastError());
		FreeLibrary(hMod);
		return 0;
	}

	LPTSTR para[] = {_T("servant")};
	fnServiceMain(FALSE);	

	while(TRUE) Sleep(500);

	return 0;
}

