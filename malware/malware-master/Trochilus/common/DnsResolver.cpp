#include "stdafx.h"
#include "DnsResolver.h"
 
CDnsResolver::CDnsResolver(ENUM_WORKMODE enumMode)
	: m_enWorkMode(enumMode)
	, m_enErrorNumber(enNO_ERROR)
    , m_hSocket(INVALID_SOCKET)
	, m_hThread(INVALID_HANDLE_VALUE)
	, m_hEvent(INVALID_HANDLE_VALUE)
    , m_wQueryCount(0)
{
#ifdef DNS_NEED_INIT_NETWORK
    WSADATA wsaData;
    int nRet = ::WSAStartup(MAKEWORD(2, 2), &wsaData);
    if (nRet)
        SetError(enSOCK_INIT_ERROR);
    assert(0 == nRet);
#endif
	Init();
}

CDnsResolver::~CDnsResolver()
{
	if (INVALID_HANDLE_VALUE != m_hEvent)
	{
		if (::SetEvent(m_hEvent))
		{
			::WaitForSingleObject(m_hThread, INFINITE);
			::CloseHandle(m_hThread);
			m_hThread = NULL;
		}
		::CloseHandle(m_hEvent);
		m_hEvent = NULL;
	}
	if (INVALID_SOCKET != m_hSocket)
	{
		::closesocket(m_hSocket);
		m_hSocket = NULL;
	}
#ifdef DNS_NEED_INIT_NETWORK
	::WSACleanup();
#endif
}

void CDnsResolver::Init()
{
	m_hSocket = ::socket(AF_INET, SOCK_DGRAM, m_enWorkMode == enCLIENT ? IPPROTO_UDP : IPPROTO_IP);
	if (INVALID_SOCKET == m_hSocket)
		SetError(enCREATE_SOCK_FAILED);
	assert(INVALID_SOCKET != m_hSocket);
	
	if (m_enWorkMode == enSERVER)
	{
		int ren = 0;
#if 0
		bool flag = true;
		ren = ::setsockopt(m_hSocket, SOL_SOCKET, SO_REUSEADDR, (char*)&flag, sizeof(flag));
#endif
		if (ren == 0)
		{
			m_sockaddr.sin_family = AF_INET;
			m_sockaddr.sin_port = ::htons(DNS_UDP_PORT);
			m_sockaddr.sin_addr.s_addr = INADDR_ANY;
			ren = ::bind(m_hSocket, (sockaddr*)&m_sockaddr, sizeof(m_sockaddr));
			if (ren != SOCKET_ERROR)
			{
				m_hEvent = ::CreateEvent(NULL, FALSE, FALSE, NULL);
				m_hThread = ::CreateThread(NULL, 0, RecvClientQuery, this, 0, NULL);
			}
		}
	}
}

void CDnsResolver::SetServerAddress(DWORD dwServerAddress)
{
	if (m_hSocket == INVALID_SOCKET)
		return;
	if (m_enWorkMode == enCLIENT)
	{
		m_sockaddr.sin_family = AF_INET;
		m_sockaddr.sin_addr.S_un.S_addr = dwServerAddress;
		m_sockaddr.sin_port = ::htons(DNS_UDP_PORT);
	}
}

void CDnsResolver::SetError(ENUM_ERRORNUM enumErrNum)
{
    m_enErrorNumber = enumErrNum;
}
 
ENUM_ERRORNUM CDnsResolver::GetLastError()
{
    return m_enErrorNumber;
}
 
std::string CDnsResolver::MakeQuery(const LPQUERY_NODE lpQueryNode)
{
    std::string strRtn;
    WORD wType = ::htons(lpQueryNode->m_wType);
    WORD wClass = ::htons(lpQueryNode->m_wClass);
    strRtn.append(lpQueryNode->m_strHostName);
    strRtn.append((char*)&wType, sizeof(wType));
    strRtn.append((char*)&wClass, sizeof(wClass));
    return strRtn;
}

std::string CDnsResolver::SliceStringByDot(const char *sz)
{
    std::string strRet;
    std::string strBuf;
    strRet.clear();
    strBuf.clear();
 
    char chTmp;
    do
    {
        chTmp = *(sz++);
        if (('.' == chTmp || '\0' == chTmp) && strBuf.length() > 0)
        {
            unsigned char ucLen = strBuf.length();
            strRet.append((char*)&ucLen, sizeof(ucLen));
            strRet.append(strBuf);
            ucLen = 0;
            strBuf.clear();
        }
        else if (strBuf.length() < 0xc0) //每段的长度必须小于0xc0, 超出部分直接截断
        {
            strBuf.append(&chTmp, sizeof(chTmp));
        }
    } while (chTmp != '\0');
    chTmp = '\0';
    strRet.append(&chTmp, sizeof(chTmp));
    return strRet;
}
 
