#pragma once
#include <Windows.h>
#include <cassert>

class SRWLock
{
private:
	typedef void (WINAPI *LPFNInitializeSRWLock)(PSRWLOCK SRWLock);
	typedef void (WINAPI *LPFNAcquireSRWLockExclusive)(PSRWLOCK SRWLock);
	typedef void (WINAPI *LPFNAcquireSRWLockShared)(PSRWLOCK SRWLock);
	typedef void (WINAPI *LPFNReleaseSRWLockExclusive)(PSRWLOCK SRWLock);
	typedef void (WINAPI *LPFNReleaseSRWLockShared)(PSRWLOCK SRWLock);
	typedef struct _PAIR_FUNCTION_NAME
	{
		FARPROC *pFunction;
		LPSTR lpaszName;
	} PAIR_FUNCTION_NAME;

public:
	SRWLock() : m_hModule(NULL), m_bInitLibrary(FALSE),
		m_lpfnInitializeSRWLock(NULL),
		m_lpfnAcquireSRWLockExclusive(NULL),
		m_lpfnAcquireSRWLockShared(NULL),
		m_lpfnReleaseSRWLockExclusive(NULL),
		m_lpfnReleaseSRWLockShared(NULL)
	{
		m_bInitLibrary = Init();
	}

	virtual ~SRWLock()
	{
		if (m_hModule)
		{
			m_hModule = NULL;
		}
		m_bInitLibrary = FALSE;
	}

	void InitializeSRWLock(PSRWLOCK SRWLock)
	{
		if (m_bInitLibrary)
		{
			m_lpfnInitializeSRWLock(SRWLock);
		}
	}

	void AcquireSRWLockExclusive(PSRWLOCK SRWLock)
	{
		if (m_bInitLibrary)
		{
			m_lpfnAcquireSRWLockExclusive(SRWLock);
		}
	}

	void AcquireSRWLockShared(PSRWLOCK SRWLock)
	{
		if (m_bInitLibrary)
		{
			m_lpfnAcquireSRWLockShared(SRWLock);
		}
	}

	void ReleaseSRWLockExclusive(PSRWLOCK SRWLock)
	{
		if (m_bInitLibrary)
		{
			m_lpfnReleaseSRWLockExclusive(SRWLock);
		}
	}

	void ReleaseSRWLockShared(PSRWLOCK SRWLock)
	{
		if (m_bInitLibrary)
		{
			m_lpfnReleaseSRWLockShared(SRWLock);
		}
	}

private:
	BOOL Init()
	{
		m_hModule = ::GetModuleHandle(_T("Kernel32.dll"));
		if (m_hModule == NULL)
			return FALSE;
		PAIR_FUNCTION_NAME arFunctionNamePair[] = {
			{(FARPROC *)&m_lpfnInitializeSRWLock, "InitializeSRWLock"},
			{(FARPROC *)&m_lpfnAcquireSRWLockExclusive, "AcquireSRWLockExclusive"},
			{(FARPROC *)&m_lpfnAcquireSRWLockShared, "AcquireSRWLockShared"},
			{(FARPROC *)&m_lpfnReleaseSRWLockExclusive, "ReleaseSRWLockExclusive"},
			{(FARPROC *)&m_lpfnReleaseSRWLockShared, "ReleaseSRWLockShared"}
		};
		int nFunctionCount = sizeof(arFunctionNamePair) / sizeof(arFunctionNamePair[0]);
		for (int i = 0; i < nFunctionCount; i++)
		{
			*arFunctionNamePair[i].pFunction = ::GetProcAddress(m_hModule, arFunctionNamePair[i].lpaszName);
			if (*arFunctionNamePair[i].pFunction == NULL)
			{
				m_hModule = NULL;
				return FALSE;
			}
		}
		return TRUE;
	}

private:
	HMODULE							m_hModule;
	BOOL							m_bInitLibrary;
	LPFNInitializeSRWLock			m_lpfnInitializeSRWLock;
	LPFNAcquireSRWLockExclusive		m_lpfnAcquireSRWLockExclusive;
	LPFNAcquireSRWLockShared		m_lpfnAcquireSRWLockShared;
	LPFNReleaseSRWLockExclusive		m_lpfnReleaseSRWLockExclusive;
	LPFNReleaseSRWLockShared		m_lpfnReleaseSRWLockShared;
};

