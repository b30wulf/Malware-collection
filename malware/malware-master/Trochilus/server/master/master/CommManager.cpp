#include "stdafx.h"
#include <Winsock2.h>
#include "file/MyFile.h"
#include "socket/MySocket.h"
#include "BinNames.h"
#include "MessageDefines.h"
#include "common.h"
#include "CommManager.h"
#include "Tcp.h"
#include "DnsResolver.h"
#include "mongoose/mongoose.h"
#include "Udp.h"

#pragma comment(lib, "libeay32.lib")
#pragma comment(lib, "ssleay32.lib")
#pragma comment(lib, "ws2_32.lib")

CommManager::CommManager()
{

}

CommManager::~CommManager()
{

}

BOOL CommManager::Init()
{
	WSAData wsaData = {0};
	::WSAStartup(MAKEWORD(2, 2), &wsaData);

	RegisterMsgHandler(MSGID_AVAILABLE_COMM, MsgHandler_AvailableComm, this);

	return TRUE;
}

void CommManager::Deinit()
{
	m_bListenIcmp = FALSE;
	m_icmpRecvThread.WaitForEnd();

	::WSACleanup();
}


void CommManager::HttpPollThread(LPVOID lpParameter)
{
	mg_server* server  = (mg_server*)lpParameter;
	for (;;) mg_poll_server(server, 1000);

};

int CommManager::AddCommService(int port,int name)
{
	COMM_MAP::iterator it;
	int ret = 0;
	int serial = 0;

	do 
	{
		serial = rand()%100;
		if (serial == 0)
		{
			serial = rand()%100;
		}

		it = m_commMap.find(serial);
	} while (it != m_commMap.end());
	
	COMMINFO info;

	switch(name)
	{
	case COMMNAME_HTTP:
		{
			struct mg_server *server;

			// Create and configure the server
			server = mg_create_server(NULL, HttpMsgHandler);

			char szPort[255];

			memset(szPort,0,255);

			itoa(port,szPort,10);

			if( mg_set_option(server, "listening_port", szPort) != NULL )
			{
				mg_destroy_server(&server);
				break;
			}

			DWORD tid = _beginthread(HttpPollThread,0,server);

			info.lpParameter1 = server;
			info.lpParameter2 = (LPVOID)tid;
			info.nCommName = COMMNAME_HTTP;

			m_commMap.insert(MAKE_PAIR(COMM_MAP,serial,info));

			ret = serial;

			break;
		}
	case COMMNAME_HTTPS:
		{
			struct mg_server *server;

			// Create and configure the server
			server = mg_create_server(NULL, HttpMsgHandler);

			CString https = _T("");

			https.Format(_T("ssl://%d:certs/cert.pem"),port);

			if( mg_set_option(server, "listening_port", CStringA(https)) != NULL )
			{
				mg_destroy_server(&server);
				break;
			}

			DWORD tid = _beginthread(HttpPollThread,0,server);

			info.lpParameter1 = server;
			info.lpParameter2 = (LPVOID)tid;
			info.nCommName = COMMNAME_HTTPS;

			m_commMap.insert(MAKE_PAIR(COMM_MAP,serial,info));

			ret = serial;

			break;
		}
	case COMMNAME_UDPS:
	case COMMNAME_UDP:
		{
			CUdp *udp = new CUdp(name == COMMNAME_UDP ? FALSE : TRUE);
			udp->Init();
			if (!udp->Start(port,UdpMsgHandler))
			{
				delete udp;
				break;
			}

			info.nCommName = COMMNAME_UDP;
			info.lpParameter1 = udp;

			m_commMap.insert(MAKE_PAIR(COMM_MAP,serial,info));

			ret = serial;

			break;
		}
	case COMMNAME_TCPS:
	case COMMNAME_TCP:
		{
			CTcp *tcp = new CTcp(name == COMMNAME_TCP ? FALSE : TRUE);
			tcp->Init();
			if (!tcp->Start(port,TcpMsgHandler))
			{
				delete tcp;
				break;
			}

			info.nCommName = COMMNAME_TCP;
			info.lpParameter1 = tcp;

			m_commMap.insert(MAKE_PAIR(COMM_MAP,serial,info));

			ret = serial;

			break;
		}
	default:
		return ret;
	}

	return ret;
}