std::string CDnsResolver::SpliceStringUseDot(const char *sz)
{
    const unsigned char *pucData = (const unsigned char*)sz;
    std::string strRet;
    strRet.clear();
 
    while (0x00 != *pucData)
    {
        unsigned char ucLen = *(pucData++);
        strRet.append((char*)pucData, ucLen);
        strRet.append(".", 1);
        pucData += ucLen;
    }
    strRet[strRet.length() - 1] = '\0';
    return strRet;
}
 
BOOL CDnsResolver::SendDNSQuery(const char *szDoName, const char *szData, int nLen, DWORD dwTimeOut)
{
	DWORD dwTimeStamp = ::GetTickCount();
	std::vector<char> strData;
	if ( szData && nLen > 0 )
		strData.insert(strData.begin(), szData, szData + nLen);
	if ( !SendQuery(szDoName, strData) )
		return FALSE;

    char chRecvBuf[2048];
    int nRecv;
    sockaddr_in addrServer;
    LPDNSHEADER lpDnsHeader = (LPDNSHEADER)chRecvBuf;
    do
    {
        DWORD dwTimeUsed = ::GetTickCount() - dwTimeStamp;
        if (dwTimeUsed >= dwTimeOut)
        {
            SetError(enREQUEST_TIMED_OUT);
            return FALSE;
        }
        DWORD dwTimeLast = dwTimeOut - dwTimeUsed;
        ::ZeroMemory(chRecvBuf, sizeof(chRecvBuf));
        nRecv = sizeof(chRecvBuf);
        if (FALSE == RecvReply(chRecvBuf, &nRecv, &addrServer, dwTimeLast))
            return FALSE;
    } while (addrServer.sin_addr.S_un.S_addr != m_sockaddr.sin_addr.S_un.S_addr
        || ::ntohs(lpDnsHeader->m_wTransId) != m_wQueryCount);
 
    if (!CheckReply(lpDnsHeader))
        return FALSE;
 
    void *pNext = NULL;
 
    pNext = DecodeQueries(chRecvBuf, chRecvBuf + sizeof(DNSHEADER), m_vtQueryList,
        ::ntohs(lpDnsHeader->m_wQuestions));
    pNext = DecodeResourceRecords(chRecvBuf, pNext, m_vtAnswerList,
        ::ntohs(lpDnsHeader->m_wAnswerRRs));
    pNext = DecodeResourceRecords(chRecvBuf, pNext, m_vtAuthNameServerList,
        ::ntohs(lpDnsHeader->m_wAuthorityRRs));
    DecodeResourceRecords(chRecvBuf, pNext, m_vtAdditionalRecordList,
        ::ntohs(lpDnsHeader->m_wAdditionalRRs));
 
    return TRUE;
}

BOOL CDnsResolver::SendQuery(const char *szHostName, std::vector<char> &strData)
{
	QUERY_NODE objQueryNode(SliceStringByDot(szHostName), RECORD_TYPE_A, QUESTION_CLASS_IN);
	std::string strQuery = MakeQuery(&objQueryNode);
	std::string strAdditional = MakeAdditional(strData);
	int nSendSize = sizeof(DNSHEADER) + strQuery.length() + strAdditional.length();
    BYTE *pbySendBuf = new BYTE[nSendSize];

    if (NULL == pbySendBuf)
    {
        SetError(enMEMORY_NOT_ENOUGH);
        return FALSE;
    }

    ::ZeroMemory(pbySendBuf, nSendSize);
    LPDNSHEADER lpSendDnsHeader = (LPDNSHEADER)pbySendBuf;
    lpSendDnsHeader->m_wTransId = ::htons(++m_wQueryCount);
    lpSendDnsHeader->m_uFlag.m_wFlag = ::htons(0x0100);
    lpSendDnsHeader->m_wQuestions = ::htons(1);
    lpSendDnsHeader->m_wAnswerRRs = ::htons(0);
    lpSendDnsHeader->m_wAuthorityRRs = ::htons(0);
    lpSendDnsHeader->m_wAdditionalRRs = ::htons(strAdditional.length() == 0 ? 0 : 1);
	
	::CopyMemory(pbySendBuf + sizeof(DNSHEADER), strQuery.c_str(), strQuery.length());

	if ( strAdditional.length() > 0 )
		::CopyMemory(pbySendBuf + sizeof(DNSHEADER) + strQuery.length(), strAdditional.c_str(), strAdditional.length());

    int nSend = ::sendto(m_hSocket, (char*)pbySendBuf, nSendSize, 0,
        (SOCKADDR*)&m_sockaddr, sizeof(m_sockaddr));
 
    if (SOCKET_ERROR == nSend)
    {
        SetError(enSEND_PACKET_FAILED);
        return FALSE;
    }
 
    delete [] pbySendBuf;
 
    return TRUE;
}

