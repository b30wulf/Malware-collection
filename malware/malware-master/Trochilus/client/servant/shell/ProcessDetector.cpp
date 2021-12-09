#include "stdafx.h"
#include <Tlhelp32.h>
#include "Exports.h"
#include "ProcessDetector.h"

ProcessDetector::ProcessDetector()
{

}

ProcessDetector::~ProcessDetector()
{

}

BOOL ProcessDetector::Start()
{
	//·À¼ì²â
	TCHAR* namestr = _T("procexp.exe,procmon.exe,windbg.exe,filemon.exe,ollyice.exe,ollydbg.exe,processspy.exe,spyxx.exe,cv.exe,wireshark.exe");
	TStringVector namelist;
	splitByChar(namestr, namelist, ',');
	m_processNameSet.clear();
	TStringVector2TStringSet(namelist, m_processNameSet);
	if (! m_checkTask.Init(CheckTask, this, _T("ct"), 3, 5))
	{
		errorLog(_T("init checktask failed"));
		return FALSE;
	}
	
	return m_checkTask.Start();
}

void ProcessDetector::Stop()
{
	m_checkTask.Stop();
}

BOOL ProcessDetector::CheckProcessList( const TStringSet& nameset, tstring& matchedName ) const
{
	HANDLE hProcessSnap = ::CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);
	if (hProcessSnap == INVALID_HANDLE_VALUE)
	{
		errorLogE(_T("create toolhelp failed."));
		return FALSE;
	}

	PROCESSENTRY32 pe32 = {0};
	pe32.dwSize = sizeof(PROCESSENTRY32);

	if (! Process32First(hProcessSnap, &pe32))
	{
		CloseHandle(hProcessSnap);
		return FALSE;
	}

	BOOL bFound = FALSE;
	do 
	{
		if (pe32.th32ProcessID == GetCurrentProcessId()) continue;

		tstring exefile = pe32.szExeFile;
		makeLower(exefile);

		if (nameset.find(exefile.c_str()) != nameset.end())
		{
			bFound = TRUE;
			matchedName = exefile;
			break;
		}
	} while(Process32Next(hProcessSnap, &pe32));

	CloseHandle(hProcessSnap);

	return bFound;
}

DWORD WINAPI ProcessDetector::CheckTask( LPVOID lpParameter )
{
	ProcessDetector* pMgr = (ProcessDetector*) lpParameter;

	tstring matchedProcess;
	if (pMgr->CheckProcessList(pMgr->m_processNameSet, matchedProcess))
	{
		debugLog(_T("detect %s"), matchedProcess.c_str());
		ExitServant();
	}

	return 0;
}
