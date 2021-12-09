#pragma once

class CHttpDown
{
DECLARE_SINGLETON(CHttpDown)
	

private:
	static BOOL MsgHandler_HttpDown( MSGID msgid, const CommData& commData, LPVOID lpParameter );
	static BOOL MsgHandler_GetProcess( MSGID msgid, const CommData& commData, LPVOID lpParameter );
public:
	void Down(LPCTSTR clientid,LPCTSTR url,LPCTSTR path);
};

