#pragma once
#include <Windows.h>
#include <Winhttp.h>
#include <string>
#include <vector>

#if defined _UNICODE || defined UNICODE
#define tstring std::wstring
#else
#define tstring std::string
#endif

namespace ctx
{
	typedef BOOL (*PROGRESSPROC)(double);
	static const DWORD INT_RETRYTIMES = 1;
	static LPCTSTR SZ_AGENT = TEXT("WinHttpClient");
	static const DWORD INT_BUFFERSIZE = 1024 * 10;

	class http
	{
	public:
		http(LPCTSTR url, PROGRESSPROC progressProc = NULL);
		virtual ~http();

		BOOL SendHttpRequest(LPCTSTR httpVerb = TEXT("GET"), BOOL disableAutoRedirect = FALSE);
		tstring GetResponseHeader();
		tstring GetResponseContent();
		tstring GetResponseCharset();
		tstring GetResponseStatusCode();
		tstring GetResponseLocation();
		tstring GetRequestHost();
		const PBYTE GetRawResponseContent();
		DWORD GetRawResponseContentLength();
		DWORD GetRawResponseReceivedContentLength();
		BOOL SaveResponseToFile(LPCTSTR filePath);
		tstring GetResponseCookies();

		BOOL SetAdditionalRequestCookies(LPCTSTR cookies);
		BOOL SetAdditionalDataToSend(PBYTE data, DWORD dataSize);
		BOOL UpdateUrl(LPCTSTR url);
		BOOL ResetAdditionalDataToSend();
		BOOL SetAdditionalRequestHeaders(LPCTSTR additionalRequestHeaders);
		BOOL SetRequireValidSslCertificates(BOOL require);
		BOOL SetProxy(LPCTSTR proxy);
		DWORD GetLastError();
		BOOL SetUserAgent(LPCTSTR userAgent);
		BOOL SetForceCharset(LPCTSTR charset);
		BOOL SetProxyUsername(LPCTSTR username);
		BOOL SetProxyPassword(LPCTSTR password);
		BOOL SetTimeouts(DWORD resolveTimeout = 0, DWORD connectTimeout = 60000, DWORD sendTimeout = 30000, DWORD receiveTimeout = 30000);

	private:
		http(const http &other);
		http &operator =(const http &other);
		BOOL SetProgress(DWORD byteCountReceived);
		BOOL ParseRegExp(const tstring &regExp, BOOL caseSensitive, int groupCount, const tstring &source, std::vector<tstring> &result, BOOL allowDuplicate = FALSE);
		void ResponseCharsetConvert();
		tstring Trim(const tstring &source, const tstring &targets);
		void HttpSendRequestFromIEProxy(HINTERNET hRequest, BOOL &bSendRequestSucceed);
		void HttpSetProxy(HINTERNET hRequest);
		void HttpSetCookies(HINTERNET hRequest);
		void HttpSetDataLength(HINTERNET hRequest);
		void DisableAutoRedirect(HINTERNET hRequest, BOOL disableAutoRedirect);
		void HttpGetResponseHeader(HINTERNET hRequest);
		void HttpGetResponseStatusCode(HINTERNET hRequest);
		BOOL HttpReceiveResponse(HINTERNET hRequest);
		BOOL HandleAutoRedirectResponse(HINTERNET hRequest, const tstring &verb, BOOL disableAutoRedirect);

		HINTERNET m_sessionHandle;
		BOOL m_requireValidSsl;
		tstring m_requestURL;
		tstring m_requestHost;
		tstring m_responseHeader;
		tstring m_responseContent;
		tstring m_responseCharset;
		PBYTE m_pResponse;
		DWORD m_responseByteCountReceived;
		PROGRESSPROC m_pfProcessProc;
		DWORD m_responseByteCount;
		tstring m_responseCookies;
		tstring m_additionalRequestCookies;
		PBYTE m_pDataToSend;
		DWORD m_dataToSendSize;
		tstring m_additionalRequestHeaders;
		tstring m_proxy;
		DWORD m_dwLastError;
		tstring m_statusCode;
		tstring m_userAgent;
		BOOL m_bForceCharset;
		tstring m_proxyUsername;
		tstring m_proxyPassword;
		tstring m_location;
		DWORD m_resolveTimeout;
		DWORD m_connectTimeout;
		DWORD m_sendTimeout;
		DWORD m_receiveTimeout;
	};
}