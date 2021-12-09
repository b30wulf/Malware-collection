#pragma once
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

//获取servant.dll所属目录，返回值以\结尾
LPCTSTR GetBinFilepath();

//获取当前dll的文件名称
LPCTSTR GetBinFilename();

//异或加解密
void XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1);

//获取操作系统版本
WIN_VER_DETAIL GetWindowsVersion();

//获取操作系统平台
BOOL IsWow64();

#define DECLARE_TIMES(_prefix) FILETIME _prefix##CreationTime = {0}, _prefix##LastAccessTime = {0}, _prefix##LastWriteTime = {0};
#define TIMES_PARAM(_prefix) _prefix##CreationTime, _prefix##LastAccessTime, _prefix##LastWriteTime
//获取文件的时间属性
BOOL GetFileTimes(LPCTSTR filepath, BOOL bIsDir, FILETIME& ftCreationTime, FILETIME& ftLastAccessTime, FILETIME& ftLastWriteTime);

//设置文件或目录的时间
BOOL SetFileTimes(LPCTSTR filepath, BOOL bIsDir, const FILETIME& ftCreationTime, const FILETIME& ftLastAccessTime, const FILETIME& ftLastWriteTime);
