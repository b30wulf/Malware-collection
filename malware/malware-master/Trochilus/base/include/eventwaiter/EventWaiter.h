#pragma once
#include <list>
#include <map>
#include "EventWaiterData.h"

class EventWaiter
{
public:
	EventWaiter();
	~EventWaiter();

	BOOL Init();
	void Deinit();

	BOOL AddNotifyCallback(LPCTSTR eventName, FnEventNotifyCallback fnCallback, LPVOID lpParameter);

private:
	typedef struct
	{
		FnEventNotifyCallback	fnCallback;
		LPVOID					lpParameter;
	} CALLBACK_INFO;

	typedef std::list<CALLBACK_INFO> CallbackList;

	typedef std::map<CString, CallbackList> EventCallbackMap;
	typedef std::map<HANDLE, CString> EventNameMap;

private:
	static DWORD WINAPI EventWaiterThread(LPVOID lpParameter);
	void EventWaiterProc();

private:
	CRITICAL_SECTION	m_dataSection;
	EventCallbackMap	m_eventCallbackMap;
	EventNameMap		m_eventNameMap;
	HANDLE				m_hChangeEvent;
	BOOL				m_bWorking;
	HANDLE				m_hWaiterThread;
};
