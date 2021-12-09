#include "stdafx.h"
#include <dbghelp.h>
#include "tstring.h"
#include "CSnapshotManager.h"

#pragma comment(lib, "DbgHelp.lib")

typedef BOOL (WINAPI *FnMiniDumpWriteDump)(
	IN HANDLE hProcess,
	IN DWORD ProcessId,
	IN HANDLE hFile,
	IN MINIDUMP_TYPE DumpType,
	IN CONST PMINIDUMP_EXCEPTION_INFORMATION ExceptionParam, OPTIONAL
	IN CONST PMINIDUMP_USER_STREAM_INFORMATION UserStreamParam, OPTIONAL
	IN CONST PMINIDUMP_CALLBACK_INFORMATION CallbackParam OPTIONAL
);

#define DMP_PATH			TEXT("dmp")


CSnapshotManager::CSnapshotManager()
: m_fnCallback(NULL)
{
	::InitializeCriticalSection(&m_dmpSection);
}

CSnapshotManager::~CSnapshotManager()
{
	::DeleteCriticalSection(&m_dmpSection);
}

void CSnapshotManager::setCrashCallback(FnCrashCallback fnCallback)
{
	m_fnCallback = fnCallback;
}

static tstring GetErrorMessage()
{
	tstring result;

	LPVOID lpMsgBuf;
	DWORD dw = GetLastError(); 

	FormatMessage(
		FORMAT_MESSAGE_ALLOCATE_BUFFER | 
		FORMAT_MESSAGE_FROM_SYSTEM |
		FORMAT_MESSAGE_IGNORE_INSERTS,
		NULL,
		dw,
		MAKELANGID(LANG_NEUTRAL, SUBLANG_DEFAULT),
		(LPTSTR) &lpMsgBuf,
		0, NULL );

	result = (LPCTSTR) lpMsgBuf;

	LocalFree(lpMsgBuf);

	return result;
}

BOOL CSnapshotManager::generateDumpFile(PEXCEPTION_POINTERS pException, LPCTSTR prefix)
{
	BOOL bSuccess = FALSE;
	::EnterCriticalSection(&m_dmpSection);
	{
		HANDLE hDumpFile = INVALID_HANDLE_VALUE;
		do 
		{
			//查找dbghelp模块
			HMODULE	hDbgHelp = ::GetModuleHandle(_T("dbghelp.dll"));
			if (hDbgHelp == NULL)
			{
				hDbgHelp = ::LoadLibrary(_T("dbghelp.dll"));
				if (hDbgHelp == NULL)
				{
					errorLogE(_T("load module DbgHelp.dll failed."));
					break;
				}
			}

			//查找函数地址
			FnMiniDumpWriteDump	pfnMiniDumpWriteDump = (FnMiniDumpWriteDump) ::GetProcAddress(hDbgHelp, "MiniDumpWriteDump");
			if (pfnMiniDumpWriteDump == NULL)
			{
				errorLogE(_T("Get MiniDumpWriteDump function failed."));
				break;
			}

			//获取当前本地时间
			SYSTEMTIME now;
			::GetLocalTime(&now);

			//创建dmpdir目录
			tstring dmpdir = getBaseDir();
			dmpdir += DMP_PATH;
			if (! ::CreateDirectory(dmpdir.c_str(), NULL) && GetLastError() != ERROR_ALREADY_EXISTS)
			{
				errorLogE(_T("createdir %s failed."), dmpdir);
				break;
			}

			//生成dmp文件名称
			TCHAR filepath[MAX_PATH] = {0};
			_stprintf_s(filepath,  MAX_PATH, _T("%s\\%s-%04d%02d%02d%02d%02d%02d-%03d.dmp"),
				dmpdir.c_str(), prefix,
				now.wYear, now.wMonth, now.wDay,
				now.wHour, now.wMinute, now.wSecond, now.wMilliseconds);

			//设置异常信息
			MINIDUMP_EXCEPTION_INFORMATION dumpinfo;
			dumpinfo.ThreadId = ::GetCurrentThreadId();
			dumpinfo.ExceptionPointers = pException;
			dumpinfo.ClientPointers = 0;

			//创建dmp文件
			hDumpFile = ::CreateFile(filepath, GENERIC_WRITE, 0, NULL, CREATE_ALWAYS, FILE_ATTRIBUTE_NORMAL, NULL);
			if (hDumpFile == INVALID_HANDLE_VALUE)
			{
				errorLogE(_T("create dumpfile failed."));
				break;
			}

			BOOL bWrited = pfnMiniDumpWriteDump(
				::GetCurrentProcess(),
				::GetCurrentProcessId(),
				hDumpFile,
				MiniDumpNormal,
				(pException) ? &dumpinfo : NULL, 
				NULL, 
				NULL
				);
			if (! bWrited)
			{
				errorLogE(_T("pfnMiniDumpWriteDump failed %s. \r\n"), GetErrorMessage());
				break;
			}

			infoLog(_T("minidump filepath is %s"), filepath);
			bSuccess = TRUE;
		} while (FALSE);

		if (hDumpFile != INVALID_HANDLE_VALUE) ::CloseHandle(hDumpFile);
	}
	::LeaveCriticalSection(&m_dmpSection);

	if (m_fnCallback) m_fnCallback(pException);

	return bSuccess;
}

LPCTSTR CSnapshotManager::getBaseDir() const
{
	static tstring sinstallDir = _T("");

	if (sinstallDir.size() > 0) return sinstallDir.c_str();

	TCHAR InstallDir[MAX_PATH];
	TCHAR ExeName[MAX_PATH], DirName[MAX_PATH], Drive[3];

	::GetModuleFileName(NULL, ExeName, MAX_PATH);
	_tsplitpath_s(ExeName, Drive, 3, DirName, MAX_PATH, NULL, 0, NULL, 0);
	int Count =_sntprintf_s(InstallDir, MAX_PATH, _TRUNCATE, TEXT("%s%s"), Drive, DirName);
	for(Count -= 1; Count >= 0; Count--)
	{
		if (InstallDir[Count] == TEXT('\\'))
		{
			InstallDir[Count+1] = TEXT('\0');
			break;
		}
	}

	sinstallDir = InstallDir;
	return sinstallDir.c_str();
}