BOOL CommManager::DeleteCommService(int serialid)
{
	COMM_MAP::iterator it = m_commMap.find(serialid);
	BOOL bRet = TRUE;

	if (it != m_commMap.end())
	{
		COMMINFO &info = it->second;
		switch(info.nCommName)
		{
		case COMMNAME_HTTP:
			{
				TerminateThread(info.lpParameter2,0);
				mg_destroy_server((mg_server**)&info.lpParameter1);

				m_commMap.erase(it);

				break;
			}
		case COMMNAME_HTTPS:
			{
				TerminateThread(info.lpParameter2,0);
				mg_destroy_server((mg_server**)&info.lpParameter1);

				m_commMap.erase(it);

				break;
			}
		case COMMNAME_UDPS:
		case COMMNAME_UDP:
			{
				CUdp *udp = (CUdp *)info.lpParameter1;
				udp->Stop();
				delete udp;

				m_commMap.erase(it);

				break;
			}
		case COMMNAME_TCPS:
		case COMMNAME_TCP:
			{
				CTcp *tcp = (CTcp *)info.lpParameter1;
				tcp->Stop();
				delete tcp;

				m_commMap.erase(it);

				break;
			}
		default:
			bRet = FALSE;
			break;
		}
		return TRUE;
	}
	else bRet = FALSE;

	return bRet;
}
BOOL CommManager::PutMessage( LPCTSTR clientid, ByteBuffer& buffer )
{
	BOOL ret  = FALSE;

	m_cspd.Enter();

	if (m_post.find(clientid) != m_post.end())
	{
		m_post[clientid].push_back(buffer);
	}
	else
	{
		PostDeque pd;
		pd.push_back(buffer);
		m_post[clientid] = pd;
	}
	m_cspd.Leave();

	return ret;
}
MSGSERIALID CommManager::AddToSendMessage( LPCTSTR clientid, const CommData& commData, BOOL bNeedReply /*= TRUE*/ )
{
	static MSGSERIALID s_serialid = 0;
	if (0 == s_serialid)
	{
		__time64_t now;
		_time64(&now);
		s_serialid = now;
	}

	MSGSERIALID ret = INVALID_MSGSERIALID;

	if (bNeedReply)
	{
		m_mapSection.Enter();
		{
			ClientDataMap::iterator finditer = m_clientDataMap.find(clientid);
			if (finditer == m_clientDataMap.end())
			{
				DataMap am;
				std::pair<ClientDataMap::iterator, bool> res = m_clientDataMap.insert(ClientDataMap::value_type(clientid, am));
				if (res.second)
				{
					finditer = res.first;
				}
			}

			if (finditer != m_clientDataMap.end())
			{
				DataMap& am = finditer->second;

				SEND_AND_REPLY tempAA;
				std::pair<DataMap::iterator, bool> res = am.insert(DataMap::value_type(s_serialid, tempAA));
				if (res.second)
				{
					SEND_AND_REPLY& aa = res.first->second;
					aa.bReply = FALSE;
					aa.sendData = commData;
					aa.sendData.SetSerialID(s_serialid);
					ret = s_serialid;

					ByteBuffer toSendData;
					aa.sendData.Serialize(toSendData);
					BOOL bPutMsg = PutMessage( clientid, toSendData );
					if (! bPutMsg)
					{
						errorLog(_T("put message msgid[%I64u] failed"), aa.sendData.GetMsgID());
					}
				}

				s_serialid++;
			}
		}
		m_mapSection.Leave();
	}
	else
	{
		m_mapSection.Enter();
		{
			CommData sendData = commData;
			sendData.SetSerialID(s_serialid);
			ret = s_serialid;

			ByteBuffer toSendData;
			sendData.Serialize(toSendData);
			CPSERIAL cpSerial = 0;
			BOOL bPutMsg = PutMessage(clientid, toSendData);
			if (! bPutMsg)
			{
				errorLog(_T("put message msgid[%I64u][noreply] failed"), sendData.GetMsgID());
			}
			
			s_serialid++;
		}
		m_mapSection.Leave();
	}

	return ret;
}

BOOL CommManager::GetReplyMessage( LPCTSTR clientid, MSGSERIALID serialid, CommData& replyData )
{
	BOOL bReplied = FALSE;

	m_mapSection.Enter();
	{
		ClientDataMap::iterator finditer = m_clientDataMap.find(clientid);
		if (finditer != m_clientDataMap.end())
		{
			DataMap& am = finditer->second;

			DataMap::iterator aaIter = am.find(serialid);
			if (aaIter != am.end())
			{
				SEND_AND_REPLY& aaData = aaIter->second;
				if (aaData.bReply)
				{
					replyData = aaData.replyData;
					bReplied = TRUE;

					am.erase(aaIter);
				}
			}
		}
	}
	m_mapSection.Leave();

	return bReplied;
}

