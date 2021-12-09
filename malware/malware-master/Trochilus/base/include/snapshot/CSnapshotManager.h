#pragma once
#include "CommonDefines.h"

typedef void (*FnCrashCallback)(PEXCEPTION_POINTERS pException);

class CSnapshotManager
{
	DECLARE_SINGLETON(CSnapshotManager);
public:
	void setCrashCallback(FnCrashCallback fnCallback);
	BOOL generateDumpFile(PEXCEPTION_POINTERS pException, LPCTSTR prefix);

private:
	LPCTSTR getBaseDir() const;

private:
	CRITICAL_SECTION	m_dmpSection;
	FnCrashCallback		m_fnCallback;
};


#define BD_TRY() __try {
#define BD_ENDTRY(prefix) } __except(CSnapshotManager::GetInstanceRef().generateDumpFile(GetExceptionInformation(), prefix), EXCEPTION_EXECUTE_HANDLER) {::TerminateProcess(GetCurrentProcess(),-1);}
