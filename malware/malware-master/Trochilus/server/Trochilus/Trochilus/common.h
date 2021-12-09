#pragma once
#include "env/EnvData.h"

namespace common
{
	CString FormatSystemTime(const SYSTEMTIME& time);

	CString FormatOSDesc(WIN_VER_DETAIL ver,LPCTSTR verCode, BOOL bX64);

	CString FormatSystemTime(__time64_t time);

	CString GetBinPath();

	void SetWindowFont(HWND hWnd);
}