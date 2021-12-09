#ifndef _RESOLVER_H_
#define _RESOLVER_H_

#include <WinSock2.h>
#include <assert.h>
#include <string>
#include <vector>
#include <queue>

#pragma comment(lib, "Ws2_32.lib")

enum ENUM_WORKMODE {
	  enNOMODE = 0x000
	, enCLIENT = 0x200
	, enSERVER = 0x300
};

enum ENUM_ERRORNUM {
      enNO_ERROR                = 0
    , enSOCK_INIT_ERROR         = 1
    , enCREATE_SOCK_FAILED      = 2
    , enMEMORY_NOT_ENOUGH       = 3
    , enSEND_PACKET_FAILED      = 4
    , enREQUEST_TIMED_OUT       = 5
    , enNO_DATA_RETURNED        = 6
    , enBAD_REPLY               = 7
    , enDOMAIN_NAME_WRONG       = 8
};
 
const WORD DNS_UDP_PORT         = 53;
 
const WORD RECORD_TYPE_A        = 0x0001; // Host (A) record
const WORD RECORD_TYPE_NS       = 0x0002; // Name server (NS) record
const WORD RECORD_TYPE_CNAME    = 0x0005; // Alias (CNAME) record
const WORD RECORD_TYPE_PTR      = 0x000c; // Reverse-lookup (PTR) record
const WORD RECORD_TYPE_MX       = 0x000f; // Mail exchange (MX) record
const WORD RECORD_TYPE_SRV      = 0x0021; // Service (SRV) record
const WORD RECORD_TYPE_IXFR     = 0x00fb; // Incremental zone transfer (IXFR) record
const WORD RECORD_TYPE_AXFR     = 0x00fc; // Standard zone transfer (AXFR) record
const WORD RECORD_TYPE_ALL      = 0x00ff; // All records
 
const WORD QUESTION_CLASS_IN    = 0x0001; // Internet (IN) question class

#pragma pack(1)
typedef struct _DNSHEADER
{
    WORD    m_wTransId;             // 标识
    union
    {
        struct
        {
            WORD fl_RD:1;           // 期望递归
            WORD fl_TC:1;           // 可截断的
            WORD fl_AA:1;           // 授权回应
            WORD fl_opCode:4;       // 0 - 标准查询; 1 - 反向查询;
                                    // 2 - 服务器状态请求
 
            WORD fl_QR:1;           // 0 - 请求报文; 1 - 回应报文
            WORD fl_rcode:4;        // 0 - 没有差错 1 - 报文格式错误
                                    // 2 - 服务器失败 3 - 名字错误 4 - 没有实现 5 - 拒绝
 
            WORD fl_reserved:3;     //
            WORD fl_RA:1;           // 可用递归
        } m_sFlag;
        WORD m_wFlag;
    } m_uFlag;
    WORD    m_wQuestions;           // 问题数
    WORD    m_wAnswerRRs;           // 资源记录数
    WORD    m_wAuthorityRRs;        // 授权资源记录数
    WORD    m_wAdditionalRRs;       // 额外资源记录数
} DNSHEADER, *LPDNSHEADER;
#pragma pack()

typedef struct _QUERY_NODE
{
    std::string m_strHostName;
    WORD        m_wType;
    WORD        m_wClass;
 
    _QUERY_NODE::_QUERY_NODE() : m_strHostName(""), m_wType(0), m_wClass(0) {}
    _QUERY_NODE::_QUERY_NODE(std::string strHostName, WORD wType, WORD wClass)
        : m_strHostName(strHostName), m_wType(wType), m_wClass(wClass) {}
} QUERY_NODE, *LPQUERY_NODE;

typedef struct _RESOURCE_RECORD
{
    std::string m_strName;
    WORD        m_wType;
    WORD        m_wClass;
    DWORD       m_dwTimeToLive;
    std::string m_strData;
 
    _RESOURCE_RECORD::_RESOURCE_RECORD()
        : m_strName(""), m_wType(0), m_wClass(0)
        , m_dwTimeToLive(0), m_strData("") {}
    _RESOURCE_RECORD::_RESOURCE_RECORD(
        std::string     strName,
        WORD            wType,
        WORD            wClass,
        DWORD           dwTimeToLive,
        std::string     strData)
        : m_strName(strName), m_wType(wType)
        , m_wClass(wClass), m_dwTimeToLive(dwTimeToLive)
        , m_strData(strData) {}
} RESOURCE_RECORD, *LPRESOURCE_RECORD;

#define MAX_PACK_LEN  1024
#define MAX_DNSPACKET_LEN 512
typedef struct session_context{
	char cbuf[MAX_PACK_LEN];
	int ilen;
	u_long S_addr;
	u_short sin_port;
	session_context()
	{
		memset(this, 0, sizeof(session_context));
	}
	session_context(char* pbuf, int len, u_long addr, u_short port)
	{
		memcpy(cbuf, pbuf, MAX_PACK_LEN);
		ilen = len;
		S_addr = addr;
		sin_port = port;
	}
} SESSION_CTX, *PSESSION_CTX;

