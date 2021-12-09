#include "stdafx.h"
#include "Redirector.h"

#define BUFFER_SIZE 1024

// This class is build from the following MSDN article.
// Q190351 HOWTO: Spawn Console Processes with Redirected Standard Handles.

/////////////////////////////////////////////////////////////////////////////
// API Function

BOOL WINAPI IsWinNT()
{
	// get windows version
	DWORD WindowsVersion = GetVersion();
	DWORD WindowsMajorVersion = (DWORD)(LOBYTE(LOWORD(WindowsVersion)));
	DWORD WindowsMinorVersion = (DWORD)(HIBYTE(LOWORD(WindowsVersion)));

	// Running on WIN9x ?
	if (WindowsVersion >= 0x80000000) return FALSE;

	// Running on NT
	return TRUE;
}

/////////////////////////////////////////////////////////////////////////////
// CRedirect class

Redirector::Redirector()
	: m_dwPID(0)
{
}

Redirector::~Redirector()
{
	TerminateChildProcess();
}

// Create standard handles, try to start child from command line.

BOOL Redirector::StartChildProcess(LPCTSTR lpszCmdLine, BOOL bShowChildWindow)
{
	if (m_bWorking) return TRUE;

	Handle hProcess = ::GetCurrentProcess();

	// Set up the security attributes struct.
	SECURITY_ATTRIBUTES sa = {0};
	sa.nLength= sizeof(SECURITY_ATTRIBUTES);
	sa.lpSecurityDescriptor = NULL;
	sa.bInheritHandle = TRUE;

	Handle hStdInWriteTmp, hStdOutReadTmp, hStdErrReadTmp;

	// Create the child stdin pipe.
	if (! ::CreatePipe(&m_hStdIn, &hStdInWriteTmp, &sa, 0)) return FALSE;

	// Create the child stdout pipe.
	if (! ::CreatePipe(&hStdOutReadTmp, &m_hStdOut, &sa, 0)) return FALSE;

	// Create the child stderr pipe.
	if (! ::CreatePipe(&hStdErrReadTmp, &m_hStdErr, &sa, 0)) return FALSE;

	// Create new stdin write, stdout and stderr read handles.
	// Set the properties to FALSE. Otherwise, the child inherits the
	// properties and, as a result, non-closeable handles to the pipes
	// are created.

	if (! ::DuplicateHandle(hProcess, hStdInWriteTmp,
		hProcess, &m_hStdInWrite, 0, FALSE, DUPLICATE_SAME_ACCESS)) return FALSE;

	if (! ::DuplicateHandle(hProcess, hStdOutReadTmp,
		hProcess, &m_hStdOutRead, 0, FALSE, DUPLICATE_SAME_ACCESS)) return FALSE;

	if (! ::DuplicateHandle(hProcess, hStdErrReadTmp,
		hProcess, &m_hStdErrRead, 0, FALSE, DUPLICATE_SAME_ACCESS)) return FALSE;
// 
// 	// Close inheritable copies of the handles you do not want to be
// 	// inherited.
// 
	hStdInWriteTmp.Close();
	hStdOutReadTmp.Close();
	hStdErrReadTmp.Close();

	// Start child process with redirected stdout, stdin & stderr
	BOOL bCreateOK = PrepAndLaunchRedirectedChild(lpszCmdLine,
		m_hStdOut, m_hStdIn, m_hStdErr, bShowChildWindow,
		&m_hChildProcess);

	if (! bCreateOK)
	{
		CHAR lpszBuffer[BUFFER_SIZE];
		sprintf_s(lpszBuffer, "Unable to start %s", t2a(lpszCmdLine));
		OnChildStdOutWrite(lpszBuffer);

		// close all handles and return FALSE
		CloseHandle(m_hStdIn);
		CloseHandle(m_hStdOut);
		CloseHandle(m_hStdErr);

		return FALSE;
	}

	DWORD dwThreadID;
	m_bRunThread = TRUE;

	// Create Exit event
	m_hExitEvent = ::CreateEvent(NULL, TRUE, FALSE, NULL);
	if ( m_hExitEvent == INVALID_HANDLE_VALUE ) return FALSE;

	// Launch the thread that read the child stdout.
	m_hStdOutThread = ::CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)staticStdOutThread,
		(LPVOID)this, 0, &dwThreadID);
	if (m_hStdOutThread == INVALID_HANDLE_VALUE) return FALSE;

	// Launch the thread that read the child stderr.
	m_hStdErrThread = ::CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)staticStdErrThread,
		(LPVOID)this, 0, &dwThreadID);
	if (m_hStdErrThread == INVALID_HANDLE_VALUE) return FALSE;

	// Launch the thread that monitoring the child process.
	m_hProcessThread = ::CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)staticProcessThread,
		(LPVOID)this, 0, &dwThreadID);
	if ( m_hProcessThread == INVALID_HANDLE_VALUE) return FALSE;

	// Virtual function to notify derived class that the child is started.
	OnChildStarted(t2a(lpszCmdLine));

	m_bWorking = TRUE;

	return TRUE;
}

