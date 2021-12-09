#include "stdafx.h"
#include "http.h"
#include <tchar.h>
#include <algorithm>
#pragma warning(push)
#pragma warning(disable: 6385 6011 4127)
#include "atlrx.h"
#pragma warning(pop)
#pragma comment(lib, "Winhttp.lib")

ctx::http::http(LPCTSTR url, PROGRESSPROC progressProc)
	: m_requestURL(url),
	m_sessionHandle(NULL),
	m_requireValidSsl(FALSE),
	m_pResponse(NULL),
	m_responseByteCountReceived(0),
	m_pfProcessProc(progressProc),
	m_responseByteCount(0),
	m_pDataToSend(NULL),
	m_dataToSendSize(0),
	m_dwLastError(0),
	m_userAgent(SZ_AGENT),
	m_bForceCharset(FALSE),
	m_resolveTimeout(0),
	m_connectTimeout(60000),
	m_sendTimeout(30000),
	m_receiveTimeout(30000)
{
}

ctx::http::~http()
{
	if (m_pResponse)
	{
		delete[] m_pResponse;
		m_pResponse = NULL;
	}
	if (m_pDataToSend)
	{
		delete[] m_pDataToSend;
		m_pDataToSend = NULL;
	}
	if (m_sessionHandle)
	{
		::WinHttpCloseHandle(m_sessionHandle);
		m_sessionHandle = NULL;
	}
}

tstring ctx::http::GetResponseHeader()
{
	return m_responseHeader;
}

tstring ctx::http::GetResponseContent()
{
	return m_responseContent;
}

tstring ctx::http::GetResponseCharset()
{
	return m_responseCharset;
}

tstring ctx::http::GetRequestHost()
{
	return m_requestHost;
}

BOOL ctx::http::SaveResponseToFile(LPCTSTR filePath)
{
	if (m_pResponse == NULL || m_responseByteCountReceived == 0)
		return FALSE;
	HANDLE hFile = ::CreateFile(filePath, GENERIC_WRITE, 0, NULL, CREATE_ALWAYS, FILE_ATTRIBUTE_NORMAL, NULL);
	if (hFile == INVALID_HANDLE_VALUE)
		return FALSE;
	DWORD dwWritten = 0;
	BOOL bRet = FALSE;
	bRet = ::WriteFile(hFile, m_pResponse, m_responseByteCountReceived, &dwWritten, NULL);
	::CloseHandle(hFile);
	return bRet;
}

BOOL ctx::http::SetProgress(DWORD byteCountReceived)
{
	BOOL bReturn = FALSE;
	if (m_pfProcessProc && m_responseByteCount > 0)
	{
		double dProgress = (double)byteCountReceived * 100 / m_responseByteCount;
		m_pfProcessProc(dProgress);
		bReturn = TRUE;
	}
	return bReturn;
}

tstring ctx::http::GetResponseCookies()
{
	return m_responseCookies;
}

BOOL ctx::http::SetAdditionalRequestCookies(LPCTSTR cookies)
{
	m_additionalRequestCookies = cookies;
	return TRUE;
}

BOOL ctx::http::SetAdditionalDataToSend(PBYTE data, DWORD dataSize)
{
	if (data == NULL || dataSize == 0)
	{
		return FALSE;
	}

	if (m_pDataToSend != NULL)
	{
		delete[] m_pDataToSend;
	}
	m_pDataToSend = NULL;
	m_pDataToSend = new BYTE[dataSize];
	if (m_pDataToSend != NULL)
	{
		memcpy(m_pDataToSend, data, dataSize);
		m_dataToSendSize = dataSize;
		return TRUE;
	}

	return FALSE;
}

BOOL ctx::http::ResetAdditionalDataToSend()
{
	if (m_pDataToSend != NULL)
	{
		delete[] m_pDataToSend;
	}

	m_pDataToSend = NULL;
	m_dataToSendSize = 0;

	return TRUE;
}

BOOL ctx::http::UpdateUrl(LPCTSTR url)
{
	m_requestURL = url;
	ResetAdditionalDataToSend();
	return TRUE;
}

BOOL ctx::http::SetAdditionalRequestHeaders(LPCTSTR additionalRequestHeaders)
{
	m_additionalRequestHeaders = additionalRequestHeaders;
	return TRUE;
}

