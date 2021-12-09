#pragma once
#include "shared-mutex_lock.h"

template<typename T> class CCircleQuene
{
public:
	CCircleQuene(DWORD dwMaxSize = 10);
	virtual ~CCircleQuene();
	BOOL Push(const T&);
	BOOL Pop(T&);

	void Reset() {
		m_srwlock.AcquireExclusive();
		m_pHead = m_pTail = 0;
		m_dwCount = 0;
		memset(m_pElements, 0, sizeof(T) * QMAX_SIZE);
		m_srwlock.ReleaseExclusive();
	}

	DWORD GetMaxSize() {
		return QMAX_SIZE - 1;
	}
	
	DWORD GetCount() {
		DWORD dwRet = 0;
		m_srwlock.AcquireShared();
		dwRet = m_dwCount;
		m_srwlock.ReleaseShared();
		return dwRet;
	}

private:
	BOOL IsFull() {
		return (m_pTail + 1) % QMAX_SIZE == m_pHead;
	}

	BOOL IsEmpty() {
		return m_pHead == m_pTail;
	}

protected:
	DWORD m_pHead;
	DWORD m_pTail;
	DWORD m_dwCount;
	DWORD QMAX_SIZE;
	T* m_pElements;
	CSharedMutexLock m_srwlock;
};

template <typename T> CCircleQuene<T>::CCircleQuene(DWORD dwMaxSize)
{
	QMAX_SIZE = dwMaxSize < 10 ? 10 : dwMaxSize;
	m_pHead = m_pTail = 0;
	m_dwCount = 0;
	m_pElements = new T[QMAX_SIZE];
	assert(m_pElements != NULL);
}

template <typename T> CCircleQuene<T>::~CCircleQuene()
{
	if (m_pElements)
	{
		delete[] m_pElements;
		m_pElements = NULL;
	}
}

template <typename T> BOOL CCircleQuene<T>::Push(const T& _val)
{
	BOOL bRet = FALSE;
	m_srwlock.AcquireExclusive();
	if (!IsFull())
	{
		m_pElements[m_pTail] = _val;
		m_pTail = (m_pTail + 1) % QMAX_SIZE;
		m_dwCount++;
		bRet = TRUE;
	}
	m_srwlock.ReleaseExclusive();
	return bRet;
}

template <typename T> BOOL CCircleQuene<T>::Pop(T& _val)
{
	BOOL bRet = FALSE;
	m_srwlock.AcquireExclusive();
	if (!IsEmpty()) {
		_val = m_pElements[m_pHead];
		m_pHead = (m_pHead + 1) % QMAX_SIZE;
		m_dwCount--;
		bRet = TRUE;
	}
	m_srwlock.ReleaseExclusive();
	return bRet;
}