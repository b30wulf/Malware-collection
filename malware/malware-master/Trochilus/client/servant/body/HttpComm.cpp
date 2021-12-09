#include "stdafx.h"
#include "socket/MySocket.h"
#include "HttpComm.h"

HttpComm::HttpComm(BOOL isSSL):
m_http(NULL),
m_ssl(FALSE)
{
	m_ssl = isSSL;
}

BOOL HttpComm::Send( ULONG targetIP, const LPBYTE pData, DWORD dwSize )
{
	

	return TRUE;
}

BOOL HttpComm::SendAndRecv( ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize, LPBYTE* pRecvData, DWORD& dwRecvSize )
{
#define RECVDATA_MAXSIZE (2 * 1024 * 1024)

	BOOL ret = Connect(targetIP);
	LPBYTE content = NULL;

	if (ret)
	{
		m_http->SetAdditionalDataToSend(pSendData,dwSendSize);
		ret = m_http->SendHttpRequest(_T("POST"));
	}

	if (ret)
	{
		content = m_http->GetRawResponseContent();
		dwRecvSize = m_http->GetRawResponseContentLength();
		*pRecvData = (LPBYTE)malloc(dwRecvSize);
		memcpy(*pRecvData,content,dwRecvSize);
	}

	return ret;
}

BOOL HttpComm::Connect( ULONG targetIP )
{
	char szPort[255] = {0};

	sprintf_s(szPort,"%d",g_ConfigInfo.nPort);

	IN_ADDR connectIP;
	connectIP.S_un.S_addr = targetIP;
	
	tstring strIp;

	if (m_ssl)
	{
		strIp = _T("https://");

		strIp += a2t(inet_ntoa(connectIP));
		strIp += _T(":");

		if (g_ConfigInfo.nPort != 443)
			strIp += a2t(szPort);
	}
	else
	{
		strIp = _T("http://");

		strIp += a2t(inet_ntoa(connectIP));
		strIp += _T(":");

		if (g_ConfigInfo.nPort != 80)
			strIp += a2t(szPort);
	}


	if (!m_http)
	{
		m_http = new ctx::http(strIp.c_str());
	}
	else
	{
		m_http->UpdateUrl(strIp.c_str());
	}

	return m_http != NULL;
}