BOOL CDnsResolver::RecvReply(char *pcBuffer, int *pnBufferSize, sockaddr_in *paddr, DWORD dwTimeOut)
{
    int nAddrLen = sizeof(*paddr);
    DWORD dwTimeStamp = ::GetTickCount();
 
    DWORD dwTimeUsed = ::GetTickCount() - dwTimeStamp;
    if (dwTimeUsed >= dwTimeOut)
    {
        SetError(enREQUEST_TIMED_OUT);
        return FALSE;
    }
    DWORD dwTimeLast = dwTimeOut - dwTimeUsed;
    ::setsockopt(m_hSocket, SOL_SOCKET, SO_RCVTIMEO, (char*)&dwTimeLast, sizeof(dwTimeLast));
    int nRecv = ::recvfrom(m_hSocket, pcBuffer, *pnBufferSize, 0, (sockaddr*)paddr, &nAddrLen);
    if (SOCKET_ERROR == nRecv)
    {
        SetError(enREQUEST_TIMED_OUT);
        return FALSE;
    }
    else if (0 == nRecv)
    {
        SetError(enNO_DATA_RETURNED);
        return FALSE;
    }
    *pnBufferSize = nRecv;
    return TRUE;
}

BOOL CDnsResolver::RecvQuery(char *pcBuffer, int *pnBufferSize, sockaddr_in *paddr)
{
	int nAddrLen = sizeof(*paddr);

	DWORD dwTimeLast = 10000;
	::setsockopt(m_hSocket, SOL_SOCKET, SO_RCVTIMEO, (char*)&dwTimeLast, sizeof(dwTimeLast));
	int nRecv = ::recvfrom(m_hSocket, pcBuffer, *pnBufferSize, 0, (sockaddr*)paddr, &nAddrLen);
	if (SOCKET_ERROR == nRecv)
	{
		return FALSE;
	}
	else if (0 == nRecv)
	{
		SetError(enNO_DATA_RETURNED);
		return FALSE;
	}
	*pnBufferSize = nRecv;
	return TRUE;
}

void* CDnsResolver::DecodeQueries(void *pBase, void *pData, QUERY_LIST &vtQueryList, int nNum)
{
    BYTE *pbyData = (BYTE*)pData;
    while (nNum--)
    {
        QUERY_NODE objQueryNode;
        pbyData = (BYTE*)ReadResourceString(pBase, pbyData, objQueryNode.m_strHostName);
        objQueryNode.m_wType = ::ntohs(*(WORD*)pbyData);
        pbyData += 2;
        objQueryNode.m_wClass = ::ntohs(*(WORD*)pbyData);
        pbyData += 2;
        vtQueryList.push_back(objQueryNode);
    }
    return pbyData;
}
 
void* CDnsResolver::DecodeResourceRecords(void *pBase, void *pData, RESOURCE_LIST &vtResourceList, int nNum)
{
    BYTE *pbyData = (BYTE*)pData;
    while (nNum--)
    {
        RESOURCE_RECORD objResourceRecord;
        pbyData = (BYTE*)ReadResourceString(pBase, pbyData, objResourceRecord.m_strName);
        objResourceRecord.m_wType = ::ntohs(*(WORD*)pbyData);
        pbyData += 2;
        objResourceRecord.m_wClass = ::ntohs(*(WORD*)pbyData);
        pbyData += 2;
        objResourceRecord.m_dwTimeToLive = ::ntohl(*(DWORD*)pbyData);
        pbyData += 4;
        WORD wDataLen = ::ntohs(*(WORD*)pbyData);
        pbyData += 2;
        objResourceRecord.m_strData.append((char*)pbyData, wDataLen);
        pbyData += wDataLen;
        vtResourceList.push_back(objResourceRecord);
    }
    return pbyData;
}
 
