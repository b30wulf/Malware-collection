#pragma once
#include "CommNames.h"

class IComm
{
public:
	virtual COMM_NAME GetName() = 0;
	virtual BOOL CanRecv() const {return TRUE;};
	virtual BOOL Init() {return TRUE;};

	virtual BOOL Send(ULONG targetIP, const LPBYTE pData, DWORD dwSize) = 0;
	virtual BOOL SendAndRecv(ULONG targetIP, const LPBYTE pSendData, DWORD dwSendSize,
		LPBYTE* pRecvData, DWORD& dwRecvSize)
	{
		dwRecvSize = 0;
		return Send(targetIP, pSendData, dwSendSize);
	};

	void FreeRecvData(LPBYTE pRecvData) const { Free(pRecvData);};
	
protected:
	LPBYTE Alloc(DWORD dwSize) const { return (LPBYTE)malloc(dwSize);};
	void Free(LPBYTE pData) const { free(pData);};
};