BOOL ctx::http::SetProxy(LPCTSTR proxy)
{
	m_proxy = proxy;
	return TRUE;
}

BOOL ctx::http::SetRequireValidSslCertificates(BOOL require)
{
	m_requireValidSsl = require;
	return TRUE;
}

const PBYTE ctx::http::GetRawResponseContent()
{
	return m_pResponse;
}

DWORD ctx::http::GetRawResponseContentLength()
{
	return m_responseByteCount;
}

DWORD ctx::http::GetRawResponseReceivedContentLength()
{
	return m_responseByteCountReceived;
}

DWORD ctx::http::GetLastError()
{
	return m_dwLastError;
}

tstring ctx::http::GetResponseStatusCode()
{
	return m_statusCode;
}

BOOL ctx::http::SetUserAgent(LPCTSTR userAgent)
{
	m_userAgent = userAgent;
	return TRUE;
}

BOOL ctx::http::SetForceCharset(LPCTSTR charset)
{
	m_responseCharset = charset;
	return TRUE;
}

BOOL ctx::http::SetProxyUsername(LPCTSTR username)
{
	m_proxyUsername = username;
	return TRUE;
}

BOOL ctx::http::SetProxyPassword(LPCTSTR password)
{
	m_proxyPassword = password;
	return TRUE;
}

tstring ctx::http::GetResponseLocation()
{
	return m_location;
}

BOOL ctx::http::SetTimeouts(DWORD resolveTimeout, DWORD connectTimeout, DWORD sendTimeout, DWORD receiveTimeout)
{
	m_resolveTimeout = resolveTimeout;
	m_connectTimeout = connectTimeout;
	m_sendTimeout = sendTimeout;
	m_receiveTimeout = receiveTimeout;
	return TRUE;
}