// Check if the child process is running. 
// On NT/2000 the handle must have PROCESS_QUERY_INFORMATION access.

BOOL Redirector::IsChildRunning() const
{
	DWORD dwExitCode;
	if ( m_hChildProcess == INVALID_HANDLE_VALUE) return FALSE;
	::GetExitCodeProcess(m_hChildProcess, &dwExitCode);
	return (dwExitCode == STILL_ACTIVE) ? TRUE: FALSE;
}

void Redirector::TerminateChildProcess()
{
	if (! m_bWorking) return;

	// Tell the threads to exit and wait for process thread to die.
	m_bRunThread = FALSE;
	::SetEvent(m_hExitEvent);
	Sleep(500);

	// Check the process thread.
	if (m_hProcessThread != NULL)
	{
		TerminateProcess(m_hProcessThread,0);
		m_hProcessThread = NULL;
	}
	// Stop the stdout read thread.
	if (m_hStdOutThread != NULL)
	{
		if (! ::IsWinNT()) ::TerminateThread(m_hStdOutThread, 0);
		CloseHandle(m_hStdOutThread);
	}

	// Stop the stderr read thread.
	if (m_hStdErrThread != NULL)
	{
		if (!::IsWinNT()) ::TerminateThread(m_hStdErrThread, 0);
		CloseHandle(m_hStdErrThread);
	}
	Sleep(100);

	// Stop the child process if not already stopped.
	// It's not the best solution, but it is a solution.
	// On Win98 it may crash the system if the child process is the COMMAND.COM.
	// The best way is to terminate the COMMAND.COM process with an "exit" command.

	if (IsChildRunning())
	{
		::TerminateProcess(m_hChildProcess, 1);
		::WaitForSingleObject(m_hChildProcess, 1000);
		CloseHandle(m_hChildProcess);
	}


	// Close all child handles first.

	CloseHandle(m_hStdIn);
	CloseHandle(m_hStdOut);
	CloseHandle(m_hStdErr);
	Sleep(100);

	// Close all parent handles.
// 	CloseHandle(m_hStdInWrite);
// 	CloseHandle(m_hStdOutRead);
// 	CloseHandle(m_hStdErrRead);
	Sleep(100);

	// cleanup the exit event
	CloseHandle(m_hExitEvent);

	m_bWorking = FALSE;
}

// Launch the process that you want to redirect.

BOOL Redirector::PrepAndLaunchRedirectedChild(LPCTSTR lpszCmdLine,
	HANDLE hStdOut, HANDLE hStdIn, HANDLE hStdErr,
	BOOL bShowChildWindow,
	HANDLE* hChildProcess)
{
	HANDLE hProcess = ::GetCurrentProcess();

	PROCESS_INFORMATION pi = {0};

	// Set up the start up info struct.
	STARTUPINFO si = {0};
	si.cb = sizeof(STARTUPINFO);
	si.wShowWindow = SW_HIDE;
	si.dwFlags = STARTF_USESTDHANDLES | STARTF_USESHOWWINDOW;
	si.hStdOutput = hStdOut;
	si.hStdInput  = hStdIn;
	si.hStdError  = hStdErr;

	// Use this if you want to show the child.
	si.wShowWindow = bShowChildWindow ? SW_SHOW: SW_HIDE;
	// Note that dwFlags must include STARTF_USESHOWWINDOW if you want to
	// use the wShowWindow flags.

	// Create the NULL security token for the process
	LPVOID lpSD = NULL;
	LPSECURITY_ATTRIBUTES lpSA = NULL;

	// On NT/2000 the handle must have PROCESS_QUERY_INFORMATION access.
	// This is made using an empty security descriptor. It is not the same
	// as using a NULL pointer for the security attribute!

// 	if (IsWinNT())
// 	{
// 		lpSD = ::GlobalAlloc(GPTR, SECURITY_DESCRIPTOR_MIN_LENGTH);
// 		if (! ::InitializeSecurityDescriptor(lpSD, SECURITY_DESCRIPTOR_REVISION)) return FALSE;
// 		if (! ::SetSecurityDescriptorDacl(lpSD, -1, 0, 0)) return FALSE;
// 
// 		lpSA = (LPSECURITY_ATTRIBUTES)::GlobalAlloc(GPTR, sizeof(SECURITY_ATTRIBUTES));
// 		lpSA->nLength = sizeof(SECURITY_ATTRIBUTES);
// 		lpSA->lpSecurityDescriptor = lpSD;
// 		lpSA->bInheritHandle = TRUE;
// 	}

	// Try to spawn the process.
	TCHAR tempCmdline[MAX_PATH * 2] = {0};
	_tcscpy_s(tempCmdline, lpszCmdLine);
	BOOL bResult = ::CreateProcess(NULL, tempCmdline, lpSA, NULL, TRUE,
		NORMAL_PRIORITY_CLASS, NULL, NULL, &si, &pi);

	// Cleanup memory allocation
	if (lpSA != NULL) ::GlobalFree(lpSA);
	if (lpSD != NULL) ::GlobalFree(lpSD);

	// Return if an error occurs.
	if (!bResult) return FALSE;

	// Close any unnecessary handles.
	::CloseHandle(pi.hThread);

	*hChildProcess = pi.hProcess;
	m_dwPID = pi.dwProcessId;

	// Save global child process handle to cause threads to exit.
	return TRUE;
}

