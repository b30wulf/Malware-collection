#include "stdafx.h"
#include "sa/CSharedSA.h"
#include "EventWaiter.h"

static CShareRestrictedSD g_sd;

EventWaiter::EventWaiter()
	: m_hChangeEvent(NULL)
	, m_bWorking(FALSE)
	, m_hWaiterThread(NULL)
{
	::InitializeCriticalSection(&m_dataSection);
}

EventWaiter::~EventWaiter()
{
	::DeleteCriticalSection(&m_dataSection);

	if (NULL != m_hChangeEvent) ::CloseHandle(m_hChangeEvent);
}

BOOL EventWaiter::Init()
{
	m_hChangeEvent = ::CreateEvent(NULL, FALSE, FALSE, NULL);
	if (NULL == m_hChangeEvent)
	{
		errorLogE(_T("create changeevent failed."));
		return FALSE;
	}

	m_bWorking = TRUE;
	m_hWaiterThread = ::CreateThread(NULL, 0, EventWaiterThread, this, 0, NULL);
	if (NULL == m_hWaiterThread)
	{
		errorLog(_T("create EventWaiter thread failed."));
		return FALSE;
	}

	return TRUE;
}

void EventWaiter::Deinit()
{
	if (! m_bWorking) return;

	m_bWorking = FALSE;
	::SetEvent(m_hChangeEvent);
	::WaitForSingleObject(m_hWaiterThread, 3000);
	::CloseHandle(m_hWaiterThread);
	m_hWaiterThread = NULL;

	::EnterCriticalSection(&m_dataSection);
	{
		EventNameMap::iterator iter = m_eventNameMap.begin();
		for (; iter != m_eventNameMap.end(); iter++)
		{
			HANDLE hEvent = iter->first;
			::CloseHandle(hEvent);
		}

		m_eventNameMap.clear();
		m_eventCallbackMap.clear();
	}
	::LeaveCriticalSection(&m_dataSection);
}

BOOL EventWaiter::AddNotifyCallback( LPCTSTR eventName, FnEventNotifyCallback fnCallback, LPVOID lpParameter )
{
	if (NULL == eventName) return FALSE;

	//将event触发通知回调函数信息加入eventCallbackMap中
	BOOL bNewEvent = FALSE;
	::EnterCriticalSection(&m_dataSection);
	{
		EventCallbackMap::iterator finditer = m_eventCallbackMap.find(eventName);
		if (finditer == m_eventCallbackMap.end())
		{
			CallbackList cbList;
			std::pair<EventCallbackMap::iterator, bool> res = 
				m_eventCallbackMap.insert(EventCallbackMap::value_type(eventName, cbList));

			if (res.second) finditer = res.first;

			bNewEvent = TRUE;
		}

		if (finditer != m_eventCallbackMap.end())
		{
			CALLBACK_INFO info;
			info.fnCallback = fnCallback;
			info.lpParameter = lpParameter;

			finditer->second.push_back(info);
		}
	}
	::LeaveCriticalSection(&m_dataSection);
	//如果该event之前已经被其他地方注册过了，就不需要新创建了，直接返回即可
	if (! bNewEvent) return TRUE;

	//创建Event
	HANDLE hEvent = ::CreateEvent(g_sd.GetSA(), FALSE, FALSE, eventName);
	if (NULL == hEvent)
	{
		errorLogE(_T("create event [%s] failed."), eventName);
		return FALSE;
	}

	//将新建的Event加入映射表m_eventNameMap
	::EnterCriticalSection(&m_dataSection);
	{
		m_eventNameMap.insert(EventNameMap::value_type(hEvent, eventName));
	}
	::LeaveCriticalSection(&m_dataSection);

	//触发event变更通知，让EventWaiterThread重新装载event并wait
	::SetEvent(m_hChangeEvent);

	return TRUE;
}

DWORD WINAPI EventWaiter::EventWaiterThread( LPVOID lpParameter )
{
	EventWaiter* pWaiter = (EventWaiter*) lpParameter;
	pWaiter->EventWaiterProc();
	return 0;
}

void EventWaiter::EventWaiterProc()
{
	HANDLE* pHandleList = NULL;
	DWORD dwCount = 0;

	while (m_bWorking)
	{
		if (NULL == pHandleList)
		{
			//从m_eventNameMap中读取Event并装入pHandleList
			::EnterCriticalSection(&m_dataSection);
			{
				dwCount = (DWORD) m_eventNameMap.size() + 1;

				pHandleList = new HANDLE[dwCount];

				pHandleList[0] = m_hChangeEvent;
				EventNameMap::iterator iter = m_eventNameMap.begin();
				for (int i = 1; iter != m_eventNameMap.end(); iter++, i++)
				{
					pHandleList[i] = iter->first;
				}
			}
			::LeaveCriticalSection(&m_dataSection);
		}
		
		DWORD dwWait = ::WaitForMultipleObjects(dwCount, pHandleList, FALSE, INFINITE);

		//如果停止工作了，则退出
		if (! m_bWorking) break;

		//如果是事件变更通知，则重新装载
		if (WAIT_OBJECT_0 == dwWait)
		{
			delete[] pHandleList;
			pHandleList = NULL;

			debugLog(_T("change event is triggered."));

			continue;
		}

		//调用回调函数
		DWORD index = dwWait - WAIT_OBJECT_0;
		if (index < dwCount)
		{
			HANDLE hTriggeredEvent = pHandleList[index];
			CallbackList callbackList;
			CString eventName;

			//查找event对应的callback
			::EnterCriticalSection(&m_dataSection);
			{
				EventNameMap::iterator nameIter = m_eventNameMap.find(hTriggeredEvent);
				if (nameIter != m_eventNameMap.end())
				{
					eventName = nameIter->second;
					
					EventCallbackMap::iterator cbiter = m_eventCallbackMap.find(eventName);
					if (cbiter != m_eventCallbackMap.end())
					{
						callbackList = cbiter->second;
					}
				}
			}
			::LeaveCriticalSection(&m_dataSection);

			debugLog(_T("event[%s] is triggered"), eventName);

			//依次调用callback
			CallbackList::iterator iter = callbackList.begin();
			for (; iter != callbackList.end(); iter++)
			{
				const CALLBACK_INFO& info = *iter;
				BOOL bContinue = info.fnCallback(eventName, info.lpParameter);
				if (! bContinue) break;
			}
		}
	}

	if (NULL != pHandleList) delete[] pHandleList;	
}
