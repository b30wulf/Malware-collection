#pragma once
#include <map>
#include <vector>
#include "CommData.h"

typedef struct  
{
	MSGSERIALID	serial;
	CString		desc;
	BOOL		bToClient;
} MESSAGE_INFO;
typedef std::vector<MESSAGE_INFO> MessageInfoList;

class MessageRecorder
{
	DECLARE_SINGLETON(MessageRecorder);
public:
	void AddMsg(LPCTSTR clientid, MSGSERIALID serial, BOOL bToClient, LPCTSTR desc);
	void QueryMsg(LPCTSTR clientid, MessageInfoList& miList);

private:
	typedef std::map<MSGSERIALID, MESSAGE_INFO> MsgInfoMap;
	typedef std::map<CString, MsgInfoMap> ClientMsgMap;

private:
	CriticalSection	m_mapSection;
	ClientMsgMap	m_clientMsgMap;
};
