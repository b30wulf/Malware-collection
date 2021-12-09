#pragma once
#include "tstring.h"

class RepeatTask
{
	DECLARE_UNCOPYABLE(RepeatTask);
public:
	RepeatTask();
	~RepeatTask();
	BOOL Init(LPTHREAD_START_ROUTINE pFun, LPVOID pTaskParameter, LPCTSTR taskMemo, DWORD dwStartOffset, DWORD dwIntervalSeconds);

	BOOL Start();
	void Stop();

	void ExecTaskImmediately();
	BOOL IsRunning() const;

private:
	static DWORD WINAPI TaskThread(LPVOID lpParameter);
	void TaskProc();

private:
	BOOL					m_bRunning;
	HANDLE					m_hWaitEvent;
	HANDLE					m_hThread;

	LPTHREAD_START_ROUTINE	m_pFun;
	LPVOID					m_pTaskParameter;
	tstring					m_memo;
	DWORD					m_dwStartOffset;
	DWORD					m_dwIntervalMSeconds;
};