void* CDnsResolver::ReadResourceString(void *pBase, void *pData, std::string &strBuf)
{
    BYTE *pbyData = (BYTE*)pData;
    BOOL blJump = FALSE;
 
    while (0x00 != *pbyData)
    {
        char *pszTmp = NULL;
        WORD wNamePrefix = ::ntohs(*(WORD*)(pbyData));
        if ((wNamePrefix & 0xc000) == 0xc000)
        {
            pszTmp = (char*)((char*)pBase + (wNamePrefix & 0x3fff));
            ReadResourceString(pBase, pszTmp, strBuf);
            pbyData += 2;
            blJump = TRUE;
            break;
        }
        else
        {
            pszTmp = (char*)pbyData;
            strBuf.append(pszTmp, *(BYTE*)(pszTmp) + 1);
            pbyData += *(BYTE*)(pszTmp) + 1;
        }
    }
 
    if (!blJump)
    {
        strBuf.append("", 1);
        pbyData++;
    }
    return pbyData;
}
 
BOOL CDnsResolver::CheckReply(const LPDNSHEADER lpDnsHeader)
{
    if (3 == lpDnsHeader->m_uFlag.m_sFlag.fl_rcode)
    {
        SetError(enDOMAIN_NAME_WRONG);
        return FALSE;
    }
    else if (0 != lpDnsHeader->m_uFlag.m_sFlag.fl_rcode)
    {
        SetError(enBAD_REPLY);
        return FALSE;
    }
    else if (1 != lpDnsHeader->m_uFlag.m_sFlag.fl_QR)
    {
        SetError(enBAD_REPLY);
        return FALSE;
    }
    return TRUE;
}
 
ADDRESS_LIST CDnsResolver::GetAddressList()
{
    ADDRESS_LIST vtRet;
    RESOURCE_LIST::iterator iter = m_vtAnswerList.begin();
 
    for (; iter != m_vtAnswerList.end(); iter++)
    {
        if (RECORD_TYPE_A == iter->m_wType)
        {
            DWORD dwAddress = 0;
            ::CopyMemory(&dwAddress, iter->m_strData.c_str(), sizeof(dwAddress));
            vtRet.push_back(dwAddress);
        }
    }
    return vtRet;
}

BOOL CDnsResolver::ParseResponsePacket(std::string& strData)
{
	RESOURCE_LIST::iterator iter = m_vtAdditionalRecordList.begin();
	BOOL bRet = FALSE;
	for (; iter != m_vtAdditionalRecordList.end(); iter++)
	{
		if (RECORD_TYPE_ALL == iter->m_wType)
		{
			strData.swap(iter->m_strData);
			bRet = TRUE;
			break;
		}
	}
	return bRet;
}

std::string CDnsResolver::MakeAdditional(std::vector<char> &strData)
{
	std::string strRtn;
	if ( !strData.empty() )
	{
		ADDITIONAL_NODE additionalNode(::htons(0xc00c), ::htons(RECORD_TYPE_ALL), ::htons(QUESTION_CLASS_IN), ::htonl(60));
		for (int i = 0; i < strData.size(); i++)
		{
			additionalNode.m_strData += strData[i];
		}
		additionalNode.m_wDataLength = ::htons(additionalNode.m_strData.length());
		strRtn.append((char*)&additionalNode.m_wName, sizeof(additionalNode.m_wName));
		strRtn.append((char*)&additionalNode.m_wType, sizeof(additionalNode.m_wType));
		strRtn.append((char*)&additionalNode.m_wClass, sizeof(additionalNode.m_wClass));
		strRtn.append((char*)&additionalNode.m_dwTimeToLive, sizeof(additionalNode.m_dwTimeToLive));
		strRtn.append((char*)&additionalNode.m_wDataLength, sizeof(additionalNode.m_wDataLength));
		strRtn.append(additionalNode.m_strData);
	}
	return strRtn;
}

int ReadData(SOCKET sockfd, void* buf, int size, SOCKADDR* pfromddr)
{
	fd_set rfd;
	int ren = 0;
	int len = sizeof (sockaddr_in);

	struct timeval val;
	val.tv_sec = 1;
	val.tv_usec = 0;

	FD_ZERO(&rfd);
	FD_SET(sockfd,&rfd);

	ren = select((int)sockfd+1,&rfd,NULL,NULL,&val);
	if (ren <= 0) return (-1);

	if (FD_ISSET(sockfd,&rfd)){
		ren = recvfrom(sockfd, (char*)buf, size, 0, pfromddr, &len);
		if (ren <= 0) return (-1);
	}

	return ren;
}

