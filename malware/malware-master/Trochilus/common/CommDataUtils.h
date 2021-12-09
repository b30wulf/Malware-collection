#pragma once

#include "CommData.h"

namespace CommDataUtils
{
	MSGSERIALID SendMessageToClient(LPCTSTR clientid, const CommData& commData, BOOL bNeedReply);

	BOOL WaitForReply(LPCTSTR clientid, MSGSERIALID msgSerialID, CommData& replyData, DWORD dwWaitTimeoutS = 10);
	
	BOOL SendAndWaitForReply(LPCTSTR clientid, const CommData& request, CommData& reply, DWORD dwWaitTimeoutS = 10 );

	BOOL ReadDataFile(LPCTSTR datafilepath, CommData& data);
}

