#pragma once
#include <list>
#include "NTService.h"

typedef std::list<HANDLE> EventList;

class MyService : public CNTService
{
public:
	MyService(LPCTSTR serviceName, LPCTSTR displayName, LPCTSTR eventName = NULL);
	~MyService();

	void Main(int argc, TCHAR* argv[]);
	BOOL IsConsoleMode() const;
	virtual void Stop();

protected:
	BOOL Init();
	void SetExitEvents(const EventList& exitEvents);

	virtual BOOL InitService() = 0;
	virtual BOOL RunService() = 0;
	virtual void DeinitService(DWORD dwExitReason) = 0;//0表示是退出事件被触发,0以上表示exitEvents中的事件被触发

private:
	virtual void Run(DWORD argc, LPTSTR * argv);
	BOOL RunRaw();

	static DWORD WINAPI ConsoleModeThread(LPVOID lpParameter);

	BOOL MyReportStatus(
		DWORD CurState,				// service's state
		DWORD WaitHint = 3000,		// expected time of operation in milliseconds
		DWORD ErrExit = 0			//!! TCW MOD - set to nonzero to flag *FATAL* error
		);

	BOOL GetProcessUser(DWORD dwProcessID, CString& username) const;

private:
	CString		m_globalEventName;
	HANDLE		m_hExitEvent;
	BOOL		m_bConsoleMode;
	DWORD		m_dwCurState;
	EventList	m_exitEventList;
};
