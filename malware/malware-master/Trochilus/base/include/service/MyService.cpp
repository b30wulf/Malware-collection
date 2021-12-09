#include "stdafx.h"
#include "../sa/CSharedSA.h"
#include "../snapshot/CSnapshotManager.h"
#include "MyService.h"

static CShareRestrictedSD g_sa;

MyService::MyService(LPCTSTR serviceName, LPCTSTR displayName, LPCTSTR eventName)
: CNTService(serviceName, displayName)
, m_hExitEvent(NULL)
, m_bConsoleMode(FALSE)
{
	if (eventName != NULL) m_globalEventName = eventName;
}

MyService::~MyService()
{
	if (m_hExitEvent != NULL)
	{
		::CloseHandle(m_hExitEvent);
	}
}

void MyService::Main( int argc, TCHAR* argv[] )
{
	CString processUsername;
	if (argc == 1 
		&& GetProcessUser(::GetCurrentProcessId(), processUsername)
		&& processUsername.CompareNoCase(_T("system")) != 0 
		&& processUsername.CompareNoCase(_T("local service")) != 0)
	{
		m_bConsoleMode = TRUE;
	}

	if (m_bConsoleMode)
	{
		printf("******* Run in console mode. press any key to stop *******\n\n");

		HANDLE hThread = ::CreateThread(NULL, 0, ConsoleModeThread, this, 0, NULL);

		getchar();
		::CloseHandle(hThread);
		Stop();

		while (m_dwCurState != SERVICE_STOPPED)
		{
			::Sleep(500);
		}

		printf("\n\n******* The END *******\n\n");
	}
	else
	{
		exit(RegisterService(argc, argv));
	}
}

BOOL MyService::IsConsoleMode() const
{
	return m_bConsoleMode;
}

BOOL MyService::Init()
{
	if (m_globalEventName.GetLength() > 0)
	{
		m_hExitEvent = ::CreateEvent(g_sa.GetSA(), FALSE, FALSE, m_globalEventName);
	}
	else
	{
		m_hExitEvent = ::CreateEvent(NULL, FALSE, FALSE, NULL);
	}
		
	if (NULL == m_hExitEvent)
	{
		OutputDebugString(_T("MyService create exit event failed"));
		return FALSE;
	}

	::ResetEvent(m_hExitEvent);

	return TRUE;
}

void MyService::SetExitEvents(const EventList& exitEvents)
{
	m_exitEventList = exitEvents;
}

void MyService::Run(DWORD argc, LPTSTR * argv)
{
	//初始化
	MyReportStatus(SERVICE_START_PENDING);
	if (! Init() || ! InitService())
	{
		MyReportStatus(SERVICE_STOPPED);
		OutputDebugString(_T("init service failed"));
		return;
	}
	MyReportStatus(SERVICE_RUNNING);

	//开始运行
	if (! RunRaw())
	{
		return;
	}

	//等待结束的信号通知
	DWORD dwWait = 0;
	if (m_exitEventList.size() == 0)
	{
		while( WaitForSingleObject(m_hExitEvent, INFINITE) != WAIT_OBJECT_0 ) 
		{
			Sleep(500);
		}
	}
	else
	{
		HANDLE pExitHandles[256] = {0};
		int exitHandleIndex = 0;
		pExitHandles[exitHandleIndex++] = m_hExitEvent;
		EventList::iterator iter = m_exitEventList.begin();
		for (; iter != m_exitEventList.end(); iter++)
		{
			pExitHandles[exitHandleIndex++] = *iter;
		}

		dwWait = ::WaitForMultipleObjects(exitHandleIndex, pExitHandles, FALSE, INFINITE);
		while (dwWait >= WAIT_OBJECT_0 + exitHandleIndex)
		{
			Sleep(500);
			dwWait = ::WaitForMultipleObjects(exitHandleIndex, pExitHandles, FALSE, INFINITE);
		}
	}
	
	//反初始化
	DeinitService(dwWait);
	MyReportStatus(SERVICE_STOPPED);
}

BOOL MyService::RunRaw()
{
	BD_TRY();
	if (! RunService())
	{
		MyReportStatus(SERVICE_STOPPED);
		OutputDebugString(_T("run service failed"));
		return FALSE;
	}
	BD_ENDTRY(_T("service"));

	return TRUE;
}

void MyService::Stop()
{
	if (m_bConsoleMode && m_dwCurState == SERVICE_STOPPED) return;

	MyReportStatus(SERVICE_STOP_PENDING);
	if( m_hExitEvent ) SetEvent(m_hExitEvent);
}

DWORD WINAPI MyService::ConsoleModeThread(LPVOID lpParameter)
{
	MyService* pService = (MyService*) lpParameter;

	pService->Run(0, NULL);

	return 0;
}

BOOL MyService::MyReportStatus( DWORD CurState, /* service's state */ DWORD WaitHint /*= 3000*/, /* expected time of operation in milliseconds */ DWORD ErrExit /*= 0 /*!! TCW MOD - set to nonzero to flag *FATAL* error */ )
{
	if (m_bConsoleMode)
	{
		printf("CONSOLE_MOD : %u\n", CurState);
		m_dwCurState = CurState;

		return TRUE;
	}
	else
	{
		return ReportStatus(CurState, WaitHint, ErrExit);
	}
}

BOOL MyService::GetProcessUser(DWORD dwProcessID, CString& username) const
{
	BOOL fResult  = FALSE;
	HANDLE hProc  = NULL;
	HANDLE hToken = NULL;
	TOKEN_USER *pTokenUser = NULL;

	__try
	{
		// Open the process with PROCESS_QUERY_INFORMATION access
		hProc = ::OpenProcess(PROCESS_QUERY_INFORMATION, FALSE, dwProcessID);
		if (hProc == NULL)
		{
			__leave;
		}
		fResult = ::OpenProcessToken(hProc, TOKEN_QUERY, &hToken);
		if(!fResult)  
		{
			__leave;
		}

		DWORD dwNeedLen = 0;  
		fResult = ::GetTokenInformation(hToken,TokenUser, NULL, 0, &dwNeedLen);
		if (dwNeedLen > 0)
		{
			pTokenUser = (TOKEN_USER*)new BYTE[dwNeedLen];
			fResult = ::GetTokenInformation(hToken,
				TokenUser,
				pTokenUser,
				dwNeedLen,
				&dwNeedLen);
			if (!fResult)
			{
				__leave;
			}
		}
		else
		{
			__leave;
		}

		SID_NAME_USE sn;
		TCHAR szDomainName[MAX_PATH];
		DWORD dwDmLen = MAX_PATH;
		TCHAR szUserName[MAX_PATH] = {0};
		DWORD nNameLen = MAX_PATH;
		fResult = ::LookupAccountSid(NULL,
			pTokenUser->User.Sid,
			szUserName,
			&nNameLen,
			szDomainName,
			&dwDmLen,
			&sn);
		if (fResult) username = szUserName;
	}
	__finally
	{
		if (hProc) ::CloseHandle(hProc);
		if (hToken) ::CloseHandle(hToken);
		if (pTokenUser) delete[] (char*)pTokenUser;
	}
	return fResult;
}
