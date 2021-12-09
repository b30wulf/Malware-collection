#include "stdafx.h"
#include "tstring.h"
#include "ods.h"

#include "file/MyFile.h"
static CriticalSection g_fileSection;

void OutputFile(LPCTSTR content)
{
	static BOOL bOpened = FALSE;
	g_fileSection.Enter();
	{
		do 
		{
			HANDLE hFile = CreateFile(_T("C:\\ods.log"), GENERIC_READ | GENERIC_WRITE, FILE_SHARE_READ | FILE_SHARE_WRITE,NULL,OPEN_ALWAYS,FILE_ATTRIBUTE_NORMAL,0);
			if (hFile != INVALID_HANDLE_VALUE)
				::SetFilePointer(hFile, 0, NULL, FILE_END);
			else
				break;

			std::string ansiContent = t2a(content);
			ansiContent += "\r\n";
			DWORD dwWritten = 0 ;
			WriteFile(hFile,(LPBYTE)ansiContent.c_str(), ansiContent.size(),&dwWritten,0);

			CloseHandle(hFile);
		} while (FALSE);
	}
	g_fileSection.Leave();
}

void WriteDebugLog(DWORD dwLastError, LPCSTR file, int codeLine, LOG_LEVEL level, LPCTSTR content, ...)
{
	//格式化日志
	TCHAR logContent[ODS_LOG_MAXLENGTH + 1] = {0};

	DWORD dwThreadID = ::GetCurrentThreadId();
	std::string ansiCodefile = file;
	std::string::size_type pos = ansiCodefile.find_last_of('\\');
	if (pos != std::string::npos && pos + 1 < ansiCodefile.size()) ansiCodefile = ansiCodefile.substr(pos + 1);
	tstring codeFile = s2ws(ansiCodefile);

	tstring strLevel;
	switch (level)
	{
	case ODSLEVEL_DEBUG:
		strLevel = _T("debug");
		break;
	case ODSLEVEL_INFO:
		strLevel = _T("info");
		break;
	case ODSLEVEL_ERROR:
		strLevel = _T("error");
		break;
	default:
		strLevel = _T("unknown");
		break;
	}

	int iWritten = _stprintf_s(logContent, _T("%s [%s:%d] %u "), strLevel.c_str(), codeFile.c_str(), codeLine, dwThreadID);

	va_list ap;
	va_start(ap, content);
	_vsntprintf_s(logContent + iWritten, ODS_LOG_MAXLENGTH - iWritten, _TRUNCATE, content, ap);
	va_end(ap);

	if (dwLastError != 0)
	{
		TCHAR lastError[16] = {0};
		_stprintf_s(lastError, 15, _T(" E%u"), dwLastError);
		
		size_t len = _tcslen(logContent);
		if (len + _tcslen(lastError) < ODS_LOG_MAXLENGTH)
		{
			_tcscat_s(logContent, lastError);
		}
	}

// 	//写入日志
// #ifdef ODS_OUTPUT_STD
	printf("%s\n", t2a(logContent));
// #endif
#ifdef ODS_OUTPUT_FILE
 	OutputFile(logContent);
#endif
	OutputDebugString(logContent);
}
