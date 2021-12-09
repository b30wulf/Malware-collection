#include "stdafx.h"
#include "FileVersionQuery.h"

#pragma comment(lib, "Version.lib")

FileVersionQuery::FileVersionQuery()
{

}

FileVersionQuery::~FileVersionQuery()
{

}

BOOL FileVersionQuery::Open( LPCTSTR filepath )
{
	DWORD dwHandle = 0;
	DWORD dwSizeInBytes = ::GetFileVersionInfoSize(filepath, &dwHandle);
	if (0 == dwSizeInBytes)
	{
		return FALSE;
	}

	m_buffer.Alloc(dwSizeInBytes);
	if (! ::GetFileVersionInfo(filepath, 0, dwSizeInBytes, (LPBYTE)m_buffer))
	{
		return FALSE;
	}

	struct LANG_AND_CODEPAGE
	{
		WORD wLanguage;
		WORD wCodePage;
	} *lpTranslate;

	UINT len = 0;
	BOOL bRet = ::VerQueryValue((LPCVOID)(LPBYTE)m_buffer, _T("\\VarFileInfo\\Translation"), (LPVOID*)&lpTranslate, &len);
	if (! bRet || len == 0)
	{
		m_buffer.Free();
		return FALSE;
	}

	m_format.Format(_T("\\StringFileInfo\\%04x%04x\\%%s"),
		lpTranslate->wLanguage,
		lpTranslate->wCodePage
		);

	return TRUE;
}

BOOL FileVersionQuery::QueryValue( LPCTSTR subBlock, CString& value )
{
	if (NULL == (LPBYTE)m_buffer) return FALSE;

	CString queryBlock;
	queryBlock.Format(m_format, subBlock);

 	LPVOID pValue = NULL;
 	UINT len = 0;
 	if (! ::VerQueryValue((LPCVOID)(LPBYTE)m_buffer, queryBlock, &pValue, &len))
 	{
 		return FALSE;
 	}
 
	if (len == 0) 
	{
		value = _T("");
	}
 	else 
	{
		value = CString((LPCTSTR)pValue, len);
	}

	return TRUE;
}

BOOL FileVersionQuery::QueryValue( VERSION_ITEM subBlock, CString& value )
{
	return QueryValue(GetSubBlock(subBlock), value);
}

LPCTSTR FileVersionQuery::GetSubBlock( VERSION_ITEM item ) const
{
	switch (item)
	{
	case Comments:
		return _T("Comments");
		break;
	case InternalName:
		return _T("InternalName");
		break;
	case ProductName:
		return _T("ProductName");
		break;
	case CompanyName:
		return _T("CompanyName");
		break;
	case LegalCopyright:
		return _T("LegalCopyright");
		break;
	case ProductVersion:
		return _T("ProductVersion");
		break;
	case FileDescription:
		return _T("FileDescription");
		break;
	case LegalTrademarks:
		return _T("LegalTrademarks");
		break;
	case PrivateBuild:
		return _T("PrivateBuild");
		break;
	case FileVersion:
		return _T("FileVersion");
		break;
	case OriginalFilename:
		return _T("OriginalFilename");
		break;
	case SpecialBuild:
		return _T("SpecialBuild");
		break;
	default:
		return _T("unknown");
	}
}

