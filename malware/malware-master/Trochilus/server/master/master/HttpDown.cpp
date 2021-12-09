#include "StdAfx.h"
#include "HttpDown.h"
#include "CommManager.h"
#include "MessageDefines.h"



CHttpDown::CHttpDown(void)
{

}


CHttpDown::~CHttpDown(void)
{
}

void CHttpDown::Down(LPCTSTR clientid,LPCTSTR url,LPCTSTR path)
{
	CommData data;
	data.SetClientID(clientid);
	data.SetMsgID(MSGID_HTTPDOWN_FILE);
	data.SetData(_T("url"),url);
	data.SetData(_T("path"),path);

	CommManager::GetInstanceRef().AddToSendMessage(clientid,data,FALSE);

	return;
}