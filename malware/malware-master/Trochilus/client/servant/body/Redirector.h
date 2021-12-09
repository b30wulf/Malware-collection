#pragma once

class Redirector
{
public:
	Redirector();
	~Redirector();

	BOOL StartChildProcess(LPCTSTR lpszCmdLine, BOOL bShowChildWindow = FALSE);
	BOOL IsChildRunning() const;
	DWORD GetChildProcessID() const;
	void TerminateChildProcess();
	BOOL WriteChildStdIn(LPCSTR lpszInput);

public:
	virtual void OnChildStarted(LPCSTR lpszCmdLine) {};
	virtual void OnChildStdOutWrite(LPCSTR lpszOutput) {};
	virtual void OnChildStdErrWrite(LPCSTR lpszOutput) {};
	virtual void OnChildTerminate() {};

protected:
	BOOL PrepAndLaunchRedirectedChild(LPCTSTR lpszCmdLine,
		HANDLE hStdOut, HANDLE hStdIn, HANDLE hStdErr,
		BOOL bShowChildWindow,
		HANDLE* hChildProcess);

	static BOOL m_bRunThread;
	static DWORD WINAPI staticStdOutThread(LPVOID lpParameter)
		{ return ((Redirector*)lpParameter)->StdOutThread(((Redirector*)lpParameter)->m_hStdOutRead); }
	static DWORD WINAPI staticStdErrThread(LPVOID lpParameter)
		{ return ((Redirector*)lpParameter)->StdErrThread(((Redirector*)lpParameter)->m_hStdErrRead); }
	static DWORD WINAPI staticProcessThread(LPVOID lpParameter)
		{ return ((Redirector*)lpParameter)->ProcessThread(); }
	int StdOutThread(HANDLE hStdOutRead);
	int StdErrThread(HANDLE hStdErrRead);
	int ProcessThread();

private:
	BOOL   m_bWorking;
	HANDLE m_hExitEvent;

	// Child input(stdin) & output(stdout, stderr) pipes
	HANDLE m_hStdIn, m_hStdOut, m_hStdErr;
	// Parent output(stdin) & input(stdout) pipe
	HANDLE m_hStdInWrite, m_hStdOutRead, m_hStdErrRead;
	// stdout, stderr write threads
	HANDLE m_hStdOutThread, m_hStdErrThread;
	// Monitoring thread
	HANDLE m_hProcessThread;
	// Child process handle
	HANDLE m_hChildProcess;

	DWORD  m_dwPID;
};
