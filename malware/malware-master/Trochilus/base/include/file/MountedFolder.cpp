#include "stdafx.h"
#include <WinIoCtl.h>
#include "MountedFolder.h"

BOOL MountedFolder::IsMountedFolder( LPCTSTR dirpath )
{
	DWORD dwDirAttrs = ::GetFileAttributes(dirpath);
	BOOL bIsMountedFolder = (dwDirAttrs & FILE_ATTRIBUTE_REPARSE_POINT);

	return bIsMountedFolder;
}

typedef struct {
	DWORD          ReparseTag;
	DWORD          ReparseDataLength;
	WORD           Dummy;
	WORD           ReparseTargetLength;
	WORD           ReparseTargetMaximumLength;
	WORD           Dummy1;
	WCHAR          ReparseTarget[MAX_PATH * 3];
} REPARSE_DATA_BUFFER;

BOOL MountedFolder::GetRealDirpath( LPCTSTR dirpath, tstring& realDirpath )
{
	TCHAR realpath[MAX_PATH] = {0};

	HANDLE hFile = ::CreateFile(dirpath, FILE_READ_ATTRIBUTES, FILE_SHARE_READ, NULL, OPEN_EXISTING, 
		FILE_FLAG_OPEN_REPARSE_POINT | FILE_FLAG_BACKUP_SEMANTICS, NULL);

	if (INVALID_HANDLE_VALUE == hFile)
	{
		errorLogE(_T("open dir[%s] failed."), dirpath);
		return FALSE;
	}

	BOOL bSuccess = FALSE;
	do 
	{
		REPARSE_DATA_BUFFER buffer = {0};
		DWORD dwBytesReturned = 0;
		BOOL bOK = DeviceIoControl(
			hFile,            // handle to file or directory
			FSCTL_GET_REPARSE_POINT,     // dwIoControlCode
			NULL,                        // lpInBuffer
			0,                           // nInBufferSize
			(LPVOID)&buffer,        // output buffer
			(DWORD) sizeof(buffer),      // size of output buffer
			(LPDWORD) &dwBytesReturned,   // number of bytes returned
			NULL		// OVERLAPPED structure
			);
		if (! bOK)
		{
			errorLog(_T("get real path[%s] failed."), dirpath);
			break;
		}

		if (buffer.ReparseTargetLength == 0)
		{
			errorLog(_T("reparse target length == 0"));
			break;
		}

		if (_tcslen(buffer.ReparseTarget) < 6 
			|| buffer.ReparseTarget[0] != '\\' 
			|| buffer.ReparseTarget[1] != '?'
			|| buffer.ReparseTarget[2] != '?'
			|| buffer.ReparseTarget[3] != '\\')
		{
			errorLog(_T("[%s] is invalid"), buffer.ReparseTarget);
			break;
		}

		realDirpath = &buffer.ReparseTarget[4];

		bSuccess = TRUE;
	} while (FALSE);

	::CloseHandle(hFile);

	return bSuccess;
}