void CommManager::CleanRequest( LPCTSTR clientid, MSGSERIALID serialid )
{
	m_mapSection.Enter();
	{
		ClientDataMap::iterator finditer = m_clientDataMap.find(clientid);
		if (finditer != m_clientDataMap.end())
		{
			DataMap& am = finditer->second;

			DataMap::iterator aaIter = am.find(serialid);
			if (aaIter != am.end())
			{
				am.erase(aaIter);
			}
		}
	}
	m_mapSection.Leave();
}

void CommManager::ListAvailableClient( TStringVector& clientidList, DWORD dwDiffS /*= 60 * 5*/ )
{
	__time64_t now;
	_time64(&now);

	m_mapSection.Enter();
	{
		HeartbeatMap::iterator iter = m_heartbeatMap.begin();
		for (; iter != m_heartbeatMap.end(); iter++)
		{
			__time64_t lastHeartbeat = iter->second.time;
			if (now >= lastHeartbeat && now - lastHeartbeat < dwDiffS)
			{
				clientidList.push_back(iter->first);
			}
		}
	}
	m_mapSection.Leave();
}

BOOL CommManager::GetLastConnectionAddr( LPCTSTR clientid, SOCKADDR_IN& addr )
{
	BOOL bFound = FALSE;
	m_mapSection.Enter();
	{
		HeartbeatMap::const_iterator finditer = m_heartbeatMap.find(clientid);
		if (finditer != m_heartbeatMap.end())
		{
			addr = finditer->second.lastAddr;
			bFound = TRUE;
		}
	}
	m_mapSection.Leave();

	return bFound;
}

void CommManager::RegisterMsgHandler( MSGID msgid, FnMsgHandler fnHandler, LPVOID lpParameter )
{
	m_msgHandlerSection.Enter();
	{
		MsgHandlerMap::iterator finditer = m_msgHandlerMap.find(msgid);
		if (finditer == m_msgHandlerMap.end())
		{
			MsgHandlerInfoList handlerList;
			std::pair<MsgHandlerMap::iterator, bool> res = m_msgHandlerMap.insert(MsgHandlerMap::value_type(msgid, handlerList));
			if(res.second) finditer = res.first;
		}

		if (finditer != m_msgHandlerMap.end())
		{
			MSG_HANDLER_INFO info;
			info.fnCallback = fnHandler;
			info.lpParameter = lpParameter;

			finditer->second.push_back(info);
		}
	}
	m_msgHandlerSection.Leave();
}

void CommManager::HandleMsgByMsgHandler( MSGID msgid, const CommData& commData )
{
	MsgHandlerInfoList handlerList;
	m_msgHandlerSection.Enter();
	{
		MsgHandlerMap::iterator finditer = m_msgHandlerMap.find(msgid);
		if (finditer != m_msgHandlerMap.end())
		{
			handlerList = finditer->second;
		}
	}
	m_msgHandlerSection.Leave();

	MsgHandlerInfoList::iterator iter = handlerList.begin();
	for (; iter != handlerList.end(); iter++)
	{
		MSG_HANDLER_INFO& info = *iter;
		if (! info.fnCallback(msgid, commData, info.lpParameter))
		{
			break;
		}
	}
}

BOOL CommManager::UdpMsgHandler( LPBYTE data,DWORD size,SOCKADDR_IN sin,ByteBuffer& toSender )
{
	BOOL bValidData = FALSE;

	return CommManager::GetInstanceRef().HandleMessageAndReply(sin,data , size, bValidData, toSender);
}

BOOL CommManager::TcpMsgHandler( LPBYTE data,DWORD size,SOCKADDR_IN sin,ByteBuffer& toSender )
{
	BOOL bValidData = FALSE;

	return CommManager::GetInstanceRef().HandleMessageAndReply(sin,data , size, bValidData, toSender);
}
int CommManager::HttpMsgHandler( struct mg_connection *conn, enum mg_event ev )
{
	BOOL bValidData = FALSE;
	BOOL bNeedReply = FALSE;
	BOOL bHasRecv = FALSE;

	ByteBuffer buffer;
	int nOutSize = 0;
	LPBYTE pOutBuf = NULL;

	ByteBuffer toSendBuffer;
	SOCKADDR_IN addr = {0};
	char szLength[255] = {0};

	switch (ev) 
	{
	case MG_AUTH: return MG_TRUE;

	case MG_REQUEST:

		if (strcmp(conn->request_method,"GET") == 0)
		{
			mg_printf_data(conn,"<h1>Website Buiding!</h1>");
			return MG_TRUE;
		}

		addr.sin_addr.S_un.S_addr = inet_addr(conn->remote_ip);

		bNeedReply = CommManager::GetInstanceRef().HandleMessageAndReply(addr,(LPBYTE)conn->content , conn->content_len, bValidData, toSendBuffer);

		sprintf_s(szLength,"%d",toSendBuffer.Size());

		mg_send_header(conn,"Content-Length",szLength);

		if (bNeedReply)
			mg_send_data(conn,toSendBuffer,toSendBuffer.Size());

		return MG_TRUE;

	default: return MG_FALSE;
	}
}

