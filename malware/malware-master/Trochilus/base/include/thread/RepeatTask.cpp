#include "stdafx.h"
#include "RepeatTask.h"

RepeatTask::RepeatTask()
: m_bRunning(FALSE)
, m_hWaitEvent(NULL)
, m_pFun(NULL)
, m_pTaskParameter(NULL)
, m_dwStartOffset(0)
, m_dwIntervalMSeconds(0)
, m_hThread(NULL)
{

}

RepeatTask::~RepeatTask()
{
	TerminateThread(m_hThread,0);
	if (NULL != m_hWaitEvent) ::CloseHandle(m_hWaitEvent);
}

BOOL RepeatTask::Init(LPTHREAD_START_ROUTINE pFun, LPVOID pTaskParameter, LPCTSTR taskMemo, DWORD dwStartOffset, DWORD dwIntervalSeconds)
{
	m_hWaitEvent = ::CreateEvent(NULL, FALSE, FALSE, NULL);
	if (NULL == m_hWaitEvent)
	{
		errorLog(_T("create event failed"));
		return FALSE;
	}

	m_pFun = pFun;
	m_pTaskParameter = pTaskParameter;
	m_memo = taskMemo;
	m_dwStartOffset = dwStartOffset;
	m_dwIntervalMSeconds = (dwIntervalSeconds == INFINITE) ? INFINITE : dwIntervalSeconds * 1000;

	return TRUE;
}

BOOL RepeatTask::Start()
{
	if (m_bRunning) 
	{
		return FALSE;
	}
	else
	{
		::ResetEvent(m_hWaitEvent);

		m_bRunning = TRUE;	//这里设置为TRUE是为了线程里面运行正常
		m_hThread = ::CreateThread(NULL, 0, RepeatTask::TaskThread, this, 0, NULL);
		if (NULL == m_hThread)
		{
			m_bRunning = FALSE;
		}

		return m_bRunning;
	}
}

void RepeatTask::Stop()
{
	if (m_bRunning)
	{
		m_bRunning = FALSE;
		::SetEvent(m_hWaitEvent);
		::WaitForSingleObject(m_hThread, INFINITE);
		m_hThread = NULL;
	}
}

void RepeatTask::ExecTaskImmediately()
{
	::SetEvent(m_hWaitEvent);
}

BOOL RepeatTask::IsRunning() const
{
	return m_bRunning;
}

DWORD WINAPI RepeatTask::TaskThread(LPVOID lpParameter)
{
	RepeatTask* pTask = (RepeatTask*) lpParameter;
	pTask->TaskProc();
	return 0;
}
	
void RepeatTask::TaskProc()
{
	if (m_dwStartOffset > 0)
	{
		::WaitForSingleObject(m_hWaitEvent, m_dwStartOffset * 1000);
	}
	
	while (m_bRunning)
	{
		m_pFun(m_pTaskParameter);

		::WaitForSingleObject(m_hWaitEvent, m_dwIntervalMSeconds);
	}

	debugLog(_T("RepeatTask [%s] stopped"), m_memo.c_str());
}
