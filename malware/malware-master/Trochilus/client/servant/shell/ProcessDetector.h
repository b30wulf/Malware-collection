#pragma once
#include "tstring.h"
#include "thread/RepeatTask.h"

class ProcessDetector
{
public:
	ProcessDetector();
	~ProcessDetector();

	BOOL Start();
	void Stop();

private:
	BOOL CheckProcessList( const TStringSet& nameset, tstring& matchedName ) const;
	static DWORD WINAPI CheckTask( LPVOID lpParameter );

private:
	TStringSet	m_processNameSet;
	RepeatTask	m_checkTask;
};