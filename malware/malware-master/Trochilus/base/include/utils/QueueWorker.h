#pragma once
#include <list>

template <class OwnerType, class WorkType>
class QueueWorker
{
public:
	typedef void (*Handler)(WorkType item, OwnerType* pOwner);

public:
	QueueWorker()
		: m_pOwner(NULL)
		, m_hSemaphore(NULL)
		, m_bWorking(FALSE)
		, m_hWorkerThread(NULL)
		, m_handler(NULL)
		, m_bWorkerBusy(FALSE)
	{
		::InitializeCriticalSection(&m_section);
	}

	~QueueWorker()
	{
		if (NULL != m_hSemaphore) ::CloseHandle(m_hSemaphore);
		::DeleteCriticalSection(&m_section);
	}

	BOOL Init(OwnerType* pOwner, Handler pHandler, LPCTSTR memo)
	{
		m_pOwner = pOwner;
		m_handler = pHandler;
		m_memo = memo;

		m_hSemaphore = ::CreateSemaphore(NULL, 0, MAXLONG, NULL);
		if (NULL == m_hSemaphore)
		{
			errorLogE(_T("create semaphore failed."));
			return FALSE;
		}

		return TRUE;
	}

	BOOL Start()
	{
		if (m_bWorking) return FALSE;

		m_bWorking = TRUE;
		m_hWorkerThread = ::CreateThread(NULL, 0, WorkerRoutine, this, 0, NULL);
		if (NULL == m_hWorkerThread)
		{
			errorLogE(_T("create for queueworker failed."));
			m_bWorking = FALSE;
			return FALSE;
		}

		debugLog(_T("QueueWorker for [%s] start"), m_memo);

		return TRUE;
	}

	void Stop(DWORD dwTimeoutMS = 5000)
	{
		if (! m_bWorking) return;

		m_bWorking = FALSE;
		::ReleaseSemaphore(m_hSemaphore, 1, NULL);

		::WaitForSingleObject(m_hWorkerThread, dwTimeoutMS);
		::CloseHandle(m_hWorkerThread);
		m_hWorkerThread = NULL;
	}

	void AddWorkItem(const WorkType& item)
	{
		::EnterCriticalSection(&m_section);
		{
			m_queue.push_back(item);
		}
		::LeaveCriticalSection(&m_section);	

		::ReleaseSemaphore(m_hSemaphore, 1, NULL);
	}

	void DeleteWorkItem(WorkType item)
	{
		::EnterCriticalSection(&m_section);
		{
			WorkQueue::iterator iter = m_queue.begin();
			while (iter != m_queue.end())
			{
				if (*iter == item) iter = m_queue.erase(iter);
				else iter++;
			}
		}
		::LeaveCriticalSection(&m_section);	
	}

	DWORD QueueLength()
	{
		DWORD dwLen = 0;
		::EnterCriticalSection(&m_section);
		{
			dwLen = (DWORD) m_queue.size();
		}
		::LeaveCriticalSection(&m_section);	

		return dwLen;
	}

	BOOL IsWorkerBusy()
	{
		return (BOOL) m_bWorkerBusy;
	}

private:
	static DWORD WINAPI WorkerRoutine(LPVOID lpParameter)
	{
		QueueWorker<OwnerType, WorkType>* pWorker = (QueueWorker<OwnerType, WorkType>*) lpParameter;
		pWorker->WorkerProc();
		return 0;
	}

	void WorkerProc()
	{
		while (m_bWorking)
		{
			::WaitForSingleObject(m_hSemaphore, INFINITE);

			if (! m_bWorking) break;

			::InterlockedExchange(&m_bWorkerBusy, TRUE);

			BOOL bHasDataToBeHandle = FALSE;
			WorkType item;
			::EnterCriticalSection(&m_section);
			{
				if (! m_queue.empty())
				{
					item = m_queue.front();
					bHasDataToBeHandle = TRUE;
					m_queue.pop_front();
				}
			}
			::LeaveCriticalSection(&m_section);
			
			if (bHasDataToBeHandle)
			{
				m_handler(item, m_pOwner);
			}

			::InterlockedExchange(&m_bWorkerBusy, FALSE);
		}

		debugLog(_T("QueueWorker[%s] end."), m_memo.c_str());
	}

private:
	typedef std::list<WorkType> WorkQueue;

private:
	CRITICAL_SECTION	m_section;
	OwnerType*			m_pOwner;
	HANDLE				m_hSemaphore;
	BOOL				m_bWorking;
	HANDLE				m_hWorkerThread;
	WorkQueue			m_queue;
	Handler				m_handler;
	tstring				m_memo;
	LONG				m_bWorkerBusy;
};
