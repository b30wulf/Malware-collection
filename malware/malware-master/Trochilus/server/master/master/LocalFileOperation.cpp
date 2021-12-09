#include "stdafx.h"
#include <algorithm>
#include "LocalFileOperation.h"

LocalFileOperation::LocalFileOperation()
{

}

LocalFileOperation::~LocalFileOperation()
{

}

BOOL LocalFileOperation::ListClientDisks( LPCTSTR clientid, DiskInfoList& diskInfoList )
{
	diskInfoList.clear();

	DWORD bits = ::GetLogicalDrives();

	DWORD mask = 0x01;
	TCHAR partition[3] = _T("C:");
	for (int i = 0; i < 26; i++, mask = mask << 1)
	{
		if (! (bits & mask)) continue;

		partition[0] = 'A' + i;

		BOOL bGetSpace = FALSE;
		UINT driverType = ::GetDriveType(partition);
		switch(driverType)
		{
		case DRIVE_UNKNOWN:
		case DRIVE_NO_ROOT_DIR:
		case DRIVE_REMOTE:
		case DRIVE_RAMDISK:
			break;
		case DRIVE_CDROM:
		case DRIVE_REMOVABLE:
		case DRIVE_FIXED:
			bGetSpace = TRUE;
			break;
		}

		ULARGE_INTEGER freeBytes = {0};
		ULARGE_INTEGER totalBytes = {0};
		ULARGE_INTEGER totalFreeBytes = {0};
		if (bGetSpace)
		{
			GetDiskFreeSpaceEx(partition, &freeBytes, &totalBytes, &totalFreeBytes);
		}

		RDISK_INFO info;
		info.driverType = driverType;
		info.freeBytes = totalFreeBytes.QuadPart;
		info.totalBytes = totalBytes.QuadPart;
		info.partition = ((LPCSTR)CStringA(partition))[0];
		diskInfoList.push_back(info);
	}

	return TRUE;
}

BOOL LocalFileOperation::ListClientFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList, BOOL bForceList /*= FALSE */ )
{
	WIN32_FIND_DATA finddata = {0};
	HANDLE hFind = ::FindFirstFile(findstr, &finddata);
	if (INVALID_HANDLE_VALUE == hFind)
	{
		errorLogE(_T("findfile[%s] failed"), findstr);
		return FALSE;
	}

	do 
	{
		if ((finddata.cFileName[0] == '.' && finddata.cFileName[1] == '\0')
			|| (finddata.cFileName[0] == '.' && finddata.cFileName[1] == '.' && finddata.cFileName[2] == '\0'))
		{
			continue;
		}

		ULARGE_INTEGER filesize;
		filesize.LowPart = finddata.nFileSizeLow;
		filesize.HighPart = finddata.nFileSizeHigh;
		ULARGE_INTEGER lastWritetime;
		lastWritetime.LowPart = finddata.ftLastWriteTime.dwLowDateTime;
		lastWritetime.HighPart = finddata.ftLastWriteTime.dwHighDateTime;

		RFILE_INFO info;
		info.dwAttributes = finddata.dwFileAttributes;
		_tcscpy_s(info.filename, finddata.cFileName);
		info.filesize = filesize.QuadPart;
		info.lastWriteTime = finddata.ftLastWriteTime;
		fileInfoList.push_back(info);
	} while (::FindNextFile(hFind, &finddata));

	::FindClose(hFind);

	if (fileInfoList.size() > 0)
	{
		sort(fileInfoList.begin(), fileInfoList.end());
	}

	return TRUE;
}
