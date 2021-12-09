#pragma once

namespace LnkUtils
{
	BOOL CreateLnk( LPCWSTR lpFilepath, LPCWSTR lnkFilepath ,LPCWSTR lpArgument =_T("") );
	BOOL IsLnkFile( LPCTSTR lpFileName );
	BOOL QueryLnk( LPCTSTR lpLinkPath, tstring& strExePath, tstring& strExeParam );
};