BOOL ctx::http::SendHttpRequest(LPCTSTR httpVerb, BOOL disableAutoRedirect)
{
	if (m_requestURL.empty())
	{
		m_dwLastError = ERROR_PATH_NOT_FOUND;
		return FALSE;
	}
	tstring verb = httpVerb;
	std::transform(verb.begin(), verb.end(), verb.begin(), toupper);
	if (0 == verb.compare(TEXT("GET")))
	{
		verb = TEXT("GET");
	}
	else if (0 == verb.compare(TEXT("POST")))
	{
		verb = TEXT("POST");
	}
	else {
		m_dwLastError = ERROR_INVALID_PARAMETER;
		return FALSE;
	}
	
	BOOL bRetVal = TRUE;
	if (m_sessionHandle == NULL)
	{
		m_sessionHandle = ::WinHttpOpen(m_userAgent.c_str(), WINHTTP_ACCESS_TYPE_DEFAULT_PROXY, WINHTTP_NO_PROXY_NAME, WINHTTP_NO_PROXY_BYPASS, 0);
		if (m_sessionHandle == NULL)
		{
			m_dwLastError = ::GetLastError();
			return FALSE;
		}
	}
	::WinHttpSetTimeouts(m_sessionHandle, m_resolveTimeout, m_connectTimeout, m_sendTimeout, m_receiveTimeout);
	TCHAR szHostName[MAX_PATH] = {0};
	TCHAR szURLPath[MAX_PATH * 5] = {0};
	URL_COMPONENTS urlComp = { sizeof(URL_COMPONENTS) };
	urlComp.lpszHostName = szHostName;
	urlComp.dwHostNameLength = MAX_PATH;
	urlComp.lpszUrlPath = szURLPath;
	urlComp.dwUrlPathLength = MAX_PATH * 5;
	urlComp.dwSchemeLength = 1;
	if (!::WinHttpCrackUrl(m_requestURL.c_str(), m_requestURL.size(), 0, &urlComp))
		return FALSE;
	m_requestHost = szHostName;
	HINTERNET hConnect = ::WinHttpConnect(m_sessionHandle, szHostName, urlComp.nPort, 0);
	if (hConnect == NULL)
		return FALSE;
	DWORD dwOpenRequestFlag = (urlComp.nScheme == INTERNET_SCHEME_HTTPS) ? WINHTTP_FLAG_SECURE : 0;
	HINTERNET hRequest = ::WinHttpOpenRequest(hConnect, verb.c_str(), urlComp.lpszUrlPath, NULL, WINHTTP_NO_REFERER, WINHTTP_DEFAULT_ACCEPT_TYPES, dwOpenRequestFlag);
	if (hRequest)
	{
		if (!m_requireValidSsl && urlComp.nScheme == INTERNET_SCHEME_HTTPS)
		{
			DWORD options = SECURITY_FLAG_IGNORE_CERT_CN_INVALID | SECURITY_FLAG_IGNORE_CERT_DATE_INVALID | SECURITY_FLAG_IGNORE_UNKNOWN_CA;
			::WinHttpSetOption(hRequest, WINHTTP_OPTION_SECURITY_FLAGS, (LPVOID)&options, sizeof(DWORD));
		}
		BOOL bGetReponseSucceed = FALSE;
		DWORD dwRetryTimes = 0;
		while (!bGetReponseSucceed && dwRetryTimes++ < INT_RETRYTIMES)
		{
			if (!m_additionalRequestHeaders.empty())
			{
				if (!::WinHttpAddRequestHeaders(hRequest, m_additionalRequestHeaders.c_str(), m_additionalRequestHeaders.size(), WINHTTP_ADDREQ_FLAG_COALESCE_WITH_SEMICOLON))
				{
					m_dwLastError = ::GetLastError();
				}
			}
			
			if (0 == verb.compare(TEXT("POST")))
			{
				HttpSetDataLength(hRequest);
			}

			HttpSetCookies(hRequest);
			HttpSetProxy(hRequest);
			DisableAutoRedirect(hRequest, disableAutoRedirect);

			BOOL bSendRequestSucceed = FALSE;
			if (::WinHttpSendRequest(hRequest, WINHTTP_NO_ADDITIONAL_HEADERS, 0, WINHTTP_NO_REQUEST_DATA, 0, 0, NULL))
			{
				bSendRequestSucceed = TRUE;
			}
			else
			{
				HttpSendRequestFromIEProxy(hRequest, bSendRequestSucceed);
			}

			if (bSendRequestSucceed)
			{
				if (m_pDataToSend != NULL)
				{
					DWORD dwWritten = 0;
					if (!::WinHttpWriteData(hRequest, m_pDataToSend, m_dataToSendSize, &dwWritten))
					{
						m_dwLastError = ::GetLastError();
					}
				}
				if (::WinHttpReceiveResponse(hRequest, NULL))
				{
					HttpGetResponseHeader(hRequest);
					HttpGetResponseStatusCode(hRequest);
					if (!HttpReceiveResponse(hRequest))
						break;
					ResponseCharsetConvert();
					bGetReponseSucceed = TRUE;
				}
				else
				{
					m_dwLastError = ::GetLastError();
				}
			}
		}

		if (!bGetReponseSucceed)
		{
			bRetVal = FALSE;
		}

		::WinHttpCloseHandle(hRequest);
	}
	::WinHttpCloseHandle(hConnect);
	return bRetVal;
}

