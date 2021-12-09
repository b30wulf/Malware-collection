#pragma once
#include "MessageDefines.h"
#include "ArrayData.h"
#include "tstring.h"

typedef struct
{
	WCHAR clientid[255];
	CLIENT_INFO info;
}INIT_INFO,*PINIT_INFO;

#ifdef MASTER_MODULE_IMPORTS
#	define MASTERMODULE_API extern "C" __declspec(dllimport)
#else
#	define MASTERMODULE_API extern "C" __declspec(dllexport)
#endif

typedef BOOL (*fnInitModule)();
typedef BOOL (*fnAllocWindow)(HWND& hWnd,HWND hParent,INIT_INFO& info,LPVOID* lpParameter);
typedef void (*fnFreeWindow)(HWND hWnd);
typedef void (*fnDeinitModule)();
typedef LPCTSTR (*fnGetDisplayName)();

typedef void (*fnHandleMsg)(LPCTSTR clientid,UINT nMsg, LPVOID lpContext, LPVOID lpParameter);

typedef BOOL (*fnInsideInitModule)(LPCTSTR clientid,CLIENT_INFO& info,FnModuleNotifyProc fnCallback,LPVOID lpParameter);
typedef struct
{
	HWND hWnd;
	HWND hTemp;
	BOOL isInside;

	union
	{
		fnInitModule init;
		fnInsideInitModule inside_init;
	};

	fnHandleMsg handler;
	fnAllocWindow alloc;
	fnFreeWindow free;
	fnDeinitModule deinit;
	fnGetDisplayName getname;

	LPVOID lpParameter;
}MODULE_INFO,*PMODULE_INFO;
typedef std::map<int,MODULE_INFO> ModuleMap;

//»Øµ÷º¯Êý

MASTERMODULE_API BOOL InitModule(LPCTSTR clientid,CLIENT_INFO& info,FnModuleNotifyProc fnCallback,LPVOID lpParameter);

MASTERMODULE_API BOOL AllocWindow(HWND& hWnd,HWND hParent,INIT_INFO& info,LPVOID* lpParameter);

MASTERMODULE_API void FreeWindow(HWND hWnd);

MASTERMODULE_API void DeinitModule();

MASTERMODULE_API void HandleMsg(UINT nMsg, LPVOID lpContext, LPVOID lpParameter);