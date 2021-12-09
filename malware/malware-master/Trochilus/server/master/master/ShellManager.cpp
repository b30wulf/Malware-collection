#include "stdafx.h"
#include "CommManager.h"
#include "ShellManager.h"

ShellManager::ShellManager()
{

}

ShellManager::~ShellManager()
{

}

BOOL ShellManager::Init()
{
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_CMDREDIRECT_OUTPUT, MsgHandler_CmdOutput, this);
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_CMDREDIRECT_INPUT, MsgHandler_CmdInput, this);
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_CMDREDIRECT_CLOSE, MsgHandler_CmdInput, this);

	return TRUE;
}

void ShellManager::Deinit()
{
	Clean();
}

BOOL ShellManager::OpenShell( LPCTSTR clientid, FnRemoteCmdOutput fnRemoteCmdOutput, LPVOID lpParameter )
{
	if (NULL != GetShell(clientid))
	{
		CloseShell(clientid);
	}

	CmdShell* pShell = new CmdShell;
	m_mapSection.Enter();
	{
		m_shellMap[clientid] = pShell;
	}
	m_mapSection.Leave();

	if (! pShell->Init(clientid, fnRemoteCmdOutput, lpParameter))
	{
		errorLog(_T("init shell failed"));
		CloseShell(clientid);
		return FALSE;
	}

	return TRUE;
}

void ShellManager::CloseShell( LPCTSTR clientid )
{
	m_mapSection.Enter();
	{
		ShellMap::iterator finditer = m_shellMap.find(clientid);
		if (finditer != m_shellMap.end())
		{
			finditer->second->Close();
			delete finditer->second;
			m_shellMap.erase(finditer);
		}
	}
	m_mapSection.Leave();
}

CmdShell* ShellManager::GetShell( LPCTSTR clientid )
{
	CmdShell* pShell = NULL;
	m_mapSection.Enter();
	{
		ShellMap::iterator finditer = m_shellMap.find(clientid);
		if (finditer != m_shellMap.end())
		{
			pShell = finditer->second;
		}
	}
	m_mapSection.Leave();

	return pShell;
}

void ShellManager::Clean()
{
	m_mapSection.Enter();
	{
		ShellMap::iterator iter = m_shellMap.begin();
		for (; iter != m_shellMap.end(); iter++)
		{
			CmdShell* pShell = iter->second;
			delete pShell;
		}

		m_shellMap.clear();
	}
	m_mapSection.Leave();
}

BOOL ShellManager::MsgHandler_CmdOutput( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	ShellManager* pMgr = (ShellManager*) lpParameter;

	CmdShell* pShell = pMgr->GetShell(commData.GetClientID());
	if (NULL != pShell)
	{
		const ByteBuffer& byteData = commData.GetByteData();
		pShell->RecvRemoteOutput((LPCSTR)(const LPBYTE)byteData);
	}

	return TRUE;
}

BOOL ShellManager::MsgHandler_CmdInput( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	tstring clientid = commData.GetClientID();
	CommManager::GetInstanceRef().CleanRequest(clientid.c_str(), commData.GetSerialID());
	return TRUE;
}

BOOL ShellManager::MsgHandler_CmdClose( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	tstring clientid = commData.GetClientID();
	CommManager::GetInstanceRef().CleanRequest(clientid.c_str(), commData.GetSerialID());
	return TRUE;
}