BOOL ctx::http::ParseRegExp(const tstring &regExp, BOOL caseSensitive, int groupCount, const tstring &source, std::vector<tstring> &result, BOOL allowDuplicate)
{
	if (regExp.empty() || source.empty() || groupCount <= 0)
		return FALSE;
	result.clear();
	CAtlRegExp<> re;
	REParseError error = re.Parse(regExp.c_str(), caseSensitive);
	if (error != REPARSE_ERROR_OK)
		return FALSE;
	LPTSTR pSource = new TCHAR[source.size() + 1];
	LPTSTR pSourceEnd = pSource + source.size();
	if (pSource == NULL) {
		return FALSE;
	}
	_tcscpy_s(pSource, source.size() + 1, source.c_str());
	BOOL bSucceed = TRUE;
	CAtlREMatchContext<> mc;
	LPCTSTR pFrom = pSource;
	LPCTSTR pTo = NULL;
	while (bSucceed)
	{
		bSucceed = re.Match(pFrom, &mc, &pTo);
		if (bSucceed)
		{
			LPCTSTR pStart = NULL;
			LPCTSTR pEnd = NULL;
			std::vector<tstring> tempMatch;
			for (int i = 0; i < groupCount; i++)
			{
				mc.GetMatch(i, &pStart, &pEnd);
				if (pStart != NULL && pEnd != NULL)
				{
					tstring match(pStart, pEnd-pStart);
					tempMatch.push_back(match);
				}
				else
				{
					break;
				}
			}
			BOOL bAdd = TRUE;
			if (!allowDuplicate)
			{
				for (std::vector<tstring>::iterator it = result.begin(); it != result.end();)
				{
					BOOL bEqual = TRUE;
					for (std::vector<tstring>::iterator tempMatchIt = tempMatch.begin(); tempMatchIt != tempMatch.end(); tempMatchIt++, it++)
					{
						BOOL bGroupEqual = TRUE;
						if (caseSensitive)
						{
							bGroupEqual = (_tcscmp(it->c_str(), tempMatchIt->c_str()) == 0);
						}
						else
						{
							bGroupEqual = (_tcsicmp(it->c_str(), tempMatchIt->c_str()) == 0);
						}
						if (!bGroupEqual)
						{
							bEqual = FALSE;
						}
					}
					if (bEqual)
					{
						bAdd = FALSE;
						break;
					}
				}
			}
			if (bAdd)
			{
				for (std::vector<tstring>::iterator tempMatchIt = tempMatch.begin(); tempMatchIt != tempMatch.end(); tempMatchIt++)
				{
					result.push_back(*tempMatchIt);
				}
			}
			if (pTo < pSourceEnd)
			{
				pFrom = pTo;
			}
			else
			{
				break;
			}
		}
		else
		{
			break;
		}
	}

	delete[] pSource;

	return TRUE;
}

tstring ctx::http::Trim(const tstring &source, const tstring &targets)
{
	tstring::size_type start = 0;
	tstring::size_type end = 0;
	for (start = 0; start < source.size(); start++)
	{
		BOOL bIsTarget = FALSE;
		for (tstring::size_type i = 0; i < targets.size(); i++)
		{
			if (source[start] == targets[i])
			{
				bIsTarget = TRUE;
				break;
			}
		}
		if (!bIsTarget)
		{
			break;
		}
	}
	for (end = source.size() - 1; (int)end >= 0; end--)
	{
		BOOL bIsTarget = FALSE;
		for (tstring::size_type i = 0; i < targets.size(); i++)
		{
			if (source[end] == targets[i])
			{
				bIsTarget = TRUE;
				break;
			}
		}
		if (!bIsTarget)
		{
			break;
		}
	}
	tstring result = TEXT("");
	if (end >= start && start < source.size() && end >= 0)
	{
		result = source.substr(start, end-start+1);
	}

	return result;
}

void ctx::http::ResponseCharsetConvert()
{
	UINT codePage = CP_ACP;
	DWORD dwFlag = MB_PRECOMPOSED;
	if (0 == _tcsnicmp(m_responseCharset.c_str(), TEXT("utf-8"), 5))
	{
		codePage = CP_UTF8;
		dwFlag = 0;
	}
	int iLength = ::MultiByteToWideChar(codePage, dwFlag, (LPCSTR)m_pResponse, m_responseByteCountReceived + 1, NULL, 0);
	if (iLength <= 0)
	{
		codePage = CP_ACP;
		dwFlag = MB_PRECOMPOSED;
		iLength = ::MultiByteToWideChar(codePage, dwFlag, (LPCSTR)m_pResponse, m_responseByteCountReceived + 1, NULL, 0);
	}
	if (iLength > 0)
	{
		PWCHAR pwideChar = new WCHAR[iLength];
		if (pwideChar != NULL)
		{
			memset(pwideChar, 0, iLength * sizeof(WCHAR));
			iLength = ::MultiByteToWideChar(codePage, dwFlag, (LPCSTR)m_pResponse, m_responseByteCountReceived + 1, pwideChar, iLength);
			if (iLength > 0)
			{
				m_responseContent = pwideChar;
			}
			delete[] pwideChar;
		}
	}
}