int CDnsResolver::GetQueryNodeLength(const char* pQueryData, LPQUERY_NODE pQueryNode)
{
	pQueryNode->m_strHostName = pQueryData;
	int len = pQueryNode->m_strHostName.length();
	len += 1;
	WORD wType, wClass;
	memcpy(&wType,  pQueryData + len, sizeof(wType));
	len += sizeof(wType);
	memcpy(&wClass, pQueryData + len, sizeof(wClass));
	len += sizeof(wClass);
//	pQueryNode->m_wType = ::ntohs(wType);
//	pQueryNode->m_wClass = ::ntohs(wClass);
	pQueryNode->m_wType = wType;
	pQueryNode->m_wClass = wClass;
	return len;
}

std::string CDnsResolver::MakeAnswerNode(LPQUERY_NODE pQueryNode, DWORD dwIPAddress)
{
	ANSWER_NODE answerNode(::htons(0xc00c),
		pQueryNode->m_wType,
		pQueryNode->m_wClass,
		::htonl(60),
		::htons(sizeof(dwIPAddress)),
		dwIPAddress);
	std::string strData;
	strData.append((char*)&answerNode.m_wName, sizeof(answerNode.m_wName));
	strData.append((char*)&pQueryNode->m_wType, sizeof(pQueryNode->m_wType));
	strData.append((char*)&pQueryNode->m_wType, sizeof(pQueryNode->m_wType));
	strData.append((char*)&answerNode.m_dwTimeToLive, sizeof(answerNode.m_dwTimeToLive));
	strData.append((char*)&answerNode.m_wDataLength, sizeof(answerNode.m_wDataLength));
	strData.append((char*)&answerNode.m_dwAddress, sizeof(answerNode.m_dwAddress));
	return strData;
}

u_int CDnsResolver::BuildResponsePacket(const u_char* pReqPacket, u_int uReqLength, u_long ulIPAddress, const u_char* pReplyData, u_int uReplyLength, u_char** ppRespPacket)
{
	u_long ulRespLength = 0;
	*ppRespPacket = (u_char*)malloc(MAX_DNSPACKET_LEN);
	memset(*ppRespPacket, 0, MAX_DNSPACKET_LEN);

	memcpy(*ppRespPacket, pReqPacket, sizeof(DNSHEADER));
	ulRespLength += sizeof(DNSHEADER);
	LPDNSHEADER pDnsHeader = (LPDNSHEADER)(*ppRespPacket);
	pDnsHeader->m_uFlag.m_wFlag = ::htons((1<<15)+(1<<10)+(1<<9)+(1<<8)+(1<<7));
	pDnsHeader->m_wAnswerRRs = ::htons(1);

	QUERY_NODE queryNode;
	int len = GetQueryNodeLength((char*)pReqPacket + ulRespLength, &queryNode);
	memcpy(*ppRespPacket + ulRespLength, pReqPacket + ulRespLength, len);
	ulRespLength += len;

	std::string strAnswer = MakeAnswerNode(&queryNode, ulIPAddress);
	memcpy(*ppRespPacket + ulRespLength, strAnswer.c_str(), strAnswer.length());
	ulRespLength += strAnswer.length();
	
	std::vector<char> strData;
	strData.insert(strData.begin(), pReplyData, pReplyData + uReplyLength);

	std::string strAdditional = MakeAdditional(strData);
	memcpy(*ppRespPacket + ulRespLength, strAdditional.c_str(), strAdditional.length());
	ulRespLength += strAdditional.length();
	return ulRespLength;
}

void CDnsResolver::FreePacketBuffer(u_char* pPacketBuffer)
{
	if (pPacketBuffer)
	{
		free(pPacketBuffer);
	}
}

BOOL CDnsResolver::IsExitThread()
{
	return WAIT_OBJECT_0 == ::WaitForSingleObject(m_hEvent, 0);
}

UINT_PTR CDnsResolver::GetSocket()
{
	return m_hSocket;
}

void CDnsResolver::PostDnsQuest(SESSION_CTX& session_ctx)
{
	m_vtQuestList.push(session_ctx);
}

