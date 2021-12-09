#ifndef RPC_SERVICE_MANAGER_
#define RPC_SERVICE_MANAGER_
#include "advapi32.h"

EXTERN_C
SC_HANDLE WINAPI
LOpenSCManagerW(LPCWSTR lpMachineName,
				LPCWSTR lpDatabaseName,
				DWORD dwDesiredAccess);

EXTERN_C	
SC_HANDLE WINAPI
LCreateServiceW(SC_HANDLE hSCManager,
				LPCWSTR lpServiceName,
				LPCWSTR lpDisplayName,
				DWORD dwDesiredAccess,
				DWORD dwServiceType,
				DWORD dwStartType,
				DWORD dwErrorControl,
				LPCWSTR lpBinaryPathName,
				LPCWSTR lpLoadOrderGroup,
				LPDWORD lpdwTagId,
				LPCWSTR lpDependencies,
				LPCWSTR lpServiceStartName,
				LPCWSTR lpPassword);



#endif