void ctx::http::HttpSendRequestFromIEProxy(HINTERNET hRequest, BOOL &bSendRequestSucceed)
{
	WINHTTP_CURRENT_USER_IE_PROXY_CONFIG proxyConfig = {0};
	if (::WinHttpGetIEProxyConfigForCurrentUser(&proxyConfig))
	{
		if (proxyConfig.lpszAutoConfigUrl != NULL)
		{
			WINHTTP_AUTOPROXY_OPTIONS autoProxyOptions = {0};
			autoProxyOptions.dwFlags = WINHTTP_AUTOPROXY_AUTO_DETECT | WINHTTP_AUTOPROXY_CONFIG_URL;
			autoProxyOptions.dwAutoDetectFlags = WINHTTP_AUTO_DETECT_TYPE_DHCP;
			autoProxyOptions.lpszAutoConfigUrl = proxyConfig.lpszAutoConfigUrl;
			autoProxyOptions.fAutoLogonIfChallenged = TRUE;
			autoProxyOptions.dwReserved = 0;
			autoProxyOptions.lpvReserved = NULL;

			WINHTTP_PROXY_INFO proxyInfo = {0};
			if (::WinHttpGetProxyForUrl(m_sessionHandle, m_requestURL.c_str(), &autoProxyOptions, &proxyInfo))
			{
				if (::WinHttpSetOption(hRequest, WINHTTP_OPTION_PROXY, &proxyInfo, sizeof(proxyInfo)))
				{
					if (::WinHttpSendRequest(hRequest, WINHTTP_NO_ADDITIONAL_HEADERS, 0, WINHTTP_NO_REQUEST_DATA, 0, 0, NULL))
					{
						bSendRequestSucceed = TRUE;
					}
				}
				if (proxyInfo.lpszProxy != NULL)
				{
					::GlobalFree(proxyInfo.lpszProxy);
				}
				if (proxyInfo.lpszProxyBypass != NULL)
				{
					::GlobalFree(proxyInfo.lpszProxyBypass);
				}
			}
			else
			{
				m_dwLastError = ::GetLastError();
			}
		}
		else if (proxyConfig.lpszProxy != NULL)
		{
			WINHTTP_PROXY_INFO proxyInfo = {0};
			proxyInfo.dwAccessType = WINHTTP_ACCESS_TYPE_NAMED_PROXY;
			TCHAR szProxy[MAX_PATH] = {0};
			_tcscpy_s(szProxy, MAX_PATH, proxyConfig.lpszProxy);
			proxyInfo.lpszProxy = szProxy;

			if (proxyConfig.lpszProxyBypass != NULL)
			{
				TCHAR szProxyBypass[MAX_PATH] = {0};
				_tcscpy_s(szProxyBypass, MAX_PATH, proxyConfig.lpszProxyBypass);
				proxyInfo.lpszProxyBypass = szProxyBypass;
			}

			if (!::WinHttpSetOption(hRequest, WINHTTP_OPTION_PROXY, &proxyInfo, sizeof(proxyInfo)))
			{
				m_dwLastError = ::GetLastError();
			}
		}

		if (proxyConfig.lpszAutoConfigUrl != NULL)
		{
			::GlobalFree(proxyConfig.lpszAutoConfigUrl);
		}
		if (proxyConfig.lpszProxy != NULL)
		{
			::GlobalFree(proxyConfig.lpszProxy);
		}
		if (proxyConfig.lpszProxyBypass != NULL)
		{
			::GlobalFree(proxyConfig.lpszProxyBypass);
		}
	}
	else
	{
		m_dwLastError = ::GetLastError();
	}
}

void ctx::http::HttpSetProxy(HINTERNET hRequest)
{
	if (!m_proxy.empty())
	{
		WINHTTP_PROXY_INFO proxyInfo = {0};
		proxyInfo.dwAccessType = WINHTTP_ACCESS_TYPE_NAMED_PROXY;
		TCHAR szProxy[MAX_PATH] = {0};
		_tcscpy_s(szProxy, MAX_PATH, m_proxy.c_str());
		proxyInfo.lpszProxy = szProxy;

		if (!::WinHttpSetOption(hRequest, WINHTTP_OPTION_PROXY, &proxyInfo, sizeof(proxyInfo)))
		{
			m_dwLastError = ::GetLastError();
		}

		if (!m_proxyUsername.empty())
		{
			if (!::WinHttpSetOption(hRequest, WINHTTP_OPTION_PROXY_USERNAME, (LPVOID)m_proxyUsername.c_str(), m_proxyUsername.size() * sizeof(TCHAR)))
			{
				m_dwLastError = ::GetLastError();
			}
			if (!m_proxyPassword.empty())
			{
				if (!::WinHttpSetOption(hRequest, WINHTTP_OPTION_PROXY_PASSWORD, (LPVOID)m_proxyPassword.c_str(), m_proxyPassword.size() * sizeof(TCHAR)))
				{
					m_dwLastError = ::GetLastError();
				}
			}
		}
	}
}

