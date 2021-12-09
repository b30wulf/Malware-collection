#include "midutils.h"
#include <afxwin.h>
#include <afxinet.h>
#include "tinyxml/tinyxml.h"
#include <string>
#include <map>

#define PER_DOWNLOAD_BUFFER 4096

static bool HttpGet(const wchar_t* url, std::string& content)
{
	if (url == NULL) return false;

	CInternetSession netSession(_T("base"));
	CStdioFile*	pTargetFile = NULL;

	ULONGLONG filesize = 0;

	BOOL bSuccess = TRUE;
	try
	{
		CStringA ret;
		pTargetFile = netSession.OpenURL(url, 1, INTERNET_FLAG_TRANSFER_BINARY | INTERNET_FLAG_RELOAD);

		UINT byteswrite = 0;
		BYTE buffer[PER_DOWNLOAD_BUFFER + 1] = {0};
		while ((byteswrite = pTargetFile->Read(buffer, PER_DOWNLOAD_BUFFER)) > 0)
		{
			ret.Append((LPCSTR)buffer);

			ZeroMemory(buffer, PER_DOWNLOAD_BUFFER + 1);
		}

		content = ret;
	}
	catch(CInternetException *IE)
	{
		bSuccess = FALSE;

		TCHAR error[255] = {0};
		IE->GetErrorMessage(error, 255);
		IE->Delete();
	}

	if (pTargetFile != NULL) 
	{
		pTargetFile->Close();
		delete pTargetFile;
	}

	return bSuccess;
}

static bool GetXmlValue(TiXmlElement* pElement, LPCSTR lpszName, std::string &strValue)
{
	LPCSTR lpszValue = pElement->Attribute(lpszName);
	if (lpszValue) {
		strValue = lpszValue;
		return true;
	}
	return false;
}

static std::wstring s2ws(const std::string& s, int encoding = CP_ACP)
{
	int _Dsize = ::MultiByteToWideChar(encoding, 0, s.c_str(), -1, NULL, 0);
	wchar_t* _Dest = new wchar_t[_Dsize];
	wmemset(_Dest, 0, _Dsize);
	::MultiByteToWideChar(encoding, 0, s.c_str(), -1, _Dest, _Dsize);
	std::wstring result = _Dest;
	delete[] _Dest;
	return result;
}

#define	REQUEST_TITLE	_T("IP地址在线查询")
#define RESULT_TEXT		_T("查询结果：")

MIDUTILS_LIB_API bool GetCountryCityByIp(const wchar_t* lpIPAddress, wchar_t* lpCountryCity, unsigned nMaxLength)
{
	if (lpIPAddress == NULL || lpCountryCity == NULL || nMaxLength == 0)
		return false;
	static std::map<std::wstring, std::wstring> s_sIpCountryCityMap;
	std::map<std::wstring, std::wstring>::const_iterator iter;
	iter = s_sIpCountryCityMap.find(lpIPAddress);
	if (iter != s_sIpCountryCityMap.end())
	{
		_tcsncpy(lpCountryCity, iter->second.c_str(), nMaxLength);
		return true;
	}
	bool bRet = false;
	std::wstring strUrl = _T("http://wap.ip138.com/ip.asp?ip=");
	strUrl += lpIPAddress;
	std::string sData;
	bRet = HttpGet(strUrl.c_str(), sData);
	if ( !bRet )
		return false;
	TiXmlDocument doc;
	doc.Parse(sData.c_str());
	TiXmlElement* pRootEle = doc.RootElement();
	TiXmlHandle hRoot(pRootEle);
	std::string strValue;
	TiXmlElement* pParentEle = hRoot.FirstChildElement("card").Element();
	while (pParentEle)
	{
		GetXmlValue(pParentEle, "title", strValue);
		if (0 == s2ws(strValue, CP_UTF8).compare(REQUEST_TITLE))
			break;
		pParentEle = pParentEle->NextSiblingElement("card");
	}
	if (!pParentEle)
		return false;
	pParentEle = pParentEle->FirstChildElement("p");
	while (pParentEle)
	{
		strValue = pParentEle->GetText();
		if (0 == s2ws(strValue, CP_UTF8).compare(lpIPAddress))
			break;
		pParentEle = pParentEle->NextSiblingElement("p");
	}
	if (!pParentEle)
		return false;
	TiXmlElement* p = pParentEle->FirstChildElement("b");
	if (p) {
		strValue = p->GetText();
		if (!strValue.empty())
		{
			std::wstring strText = s2ws(strValue, CP_UTF8);
			int i = strText.find(RESULT_TEXT);
			if (i != -1)
			{
				strText = strText.substr(i + _tcslen(RESULT_TEXT));
				i = strText.find(0x20);
				if (i != -1)
					strText = strText.substr(0, i);
			}
			_tcsncpy(lpCountryCity, strText.c_str(), nMaxLength);
			bRet = true;
		}
	}
	if (bRet) {
		s_sIpCountryCityMap.insert(std::map<std::wstring, std::wstring>::value_type(lpIPAddress, lpCountryCity));
	}
	return bRet;
}

MIDUTILS_LIB_API bool Filetime2Time(const FILETIME& filetime, __time64_t* pTime)
{
	if (NULL == pTime) return FALSE;
	//做过一个试验：
	//__time64_t变量1->_gmtime64_s->struct tm 变量2->_mktime64->__time64_t变量3
	//发现变量1 不等于 变量3
	//应该是mktime处理的时候考虑了时区导致，所以在这里做了特殊处理
	TIME_ZONE_INFORMATION timeZoneInfo = {0};
	if (TIME_ZONE_ID_INVALID == ::GetTimeZoneInformation(&timeZoneInfo))
	{
		return FALSE;
	}

	SYSTEMTIME systime;
	if (! ::FileTimeToSystemTime(&filetime, &systime))
	{
		return FALSE;
	}

	struct tm tt = {0};
	tt.tm_sec = systime.wSecond;
	tt.tm_min = systime.wMinute;
	tt.tm_hour = systime.wHour;
	tt.tm_mday = systime.wDay;
	tt.tm_mon = systime.wMonth - 1;
	tt.tm_year = systime.wYear - 1900;

	__time64_t temp = _mktime64(&tt);
	if (-1 == temp) 
	{
		return FALSE;
	}
	*pTime = temp - timeZoneInfo.Bias * 60;

	return TRUE;
}


MIDUTILS_LIB_API bool Time2Filetime( __time64_t time, FILETIME* pFiletime )
{
	if (NULL == pFiletime) return FALSE;

	struct tm tt = {0};
	if (0 != _gmtime64_s(&tt, &time))
	{
		return FALSE;
	}

	SYSTEMTIME systime = {0};
	systime.wSecond = tt.tm_sec;
	systime.wMinute = tt.tm_min;
	systime.wHour = tt.tm_hour;
	systime.wDay = tt.tm_mday;
	systime.wMonth = tt.tm_mon + 1;
	systime.wYear = tt.tm_year + 1900;

	return SystemTimeToFileTime(&systime, pFiletime);
}

bool MakeUpDirs( LPCTSTR dirs )
{
	return CreateDirectory(dirs,NULL);
}

void getBinFilePath(LPWSTR path, DWORD size)
{
	HMODULE hMod = NULL;
	// 	if (sLoadModName.size() > 0)
	// 	{
	// 		hMod = ::GetModuleHandle(sLoadModName.c_str());
	// 	}

	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize + 1] = '\0';
	lstrcpy(path,baseName);
}