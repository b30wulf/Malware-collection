#pragma once
#include <vector>
#include <map>
#include <CommDataUtils.h>
#include "IRCFileOperation.h"

class RemoteFileOperation : public IRCFileOperation
{
public:
	RemoteFileOperation();
	~RemoteFileOperation();

	void SetCacheTimeoutS(DWORD dwTimeoutS);
	virtual BOOL ListClientFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList, BOOL bForceList = FALSE );
	virtual BOOL ListClientDisks( LPCTSTR clientid, DiskInfoList& diskInfoList );

private:
	//远端文件目录信息缓存
	typedef struct  
	{
		__time64_t		recordTime;
		FileInfoList	fileInfoList;
	} FILE_INFO_CACHE;
	typedef std::map<CString, FILE_INFO_CACHE> FileCacheMap;

private:
	BOOL ListFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList ) const;
	BOOL ListDisks( LPCTSTR clientid, DiskInfoList& diskInfoList ) const;
	CString MakeCacheKey(LPCTSTR clientid, LPCTSTR findstr) const;

private:
	DWORD			m_dwCacheTimeouts;
	CriticalSection	m_mapSection;
	FileCacheMap	m_map;
};