void ctx::http::HttpSetDataLength(HINTERNET hRequest)
{
	char szData[255] = {0};
	if (m_pDataToSend)
	{
		tstring cookies = TEXT("Content-Length: ");
		sprintf(szData,"%d",m_dataToSendSize);
		cookies += a2t(szData);
		if (!::WinHttpAddRequestHeaders(hRequest, cookies.c_str(), cookies.size(), WINHTTP_ADDREQ_FLAG_COALESCE_WITH_SEMICOLON))
		{
			m_dwLastError = ::GetLastError();
		}
	}
}

void ctx::http::HttpSetCookies(HINTERNET hRequest)
{
	if (!m_additionalRequestCookies.empty())
	{
		tstring cookies = TEXT("Cookie: ");
		cookies += m_additionalRequestCookies;
		if (!::WinHttpAddRequestHeaders(hRequest, cookies.c_str(), cookies.size(), WINHTTP_ADDREQ_FLAG_COALESCE_WITH_SEMICOLON))
		{
			m_dwLastError = ::GetLastError();
		}
	}
}

void ctx::http::DisableAutoRedirect(HINTERNET hRequest, BOOL disableAutoRedirect)
{
	if (disableAutoRedirect)
	{
		DWORD dwDisableFeature = WINHTTP_DISABLE_REDIRECTS;
		if (!::WinHttpSetOption(hRequest, WINHTTP_OPTION_DISABLE_FEATURE, &dwDisableFeature, sizeof(dwDisableFeature)))
		{
			m_dwLastError = ::GetLastError();
		}
	}
}

void ctx::http::HttpGetResponseHeader(HINTERNET hRequest)
{
	DWORD dwSize = 0;
	BOOL bResult = ::WinHttpQueryHeaders(hRequest, WINHTTP_QUERY_RAW_HEADERS_CRLF, WINHTTP_HEADER_NAME_BY_INDEX, NULL, &dwSize, WINHTTP_NO_HEADER_INDEX);
	if (bResult || (!bResult && (::GetLastError() == ERROR_INSUFFICIENT_BUFFER)))
	{
		LPTSTR szHeader = new TCHAR[dwSize];
		if (szHeader != NULL)
		{
			memset(szHeader, 0, dwSize* sizeof(TCHAR));
			if (::WinHttpQueryHeaders(hRequest, WINHTTP_QUERY_RAW_HEADERS_CRLF, WINHTTP_HEADER_NAME_BY_INDEX, szHeader, &dwSize, WINHTTP_NO_HEADER_INDEX))
			{
				m_responseHeader.assign(szHeader);
				std::vector<tstring> result;
				tstring regExp;
				if (!m_bForceCharset)
				{
					regExp = TEXT("charset={[A-Za-z0-9\\-_]+}");
					if (ParseRegExp(regExp, FALSE, 1, m_responseHeader, result) && result.size() > 0)
					{
						m_responseCharset = result[0];
					}
				}
				regExp = TEXT("Content-Length: {[0-9]+}");
				if (ParseRegExp(regExp, FALSE, 1, m_responseHeader, result) && result.size() > 0)
				{
					m_responseByteCount = (DWORD)_ttol(result[0].c_str());
				}
				regExp = TEXT("Location: {[0-9]+}");
				if (ParseRegExp(regExp, FALSE, 1, m_responseHeader, result) && result.size() > 0)
				{
					m_location = result[0];
				}
				regExp = TEXT("Set-Cookie:\\b*{.+?}\\n");
				if (ParseRegExp(regExp, FALSE, 1, m_responseHeader, result) && result.size() > 0)
				{
					for (std::vector<tstring>::size_type i = 0; i < result.size(); i++)
					{
						m_responseCookies += result[i];
						if (i != result.size() - 1)
						{
							m_responseCookies += TEXT("; ");
						}
					}
					m_responseCookies = Trim(m_responseCookies, TEXT(" "));
					if (m_responseCookies.size() > 0 && m_responseCookies[m_responseCookies.size() - 1] != TEXT(';'))
					{
						m_responseCookies += TEXT(";");
					}
				}
			}
			delete[] szHeader;
		}
	}
}

