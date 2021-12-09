#include "stdafx.h"
#include "common.h"

CString common::FormatSystemTime( const SYSTEMTIME& systime )
{
	CString datetime;
	datetime.Format(_T("%04d-%02d-%02d %02d:%02d:%02d"), 
		systime.wYear, systime.wMonth, systime.wDay,
		systime.wHour, systime.wMinute, systime.wSecond);

	return datetime;
}

CString common::FormatSystemTime(__time64_t time)
{
	SYSTEMTIME systime;

	FILETIME ftInstallTime = {0};
	if (Time2Filetime(time, &ftInstallTime))
	{
		FILETIME ftLocalInstallTime = {0};
		if (FileTimeToLocalFileTime(&ftInstallTime, &ftLocalInstallTime))
		{
			FileTimeToSystemTime(&ftLocalInstallTime, &systime);
		}
	}

	CString datetime;
	datetime.Format(_T("%04d-%02d-%02d %02d:%02d:%02d"), 
		systime.wYear, systime.wMonth, systime.wDay,
		systime.wHour, systime.wMinute, systime.wSecond);

	return datetime;
}

CString common::FormatOSDesc( WIN_VER_DETAIL ver,LPCTSTR verCode, BOOL bX64 )
{
	CString desc;
	switch(ver)
	{
	case WINDOWS_VERSION_95:
	case WINDOWS_VERSION_95_OSR2:
		desc = _T("Windows 95");
		break;
	case WINDOWS_VERSION_98:
	case WINDOWS_VERSION_98_SE:
		desc = _T("Windows 98");
		break;
	case WINDOWS_VERSION_ME:
		desc = _T("Windows Me");
		break;
	case WINDOWS_VERSION_NT:
		desc = _T("Windows NT");
		break;
	case WINDOWS_VERSION_2K:
		desc = _T("Windows 2000");
		break;
	case WINDOWS_VERSION_XP:
		desc = _T("Windows XP");
		break;
	case WINDOWS_VERSION_2K3:
		desc = _T("Windows 2003");
		break;
	case WINDOWS_VERSION_2K3_SP1_SP2:
		desc = _T("Windows 2003SP");
		break;
	case WINDOWS_VERSION_VISTA:
		desc = _T("Windows Vista");
		break;
	case WINDOWS_VERSION_7:
		desc = _T("Windows 7");
		break;
	case WINDOWS_VERSION_2K8:
		desc = _T("Windows 2008");
		break;
	case WINDOWS_VERSION_8:
		desc = _T("Windows 8");
		break;
	case WINDOWS_VERSION_2012:
		desc = _T("Windows 2012");
		break;
	default:
		desc = _T("Unknown");
		return desc;
	}
	desc.AppendFormat(_T(" x%s"), bX64 ? _T("64") : _T("86"));
	desc.AppendFormat(_T(" %s"), verCode);
	return desc;
}

CString common::GetBinPath()
{
	static CString binFilePath;

	if (binFilePath.GetLength() != 0)
	{
		return binFilePath;
	}

	HMODULE hMod = NULL;

	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize + 1] = '\0';
	binFilePath = baseName;

	return binFilePath;	
}
void common::SetWindowFont( HWND hWnd )
{
	HFONT hFont = CreateFont (16, 0, 0, 0, 0, 0, 0, 0, CHINESEBIG5_CHARSET, 0, 0, 0, 0,_T( "Î¢ÈíÑÅºÚ"));

	CFont *pFont = CFont::FromHandle(hFont);

	CWnd* pw = CWnd::FromHandle(hWnd);

	do
	{
		pw->SetFont(pFont);
		pw = pw->GetWindow(GW_HWNDNEXT);

	}while(pw != NULL);
}