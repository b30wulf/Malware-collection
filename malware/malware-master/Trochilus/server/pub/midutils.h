#pragma once

#include <afxwin.h>
#include <afxinet.h>

#ifndef MIDUTILS_LIB_API
#ifdef MIDUTILS_EXPORTS
#define MIDUTILS_LIB_API __declspec(dllexport)
#else
#define MIDUTILS_LIB_API __declspec(dllimport)
#endif
#endif

#ifdef __cplusplus
extern "C" {
#endif

MIDUTILS_LIB_API bool GetCountryCityByIp(const wchar_t* lpIPAddress, wchar_t* lpCountryCity, unsigned nMaxLength);
MIDUTILS_LIB_API bool Filetime2Time(const FILETIME& filetime, __time64_t* pTime);
MIDUTILS_LIB_API bool Time2Filetime(__time64_t time, FILETIME* pFiletime);
MIDUTILS_LIB_API bool MakeUpDirs(LPCTSTR dirs);
MIDUTILS_LIB_API void getBinFilePath(LPWSTR path, DWORD size);
#ifdef __cplusplus
};
#endif

