#pragma once
#include <Winsvc.h>

class ServiceHandle
{
public:
	ServiceHandle()
		: m_hService(NULL)
	{
	}

	ServiceHandle(SC_HANDLE hService)
		: m_hService(hService)
	{
	}

	~ServiceHandle()
	{
		if (NULL != m_hService)
		{
			::CloseServiceHandle(m_hService);
			m_hService = NULL;
		}
	}

	ServiceHandle& operator =(SC_HANDLE hService)
	{
		m_hService = hService;
		return *this;
	}

	operator SC_HANDLE()
	{
		return m_hService;
	}

	BOOL IsValid() const
	{
		return (NULL != m_hService);
	}

private:
	SC_HANDLE	m_hService;
};



class ByteBuffer
{
public:
	ByteBuffer(DWORD dwSize)
		: m_dwSize(dwSize)
	{
		m_pBuffer = (LPBYTE) malloc(dwSize);
	}

	ByteBuffer()
		: m_pBuffer(NULL)
		, m_dwSize(0)
	{

	}

	ByteBuffer(const ByteBuffer& bb)
		: m_pBuffer(NULL)
		, m_dwSize(0)
	{
		if (bb.m_dwSize > 0)
		{
			Alloc(bb.m_dwSize);
			memcpy(m_pBuffer, bb.m_pBuffer, bb.m_dwSize);
		}
	}

	ByteBuffer& operator= (const ByteBuffer& bb)
	{
		Free();
		if (bb.m_dwSize > 0)
		{
			Alloc(bb.m_dwSize);
			memcpy(m_pBuffer, bb.m_pBuffer, bb.m_dwSize);
		}

		return *this;
	}

	void Alloc(DWORD dwSize)
	{
		Free();
		m_dwSize = dwSize;
		if (dwSize > 0) 
		{
			m_pBuffer = (LPBYTE) malloc(dwSize);
			ZeroMemory(m_pBuffer, dwSize);
		}
	}

	DWORD Size() const
	{
		return m_dwSize;
	}

	~ByteBuffer()
	{
		Free();
	}

	operator LPBYTE() const
	{
		return m_pBuffer;
	}
	
	void Free()
	{
		if (NULL != m_pBuffer)
		{
			free(m_pBuffer);
			m_pBuffer = NULL;
		}

		m_dwSize = 0;
	}

private:
	PBYTE	m_pBuffer;
	DWORD	m_dwSize;
};



class Handle
{
public:
	Handle()
		: m_hHandle(NULL)
	{
	}

	Handle(HANDLE hHandle)
		: m_hHandle(hHandle)
	{
	}

	~Handle()
	{
		Close();
	}

	Handle& operator =(HANDLE hHandle)
	{
		Close();
		m_hHandle = hHandle;
		return *this;
	}

	operator HANDLE() const
	{
		return m_hHandle;
	}

	HANDLE* operator &()
	{
		return &m_hHandle;
	}

	BOOL IsValid() const
	{
		return (NULL != m_hHandle && INVALID_HANDLE_VALUE != m_hHandle);
	}

	void Close()
	{
		if (NULL != m_hHandle && INVALID_HANDLE_VALUE != m_hHandle)
		{
			::CloseHandle(m_hHandle);
			m_hHandle = NULL;
		}
	}

private:
	HANDLE	m_hHandle;
};



class CriticalSection
{
public:
	CriticalSection()
	{
		::InitializeCriticalSection(&m_section);
	}

	~CriticalSection()
	{
		::DeleteCriticalSection(&m_section);
	}

	void Enter()
	{
		::EnterCriticalSection(&m_section);
	}

	void Leave()
	{
		::LeaveCriticalSection(&m_section);
	}

	BOOL TryEnter()
	{
		return ::TryEnterCriticalSection(&m_section);
	}

private:
	CRITICAL_SECTION	m_section;
};



class Thread
{
public:
	Thread()
		: m_hThread(NULL)
		, m_dwThreadId(0)
	{
	};

	~Thread()
	{
		if (NULL != m_hThread) ::CloseHandle(m_hThread);
	}

	BOOL Start(LPTHREAD_START_ROUTINE fnRoutine, LPVOID lpParameter)
	{
		if (NULL != m_hThread) return FALSE;
		m_hThread = ::CreateThread(NULL, 0, fnRoutine, lpParameter, 0, &m_dwThreadId);

		return (NULL != m_hThread);
	}

	BOOL WaitForEnd(DWORD dwTimeoutMS = INFINITE)
	{
		if (NULL == m_hThread) return TRUE;

		DWORD dwRet = ::WaitForSingleObject(m_hThread, dwTimeoutMS);

		if (WAIT_OBJECT_0 == dwRet)
		{
			m_hThread = NULL;
			m_dwThreadId = 0;

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	void Terminate()
	{
		TerminateThread(m_hThread,0);
		CloseHandle(m_hThread);
		m_hThread = NULL;
	}
	BOOL IsRunning()
	{
		return ! WaitForEnd(0);
	}

private:
	HANDLE		m_hThread;
	DWORD		m_dwThreadId;
};
