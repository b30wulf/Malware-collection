#include "stdafx.h"
#include "AutoCleanup.h"
#include "file/MyFile.h"
#include "CommDataUtils.h"

MSGSERIALID CommDataUtils::SendMessageToClient( LPCTSTR clientid, const CommData& commData, BOOL bNeedReply )
{
	ByteBuffer byteData;
	commData.Serialize(byteData);

	return SendMessage2Client(clientid, (LPBYTE)byteData, byteData.Size(), bNeedReply);
}

BOOL CommDataUtils::WaitForReply( LPCTSTR clientid, MSGSERIALID msgSerialID, CommData& replyData, DWORD dwWaitTimeoutS )
{
	//µÈ´ý»ØÓ¦
	DWORD dwSleepMS = 200;
	BOOL bReplied = FALSE;
	for (int i = 0; i < (int)(dwWaitTimeoutS * 1000 / dwSleepMS); i++)
	{
		Sleep(dwSleepMS);

		ByteList replyByteList;
		if (GetReplyByMsgserialid(clientid, msgSerialID, &replyByteList))
		{
			if (! replyData.Parse(replyByteList.GetPointer(), replyByteList.Count()))
			{
				errorLog(_T("get reply OK but parse failed[%s][%I64u]"), clientid, msgSerialID);
				return FALSE;
			}

			return TRUE;
		}
	}

	return FALSE;
}

BOOL CommDataUtils::SendAndWaitForReply( LPCTSTR clientid, const CommData& request, CommData& reply, DWORD dwWaitTimeoutS )
{
	MSGSERIALID msgserialID = SendMessageToClient(clientid, request, TRUE);
	if (INVALID_MSGSERIALID == msgserialID)
	{
		errorLog(_T("send message to client failed. [%s]"), clientid);
		return FALSE;
	}

	return WaitForReply(clientid, msgserialID, reply, dwWaitTimeoutS);
}

BOOL CommDataUtils::ReadDataFile( LPCTSTR datafilepath, CommData& data )
{
	MyFile file;
	if (! file.Open(datafilepath, GENERIC_READ, OPEN_EXISTING, FILE_SHARE_READ))
	{
		errorLog(_T("open file [%s] failed"), datafilepath);
		return FALSE;
	}

	ByteBuffer content;
	if (! file.ReadAll(content))
	{
		errorLog(_T("readall [%s] failed"), datafilepath);
		return FALSE;
	}

	data.SetByteData(content, content.Size());

	return TRUE;

}
