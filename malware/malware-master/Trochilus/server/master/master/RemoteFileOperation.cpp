#include "stdafx.h"
#include <algorithm>
#include "CommDataUtils.h"
#include "RemoteFileOperation.h"

RemoteFileOperation::RemoteFileOperation()
	: m_dwCacheTimeouts(30)
{

}

RemoteFileOperation::~RemoteFileOperation()
{

}

BOOL RemoteFileOperation::ListFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList ) const
{
	CommData sendData;
	sendData.SetMsgID(MSGID_LIST_FILES);
	sendData.SetData(_T("findstr"), findstr);

	MSGSERIALID serialID = CommDataUtils::SendMessageToClient(clientid, sendData, TRUE);
	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
		return FALSE;
	}

	CommData commData;
	if (! CommDataUtils::WaitForReply(clientid, serialID, commData))
	{
		return FALSE;
	}
	
	//DECLARE_STR_PARAM_API(result);
	tstring result;
	if (! commData.GetStrData(_T("result"), result))
	{
		errorLog(_T("cannot find strdata result"));
		return FALSE;
	}
	TStringVector partitionList;
	splitByChar(result.c_str(), partitionList, ':');
	TStringVector::iterator partitionIter = partitionList.begin();
	for (; partitionIter != partitionList.end(); partitionIter++)
	{
		const tstring& line = *partitionIter;
		TStringVector dataList;
		splitByChar(line.c_str(), dataList, '|');
		if (dataList.size() != 4) continue;
		DWORD dwAttributes;
		if (0 == _stscanf_s(dataList[1].c_str(), _T("%u"), &dwAttributes)) continue;
		UINT64 filesize = 0;
		ULARGE_INTEGER lastWritetime = {0};
		if (0 == _stscanf_s(dataList[2].c_str(), _T("%I64u"), &filesize)) continue;
		if (0 == _stscanf_s(dataList[3].c_str(), _T("%I64u"), &lastWritetime.QuadPart)) continue;

		RFILE_INFO info = {0};
		_tcscpy_s(info.filename, dataList[0].c_str());
		info.dwAttributes = dwAttributes;
		info.filesize = filesize;
		info.lastWriteTime.dwHighDateTime = lastWritetime.HighPart;
		info.lastWriteTime.dwLowDateTime = lastWritetime.LowPart;

		fileInfoList.push_back(info);
	}

	if (fileInfoList.size() > 0)
	{
		sort(fileInfoList.begin(), fileInfoList.end());
	}

	return TRUE;
}

BOOL RemoteFileOperation::ListDisks( LPCTSTR clientid, DiskInfoList& diskInfoList ) const
{
	CommData sendData;
	sendData.SetMsgID(MSGID_DISKS);

	MSGSERIALID serialID = CommDataUtils::SendMessageToClient(clientid, sendData, TRUE);
	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
		return FALSE;
	}

	CommData commData;
	if (! CommDataUtils::WaitForReply(clientid, serialID, commData))
	{
		return FALSE;
	}
	
	tstring result;
	if (! commData.GetStrData(_T("result"), result))
	{
		errorLog(_T("cannot find strdata result"));
		return FALSE;
	}
	TStringVector partitionList;
	splitByChar(result.c_str(), partitionList, ':');
	TStringVector::iterator partitionIter = partitionList.begin();
	for (; partitionIter != partitionList.end(); partitionIter++)
	{
		const tstring& line = *partitionIter;
		TStringVector dataList;
		splitByChar(line.c_str(), dataList, '|');
		if (dataList.size() != 4) continue;
		tstring& partition = dataList[0];
		if (partition.size() == 0) continue;
		UINT driverType;
		if (0 == _stscanf_s(dataList[1].c_str(), _T("%u"), &driverType)) continue;
		UINT64 totalBytes = 0;
		UINT64 freeBytes = 0;
		if (0 == _stscanf_s(dataList[2].c_str(), _T("%I64u"), &totalBytes)) continue;
		if (0 == _stscanf_s(dataList[3].c_str(), _T("%I64u"), &freeBytes)) continue;

		RDISK_INFO info;
		info.partition = ws2s(partition)[0];
		info.freeBytes = freeBytes;
		info.totalBytes = totalBytes;
		info.driverType = driverType;

		diskInfoList.push_back(info);
	}
	
	return TRUE;
}

CString RemoteFileOperation::MakeCacheKey( LPCTSTR clientid, LPCTSTR findstr ) const
{
	CString ret;
	ret.Format(_T("%s___%s"), clientid, findstr);
	ret.MakeLower();
	ret.Trim();

	return ret;
}

void RemoteFileOperation::SetCacheTimeoutS( DWORD dwTimeoutS )
{
	m_dwCacheTimeouts = dwTimeoutS;
}

BOOL RemoteFileOperation::ListClientFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList, BOOL bForceList )
{
	__time64_t now;
	_time64(&now);

	CString key = MakeCacheKey(clientid, findstr);

	BOOL bFound = FALSE;
	m_mapSection.Enter();
	{
		FileCacheMap::iterator cacheIter = m_map.find(key);
		if (cacheIter != m_map.end())
		{
			if (bForceList || max(cacheIter->second.recordTime, now) - min(cacheIter->second.recordTime, now) > m_dwCacheTimeouts)
			{
				m_map.erase(cacheIter);
			}
			else
			{
				fileInfoList = cacheIter->second.fileInfoList;
				bFound = TRUE;
			}
		}
	}
	m_mapSection.Leave();

	if (bFound) return TRUE;

	if (! ListFiles(clientid, findstr, fileInfoList))
	{
		return FALSE;
	}

	m_mapSection.Enter();
	{
		FILE_INFO_CACHE cache;
		_time64(&cache.recordTime);
		cache.fileInfoList = fileInfoList;

		m_map[key] = cache;
	}
	m_mapSection.Leave();

	return TRUE;
}

BOOL RemoteFileOperation::ListClientDisks( LPCTSTR clientid, DiskInfoList& diskInfoList )
{
	return ListDisks(clientid, diskInfoList);
}