void ctx::http::HttpGetResponseStatusCode(HINTERNET hRequest)
{
	DWORD dwSize = 0;
	BOOL bResult = ::WinHttpQueryHeaders(hRequest, WINHTTP_QUERY_STATUS_CODE, WINHTTP_HEADER_NAME_BY_INDEX, NULL, &dwSize, WINHTTP_NO_HEADER_INDEX);
	if (bResult || (!bResult && (::GetLastError() == ERROR_INSUFFICIENT_BUFFER)))
	{
		LPTSTR szStatusCode = new TCHAR[dwSize];
		if (szStatusCode != NULL)
		{
			memset(szStatusCode, 0, dwSize* sizeof(TCHAR));
			if (::WinHttpQueryHeaders(hRequest, WINHTTP_QUERY_STATUS_CODE, WINHTTP_HEADER_NAME_BY_INDEX, szStatusCode, &dwSize, WINHTTP_NO_HEADER_INDEX))
			{
				m_statusCode = szStatusCode;
			}
			delete[] szStatusCode;
		}
	}
}

BOOL ctx::http::HttpReceiveResponse(HINTERNET hRequest)
{
	DWORD dwMaxBufferSize = INT_BUFFERSIZE;
	DWORD dwCurrentBufferSize = 0;
	if (m_pResponse != NULL)
	{
		delete[] m_pResponse;
		m_pResponse = NULL;
	}
	m_pResponse = new BYTE[dwMaxBufferSize];
	if (m_pResponse == NULL)
		return FALSE;
	memset(m_pResponse, 0, dwMaxBufferSize);
	BOOL bRetVal = TRUE;
	DWORD dwSize = 0;
	do {
		dwSize = 0;
		if (::WinHttpQueryDataAvailable(hRequest, &dwSize))
		{
			SetProgress(dwCurrentBufferSize);
			PBYTE pResponse = new BYTE[dwSize + 1];
			if (pResponse != NULL)
			{
				memset(pResponse, 0, (dwSize + 1)*sizeof(BYTE));
				DWORD dwRead = 0;
				if (::WinHttpReadData(hRequest, pResponse, dwSize, &dwRead))
				{
					if (dwRead + dwCurrentBufferSize > dwMaxBufferSize)
					{
						PBYTE pOldBuffer = m_pResponse;
						m_pResponse = new BYTE[dwMaxBufferSize * 2];
						if (m_pResponse == NULL)
						{
							m_pResponse = pOldBuffer;
							bRetVal = FALSE;
							break;
						}
						dwMaxBufferSize *= 2;
						memset(m_pResponse, 0, dwMaxBufferSize);
						memcpy(m_pResponse, pOldBuffer, dwCurrentBufferSize);
						delete[] pOldBuffer;
					}
					memcpy(m_pResponse + dwCurrentBufferSize, pResponse, dwRead);
					dwCurrentBufferSize += dwRead;
				}
				delete[] pResponse;
			}
		}
		else
		{
			m_dwLastError = ::GetLastError();
		}
	} while (dwSize > 0);
	SetProgress(dwCurrentBufferSize);
	m_responseByteCountReceived = dwCurrentBufferSize;

	return bRetVal;
}

BOOL ctx::http::HandleAutoRedirectResponse(HINTERNET hRequest, const tstring &verb, BOOL disableAutoRedirect)
{
	if (verb == TEXT("GET") && !disableAutoRedirect)
	{
		tstring regExp = TEXT("{<html>}");
		std::vector<tstring> result;
		if (ParseRegExp(regExp, FALSE, 1, m_responseContent, result) && result.size() > 0)
		{
			regExp = TEXT("{</html>}");
			if (!ParseRegExp(regExp, FALSE, 1, m_responseContent, result) || result.size() <= 0)
			{
				m_dwLastError = ERROR_INVALID_DATA;
				return FALSE;
			}
		}
	}
	return TRUE;
}