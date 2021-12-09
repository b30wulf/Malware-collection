#pragma once
#include "FileParser.h"
#include <list>
#include "thread/ThreadArray.h"
#include "FileTransferData.h"

class CFileTransfer
{
	DECLARE_SINGLETON(CFileTransfer)
public:
	static BOOL MsgHandler_GetFile( MSGID msgid, const CommData& commData, LPVOID lpParameter );
	BOOL MsgHandler_GetFile_Proc(MSGID msgid, const CommData& commData);

	static BOOL MsgHandler_PutFile( MSGID msgid, const CommData& commData, LPVOID lpParameter );
	BOOL MsgHandler_PutFile_Proc( MSGID msgid, const CommData& commData);

	BOOL IsHasStop(LPCTSTR serverpath);
	BOOL AddStopList(LPCTSTR serverpath);
	BOOL DeleteStopList(LPCTSTR serverpath);

	void GetTransferList(LPCTSTR clientid,TransStatusVector& list);
	void UpdateTransferList(LPCTSTR clientid,TRANS_STATUS& status);
	BOOL DeleteTransferInfo(LPCTSTR clientid,TRANS_STATUS& status);

	BOOL RequestGetFile(LPCTSTR clientid,LPCTSTR clientpath,LPCTSTR serverpath);
	BOOL RequestPutFile(LPCTSTR clientid,LPCTSTR clientpath,LPCTSTR serverpath);

	BOOL GetStatusByPath(LPCTSTR clientid,CString strSPath,TRANS_STATUS& status);

private:
	CriticalSection m_csStopMap;
	typedef std::list<CString> TransStopList;
	TransStopList m_stopList;

	CriticalSection m_csProcessMap;
	typedef std::map<CString,TransStatusVector> ProcessMap;
	ProcessMap m_processMap;
};

