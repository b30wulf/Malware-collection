#pragma once
#include "env/EnvData.h"
#include "eventwaiter/EventWaiterData.h"

typedef struct 
{
	MyString	codePosition;
	INT64		beginTimeMS;
} COUNTER_INFO, *PCOUNTER_INFO;

#define TASKID LPVOID
#define INVALID_TASKID NULL

typedef enum
{
	DirName_UserStartMenuProgDir = 0,
	DirName_CommonStartMenuProgDir,
	DirName_UserDesktopDir,
	DirName_CommonDesktopDir,
	DirName_UserStartMenuRootDir,
	DirName_CommonStartMenuRootDir,
	DirName_UserStartRunDir,
	DirName_CommonStartRunDir,
	DirName_UserFavoriteDir,
	DirName_CommonFavoriteDir,
	DirName_AppDataDir,
	DirName_QuickLanchDir,
	DirName_WindowsDir,
	DirName_Max
} DIR_NAME;