BOOL CommManager::HandleMessage( SOCKADDR_IN fromAddr, const LPBYTE pData, DWORD dwDataSize,tstring& clientid )
{
	ByteBuffer dataBuffer;
	dataBuffer.Alloc(dwDataSize);
	if (! XorFibonacciCrypt(pData, dwDataSize, (LPBYTE)dataBuffer, 2, 7))
	{
		return FALSE;
	}

	CommData recvCommdata;

	BOOL ret = recvCommdata.Parse(dataBuffer, dataBuffer.Size());

	//解析数据
	if ( ret )
	{
		debugLog(_T("recv msg msgid[%I64u] serial[%I64u]"), recvCommdata.GetMsgID(), recvCommdata.GetSerialID());

		SetMessageToAnswer(recvCommdata);
		HandleMsgByMsgHandler(recvCommdata.GetMsgID(), recvCommdata);

		//更新心跳数据
		clientid = recvCommdata.GetClientID();

		UpdateHeartbeat(clientid.c_str(), fromAddr);
	}
	else
	{
		errorLog(_T("parse message failed"));
	}

	return ret;
}

void CommManager::CreateEmptyPacket(ByteBuffer& buffer)
{
	CommData data;
	data.SetMsgID(MSGID_AVAILABLE_COMM);

	data.Serialize(buffer);
}

BOOL CommManager::HandleMessageAndReply( SOCKADDR_IN fromAddr, const LPBYTE pData, DWORD dwDataSize, BOOL& bValidData, ByteBuffer& replyBuffer )
{
	tstring clientid;
	bValidData = HandleMessage(fromAddr, pData, dwDataSize, clientid);
	if (! bValidData)
	{
		return FALSE;
	}

	//找到需要发送的消息
	ByteBuffer toSendData;

	m_cspd.Enter();

	if ( m_post.find(clientid)  != m_post.end() )
	{
		PostDeque &m_pd = m_post[clientid];

		if (m_pd.size() != 0)
		{
			toSendData = m_pd.front();
			m_pd.pop_front();
		}
		else
		{
			CreateEmptyPacket(toSendData);
		}
	}

	m_cspd.Leave();

	replyBuffer.Alloc(toSendData.Size());
	BOOL bEncryptOK = XorFibonacciCrypt((LPBYTE)toSendData, toSendData.Size(), (LPBYTE)replyBuffer, 2, 7);

	return bEncryptOK;
}

BOOL CommManager::SetMessageToAnswer( const CommData& commData )
{
	BOOL bFound = FALSE;

	m_mapSection.Enter();
	{
		ClientDataMap::iterator finditer = m_clientDataMap.find(commData.GetClientID());
		if (finditer != m_clientDataMap.end())
		{
			DataMap& answerMap = finditer->second;

			MSGSERIALID serialid = commData.GetSerialID();
			DataMap::iterator aaiter = answerMap.find(serialid);
			if (aaiter != answerMap.end())
			{
				SEND_AND_REPLY& aaData = aaiter->second;
				if (! aaData.bReply)
				{
					aaData.bReply = TRUE;
					aaData.replyData = commData;
					bFound = TRUE;
				}
			}
		}
	}
	m_mapSection.Leave();

	return bFound;
}

void CommManager::UpdateHeartbeat( LPCTSTR clientid, SOCKADDR_IN addr )
{
	__time64_t now;
	_time64(&now);

	m_mapSection.Enter();
	{
		HeartbeatMap::iterator finditer = m_heartbeatMap.find(clientid);
		if (finditer != m_heartbeatMap.end())
		{
			finditer->second.time = now;
			finditer->second.lastAddr = addr;
		}
		else
		{
			HEARTBEAT_INFO info;
			info.time = now;
			info.lastAddr = addr;
			m_heartbeatMap[clientid] = info;
		}
	}
	m_mapSection.Leave();
}




BOOL CommManager::MsgHandler_AvailableComm( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	DECLARE_UINT64_PARAM(commname);

// 	CommManager* pMgr = (CommManager*) lpParameter;
// 
// 	CommData reply;
// 	reply.Reply(commData);
// 	reply.SetData(_T("commname"), commname);
// 
// 	pMgr->AddToSendMessage(commData.GetClientID(), reply, FALSE);

	return TRUE;
}

