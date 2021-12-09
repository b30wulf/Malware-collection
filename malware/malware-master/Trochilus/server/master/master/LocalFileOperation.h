#pragma once
#include "IRCFileOperation.h"
#include "json/json.h"

class LocalFileOperation : public IRCFileOperation
{
public:
	LocalFileOperation();
	~LocalFileOperation();

	virtual BOOL ListClientDisks( LPCTSTR clientid, DiskInfoList& diskInfoList );
	virtual BOOL ListClientFiles( LPCTSTR clientid, LPCTSTR findstr, FileInfoList& fileInfoList, BOOL bForceList = FALSE );
};

typedef struct  
{
	tstring clientid;
	IRCFileOperation* pFO;
	FnModuleNotifyProc fnProc;
	LPVOID lpParameter1;
	tstring notify;
} LIST_DISK_PARAMETER;


typedef struct  
{
	tstring clientid;
	tstring findstr;
	IRCFileOperation* pFo;
	FnModuleNotifyProc fnProc;
	tstring notify;
	LPVOID lpParameter1;
	BOOL	bForce;
} LIST_FILE_PARAMETER;