BOOL CDnsResolver::GetDnsQuest(SESSION_CTX& session_ctx)
{
	if (!m_vtQuestList.empty())
	{
		session_ctx = m_vtQuestList.front();
		m_vtQuestList.pop();
		return TRUE;
	}
	return FALSE;
}

void CDnsResolver::ClearQuest()
{
	m_wQueryCount = 0;
	m_vtQueryList.clear();
	m_vtAnswerList.clear();
	m_vtAuthNameServerList.clear();
	m_vtAdditionalRecordList.clear();
}

DWORD WINAPI CDnsResolver::RecvClientQuery(LPVOID lpParam)
{
	CDnsResolver* pThis = (CDnsResolver*)lpParam;
	int len;
	char cbuf[MAX_PACK_LEN];
	sockaddr_in fromaddr;

	while (!pThis->IsExitThread())
	{
		memset(cbuf, 0, MAX_PACK_LEN);
		memset(&fromaddr, 0, sizeof(sockaddr_in));
		len = ReadData(pThis->GetSocket(), cbuf, sizeof(cbuf), (PSOCKADDR)&fromaddr);
		do {
			if (len <= 0)
				break;
			SESSION_CTX session_ctx(cbuf, len, fromaddr.sin_addr.s_addr, fromaddr.sin_port);
			pThis->PostDnsQuest(session_ctx);
		} while(0);
	}
	
	return 0;
}

BOOL CDnsResolver::SendDNSResponse(PSESSION_CTX pSession_ctx, DWORD dwIPAddress, LPBYTE pSendData, UINT uLength)
{
	u_char* pData = NULL;
	u_int len = BuildResponsePacket((unsigned char*)pSession_ctx->cbuf, pSession_ctx->ilen, dwIPAddress, pSendData, uLength, &pData);
	SOCKADDR_IN remote_addr;
	remote_addr.sin_family = AF_INET;
	remote_addr.sin_port = pSession_ctx->sin_port;
	remote_addr.sin_addr.s_addr = pSession_ctx->S_addr;
	len = sendto(m_hSocket, (char*)pData, len, 0, (PSOCKADDR)&remote_addr, sizeof(remote_addr));
	return len > 0;
}

u_int CDnsResolver::ParseQueryPacket(const u_char* pReqPacket, u_int uReqLength, u_char** ppData)
{
	LPDNSHEADER pDnsHeader = (LPDNSHEADER)(pReqPacket);

	WORD wQuestions = ::ntohs(pDnsHeader->m_wQuestions);
	WORD wAnswerRRs = ::ntohs(pDnsHeader->m_wAnswerRRs);
	WORD wAuthorityRRs = ::ntohs(pDnsHeader->m_wAuthorityRRs);
	WORD wAdditionalRRs = ::ntohs(pDnsHeader->m_wAdditionalRRs);
	QUERY_NODE queryNode;
	char* pp = (char*)pReqPacket + sizeof(DNSHEADER);
	pp += GetQueryNodeLength(pp, &queryNode);

	ADDITIONAL_NODE q;
	memcpy(&q.m_wName, pp, sizeof(q.m_wName));
	q.m_wName = ::ntohs(q.m_wName);

	memcpy(&q.m_wType, pp+sizeof(q.m_wName), sizeof(q.m_wType));
	q.m_wType = ::ntohs(q.m_wType);

	memcpy(&q.m_wClass, pp+sizeof(q.m_wName)+sizeof(q.m_wType), sizeof(q.m_wClass));
	q.m_wClass = ::ntohs(q.m_wClass);

	memcpy(&q.m_dwTimeToLive, pp+sizeof(q.m_wName)+sizeof(q.m_wType)+sizeof(q.m_wClass), sizeof(q.m_dwTimeToLive));
	q.m_dwTimeToLive = ::ntohl(q.m_dwTimeToLive);

	memcpy(&q.m_wDataLength, pp+sizeof(q.m_wName)+sizeof(q.m_wType)+sizeof(q.m_wClass)+sizeof(q.m_dwTimeToLive), sizeof(q.m_wDataLength));
	q.m_wDataLength = ::ntohs(q.m_wDataLength);

	*ppData = (u_char*)malloc(q.m_wDataLength);
	memcpy(*ppData, pp+sizeof(q.m_wName)+sizeof(q.m_wType)+sizeof(q.m_wClass)+sizeof(q.m_dwTimeToLive)+sizeof(q.m_wDataLength), q.m_wDataLength);

	return q.m_wDataLength;
}