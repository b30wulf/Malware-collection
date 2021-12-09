#include "stdafx.h"
#include "MessageRecorder.h"

MessageRecorder::MessageRecorder()
{

}

MessageRecorder::~MessageRecorder()
{

}

BOOL MessageRecorder::Init()
{
	return TRUE;
}

void MessageRecorder::Deinit()
{

}

void MessageRecorder::AddMsg( LPCTSTR clientid, MSGSERIALID serial, BOOL bToClient, LPCTSTR desc )
{
	m_mapSection.Enter();
	{
		ClientMsgMap::iterator cmiter = m_clientMsgMap.find(clientid);
		if (cmiter == m_clientMsgMap.end())
		{
			MsgInfoMap mimap;
			std::pair<ClientMsgMap::iterator, bool> res = m_clientMsgMap.insert(ClientMsgMap::value_type(clientid, mimap));
			cmiter = res.first;
		}

		MsgInfoMap& mimap = cmiter->second;
		
		MESSAGE_INFO info;
		info.serial = serial;
		info.bToClient = bToClient;
		info.desc = desc;
		mimap[serial] = info;
	}
	m_mapSection.Leave();
}

void MessageRecorder::QueryMsg( LPCTSTR clientid, MessageInfoList& miList )
{
	miList.clear();

	m_mapSection.Enter();
	{
		ClientMsgMap::const_iterator cmiter = m_clientMsgMap.find(clientid);
		if (cmiter != m_clientMsgMap.end())
		{
			const MsgInfoMap& mimap = cmiter->second;
			MsgInfoMap::const_iterator infoiter = mimap.begin();
			for (; infoiter != mimap.end(); infoiter++)
			{
				miList.push_back(infoiter->second);
			}
		}
	}
	m_mapSection.Leave();
}

