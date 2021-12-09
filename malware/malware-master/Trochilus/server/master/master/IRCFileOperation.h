#pragma once
#include "FileInfoStructs.h"

class IRCFileOperation
{
public:
	virtual BOOL ListClientFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList, BOOL bForceList = FALSE ) = 0;
	virtual BOOL ListClientDisks( LPCTSTR clientid, DiskInfoList& diskInfoList ) = 0;
};