class CSharedMutexLock : public SRWLock {
private:
	typedef enum {
		LOCK_NONE = 0,
		LOCK_SHARED,
		LOCK_EXCLUSIVE
	} LOCKTYPE;

public:
	CSharedMutexLock() : m_nSharedCount(0), m_nExclusiveCount(0), m_eLockType(LOCK_NONE), m_bIsVista(FALSE), m_mtxLock(NULL), m_evtShared(NULL), m_evtExclusive(NULL)
	{
		m_bIsVista = IsVista();
		if (m_bIsVista) {
			this->InitializeSRWLock(&m_srwLock);
		}
		else {
			m_mtxLock = ::CreateMutex(NULL, FALSE, NULL);              // 内部资源访问互斥
			m_evtShared = ::CreateEvent(NULL, TRUE, FALSE, NULL);      // 共享读并发事件
			m_evtExclusive = ::CreateEvent(NULL, FALSE, FALSE, NULL);  // 独占写互斥事件
			assert(m_mtxLock == INVALID_HANDLE_VALUE || m_evtShared == INVALID_HANDLE_VALUE || m_evtExclusive == INVALID_HANDLE_VALUE);
		}
	}

	virtual ~CSharedMutexLock()
	{
		if (m_mtxLock) {
			::CloseHandle(m_mtxLock);
			m_mtxLock = NULL;
		}
		if (m_evtShared) {
			::CloseHandle(m_evtShared);
			m_evtShared = NULL;
		}
		if (m_evtExclusive) {
			::CloseHandle(m_evtExclusive);
			m_evtExclusive = NULL;
		}
	}
	
	BOOL AcquireShared(DWORD waitTime = INFINITE)
	{
		if (m_bIsVista)
		{
			this->AcquireSRWLockShared(&m_srwLock);
		}
		else
		{
			::WaitForSingleObject(m_mtxLock, INFINITE);
			++m_nSharedCount;
			if (m_eLockType == LOCK_EXCLUSIVE)
			{
				DWORD retCode = ::SignalObjectAndWait(m_mtxLock, m_evtShared, waitTime, FALSE);
				if (retCode == WAIT_OBJECT_0) {
					return TRUE;
				} else {
					if (retCode == WAIT_TIMEOUT)
						::SetLastError(WAIT_TIMEOUT);
					return FALSE;
				}
			}
			m_eLockType = LOCK_SHARED;
			::ReleaseMutex(m_mtxLock);
		}
		return TRUE;
	}
	
	void ReleaseShared()
	{
		if (m_bIsVista)
		{
			this->ReleaseSRWLockShared(&m_srwLock);
		}
		else
		{
			assert(m_eLockType == LOCK_SHARED);
			::WaitForSingleObject(m_mtxLock, INFINITE);
			--m_nSharedCount;
			if (m_nSharedCount == 0) {
				if (m_nExclusiveCount > 0)
				{
					m_eLockType = LOCK_EXCLUSIVE;
					::SetEvent(m_evtExclusive);
				} else {
					m_eLockType = LOCK_NONE;
				}
			}
			::ReleaseMutex(m_mtxLock);
		}
	}

	BOOL AcquireExclusive(DWORD waitTime = INFINITE)
	{
		if (m_bIsVista)
		{
			this->AcquireSRWLockExclusive(&m_srwLock);
		}
		else
		{
			::WaitForSingleObject(m_mtxLock, INFINITE);
			++m_nExclusiveCount;
			if (m_eLockType != LOCK_NONE)
			{
				DWORD retCode = ::SignalObjectAndWait(m_mtxLock, m_evtExclusive, waitTime, FALSE);
				if (retCode == WAIT_OBJECT_0) {
					return TRUE;
				} else {
					if (retCode == WAIT_TIMEOUT)
						::SetLastError(WAIT_TIMEOUT);
					return FALSE;
				}
			}
			m_eLockType = LOCK_EXCLUSIVE;
			::ReleaseMutex(m_mtxLock);
		}
		return TRUE;
	}
	
	void ReleaseExclusive()
	{
		if (m_bIsVista)
		{
			this->ReleaseSRWLockExclusive(&m_srwLock);
		}
		else
		{
			assert(m_eLockType == LOCK_EXCLUSIVE);
			::WaitForSingleObject(m_mtxLock, INFINITE);
			--m_nExclusiveCount;
			if (m_nExclusiveCount > 0) {
				::SetEvent(m_evtExclusive);
			} else if (m_nSharedCount > 0)
			{
				m_eLockType = LOCK_SHARED;
				::PulseEvent(m_evtShared);
			} else {
				m_eLockType = LOCK_NONE;
			}
			::ReleaseMutex(m_mtxLock);
		}
	}

	BOOL IsVista()
	{
		OSVERSIONINFO ovi = { sizeof(OSVERSIONINFO) };
		BOOL bRet = ::GetVersionEx(&ovi);
		return ((bRet != FALSE) && (ovi.dwMajorVersion >= 6));
	}

private:
	volatile LOCKTYPE m_eLockType;
	volatile int m_nSharedCount;
	volatile int m_nExclusiveCount;
	HANDLE m_mtxLock;
	HANDLE m_evtShared;
	HANDLE m_evtExclusive;
	SRWLOCK m_srwLock;
	BOOL m_bIsVista;
};