BOOL Redirector::m_bRunThread = TRUE;

// Thread to read the child stdout.

int Redirector::StdOutThread(HANDLE hStdOutRead)
{
	DWORD nBytesRead = 0;
	CHAR lpszBuffer[BUFFER_SIZE + 1] = {0};

	while (m_bRunThread)
	{
		if (! ::ReadFile(hStdOutRead, lpszBuffer, BUFFER_SIZE, &nBytesRead, NULL) 
			|| 0 == nBytesRead)
		{
			DWORD dwError = ::GetLastError();
			//if (dwError == ERROR_BROKEN_PIPE)// pipe done - normal exit path.
			break;
		}
		if (nBytesRead > 0)
		{
			// Virtual function to notify derived class that
			// characters are writted to stdout.
			OnChildStdOutWrite(lpszBuffer);
		}

		ZeroMemory(lpszBuffer, sizeof(lpszBuffer));
	}
	return 0;
}

// Thread to read the child stderr.

int Redirector::StdErrThread(HANDLE hStdErrRead)
{
	DWORD nBytesRead;
	CHAR lpszBuffer[BUFFER_SIZE + 1] = {0};

	while (m_bRunThread)
	{
		if (! ::ReadFile(hStdErrRead, lpszBuffer, BUFFER_SIZE, &nBytesRead, NULL) 
			|| nBytesRead == 0)
		{
			DWORD dwError = ::GetLastError();
			//if (dwError == ERROR_BROKEN_PIPE)// pipe done - normal exit path.
			break;
		}
		if (nBytesRead > 0)
		{
			// Virtual function to notify derived class that
			// characters are writted to stderr.
			OnChildStdErrWrite(lpszBuffer);
		}

		ZeroMemory(lpszBuffer, sizeof(lpszBuffer));
	}
	return 0;
}

// Thread to monitoring the child process.

int Redirector::ProcessThread()
{
	HANDLE hWaitHandle[2];
	hWaitHandle[0] = m_hExitEvent;
	hWaitHandle[1] = m_hChildProcess;

	while (m_bRunThread)
	{
		switch (::WaitForMultipleObjects(2, hWaitHandle, FALSE, 1))
		{
		case WAIT_OBJECT_0 + 0:	// exit on event
			//ASSERT(m_bRunThread == FALSE);
			break;

		case WAIT_OBJECT_0 + 1:	// child process exit
			//ASSERT(m_bRunThread == TRUE);
			m_bRunThread = FALSE;
			break;
		}
	}
	// Virtual function to notify derived class that
	// child process is terminated.
	// Application must call TerminateChildProcess()
	// but not direcly from this thread!
	OnChildTerminate();
	return 0;
}

// Function that write to the child stdin.

BOOL Redirector::WriteChildStdIn(LPCSTR lpszInput)
{
	DWORD nBytesWrote;
	DWORD Length = strlen(lpszInput);
	if (m_hStdInWrite != NULL && Length > 0)
	{
		if (::WriteFile(m_hStdInWrite, lpszInput, Length, &nBytesWrote, NULL) && nBytesWrote == Length)
		{
			return TRUE;
			// 			if (::GetLastError() == ERROR_NO_DATA)
			// 				;				// Pipe was closed (do nothing).
			// 			else
			// 				ASSERT(FALSE);	// Something bad happened.
		}
	}

	return FALSE;
}

DWORD Redirector::GetChildProcessID() const
{
	return m_dwPID;
}
