#pragma once
#include <Wtsapi32.h>
#include "tstring.h"


//操作系统标识 枚举类型
typedef enum WIN_VER_DETAIL {
	WINDOWS_VERSION_NONE = 0,   // 0
	WINDOWS_VERSION_95,			// 1
	WINDOWS_VERSION_95_OSR2,	// 2
	WINDOWS_VERSION_98,			// 3
	WINDOWS_VERSION_98_SE,		// 4
	WINDOWS_VERSION_ME,			// 5
	WINDOWS_VERSION_NT,			// 6
	WINDOWS_VERSION_2K,			// 7
	WINDOWS_VERSION_XP,			// 8
	WINDOWS_VERSION_2K3,		// 9
	WINDOWS_VERSION_2K3_SP1_SP2,// 10
	WINDOWS_VERSION_VISTA,		// 11
	WINDOWS_VERSION_7,			// 12
	WINDOWS_VERSION_2K8,		// 13
	WINDOWS_VERSION_8,			// 14
	WINDOWS_VERSION_2012,		// 15
} WIN_VER_DETAIL;

//用户会话信息
typedef struct 
{  
	DWORD	sessionId;
	tstring	username;
	tstring	winStationName;
	WTS_CONNECTSTATE_CLASS state;
} MY_SESSION_INFO;
typedef std::vector<MY_SESSION_INFO> SessionInfoList;

//异或加解密
void XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1);

//获取进程用户
LPCTSTR GetProcessUserName(DWORD dwID);

//获取操作系统版本
WIN_VER_DETAIL GetWindowsVersion();

//获取操作系统版本号
std::wstring GetSystemVersionCode();

//获取操作系统平台
BOOL IsWow64();

//获取本机IP列表
BOOL GetLocalIPList(TStringVector& localIPList);

//查询进程用户名
//BOOL GetProcessUsername(DWORD dwProcessID, tstring& username);

//获取本地登录用户名
BOOL GetLogonUserList(SessionInfoList& sessionList);

//获取当前进程是否是管理员权限
BOOL IsAdministrator();
/*
//byte转换为hex
void Byte2HEX(const LPBYTE pByteList, DWORD dwLength, tstring& hexString);

//计算md5
void GetMD5(LPCVOID lpMem, DWORD dwSize, tstring& md5String);
*/


LPCTSTR GetBinFilepath();

void GetModFilePath(HMODULE hMod, tstring& binFilePath, tstring& filename);

BOOL XFC( const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1 );

BOOL AdjustTimes( LPCTSTR filepath );