#pragma once
#include <Windows.h>
#include <tchar.h>

#if !defined FILETRANSFER_LIB_API
#if defined FILETRANSFER_EXPORTS
#define FILETRANSFER_LIB_API __declspec(dllexport)
#elif defined FILETRANSFER_STAT_EXPORTS
#define FILETRANSFER_LIB_API 
#else
#define FILETRANSFER_LIB_API __declspec(dllimport)
#endif
#endif

#ifdef __cplusplus
extern "C" {
#endif

FILETRANSFER_LIB_API BOOL SetFileServer(TCHAR (*pszAddress)[MAX_PATH], INT nCount);
FILETRANSFER_LIB_API DWORD StartFileTransfer(LPCTSTR lpFileName, DWORD dwIntervalTime);
FILETRANSFER_LIB_API BOOL StopFileTransfer(UINT uTaskId);
FILETRANSFER_LIB_API BOOL ViewFileTransferTask(TCHAR (*pszFilePath)[MAX_PATH]);

#ifdef __cplusplus
};
#endif