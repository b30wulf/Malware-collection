#include "stdafx.h"
#include <objbase.h>
#include <shobjidl.h>
#include <shlguid.h>
#include "LnkUtils.h"

BOOL LnkUtils::CreateLnk( LPCWSTR lpFilepath, LPCWSTR lnkFilepath ,LPCWSTR lpArgument /*=_T("") */ )
{
	if (NULL == lpFilepath || NULL == lnkFilepath) return FALSE;

	HRESULT hr = CoInitialize(NULL);
	if (FAILED(hr)) return FALSE;

	IShellLink *pisl = NULL;
	IPersistFile* pIPF = NULL;
	BOOL bSuccess = FALSE;
	do
	{
		hr = CoCreateInstance(CLSID_ShellLink, NULL, CLSCTX_INPROC_SERVER, IID_IShellLink, (void**)&pisl);
		if (FAILED(hr)) break;

		hr = pisl->SetPath(lpFilepath);
		if (FAILED(hr)) break;

		hr = pisl->SetArguments(lpArgument);
		if (FAILED(hr)) break;

		hr = pisl->QueryInterface(IID_IPersistFile, (void**)&pIPF);
		if (FAILED(hr)) break;

		pIPF->Save(lnkFilepath, FALSE);

		bSuccess = TRUE;
	} while(FALSE);

	if (NULL != pIPF) pIPF->Release();
	if (NULL != pisl) pisl->Release();

	CoUninitialize();

	return bSuccess;
}

BOOL LnkUtils::IsLnkFile( LPCTSTR lpFileName )
{
	if (NULL == lpFileName) return FALSE;
	tstring filename = lpFileName;

	if (filename.size() > 4)
	{
		tstring postfix = filename.substr(filename.size() - 4);
		makeLower(postfix);
		if (postfix == _T(".lnk"))
		{
			return TRUE;
		}
	}

	return FALSE;
}

BOOL LnkUtils::QueryLnk( LPCTSTR lpLinkPath, tstring& strExePath, tstring& strExeParam )
{
	IShellLink*	pShellLink;

	// 用IShellLink接口来解析lnk文件
	// 
	// 用单线程方式创建COM对象
	BOOL bReturn = (::CoInitialize(NULL) == S_OK);
	if(TRUE || bReturn)
	{
		bReturn = ::CoCreateInstance (CLSID_ShellLink, NULL, CLSCTX_INPROC_SERVER,
			IID_IShellLink, (void **)&pShellLink) >= 0;
		// 按CLSID_ShellLink标识符，创建了lnk解析的COM对象
		if(bReturn)
		{
			//具体请查看MSDN
			IPersistFile *ppf;
			bReturn = pShellLink->QueryInterface(IID_IPersistFile, (void **)&ppf) >= 0;
			if(bReturn)
			{
				bReturn = ppf->Load(lpLinkPath, TRUE) >= 0;
				if(bReturn)
				{
					TCHAR strIePath[MAX_PATH + 1] = {0};
					TCHAR strParam[MAX_PATH + 1] = {0};
					pShellLink->GetPath( strIePath, MAX_PATH, NULL, SLGP_UNCPRIORITY );
					pShellLink->GetArguments( strParam, MAX_PATH);

					if (_tcslen(strIePath) > 0)
					{
						strExePath = strIePath;

						if (_tcslen(strParam) > 0)
							strExeParam = strParam;

						bReturn = TRUE;
					}
					else
						bReturn = FALSE;
				}
				ppf->Release();
			}
			pShellLink->Release();
		}
		::CoUninitialize();
	}

	return bReturn;
}
