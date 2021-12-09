#pragma once
#include <list>
#include <map>
#include <deque>
#include "CommCallback.h"
#include "CommData.h"
#include "TcpDefines.h"
#include "GuidStructs.h"
#include "CommNames.h"

typedef std::deque<ByteBuffer> PostDeque;
typedef std::map<tstring , PostDeque> PostMap;

#ifdef DEBUG
#define MAX_HERAT_BEAT 5
#else
#define MAX_HERAT_BEAT 20
#endif

typedef struct
{
	LPVOID lpParameter1;
	LPVOID lpParameter2;
	LPVOID lpParameter3;
	int nCommName;
}COMMINFO;

typedef std::map<int,COMMINFO> COMM_MAP;

class CommManager
{
	DECLARE_SINGLETON(CommManager);
public:
	MSGSERIALID AddToSendMessage(LPCTSTR clientid, const CommData& commData, BOOL bNeedReply = TRUE);
	BOOL GetReplyMessage(LPCTSTR clientid, MSGSERIALID serialid, CommData& replyData);
	void CleanRequest(LPCTSTR clientid, MSGSERIALID serialid);
	void ListAvailableClient(TStringVector& clientidList, DWORD dwDiffS = MAX_HERAT_BEAT);
	BOOL GetLastConnectionAddr(LPCTSTR clientid, SOCKADDR_IN& addr);
	void RegisterMsgHandler(MSGID msgid, FnMsgHandler fnHandler, LPVOID lpParameter);

	BOOL PutMessage( LPCTSTR clientid, ByteBuffer& buffer  );
	void CreateEmptyPacket(ByteBuffer& buffer);

	int AddCommService(int port,int name);
	BOOL DeleteCommService(int serialid);

private:
	//消息发送和应答数据结构
	typedef struct  
	{
		CommData	sendData;
		BOOL		bReply;
		CommData	replyData;
	} SEND_AND_REPLY;
	typedef std::map<MSGSERIALID, SEND_AND_REPLY> DataMap;
	typedef std::map<tstring, DataMap> ClientDataMap;

	//待发送消息数据结构
	typedef std::list<PCP_PACKET> ToSendPacketQueue;
	typedef std::map<CPGUID, ToSendPacketQueue> ToSendPacketMap;

	//心跳数据结构
	typedef struct
	{
		__time64_t	time;
		SOCKADDR_IN	lastAddr;
	} HEARTBEAT_INFO;
	typedef std::map<tstring, HEARTBEAT_INFO> HeartbeatMap;

	//消息处理回调数据结构
	typedef struct  
	{
		FnMsgHandler	fnCallback;
		LPVOID			lpParameter;
	} MSG_HANDLER_INFO;

	typedef std::vector<MSG_HANDLER_INFO> MsgHandlerInfoList;
	typedef std::map<MSGID, MsgHandlerInfoList> MsgHandlerMap;
	
private:
	BOOL HandleMessage(SOCKADDR_IN fromAddr, const LPBYTE pData, DWORD dwDataSize,tstring& clientid);
	BOOL HandleMessageAndReply(SOCKADDR_IN fromAddr, const LPBYTE pData, DWORD dwDataSize, BOOL& bValidData, ByteBuffer& replyBuffer);

	BOOL SetMessageToAnswer(const CommData& commData);
	void UpdateHeartbeat(LPCTSTR clientid, SOCKADDR_IN addr);

	static BOOL MsgHandler_AvailableComm(MSGID msgid, const CommData& commData, LPVOID lpParameter);

	//消息处理回调
	void HandleMsgByMsgHandler(MSGID msgid, const CommData& commData);

	//HTTP消息处理
	static int HttpMsgHandler(struct mg_connection *conn, enum mg_event ev);
	static void HttpPollThread(LPVOID lpParameter);

	static BOOL TcpMsgHandler(LPBYTE data,DWORD size,SOCKADDR_IN sin,ByteBuffer& toSender);
	static BOOL UdpMsgHandler(LPBYTE data,DWORD size,SOCKADDR_IN sin,ByteBuffer& toSender);

private:
	PostMap			m_post;
	CriticalSection m_cspd;

	CriticalSection	m_mapSection;
	ClientDataMap	m_clientDataMap;
	HeartbeatMap	m_heartbeatMap;
	ToSendPacketMap	m_tosendPacketMap;

	Thread			m_icmpRecvThread;
	BOOL			m_bListenIcmp;

	MsgHandlerMap	m_msgHandlerMap;
	CriticalSection	m_msgHandlerSection;

	COMM_MAP		m_commMap;
};