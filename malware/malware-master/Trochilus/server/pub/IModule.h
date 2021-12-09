#pragma once

class IModule
{
public:
	CString m_clientid;
	CString m_modname;
	CLIENT_INFO m_clientinfo;

	BOOL InitModule(LPCTSTR clientid)
	{
		m_clientid = clientid;
		
		GetClientInfo(clientid,&m_clientinfo);

		return TRUE;
	}
};