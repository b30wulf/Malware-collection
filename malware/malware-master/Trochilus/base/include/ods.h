#pragma once

#define ODS_LOG_MAXLENGTH 1024

typedef enum
{
	ODSLEVEL_DEBUG = 0,
	ODSLEVEL_INFO,
	ODSLEVEL_ERROR,
} LOG_LEVEL;

void WriteDebugLog(DWORD dwLastError, LPCSTR file, int codeLine, LOG_LEVEL level, LPCTSTR content, ...);

#define DebugODS(fmt, ...) WriteDebugLog(0, __FILE__, __LINE__, ODSLEVEL_DEBUG, fmt, __VA_ARGS__)

#define DebugODSE(fmt, ...) WriteDebugLog(::GetLastError(), __FILE__, __LINE__, ODSLEVEL_DEBUG, fmt, __VA_ARGS__)

#define InfoODS(fmt, ...) WriteDebugLog(0, __FILE__, __LINE__, ODSLEVEL_INFO, fmt, __VA_ARGS__)

#define InfoODSE(fmt, ...) WriteDebugLog(::GetLastError(), __FILE__, __LINE__, ODSLEVEL_INFO, fmt, __VA_ARGS__)

#define ErrorODS(fmt, ...) WriteDebugLog(0, __FILE__, __LINE__, ODSLEVEL_ERROR, fmt, __VA_ARGS__)

#define ErrorODSE(fmt, ...) WriteDebugLog(::GetLastError(), __FILE__, __LINE__, ODSLEVEL_ERROR, fmt, __VA_ARGS__)
