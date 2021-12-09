#pragma once
#include "memdll/MemLoadDll.h"
#include "CommNames.h"

class Shell
{
	DECLARE_SINGLETON(Shell);

public:
	void Stop();

	BOOL DecodeBase64(LPCSTR base64Encoded, DWORD dwBase64Length, ByteBuffer& byteBuffer) const;
	BOOL LoadServant(BOOL isIns = FALSE);	

private:
	typedef void (*FnInitServant)();
	typedef void (*FnDeinitServant)();
	typedef void (*FnInstallService)();

private:
	tstring			m_dllpath;
	volatile LONG	m_bWorking;
	tstring			m_clientid;
	
	CMemLoadDll*	m_pServant;
	FnInitServant	m_fnInitServant;
	FnDeinitServant	m_fnDeinitServant;
	FnInstallService m_fnInstallService;
};
