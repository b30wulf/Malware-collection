#pragma once
#include "CommData.h"

class Shell
{
public:
	Shell();
	~Shell();

	BOOL Init(LPCTSTR clientid);
	LPCTSTR GetCurrentPath() const;
	BOOL ExecuteCommand(LPCTSTR cmdline, tstring& reply);

private:
	typedef struct  
	{
		BOOL	bValid;
		UINT	drivertype;
		UINT64	totalBytes;
		UINT64	freeBytes;
		tstring	curPath;
	} PARTITION_INFO;

private:
	BOOL AskAndWaitForReply(const CommData& request, CommData& reply, int iWaitRound = 50, DWORD dwSleepIntervalMS = 200) const;
	BOOL RefershPartitions();
	void SetCurrentPath(LPCTSTR filepath);
	void MakeAbsolutePath(LPCTSTR filepath, tstring& absoPath);
	BOOL FileExistsInClient(LPCTSTR filepath, BOOL& bExists, BOOL& bDir);

	BOOL Execute_Disks(const TStringVector& cmdparts, tstring& reply);
	BOOL Execute_Dir(const TStringVector& cmdparts, tstring& reply);
	BOOL Execute_Cd(const TStringVector& cmdparts, tstring& reply);

private:
	tstring			m_clientid;
	PARTITION_INFO	m_partitions[26];
	tstring			m_currentPath;
};