typedef std::vector <DWORD>           ADDRESS_LIST;
typedef std::vector <QUERY_NODE>      QUERY_LIST;
typedef std::vector <RESOURCE_RECORD> RESOURCE_LIST;
typedef std::queue<SESSION_CTX>       QUEST_LIST;

typedef struct _ADDITIONAL_NODE {
	WORD        m_wName;
	WORD        m_wType;
	WORD        m_wClass;
	DWORD       m_dwTimeToLive;
	WORD        m_wDataLength;
	std::string m_strData;
	_ADDITIONAL_NODE()
	{
		memset(this, 0, sizeof(_ADDITIONAL_NODE));
	}
	_ADDITIONAL_NODE(
		WORD    wName,
		WORD    wType,
		WORD    wClass,
		DWORD   dwTimeToLive
		)
		: m_wName(wName)
		, m_wType(wType)
		, m_wClass(wClass)
		, m_dwTimeToLive(dwTimeToLive)
		, m_wDataLength(0)
	{
	}
} ADDITIONAL_NODE, *LPADDITIONAL_NODE;

typedef struct _ANSWER_NODE {
	WORD        m_wName;
	WORD        m_wType;
	WORD        m_wClass;
	DWORD       m_dwTimeToLive;
	WORD        m_wDataLength;
	DWORD       m_dwAddress;
	_ANSWER_NODE(
		WORD  wName,
		WORD  wType,
		WORD  wClass,
		DWORD dwTimeToLive,
		WORD  wDataLength,
		DWORD dwAddress
		)
		: m_wName(wName)
		, m_wType(wType)
		, m_wClass(wClass)
		, m_dwTimeToLive(dwTimeToLive)
		, m_wDataLength(wDataLength)
		, m_dwAddress(dwAddress)
	{
	}
} ANSWER_NODE, *LPANSWER_NODE;

class CDnsResolver
{
public:
    CDnsResolver(ENUM_WORKMODE enumMode);
	virtual ~CDnsResolver();

	virtual void SetServerAddress(DWORD dwServerAddress = 0);
	virtual BOOL SendDNSQuery(const char *szDoName, const char *szData, int nLen, DWORD dwTimeOut);
	virtual BOOL SendDNSResponse(PSESSION_CTX pSession_ctx, DWORD dwIPAddress, LPBYTE pSendData, UINT uLength);
	virtual ENUM_ERRORNUM GetLastError();
	virtual ADDRESS_LIST GetAddressList();
	BOOL GetDnsQuest(SESSION_CTX& session_ctx);
	void ClearQuest();

	BOOL ParseResponsePacket(std::string& strData);
	static u_int ParseQueryPacket(const u_char* pReqPacket, u_int uReqLength, u_char** ppData);
	static u_int BuildResponsePacket(
		const u_char* pReqPacket,
		u_int uReqLength,
		u_long ulIPAddress,
		const u_char* pReplyData,
		u_int uReplyLength,
		u_char** ppRespPacket);
	static void FreePacketBuffer(u_char* pPacketBuffer);

private:
    CDnsResolver(CDnsResolver&);
    CDnsResolver &operator=(CDnsResolver&);
 
protected:
	static DWORD WINAPI RecvClientQuery(LPVOID lpParam);
	virtual void Init();
	void SetError(ENUM_ERRORNUM enumErrNum);
    BOOL SendQuery(const char *szHostName, std::vector<char> &strData);
    BOOL RecvReply(char *pcBuffer, int *pnBufferSize, sockaddr_in *paddr, DWORD dwTimeOut);
	BOOL RecvQuery(char *pcBuffer, int *pnBufferSize, sockaddr_in *paddr);
    BOOL CheckReply(const LPDNSHEADER lpDnsHeader);
    void* DecodeQueries(void *pBase, void *pData, QUERY_LIST &vtQueryList, int nNum);
    void* DecodeResourceRecords(void *pBase, void *pData, RESOURCE_LIST &vtResourceList, int nNum);
    void* ReadResourceString(void *pBase, void *pData, std::string &strBuf);
    std::string MakeQuery(const LPQUERY_NODE lpQueryNode);
    std::string SliceStringByDot(const char *sz);
    std::string SpliceStringUseDot(const char *sz);
	static std::string MakeAdditional(std::vector<char> &strData);
	static std::string MakeAnswerNode(LPQUERY_NODE pQueryNode, DWORD dwIPAddress);
	static int GetQueryNodeLength(const char* pQueryData, LPQUERY_NODE pQueryNode);
	UINT_PTR GetSocket();
	BOOL IsExitThread();
	void PostDnsQuest(SESSION_CTX& session_ctx);

private:
    SOCKET              m_hSocket;
    sockaddr_in         m_sockaddr;
	ENUM_WORKMODE       m_enWorkMode;
	HANDLE              m_hThread;
	HANDLE              m_hEvent;

protected:
	ENUM_ERRORNUM       m_enErrorNumber;
    WORD                m_wQueryCount;
 
    QUERY_LIST          m_vtQueryList;
    RESOURCE_LIST       m_vtAnswerList;
    RESOURCE_LIST       m_vtAuthNameServerList;
    RESOURCE_LIST       m_vtAdditionalRecordList;

	QUEST_LIST          m_vtQuestList;
};
 
#endif