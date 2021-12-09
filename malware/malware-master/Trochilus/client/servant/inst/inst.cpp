// inst.cpp : 定义控制台应用程序的入口点。
//

#include "stdafx.h"
#include "tstring.h"
#include "ConfigInfo.h"
#include <windows.h>


static tstring getModFilePath()
{
	tstring binFilePath;

	HMODULE hMod = NULL;
	// 	if (sLoadModName.size() > 0)
	// 	{
	// 		hMod = ::GetModuleHandle(sLoadModName.c_str());
	// 	}

	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize + 1] = '\0';
	binFilePath = baseName;

	return binFilePath.c_str();	
}
static BOOL StartService(LPCTSTR serviceName)
{
	SC_HANDLE hService = NULL;
	SC_HANDLE sch = ::OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

	hService = OpenService(sch,serviceName,SERVICE_ALL_ACCESS); 
	BOOL bRet= StartService( hService, NULL, NULL );  

	CloseServiceHandle(hService);

	return bRet;
}
static BOOL InstallSvchostService(LPCTSTR serviceName, LPCTSTR displayName, LPCTSTR descripion, LPCTSTR filepath, LPCTSTR svchostName)
{
	SC_HANDLE sch = ::OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if(sch==NULL)
	{
		printf("openscmanager error\n");
		return FALSE;
	}

	SC_HANDLE hService = NULL;
	HKEY hSvchostKey = NULL;
	HKEY hServiceKey = NULL;
	HKEY hParametersKey = NULL;
	BOOL bSuccess = FALSE;
	do 
	{
		LONG lRet = ::RegOpenKeyEx(HKEY_LOCAL_MACHINE, _T("SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion\\svchost"), 0, KEY_QUERY_VALUE | KEY_WRITE, &hSvchostKey);
		if(ERROR_SUCCESS != lRet)
		{
			printf("open svchost failed\n");
			break;
		}

		lRet = RegSetValueEx(hSvchostKey, svchostName, 0, REG_MULTI_SZ, (const BYTE*)serviceName, _tcslen(serviceName) * sizeof(TCHAR));
		if (ERROR_SUCCESS != lRet)
		{
			printf("set svchost set failed");
			break;
		}

		tstring binpath = _T("%SystemRoot%\\system32\\svchost.exe -k ");
		binpath += svchostName;

		hService = ::CreateService(sch, serviceName, displayName, SERVICE_ALL_ACCESS, SERVICE_WIN32_SHARE_PROCESS, SERVICE_AUTO_START,
			SERVICE_ERROR_NORMAL, binpath.c_str(), NULL, NULL, NULL, NULL, NULL);
		if(NULL == hService)
		{
			printf("error:%d\n",GetLastError());
			break;
		}
		
		SERVICE_DESCRIPTION desc;
		desc.lpDescription = (LPTSTR) descripion; 
		ChangeServiceConfig2(hService, SERVICE_CONFIG_DESCRIPTION, &desc);
		CloseServiceHandle(sch);

		tstring newserviceRegpath = _T("SYSTEM\\CurrentControlSet\\Services\\");
		newserviceRegpath += serviceName;
		lRet = ::RegOpenKeyEx(HKEY_LOCAL_MACHINE, newserviceRegpath.c_str(), 0, KEY_ALL_ACCESS, &hServiceKey);
		if(ERROR_SUCCESS != lRet)
		{
			printf("open service reg key failed %u\n", lRet);
			break;
		}

		lRet = ::RegCreateKey(hServiceKey, _T("Parameters"), &hParametersKey);
		if (ERROR_SUCCESS != lRet)
		{
			printf("create parameters key failed\n");
			break;
		}

		tstring unicodeFilepath = filepath;
		lRet = RegSetValueEx(hParametersKey, _T("ServiceDll"), 0, REG_EXPAND_SZ, (const BYTE*)unicodeFilepath.c_str(), unicodeFilepath.size() * sizeof(TCHAR));
		if (ERROR_SUCCESS != lRet)
		{
			printf("set servicedll failed");
			break;
		}

		bSuccess = TRUE;
	} while (FALSE);
	
	if (NULL != hParametersKey) ::RegCloseKey(hParametersKey);
	if (NULL != hServiceKey) ::RegCloseKey(hServiceKey);
	if (NULL != hService) ::CloseServiceHandle(hService);
	if (NULL != sch) ::CloseServiceHandle(sch);
	if (NULL != hSvchostKey) ::RegCloseKey(hSvchostKey);

	return bSuccess;
}

BOOL DeleteSvchostService(LPCTSTR serviceName, LPCTSTR svchostName)
{
	SC_HANDLE sch = ::OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if(sch==NULL)
	{
		printf("openscmanager error\n");
		return FALSE;
	}

	SC_HANDLE hService = NULL;
	HKEY hSvchostKey = NULL;
	BOOL bSuccess = FALSE;
	do 
	{
		hService = OpenService(sch, serviceName, SERVICE_ALL_ACCESS);
		if (NULL == hService)
		{
			printf("open service failed\n");
			break;
		}

		if (! ::DeleteService(hService))
		{
			printf("delete service failed\n");
			break;
		}

		LONG lRet = ::RegOpenKeyEx(HKEY_LOCAL_MACHINE, _T("SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion\\svchost"), 0, KEY_QUERY_VALUE | KEY_WRITE, &hSvchostKey);
		if(ERROR_SUCCESS != lRet)
		{
			printf("open svchost failed\n");
			break;
		}

		::RegDeleteValue(hSvchostKey, svchostName);

		bSuccess = TRUE;
	} while (FALSE);

	if (NULL != hService) ::CloseServiceHandle(hService);
	if (NULL != sch) ::CloseServiceHandle(sch);
	if (NULL != hSvchostKey) ::RegCloseKey(hSvchostKey);

	return bSuccess;
}

static SERVICE_INFO g_ServiceInfo = { CONNECT_FLAG };

int _tmain(int argc, _TCHAR* argv[])
{
	tstring filepath = getModFilePath();
	filepath += _T("servant.dll");

//	if (_tcscmp(argv[1], _T("-i")) == 0)
	{
		BOOL bRet = InstallSvchostService(a2t(g_ServiceInfo.szServiceName), a2t(g_ServiceInfo.szDisplayName), a2t(g_ServiceInfo.szServiceDecript), filepath.c_str(), _T("XLServant"));
		if (bRet)
			StartService(a2t(g_ServiceInfo.szServiceName));
		printf("install service ret %d", bRet);
		return 0;
	}
//	else if (_tcscmp(argv[1], _T("-u")) == 0)
	{
		BOOL bRet = DeleteSvchostService(a2t(g_ServiceInfo.szServiceName), _T("XLServant"));
		printf("uninstall service ret %d", bRet);
	}


	return 0;
}

