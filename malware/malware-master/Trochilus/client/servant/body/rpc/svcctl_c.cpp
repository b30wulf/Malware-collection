

/* this ALWAYS GENERATED file contains the RPC client stubs */


 /* File created by MIDL compiler version 7.00.0500 */
/* at Sat Mar 22 15:49:01 2014
 */
/* Compiler settings for .\svcctl.idl:
    Oicf, W1, Zp8, env=Win32 (32b run)
    protocol : dce , ms_ext, c_ext, robust
    error checks: allocation ref bounds_check enum stub_data 
    VC __declspec() decoration level: 
         __declspec(uuid()), __declspec(selectany), __declspec(novtable)
         DECLSPEC_UUID(), MIDL_INTERFACE()
*/
//@@MIDL_FILE_HEADING(  )


#pragma warning( disable: 4049 )  /* more than 64k source lines */
#pragma warning(push)

#pragma warning( disable: 4211 )  /* redefine extern to static */
#pragma warning( disable: 4232 )  /* dllimport identity*/
#pragma warning( disable: 4024 )  /* array to pointer mapping*/
#pragma warning( disable: 4100 ) /* unreferenced arguments in x86 call */

#pragma optimize("", off ) 

#include "stdafx.h"

#include <string.h>

#include "svcctl_h.h"

#define TYPE_FORMAT_STRING_SIZE   1619                              
#define PROC_FORMAT_STRING_SIZE   3621                              
#define EXPR_FORMAT_STRING_SIZE   1                                 
#define TRANSMIT_AS_TABLE_SIZE    0            
#define WIRE_MARSHAL_TABLE_SIZE   0            

#pragma warning(push)

typedef struct _svcctl_MIDL_TYPE_FORMAT_STRING
    {
    short          Pad;
    unsigned char  Format[ TYPE_FORMAT_STRING_SIZE ];
    } svcctl_MIDL_TYPE_FORMAT_STRING;

typedef struct _svcctl_MIDL_PROC_FORMAT_STRING
    {
    short          Pad;
    unsigned char  Format[ PROC_FORMAT_STRING_SIZE ];
    } svcctl_MIDL_PROC_FORMAT_STRING;

typedef struct _svcctl_MIDL_EXPR_FORMAT_STRING
    {
    long          Pad;
    unsigned char  Format[ EXPR_FORMAT_STRING_SIZE ];
    } svcctl_MIDL_EXPR_FORMAT_STRING;


static RPC_SYNTAX_IDENTIFIER  _RpcTransferSyntax = 
{{0x8A885D04,0x1CEB,0x11C9,{0x9F,0xE8,0x08,0x00,0x2B,0x10,0x48,0x60}},{2,0}};


extern const svcctl_MIDL_TYPE_FORMAT_STRING svcctl__MIDL_TypeFormatString;
extern const svcctl_MIDL_PROC_FORMAT_STRING svcctl__MIDL_ProcFormatString;
extern const svcctl_MIDL_EXPR_FORMAT_STRING svcctl__MIDL_ExprFormatString;

#define GENERIC_BINDING_TABLE_SIZE   3            


/* Standard interface: __MIDL_itf_svcctl_0000_0000, ver. 0.0,
   GUID={0x00000000,0x0000,0x0000,{0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00}} */


/* Standard interface: svcctl, ver. 2.0,
   GUID={0x367abb81,0x9844,0x35f1,{0xad,0x32,0x98,0xf0,0x38,0x00,0x10,0x03}} */


static RPC_PROTSEQ_ENDPOINT __RpcProtseqEndpoint[] = 
    {
    {(unsigned char *) "ncacn_np", (unsigned char *) "\\pipe\\ntsvcs"}
    };


static const RPC_CLIENT_INTERFACE svcctl___RpcClientInterface =
    {
    sizeof(RPC_CLIENT_INTERFACE),
    {{0x367abb81,0x9844,0x35f1,{0xad,0x32,0x98,0xf0,0x38,0x00,0x10,0x03}},{2,0}},
    {{0x8A885D04,0x1CEB,0x11C9,{0x9F,0xE8,0x08,0x00,0x2B,0x10,0x48,0x60}},{2,0}},
    0,
    1,
    __RpcProtseqEndpoint,
    0,
    0,
    0x00000000
    };
RPC_IF_HANDLE svcctl_v2_0_c_ifspec = (RPC_IF_HANDLE)& svcctl___RpcClientInterface;

extern const MIDL_STUB_DESC svcctl_StubDesc;

static RPC_BINDING_HANDLE svcctl__MIDL_AutoBindHandle;


DWORD RCloseServiceHandle( 
    /* [out][in] */ LPSC_RPC_HANDLE hSCObject)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[0],
                  ( unsigned char * )&hSCObject);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RControlService( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwControl,
    /* [out] */ LPSERVICE_STATUS lpServiceStatus)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[42],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RDeleteService( 
    /* [in] */ SC_RPC_HANDLE hService)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[96],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RLockServiceDatabase( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [out] */ LPSC_RPC_LOCK lpLock)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[138],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceObjectSecurity( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SECURITY_INFORMATION dwSecurityInformation,
    /* [size_is][out] */ LPBYTE lpSecurityDescriptor,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[186],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RSetServiceObjectSecurity( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwSecurityInformation,
    /* [size_is][in] */ LPBYTE lpSecurityDescriptor,
    /* [in] */ DWORD dwSecuityDescriptorSize)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[252],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceStatus( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [out] */ LPSERVICE_STATUS lpServiceStatus)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[312],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RSetServiceStatus( 
    /* [in] */ RPC_SERVICE_STATUS_HANDLE hServiceStatus,
    /* [in] */ LPSERVICE_STATUS lpServiceStatus)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[360],
                  ( unsigned char * )&hServiceStatus);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RUnlockServiceDatabase( 
    /* [out][in] */ LPSC_RPC_LOCK Lock)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[408],
                  ( unsigned char * )&Lock);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RNotifyBootConfigStatus( 
    /* [unique][string][in] */ SVCCTL_HANDLEW lpMachineName,
    /* [in] */ DWORD BootAcceptable)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[450],
                  ( unsigned char * )&lpMachineName);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RI_ScSetServiceBitsW( 
    /* [in] */ RPC_SERVICE_STATUS_HANDLE hServiceStatus,
    /* [in] */ DWORD dwServiceBits,
    /* [in] */ BOOL bSetBitsOn,
    /* [in] */ BOOL bUpdateImmediately,
    /* [unique][string][in] */ LPWSTR lpString)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[498],
                  ( unsigned char * )&hServiceStatus);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RChangeServiceConfigW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwStartType,
    /* [in] */ DWORD dwErrorControl,
    /* [unique][string][in] */ LPWSTR lpBinaryPathName,
    /* [unique][string][in] */ LPWSTR lpLoadOrderGroup,
    /* [unique][out][in] */ LPDWORD lpdwTagId,
    /* [size_is][unique][in] */ LPBYTE lpDependencies,
    /* [range][in] */ DWORD dwDependSize,
    /* [unique][string][in] */ LPWSTR lpServiceStartName,
    /* [size_is][unique][in] */ LPBYTE lpPassword,
    /* [range][in] */ DWORD dwPwSize,
    /* [unique][string][in] */ LPWSTR lpDisplayName)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[564],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RCreateServiceW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCWSTR lpServiceName,
    /* [unique][string][in] */ LPCWSTR lpDisplayName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwStartType,
    /* [in] */ DWORD dwErrorControl,
    /* [string][in] */ LPCWSTR lpBinaryPathName,
    /* [unique][string][in] */ LPCWSTR lpLoadOrderGroup,
    /* [unique][out][in] */ LPDWORD lpdwTagId,
    /* [size_is][unique][in] */ LPBYTE lpDependencies,
    /* [range][in] */ DWORD dwDependSize,
    /* [unique][string][in] */ LPCWSTR lpServiceStartName,
    /* [size_is][unique][in] */ LPBYTE lpPassword,
    /* [range][in] */ DWORD dwPwSize,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[678],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumDependentServicesW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpServices,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[810],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumServicesStatusW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD dwBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[882],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD ROpenSCManagerW( 
    /* [unique][string][in] */ SVCCTL_HANDLEW lpMachineName,
    /* [unique][string][in] */ LPWSTR lpDatabaseName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpScHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[966],
                  ( unsigned char * )&lpMachineName);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD ROpenServiceW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPWSTR lpServiceName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1026],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceConfigW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [size_is][out] */ LPBYTE lpServiceConfig,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1086],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceLockStatusW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [size_is][out] */ LPBYTE lpLockStatus,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_4K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1146],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RStartServiceW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [range][in] */ DWORD argc,
    /* [size_is][unique][in] */ LPSTRING_PTRSW argv)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1206],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RGetServiceDisplayNameW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCWSTR lpServiceName,
    /* [size_is][string][out] */ LPWSTR lpDisplayName,
    /* [out][in] */ DWORD *lpcchBuffer)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1260],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RGetServiceKeyNameW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCWSTR lpDisplayName,
    /* [size_is][string][out] */ LPWSTR lpServiceName,
    /* [out][in] */ DWORD *lpcchBuffer)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1320],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RI_ScSetServiceBitsA( 
    /* [in] */ RPC_SERVICE_STATUS_HANDLE hServiceStatus,
    /* [in] */ DWORD dwServiceBits,
    /* [in] */ BOOL bSetBitsOn,
    /* [in] */ BOOL bUpdateImmediately,
    /* [unique][string][in] */ LPSTR lpString)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1380],
                  ( unsigned char * )&hServiceStatus);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RChangeServiceConfigA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwStartType,
    /* [in] */ DWORD dwErrorControl,
    /* [unique][string][in] */ LPSTR lpBinaryPathName,
    /* [unique][string][in] */ LPSTR lpLoadOrderGroup,
    /* [unique][out][in] */ LPDWORD lpdwTagId,
    /* [size_is][unique][in] */ LPBYTE lpDependencies,
    /* [range][in] */ DWORD dwDependSize,
    /* [unique][string][in] */ LPSTR lpServiceStartName,
    /* [size_is][unique][in] */ LPBYTE lpPassword,
    /* [range][in] */ DWORD dwPwSize,
    /* [unique][string][in] */ LPSTR lpDisplayName)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1446],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RCreateServiceA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPSTR lpServiceName,
    /* [unique][string][in] */ LPSTR lpDisplayName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwStartType,
    /* [in] */ DWORD dwErrorControl,
    /* [string][in] */ LPSTR lpBinaryPathName,
    /* [unique][string][in] */ LPSTR lpLoadOrderGroup,
    /* [unique][out][in] */ LPDWORD lpdwTagId,
    /* [size_is][unique][in] */ LPBYTE lpDependencies,
    /* [range][in] */ DWORD dwDependSize,
    /* [unique][string][in] */ LPSTR lpServiceStartName,
    /* [size_is][unique][in] */ LPBYTE lpPassword,
    /* [range][in] */ DWORD dwPwSize,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1560],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumDependentServicesA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpServices,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1692],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumServicesStatusA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD dwBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1764],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD ROpenSCManagerA( 
    /* [unique][string][in] */ SVCCTL_HANDLEA lpMachineName,
    /* [unique][string][in] */ LPSTR lpDatabaseName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpScHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1848],
                  ( unsigned char * )&lpMachineName);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD ROpenServiceA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPSTR lpServiceName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1908],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceConfigA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [size_is][out] */ LPBYTE lpServiceConfig,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[1968],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceLockStatusA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [size_is][out] */ LPBYTE lpLockStatus,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_4K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2028],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RStartServiceA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [range][in] */ DWORD argc,
    /* [size_is][unique][in] */ LPSTRING_PTRSA argv)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2088],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RGetServiceDisplayNameA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCSTR lpServiceName,
    /* [size_is][string][out] */ LPSTR lpDisplayName,
    /* [out][in] */ LPBOUNDED_DWORD_4K lpcchBuffer)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2142],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RGetServiceKeyNameA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCSTR lpDisplayName,
    /* [size_is][string][out] */ LPSTR lpKeyName,
    /* [out][in] */ LPBOUNDED_DWORD_4K lpcchBuffer)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2202],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RI_ScGetCurrentGroupStateW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [unique][string][in] */ LPWSTR lpLoadOrderGroup,
    /* [out] */ LPDWORD lpState)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2262],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumServiceGroupW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeIndex,
    /* [unique][string][in] */ LPCWSTR pszGroupName)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2316],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RChangeServiceConfig2A( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_RPC_CONFIG_INFOA Info)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2406],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RChangeServiceConfig2W( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_RPC_CONFIG_INFOW Info)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2454],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceConfig2A( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwInfoLevel,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2502],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceConfig2W( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwInfoLevel,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2568],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceStatusEx( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_STATUS_TYPE InfoLevel,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2634],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumServicesStatusExA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ SC_ENUM_TYPE InfoLevel,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeIndex,
    /* [unique][string][in] */ LPCSTR pszGroupName)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2700],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD REnumServicesStatusExW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ SC_ENUM_TYPE InfoLevel,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeIndex,
    /* [unique][string][in] */ LPCWSTR pszGroupName)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2796],
                  ( unsigned char * )&hSCManager);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RSendTSMessage( 
    /* [in] */ handle_t BindingHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2892],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RCreateServiceWOW64A( 
    /* [in] */ handle_t BindingHandle,
    /* [string][in] */ LPSTR lpServiceName,
    /* [unique][string][in] */ LPSTR lpDisplayName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwStartType,
    /* [in] */ DWORD dwErrorControl,
    /* [string][in] */ LPSTR lpBinaryPathName,
    /* [unique][string][in] */ LPSTR lpLoadOrderGroup,
    /* [unique][out][in] */ LPDWORD lpdwTagId,
    /* [size_is][unique][in] */ LPBYTE lpDependencies,
    /* [range][in] */ DWORD dwDependSize,
    /* [unique][string][in] */ LPSTR lpServiceStartName,
    /* [size_is][unique][in] */ LPBYTE lpPassword,
    /* [range][in] */ DWORD dwPwSize,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[2926],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RCreateServiceWOW64W( 
    /* [in] */ handle_t BindingHandle,
    /* [string][in] */ LPWSTR lpServiceName,
    /* [unique][string][in] */ LPWSTR lpDisplayName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwStartType,
    /* [in] */ DWORD dwErrorControl,
    /* [string][in] */ LPWSTR lpBinaryPathName,
    /* [unique][string][in] */ LPWSTR lpLoadOrderGroup,
    /* [unique][out][in] */ LPDWORD lpdwTagId,
    /* [size_is][unique][in] */ LPBYTE lpDependencies,
    /* [range][in] */ DWORD dwDependSize,
    /* [unique][string][in] */ LPWSTR lpServiceStartName,
    /* [size_is][unique][in] */ LPBYTE lpPassword,
    /* [range][in] */ DWORD dwPwSize,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3050],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RQueryServiceTagInfo( 
    /* [in] */ handle_t BindingHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3174],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RNotifyServiceStatusChange( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_RPC_NOTIFY_PARAMS NotifyParams,
    /* [in] */ GUID *pClientProcessGuid,
    /* [out] */ GUID *pSCMProcessGuid,
    /* [out] */ PBOOL pfCreateRemoteQueue,
    /* [out] */ LPSC_NOTIFY_RPC_HANDLE phNotify)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3208],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RGetNotifyResults( 
    /* [in] */ SC_NOTIFY_RPC_HANDLE hNotify,
    /* [out] */ PSC_RPC_NOTIFY_PARAMS_LIST *ppNotifyParams)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3280],
                  ( unsigned char * )&hNotify);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RCloseNotifyHandle( 
    /* [out][in] */ LPSC_NOTIFY_RPC_HANDLE phNotify,
    /* [out] */ PBOOL pfApcFired)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3328],
                  ( unsigned char * )&phNotify);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RControlServiceExA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwControl,
    /* [in] */ DWORD dwInfoLevel)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3376],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RControlServiceExW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwControl,
    /* [in] */ DWORD dwInfoLevel)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3430],
                  ( unsigned char * )&hService);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RSendPnPMessage( 
    /* [in] */ handle_t BindingHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3484],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RValidatePnPService( 
    /* [in] */ handle_t BindingHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3518],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD ROpenServiceStatusHandle( 
    /* [in] */ handle_t BindingHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3552],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}


DWORD RFunction55( 
    /* [in] */ handle_t BindingHandle)
{

    CLIENT_CALL_RETURN _RetVal;

    _RetVal = NdrClientCall2(
                  ( PMIDL_STUB_DESC  )&svcctl_StubDesc,
                  (PFORMAT_STRING) &svcctl__MIDL_ProcFormatString.Format[3586],
                  ( unsigned char * )&BindingHandle);
    return ( DWORD  )_RetVal.Simple;
    
}

extern const GENERIC_BINDING_ROUTINE_PAIR BindingRoutines[ GENERIC_BINDING_TABLE_SIZE ];
extern const EXPR_EVAL ExprEvalRoutines[];

#if !defined(__RPC_WIN32__)
#error  Invalid build platform for this stub.
#endif

#if !(TARGET_IS_NT50_OR_LATER)
#error You need a Windows 2000 or later to run this stub because it uses these features:
#error   /robust command line switch.
#error However, your C/C++ compilation flags indicate you intend to run this app on earlier systems.
#error This app will fail with the RPC_X_WRONG_STUB_VERSION error.
#endif


static const svcctl_MIDL_PROC_FORMAT_STRING svcctl__MIDL_ProcFormatString =
    {
        0,
        {

	/* Procedure RCloseServiceHandle */

			0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/*  2 */	NdrFcLong( 0x0 ),	/* 0 */
/*  6 */	NdrFcShort( 0x0 ),	/* 0 */
/*  8 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 10 */	0x30,		/* FC_BIND_CONTEXT */
			0xe0,		/* Ctxt flags:  via ptr, in, out, */
/* 12 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 14 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 16 */	NdrFcShort( 0x38 ),	/* 56 */
/* 18 */	NdrFcShort( 0x40 ),	/* 64 */
/* 20 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x2,		/* 2 */
/* 22 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 24 */	NdrFcShort( 0x0 ),	/* 0 */
/* 26 */	NdrFcShort( 0x0 ),	/* 0 */
/* 28 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCObject */

/* 30 */	NdrFcShort( 0x118 ),	/* Flags:  in, out, simple ref, */
/* 32 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 34 */	NdrFcShort( 0x6 ),	/* Type Offset=6 */

	/* Return value */

/* 36 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 38 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 40 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RControlService */

/* 42 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 44 */	NdrFcLong( 0x0 ),	/* 0 */
/* 48 */	NdrFcShort( 0x1 ),	/* 1 */
/* 50 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 52 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 54 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 56 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 58 */	NdrFcShort( 0x2c ),	/* 44 */
/* 60 */	NdrFcShort( 0x48 ),	/* 72 */
/* 62 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x4,		/* 4 */
/* 64 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 66 */	NdrFcShort( 0x0 ),	/* 0 */
/* 68 */	NdrFcShort( 0x0 ),	/* 0 */
/* 70 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 72 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 74 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 76 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwControl */

/* 78 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 80 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 82 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpServiceStatus */

/* 84 */	NdrFcShort( 0x8112 ),	/* Flags:  must free, out, simple ref, srv alloc size=32 */
/* 86 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 88 */	NdrFcShort( 0x12 ),	/* Type Offset=18 */

	/* Return value */

/* 90 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 92 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 94 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RDeleteService */

/* 96 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 98 */	NdrFcLong( 0x0 ),	/* 0 */
/* 102 */	NdrFcShort( 0x2 ),	/* 2 */
/* 104 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 106 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 108 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 110 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 112 */	NdrFcShort( 0x24 ),	/* 36 */
/* 114 */	NdrFcShort( 0x8 ),	/* 8 */
/* 116 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x2,		/* 2 */
/* 118 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 120 */	NdrFcShort( 0x0 ),	/* 0 */
/* 122 */	NdrFcShort( 0x0 ),	/* 0 */
/* 124 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 126 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 128 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 130 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Return value */

/* 132 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 134 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 136 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RLockServiceDatabase */

/* 138 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 140 */	NdrFcLong( 0x0 ),	/* 0 */
/* 144 */	NdrFcShort( 0x3 ),	/* 3 */
/* 146 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 148 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 150 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 152 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 154 */	NdrFcShort( 0x24 ),	/* 36 */
/* 156 */	NdrFcShort( 0x40 ),	/* 64 */
/* 158 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x3,		/* 3 */
/* 160 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 162 */	NdrFcShort( 0x0 ),	/* 0 */
/* 164 */	NdrFcShort( 0x0 ),	/* 0 */
/* 166 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 168 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 170 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 172 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpLock */

/* 174 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 176 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 178 */	NdrFcShort( 0x22 ),	/* Type Offset=34 */

	/* Return value */

/* 180 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 182 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 184 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceObjectSecurity */

/* 186 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 188 */	NdrFcLong( 0x0 ),	/* 0 */
/* 192 */	NdrFcShort( 0x4 ),	/* 4 */
/* 194 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 196 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 198 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 200 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 202 */	NdrFcShort( 0x34 ),	/* 52 */
/* 204 */	NdrFcShort( 0x24 ),	/* 36 */
/* 206 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x6,		/* 6 */
/* 208 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 210 */	NdrFcShort( 0x1 ),	/* 1 */
/* 212 */	NdrFcShort( 0x0 ),	/* 0 */
/* 214 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 216 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 218 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 220 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwSecurityInformation */

/* 222 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 224 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 226 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpSecurityDescriptor */

/* 228 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 230 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 232 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter cbBufSize */

/* 234 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 236 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 238 */	NdrFcShort( 0x36 ),	/* 54 */

	/* Parameter pcbBytesNeeded */

/* 240 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 242 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 244 */	NdrFcShort( 0x44 ),	/* Type Offset=68 */

	/* Return value */

/* 246 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 248 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 250 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RSetServiceObjectSecurity */

/* 252 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 254 */	NdrFcLong( 0x0 ),	/* 0 */
/* 258 */	NdrFcShort( 0x5 ),	/* 5 */
/* 260 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 262 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 264 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 266 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 268 */	NdrFcShort( 0x34 ),	/* 52 */
/* 270 */	NdrFcShort( 0x8 ),	/* 8 */
/* 272 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 274 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 276 */	NdrFcShort( 0x0 ),	/* 0 */
/* 278 */	NdrFcShort( 0x1 ),	/* 1 */
/* 280 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 282 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 284 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 286 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwSecurityInformation */

/* 288 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 290 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 292 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpSecurityDescriptor */

/* 294 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 296 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 298 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter dwSecuityDescriptorSize */

/* 300 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 302 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 304 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 306 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 308 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 310 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceStatus */

/* 312 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 314 */	NdrFcLong( 0x0 ),	/* 0 */
/* 318 */	NdrFcShort( 0x6 ),	/* 6 */
/* 320 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 322 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 324 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 326 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 328 */	NdrFcShort( 0x24 ),	/* 36 */
/* 330 */	NdrFcShort( 0x48 ),	/* 72 */
/* 332 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x3,		/* 3 */
/* 334 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 336 */	NdrFcShort( 0x0 ),	/* 0 */
/* 338 */	NdrFcShort( 0x0 ),	/* 0 */
/* 340 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 342 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 344 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 346 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceStatus */

/* 348 */	NdrFcShort( 0x8112 ),	/* Flags:  must free, out, simple ref, srv alloc size=32 */
/* 350 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 352 */	NdrFcShort( 0x12 ),	/* Type Offset=18 */

	/* Return value */

/* 354 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 356 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 358 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RSetServiceStatus */

/* 360 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 362 */	NdrFcLong( 0x0 ),	/* 0 */
/* 366 */	NdrFcShort( 0x7 ),	/* 7 */
/* 368 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 370 */	0x31,		/* FC_BIND_GENERIC */
			0x4,		/* 4 */
/* 372 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 374 */	0x0,		/* 0 */
			0x5c,		/* FC_PAD */
/* 376 */	NdrFcShort( 0x48 ),	/* 72 */
/* 378 */	NdrFcShort( 0x8 ),	/* 8 */
/* 380 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x3,		/* 3 */
/* 382 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 384 */	NdrFcShort( 0x0 ),	/* 0 */
/* 386 */	NdrFcShort( 0x0 ),	/* 0 */
/* 388 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hServiceStatus */

/* 390 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 392 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 394 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpServiceStatus */

/* 396 */	NdrFcShort( 0x10a ),	/* Flags:  must free, in, simple ref, */
/* 398 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 400 */	NdrFcShort( 0x12 ),	/* Type Offset=18 */

	/* Return value */

/* 402 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 404 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 406 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RUnlockServiceDatabase */

/* 408 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 410 */	NdrFcLong( 0x0 ),	/* 0 */
/* 414 */	NdrFcShort( 0x8 ),	/* 8 */
/* 416 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 418 */	0x30,		/* FC_BIND_CONTEXT */
			0xe0,		/* Ctxt flags:  via ptr, in, out, */
/* 420 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 422 */	0x1,		/* 1 */
			0x0,		/* 0 */
/* 424 */	NdrFcShort( 0x38 ),	/* 56 */
/* 426 */	NdrFcShort( 0x40 ),	/* 64 */
/* 428 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x2,		/* 2 */
/* 430 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 432 */	NdrFcShort( 0x0 ),	/* 0 */
/* 434 */	NdrFcShort( 0x0 ),	/* 0 */
/* 436 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter Lock */

/* 438 */	NdrFcShort( 0x118 ),	/* Flags:  in, out, simple ref, */
/* 440 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 442 */	NdrFcShort( 0x56 ),	/* Type Offset=86 */

	/* Return value */

/* 444 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 446 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 448 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RNotifyBootConfigStatus */

/* 450 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 452 */	NdrFcLong( 0x0 ),	/* 0 */
/* 456 */	NdrFcShort( 0x9 ),	/* 9 */
/* 458 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 460 */	0x31,		/* FC_BIND_GENERIC */
			0x4,		/* 4 */
/* 462 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 464 */	0x1,		/* 1 */
			0x5c,		/* FC_PAD */
/* 466 */	NdrFcShort( 0x8 ),	/* 8 */
/* 468 */	NdrFcShort( 0x8 ),	/* 8 */
/* 470 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x3,		/* 3 */
/* 472 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 474 */	NdrFcShort( 0x0 ),	/* 0 */
/* 476 */	NdrFcShort( 0x0 ),	/* 0 */
/* 478 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter lpMachineName */

/* 480 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 482 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 484 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter BootAcceptable */

/* 486 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 488 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 490 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 492 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 494 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 496 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RI_ScSetServiceBitsW */

/* 498 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 500 */	NdrFcLong( 0x0 ),	/* 0 */
/* 504 */	NdrFcShort( 0xa ),	/* 10 */
/* 506 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 508 */	0x31,		/* FC_BIND_GENERIC */
			0x4,		/* 4 */
/* 510 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 512 */	0x0,		/* 0 */
			0x5c,		/* FC_PAD */
/* 514 */	NdrFcShort( 0x20 ),	/* 32 */
/* 516 */	NdrFcShort( 0x8 ),	/* 8 */
/* 518 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x6,		/* 6 */
/* 520 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 522 */	NdrFcShort( 0x0 ),	/* 0 */
/* 524 */	NdrFcShort( 0x0 ),	/* 0 */
/* 526 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hServiceStatus */

/* 528 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 530 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 532 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceBits */

/* 534 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 536 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 538 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter bSetBitsOn */

/* 540 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 542 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 544 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter bUpdateImmediately */

/* 546 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 548 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 550 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpString */

/* 552 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 554 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 556 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Return value */

/* 558 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 560 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 562 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RChangeServiceConfigW */

/* 564 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 566 */	NdrFcLong( 0x0 ),	/* 0 */
/* 570 */	NdrFcShort( 0xb ),	/* 11 */
/* 572 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 574 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 576 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 578 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 580 */	NdrFcShort( 0x68 ),	/* 104 */
/* 582 */	NdrFcShort( 0x24 ),	/* 36 */
/* 584 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0xe,		/* 14 */
/* 586 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 588 */	NdrFcShort( 0x0 ),	/* 0 */
/* 590 */	NdrFcShort( 0x2 ),	/* 2 */
/* 592 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 594 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 596 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 598 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceType */

/* 600 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 602 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 604 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwStartType */

/* 606 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 608 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 610 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwErrorControl */

/* 612 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 614 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 616 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBinaryPathName */

/* 618 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 620 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 622 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpLoadOrderGroup */

/* 624 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 626 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 628 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpdwTagId */

/* 630 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 632 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 634 */	NdrFcShort( 0x5e ),	/* Type Offset=94 */

	/* Parameter lpDependencies */

/* 636 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 638 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 640 */	NdrFcShort( 0x62 ),	/* Type Offset=98 */

	/* Parameter dwDependSize */

/* 642 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 644 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 646 */	NdrFcShort( 0x72 ),	/* 114 */

	/* Parameter lpServiceStartName */

/* 648 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 650 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 652 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpPassword */

/* 654 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 656 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 658 */	NdrFcShort( 0x7c ),	/* Type Offset=124 */

	/* Parameter dwPwSize */

/* 660 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 662 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 664 */	NdrFcShort( 0x8c ),	/* 140 */

	/* Parameter lpDisplayName */

/* 666 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 668 */	NdrFcShort( 0x30 ),	/* x86 Stack size/offset = 48 */
/* 670 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Return value */

/* 672 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 674 */	NdrFcShort( 0x34 ),	/* x86 Stack size/offset = 52 */
/* 676 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RCreateServiceW */

/* 678 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 680 */	NdrFcLong( 0x0 ),	/* 0 */
/* 684 */	NdrFcShort( 0xc ),	/* 12 */
/* 686 */	NdrFcShort( 0x44 ),	/* x86 Stack size/offset = 68 */
/* 688 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 690 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 692 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 694 */	NdrFcShort( 0x70 ),	/* 112 */
/* 696 */	NdrFcShort( 0x5c ),	/* 92 */
/* 698 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x11,		/* 17 */
/* 700 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 702 */	NdrFcShort( 0x0 ),	/* 0 */
/* 704 */	NdrFcShort( 0x2 ),	/* 2 */
/* 706 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 708 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 710 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 712 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceName */

/* 714 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 716 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 718 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter lpDisplayName */

/* 720 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 722 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 724 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter dwDesiredAccess */

/* 726 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 728 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 730 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceType */

/* 732 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 734 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 736 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwStartType */

/* 738 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 740 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 742 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwErrorControl */

/* 744 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 746 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 748 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBinaryPathName */

/* 750 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 752 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 754 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter lpLoadOrderGroup */

/* 756 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 758 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 760 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpdwTagId */

/* 762 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 764 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 766 */	NdrFcShort( 0x5e ),	/* Type Offset=94 */

	/* Parameter lpDependencies */

/* 768 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 770 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 772 */	NdrFcShort( 0x7c ),	/* Type Offset=124 */

	/* Parameter dwDependSize */

/* 774 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 776 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 778 */	NdrFcShort( 0x9a ),	/* 154 */

	/* Parameter lpServiceStartName */

/* 780 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 782 */	NdrFcShort( 0x30 ),	/* x86 Stack size/offset = 48 */
/* 784 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpPassword */

/* 786 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 788 */	NdrFcShort( 0x34 ),	/* x86 Stack size/offset = 52 */
/* 790 */	NdrFcShort( 0xa4 ),	/* Type Offset=164 */

	/* Parameter dwPwSize */

/* 792 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 794 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 796 */	NdrFcShort( 0xb4 ),	/* 180 */

	/* Parameter lpServiceHandle */

/* 798 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 800 */	NdrFcShort( 0x3c ),	/* x86 Stack size/offset = 60 */
/* 802 */	NdrFcShort( 0xc2 ),	/* Type Offset=194 */

	/* Return value */

/* 804 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 806 */	NdrFcShort( 0x40 ),	/* x86 Stack size/offset = 64 */
/* 808 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumDependentServicesW */

/* 810 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 812 */	NdrFcLong( 0x0 ),	/* 0 */
/* 816 */	NdrFcShort( 0xd ),	/* 13 */
/* 818 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 820 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 822 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 824 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 826 */	NdrFcShort( 0x34 ),	/* 52 */
/* 828 */	NdrFcShort( 0x40 ),	/* 64 */
/* 830 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x7,		/* 7 */
/* 832 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 834 */	NdrFcShort( 0x1 ),	/* 1 */
/* 836 */	NdrFcShort( 0x0 ),	/* 0 */
/* 838 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 840 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 842 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 844 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceState */

/* 846 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 848 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 850 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpServices */

/* 852 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 854 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 856 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter cbBufSize */

/* 858 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 860 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 862 */	NdrFcShort( 0xc6 ),	/* 198 */

	/* Parameter pcbBytesNeeded */

/* 864 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 866 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 868 */	NdrFcShort( 0xd4 ),	/* Type Offset=212 */

	/* Parameter lpServicesReturned */

/* 870 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 872 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 874 */	NdrFcShort( 0xe2 ),	/* Type Offset=226 */

	/* Return value */

/* 876 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 878 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 880 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumServicesStatusW */

/* 882 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 884 */	NdrFcLong( 0x0 ),	/* 0 */
/* 888 */	NdrFcShort( 0xe ),	/* 14 */
/* 890 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 892 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 894 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 896 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 898 */	NdrFcShort( 0x58 ),	/* 88 */
/* 900 */	NdrFcShort( 0x5c ),	/* 92 */
/* 902 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x9,		/* 9 */
/* 904 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 906 */	NdrFcShort( 0x1 ),	/* 1 */
/* 908 */	NdrFcShort( 0x0 ),	/* 0 */
/* 910 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 912 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 914 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 916 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceType */

/* 918 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 920 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 922 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceState */

/* 924 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 926 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 928 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 930 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 932 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 934 */	NdrFcShort( 0xf0 ),	/* Type Offset=240 */

	/* Parameter dwBufSize */

/* 936 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 938 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 940 */	NdrFcShort( 0xfc ),	/* 252 */

	/* Parameter pcbBytesNeeded */

/* 942 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 944 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 946 */	NdrFcShort( 0x10a ),	/* Type Offset=266 */

	/* Parameter lpServicesReturned */

/* 948 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 950 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 952 */	NdrFcShort( 0x118 ),	/* Type Offset=280 */

	/* Parameter lpResumeHandle */

/* 954 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 956 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 958 */	NdrFcShort( 0x122 ),	/* Type Offset=290 */

	/* Return value */

/* 960 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 962 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 964 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure ROpenSCManagerW */

/* 966 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 968 */	NdrFcLong( 0x0 ),	/* 0 */
/* 972 */	NdrFcShort( 0xf ),	/* 15 */
/* 974 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 976 */	0x31,		/* FC_BIND_GENERIC */
			0x4,		/* 4 */
/* 978 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 980 */	0x1,		/* 1 */
			0x5c,		/* FC_PAD */
/* 982 */	NdrFcShort( 0x8 ),	/* 8 */
/* 984 */	NdrFcShort( 0x40 ),	/* 64 */
/* 986 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 988 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 990 */	NdrFcShort( 0x0 ),	/* 0 */
/* 992 */	NdrFcShort( 0x0 ),	/* 0 */
/* 994 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter lpMachineName */

/* 996 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 998 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1000 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpDatabaseName */

/* 1002 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1004 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1006 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter dwDesiredAccess */

/* 1008 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1010 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1012 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpScHandle */

/* 1014 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 1016 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1018 */	NdrFcShort( 0x134 ),	/* Type Offset=308 */

	/* Return value */

/* 1020 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1022 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1024 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure ROpenServiceW */

/* 1026 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1028 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1032 */	NdrFcShort( 0x10 ),	/* 16 */
/* 1034 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1036 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1038 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1040 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1042 */	NdrFcShort( 0x2c ),	/* 44 */
/* 1044 */	NdrFcShort( 0x40 ),	/* 64 */
/* 1046 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 1048 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 1050 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1052 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1054 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1056 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1058 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1060 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceName */

/* 1062 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 1064 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1066 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter dwDesiredAccess */

/* 1068 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1070 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1072 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpServiceHandle */

/* 1074 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 1076 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1078 */	NdrFcShort( 0xc2 ),	/* Type Offset=194 */

	/* Return value */

/* 1080 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1082 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1084 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceConfigW */

/* 1086 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1088 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1092 */	NdrFcShort( 0x11 ),	/* 17 */
/* 1094 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1096 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1098 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1100 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1102 */	NdrFcShort( 0x2c ),	/* 44 */
/* 1104 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1106 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x5,		/* 5 */
/* 1108 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1110 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1112 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1114 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 1116 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1118 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1120 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceConfig */

/* 1122 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 1124 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1126 */	NdrFcShort( 0x13c ),	/* Type Offset=316 */

	/* Parameter cbBufSize */

/* 1128 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1130 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1132 */	NdrFcShort( 0x148 ),	/* 328 */

	/* Parameter pcbBytesNeeded */

/* 1134 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 1136 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1138 */	NdrFcShort( 0x156 ),	/* Type Offset=342 */

	/* Return value */

/* 1140 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1142 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1144 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceLockStatusW */

/* 1146 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1148 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1152 */	NdrFcShort( 0x12 ),	/* 18 */
/* 1154 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1156 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1158 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1160 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1162 */	NdrFcShort( 0x2c ),	/* 44 */
/* 1164 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1166 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x5,		/* 5 */
/* 1168 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1170 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1172 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1174 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1176 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1178 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1180 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpLockStatus */

/* 1182 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 1184 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1186 */	NdrFcShort( 0x13c ),	/* Type Offset=316 */

	/* Parameter cbBufSize */

/* 1188 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1190 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1192 */	NdrFcShort( 0x160 ),	/* 352 */

	/* Parameter pcbBytesNeeded */

/* 1194 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 1196 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1198 */	NdrFcShort( 0x16e ),	/* Type Offset=366 */

	/* Return value */

/* 1200 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1202 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1204 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RStartServiceW */

/* 1206 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1208 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1212 */	NdrFcShort( 0x13 ),	/* 19 */
/* 1214 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1216 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1218 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1220 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1222 */	NdrFcShort( 0x2c ),	/* 44 */
/* 1224 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1226 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x4,		/* 4 */
/* 1228 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 1230 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1232 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1234 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 1236 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1238 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1240 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter argc */

/* 1242 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1244 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1246 */	NdrFcShort( 0x178 ),	/* 376 */

	/* Parameter argv */

/* 1248 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1250 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1252 */	NdrFcShort( 0x182 ),	/* Type Offset=386 */

	/* Return value */

/* 1254 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1256 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1258 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RGetServiceDisplayNameW */

/* 1260 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1262 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1266 */	NdrFcShort( 0x14 ),	/* 20 */
/* 1268 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1270 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1272 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1274 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1276 */	NdrFcShort( 0x40 ),	/* 64 */
/* 1278 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1280 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 1282 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1284 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1286 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1288 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1290 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1292 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1294 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceName */

/* 1296 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 1298 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1300 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter lpDisplayName */

/* 1302 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 1304 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1306 */	NdrFcShort( 0x1c0 ),	/* Type Offset=448 */

	/* Parameter lpcchBuffer */

/* 1308 */	NdrFcShort( 0x158 ),	/* Flags:  in, out, base type, simple ref, */
/* 1310 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1312 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 1314 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1316 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1318 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RGetServiceKeyNameW */

/* 1320 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1322 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1326 */	NdrFcShort( 0x15 ),	/* 21 */
/* 1328 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1330 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1332 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1334 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1336 */	NdrFcShort( 0x40 ),	/* 64 */
/* 1338 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1340 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 1342 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1344 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1346 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1348 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1350 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1352 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1354 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpDisplayName */

/* 1356 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 1358 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1360 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter lpServiceName */

/* 1362 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 1364 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1366 */	NdrFcShort( 0x1d0 ),	/* Type Offset=464 */

	/* Parameter lpcchBuffer */

/* 1368 */	NdrFcShort( 0x158 ),	/* Flags:  in, out, base type, simple ref, */
/* 1370 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1372 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 1374 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1376 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1378 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RI_ScSetServiceBitsA */

/* 1380 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1382 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1386 */	NdrFcShort( 0x16 ),	/* 22 */
/* 1388 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 1390 */	0x31,		/* FC_BIND_GENERIC */
			0x4,		/* 4 */
/* 1392 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1394 */	0x0,		/* 0 */
			0x5c,		/* FC_PAD */
/* 1396 */	NdrFcShort( 0x20 ),	/* 32 */
/* 1398 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1400 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x6,		/* 6 */
/* 1402 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 1404 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1406 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1408 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hServiceStatus */

/* 1410 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1412 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1414 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceBits */

/* 1416 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1418 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1420 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter bSetBitsOn */

/* 1422 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1424 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1426 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter bUpdateImmediately */

/* 1428 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1430 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1432 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpString */

/* 1434 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1436 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1438 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Return value */

/* 1440 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1442 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1444 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RChangeServiceConfigA */

/* 1446 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1448 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1452 */	NdrFcShort( 0x17 ),	/* 23 */
/* 1454 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 1456 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1458 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1460 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1462 */	NdrFcShort( 0x68 ),	/* 104 */
/* 1464 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1466 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0xe,		/* 14 */
/* 1468 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 1470 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1472 */	NdrFcShort( 0x2 ),	/* 2 */
/* 1474 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 1476 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1478 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1480 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceType */

/* 1482 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1484 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1486 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwStartType */

/* 1488 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1490 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1492 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwErrorControl */

/* 1494 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1496 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1498 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBinaryPathName */

/* 1500 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1502 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1504 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpLoadOrderGroup */

/* 1506 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1508 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1510 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpdwTagId */

/* 1512 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 1514 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 1516 */	NdrFcShort( 0x5e ),	/* Type Offset=94 */

	/* Parameter lpDependencies */

/* 1518 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1520 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 1522 */	NdrFcShort( 0x62 ),	/* Type Offset=98 */

	/* Parameter dwDependSize */

/* 1524 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1526 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 1528 */	NdrFcShort( 0x1dc ),	/* 476 */

	/* Parameter lpServiceStartName */

/* 1530 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1532 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 1534 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpPassword */

/* 1536 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1538 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 1540 */	NdrFcShort( 0x7c ),	/* Type Offset=124 */

	/* Parameter dwPwSize */

/* 1542 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1544 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 1546 */	NdrFcShort( 0x1e6 ),	/* 486 */

	/* Parameter lpDisplayName */

/* 1548 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1550 */	NdrFcShort( 0x30 ),	/* x86 Stack size/offset = 48 */
/* 1552 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Return value */

/* 1554 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1556 */	NdrFcShort( 0x34 ),	/* x86 Stack size/offset = 52 */
/* 1558 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RCreateServiceA */

/* 1560 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1562 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1566 */	NdrFcShort( 0x18 ),	/* 24 */
/* 1568 */	NdrFcShort( 0x44 ),	/* x86 Stack size/offset = 68 */
/* 1570 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1572 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1574 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1576 */	NdrFcShort( 0x70 ),	/* 112 */
/* 1578 */	NdrFcShort( 0x5c ),	/* 92 */
/* 1580 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x11,		/* 17 */
/* 1582 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 1584 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1586 */	NdrFcShort( 0x2 ),	/* 2 */
/* 1588 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1590 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1592 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1594 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceName */

/* 1596 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 1598 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1600 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter lpDisplayName */

/* 1602 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1604 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1606 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter dwDesiredAccess */

/* 1608 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1610 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1612 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceType */

/* 1614 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1616 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1618 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwStartType */

/* 1620 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1622 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1624 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwErrorControl */

/* 1626 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1628 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 1630 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBinaryPathName */

/* 1632 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 1634 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 1636 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter lpLoadOrderGroup */

/* 1638 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1640 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 1642 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpdwTagId */

/* 1644 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 1646 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 1648 */	NdrFcShort( 0x5e ),	/* Type Offset=94 */

	/* Parameter lpDependencies */

/* 1650 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1652 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 1654 */	NdrFcShort( 0x7c ),	/* Type Offset=124 */

	/* Parameter dwDependSize */

/* 1656 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1658 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 1660 */	NdrFcShort( 0x1f4 ),	/* 500 */

	/* Parameter lpServiceStartName */

/* 1662 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1664 */	NdrFcShort( 0x30 ),	/* x86 Stack size/offset = 48 */
/* 1666 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpPassword */

/* 1668 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1670 */	NdrFcShort( 0x34 ),	/* x86 Stack size/offset = 52 */
/* 1672 */	NdrFcShort( 0xa4 ),	/* Type Offset=164 */

	/* Parameter dwPwSize */

/* 1674 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1676 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 1678 */	NdrFcShort( 0x1fe ),	/* 510 */

	/* Parameter lpServiceHandle */

/* 1680 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 1682 */	NdrFcShort( 0x3c ),	/* x86 Stack size/offset = 60 */
/* 1684 */	NdrFcShort( 0xc2 ),	/* Type Offset=194 */

	/* Return value */

/* 1686 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1688 */	NdrFcShort( 0x40 ),	/* x86 Stack size/offset = 64 */
/* 1690 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumDependentServicesA */

/* 1692 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1694 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1698 */	NdrFcShort( 0x19 ),	/* 25 */
/* 1700 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 1702 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1704 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1706 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1708 */	NdrFcShort( 0x34 ),	/* 52 */
/* 1710 */	NdrFcShort( 0x40 ),	/* 64 */
/* 1712 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x7,		/* 7 */
/* 1714 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1716 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1718 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1720 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 1722 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1724 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1726 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceState */

/* 1728 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1730 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1732 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpServices */

/* 1734 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 1736 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1738 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter cbBufSize */

/* 1740 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1742 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1744 */	NdrFcShort( 0x208 ),	/* 520 */

	/* Parameter pcbBytesNeeded */

/* 1746 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 1748 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1750 */	NdrFcShort( 0x216 ),	/* Type Offset=534 */

	/* Parameter lpServicesReturned */

/* 1752 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 1754 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1756 */	NdrFcShort( 0x224 ),	/* Type Offset=548 */

	/* Return value */

/* 1758 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1760 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 1762 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumServicesStatusA */

/* 1764 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1766 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1770 */	NdrFcShort( 0x1a ),	/* 26 */
/* 1772 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 1774 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1776 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1778 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1780 */	NdrFcShort( 0x58 ),	/* 88 */
/* 1782 */	NdrFcShort( 0x5c ),	/* 92 */
/* 1784 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x9,		/* 9 */
/* 1786 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1788 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1790 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1792 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1794 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1796 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1798 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceType */

/* 1800 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1802 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1804 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceState */

/* 1806 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1808 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1810 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 1812 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 1814 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1816 */	NdrFcShort( 0xf0 ),	/* Type Offset=240 */

	/* Parameter dwBufSize */

/* 1818 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 1820 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1822 */	NdrFcShort( 0x22e ),	/* 558 */

	/* Parameter pcbBytesNeeded */

/* 1824 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 1826 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1828 */	NdrFcShort( 0x23c ),	/* Type Offset=572 */

	/* Parameter lpServicesReturned */

/* 1830 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 1832 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 1834 */	NdrFcShort( 0x24a ),	/* Type Offset=586 */

	/* Parameter lpResumeHandle */

/* 1836 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 1838 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 1840 */	NdrFcShort( 0x254 ),	/* Type Offset=596 */

	/* Return value */

/* 1842 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1844 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 1846 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure ROpenSCManagerA */

/* 1848 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1850 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1854 */	NdrFcShort( 0x1b ),	/* 27 */
/* 1856 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1858 */	0x31,		/* FC_BIND_GENERIC */
			0x4,		/* 4 */
/* 1860 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1862 */	0x2,		/* 2 */
			0x5c,		/* FC_PAD */
/* 1864 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1866 */	NdrFcShort( 0x40 ),	/* 64 */
/* 1868 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 1870 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 1872 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1874 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1876 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter lpMachineName */

/* 1878 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1880 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1882 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpDatabaseName */

/* 1884 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 1886 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1888 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter dwDesiredAccess */

/* 1890 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1892 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1894 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpScHandle */

/* 1896 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 1898 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1900 */	NdrFcShort( 0x134 ),	/* Type Offset=308 */

	/* Return value */

/* 1902 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1904 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1906 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure ROpenServiceA */

/* 1908 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1910 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1914 */	NdrFcShort( 0x1c ),	/* 28 */
/* 1916 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1918 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1920 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1922 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1924 */	NdrFcShort( 0x2c ),	/* 44 */
/* 1926 */	NdrFcShort( 0x40 ),	/* 64 */
/* 1928 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 1930 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 1932 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1934 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1936 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 1938 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 1940 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1942 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceName */

/* 1944 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 1946 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 1948 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter dwDesiredAccess */

/* 1950 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 1952 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 1954 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpServiceHandle */

/* 1956 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 1958 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 1960 */	NdrFcShort( 0xc2 ),	/* Type Offset=194 */

	/* Return value */

/* 1962 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 1964 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 1966 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceConfigA */

/* 1968 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 1970 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1974 */	NdrFcShort( 0x1d ),	/* 29 */
/* 1976 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1978 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 1980 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 1982 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 1984 */	NdrFcShort( 0x2c ),	/* 44 */
/* 1986 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1988 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x5,		/* 5 */
/* 1990 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 1992 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1994 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1996 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 1998 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2000 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2002 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceConfig */

/* 2004 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2006 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2008 */	NdrFcShort( 0x13c ),	/* Type Offset=316 */

	/* Parameter cbBufSize */

/* 2010 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2012 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2014 */	NdrFcShort( 0x262 ),	/* 610 */

	/* Parameter pcbBytesNeeded */

/* 2016 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2018 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2020 */	NdrFcShort( 0x270 ),	/* Type Offset=624 */

	/* Return value */

/* 2022 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2024 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2026 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceLockStatusA */

/* 2028 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2030 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2034 */	NdrFcShort( 0x1e ),	/* 30 */
/* 2036 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2038 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2040 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2042 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2044 */	NdrFcShort( 0x2c ),	/* 44 */
/* 2046 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2048 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x5,		/* 5 */
/* 2050 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2052 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2054 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2056 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2058 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2060 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2062 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpLockStatus */

/* 2064 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2066 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2068 */	NdrFcShort( 0x13c ),	/* Type Offset=316 */

	/* Parameter cbBufSize */

/* 2070 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2072 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2074 */	NdrFcShort( 0x27a ),	/* 634 */

	/* Parameter pcbBytesNeeded */

/* 2076 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2078 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2080 */	NdrFcShort( 0x288 ),	/* Type Offset=648 */

	/* Return value */

/* 2082 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2084 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2086 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RStartServiceA */

/* 2088 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2090 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2094 */	NdrFcShort( 0x1f ),	/* 31 */
/* 2096 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2098 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2100 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2102 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2104 */	NdrFcShort( 0x2c ),	/* 44 */
/* 2106 */	NdrFcShort( 0x8 ),	/* 8 */
/* 2108 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x4,		/* 4 */
/* 2110 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 2112 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2114 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2116 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 2118 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2120 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2122 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter argc */

/* 2124 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2126 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2128 */	NdrFcShort( 0x292 ),	/* 658 */

	/* Parameter argv */

/* 2130 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2132 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2134 */	NdrFcShort( 0x29c ),	/* Type Offset=668 */

	/* Return value */

/* 2136 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2138 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2140 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RGetServiceDisplayNameA */

/* 2142 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2144 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2148 */	NdrFcShort( 0x20 ),	/* 32 */
/* 2150 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2152 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2154 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2156 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2158 */	NdrFcShort( 0x40 ),	/* 64 */
/* 2160 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2162 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 2164 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2166 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2168 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2170 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2172 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2174 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2176 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpServiceName */

/* 2178 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 2180 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2182 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter lpDisplayName */

/* 2184 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2186 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2188 */	NdrFcShort( 0x2da ),	/* Type Offset=730 */

	/* Parameter lpcchBuffer */

/* 2190 */	NdrFcShort( 0x11a ),	/* Flags:  must free, in, out, simple ref, */
/* 2192 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2194 */	NdrFcShort( 0x2e6 ),	/* Type Offset=742 */

	/* Return value */

/* 2196 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2198 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2200 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RGetServiceKeyNameA */

/* 2202 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2204 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2208 */	NdrFcShort( 0x21 ),	/* 33 */
/* 2210 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2212 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2214 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2216 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2218 */	NdrFcShort( 0x40 ),	/* 64 */
/* 2220 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2222 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0x5,		/* 5 */
/* 2224 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2226 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2228 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2230 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2232 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2234 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2236 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpDisplayName */

/* 2238 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 2240 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2242 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter lpKeyName */

/* 2244 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2246 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2248 */	NdrFcShort( 0x2f4 ),	/* Type Offset=756 */

	/* Parameter lpcchBuffer */

/* 2250 */	NdrFcShort( 0x11a ),	/* Flags:  must free, in, out, simple ref, */
/* 2252 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2254 */	NdrFcShort( 0x300 ),	/* Type Offset=768 */

	/* Return value */

/* 2256 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2258 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2260 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RI_ScGetCurrentGroupStateW */

/* 2262 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2264 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2268 */	NdrFcShort( 0x22 ),	/* 34 */
/* 2270 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2272 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2274 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2276 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2278 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2280 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2282 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x4,		/* 4 */
/* 2284 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 2286 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2288 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2290 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2292 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2294 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2296 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter lpLoadOrderGroup */

/* 2298 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2300 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2302 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpState */

/* 2304 */	NdrFcShort( 0x2150 ),	/* Flags:  out, base type, simple ref, srv alloc size=8 */
/* 2306 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2308 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 2310 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2312 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2314 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumServiceGroupW */

/* 2316 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2318 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2322 */	NdrFcShort( 0x23 ),	/* 35 */
/* 2324 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 2326 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2328 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2330 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2332 */	NdrFcShort( 0x58 ),	/* 88 */
/* 2334 */	NdrFcShort( 0x5c ),	/* 92 */
/* 2336 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0xa,		/* 10 */
/* 2338 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2340 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2342 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2344 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2346 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2348 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2350 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwServiceType */

/* 2352 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2354 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2356 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceState */

/* 2358 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2360 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2362 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 2364 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2366 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2368 */	NdrFcShort( 0xf0 ),	/* Type Offset=240 */

	/* Parameter cbBufSize */

/* 2370 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2372 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2374 */	NdrFcShort( 0x30e ),	/* 782 */

	/* Parameter pcbBytesNeeded */

/* 2376 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2378 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2380 */	NdrFcShort( 0x31c ),	/* Type Offset=796 */

	/* Parameter lpServicesReturned */

/* 2382 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2384 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2386 */	NdrFcShort( 0x32a ),	/* Type Offset=810 */

	/* Parameter lpResumeIndex */

/* 2388 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 2390 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 2392 */	NdrFcShort( 0x334 ),	/* Type Offset=820 */

	/* Parameter pszGroupName */

/* 2394 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2396 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 2398 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Return value */

/* 2400 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2402 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 2404 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RChangeServiceConfig2A */

/* 2406 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2408 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2412 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2414 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2416 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2418 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2420 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2422 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2424 */	NdrFcShort( 0x8 ),	/* 8 */
/* 2426 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x3,		/* 3 */
/* 2428 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 2430 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2432 */	NdrFcShort( 0x3 ),	/* 3 */
/* 2434 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 2436 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2438 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2440 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter Info */

/* 2442 */	NdrFcShort( 0x8b ),	/* Flags:  must size, must free, in, by val, */
/* 2444 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2446 */	NdrFcShort( 0x3fa ),	/* Type Offset=1018 */

	/* Return value */

/* 2448 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2450 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2452 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RChangeServiceConfig2W */

/* 2454 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2456 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2460 */	NdrFcShort( 0x25 ),	/* 37 */
/* 2462 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2464 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2466 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2468 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2470 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2472 */	NdrFcShort( 0x8 ),	/* 8 */
/* 2474 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x3,		/* 3 */
/* 2476 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 2478 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2480 */	NdrFcShort( 0x6 ),	/* 6 */
/* 2482 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 2484 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2486 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2488 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter Info */

/* 2490 */	NdrFcShort( 0x8b ),	/* Flags:  must size, must free, in, by val, */
/* 2492 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2494 */	NdrFcShort( 0x478 ),	/* Type Offset=1144 */

	/* Return value */

/* 2496 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2498 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2500 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceConfig2A */

/* 2502 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2504 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2508 */	NdrFcShort( 0x26 ),	/* 38 */
/* 2510 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2512 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2514 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2516 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2518 */	NdrFcShort( 0x34 ),	/* 52 */
/* 2520 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2522 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x6,		/* 6 */
/* 2524 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2526 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2528 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2530 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 2532 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2534 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2536 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwInfoLevel */

/* 2538 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2540 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2542 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 2544 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2546 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2548 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter cbBufSize */

/* 2550 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2552 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2554 */	NdrFcShort( 0x486 ),	/* 1158 */

	/* Parameter pcbBytesNeeded */

/* 2556 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2558 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2560 */	NdrFcShort( 0x494 ),	/* Type Offset=1172 */

	/* Return value */

/* 2562 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2564 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2566 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceConfig2W */

/* 2568 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2570 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2574 */	NdrFcShort( 0x27 ),	/* 39 */
/* 2576 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2578 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2580 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2582 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2584 */	NdrFcShort( 0x34 ),	/* 52 */
/* 2586 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2588 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x6,		/* 6 */
/* 2590 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2592 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2594 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2596 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 2598 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2600 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2602 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwInfoLevel */

/* 2604 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2606 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2608 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 2610 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2612 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2614 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter cbBufSize */

/* 2616 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2618 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2620 */	NdrFcShort( 0x49e ),	/* 1182 */

	/* Parameter pcbBytesNeeded */

/* 2622 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2624 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2626 */	NdrFcShort( 0x4ac ),	/* Type Offset=1196 */

	/* Return value */

/* 2628 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2630 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2632 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceStatusEx */

/* 2634 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2636 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2640 */	NdrFcShort( 0x28 ),	/* 40 */
/* 2642 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2644 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2646 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2648 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2650 */	NdrFcShort( 0x34 ),	/* 52 */
/* 2652 */	NdrFcShort( 0x24 ),	/* 36 */
/* 2654 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x6,		/* 6 */
/* 2656 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2658 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2660 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2662 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 2664 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2666 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2668 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter InfoLevel */

/* 2670 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2672 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2674 */	0xe,		/* FC_ENUM32 */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 2676 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2678 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2680 */	NdrFcShort( 0x2a ),	/* Type Offset=42 */

	/* Parameter cbBufSize */

/* 2682 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2684 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2686 */	NdrFcShort( 0x4b6 ),	/* 1206 */

	/* Parameter pcbBytesNeeded */

/* 2688 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2690 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2692 */	NdrFcShort( 0x4c4 ),	/* Type Offset=1220 */

	/* Return value */

/* 2694 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2696 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2698 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumServicesStatusExA */

/* 2700 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2702 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2706 */	NdrFcShort( 0x29 ),	/* 41 */
/* 2708 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 2710 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2712 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2714 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2716 */	NdrFcShort( 0x60 ),	/* 96 */
/* 2718 */	NdrFcShort( 0x5c ),	/* 92 */
/* 2720 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0xb,		/* 11 */
/* 2722 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2724 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2726 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2728 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2730 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2732 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2734 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter InfoLevel */

/* 2736 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2738 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2740 */	0xe,		/* FC_ENUM32 */
			0x0,		/* 0 */

	/* Parameter dwServiceType */

/* 2742 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2744 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2746 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceState */

/* 2748 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2750 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2752 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 2754 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2756 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2758 */	NdrFcShort( 0x4d2 ),	/* Type Offset=1234 */

	/* Parameter cbBufSize */

/* 2760 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2762 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2764 */	NdrFcShort( 0x4de ),	/* 1246 */

	/* Parameter pcbBytesNeeded */

/* 2766 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2768 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2770 */	NdrFcShort( 0x4ec ),	/* Type Offset=1260 */

	/* Parameter lpServicesReturned */

/* 2772 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2774 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 2776 */	NdrFcShort( 0x4fa ),	/* Type Offset=1274 */

	/* Parameter lpResumeIndex */

/* 2778 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 2780 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 2782 */	NdrFcShort( 0x504 ),	/* Type Offset=1284 */

	/* Parameter pszGroupName */

/* 2784 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2786 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 2788 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Return value */

/* 2790 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2792 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 2794 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure REnumServicesStatusExW */

/* 2796 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2798 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2802 */	NdrFcShort( 0x2a ),	/* 42 */
/* 2804 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 2806 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 2808 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2810 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 2812 */	NdrFcShort( 0x60 ),	/* 96 */
/* 2814 */	NdrFcShort( 0x5c ),	/* 92 */
/* 2816 */	0x47,		/* Oi2 Flags:  srv must size, clt must size, has return, has ext, */
			0xb,		/* 11 */
/* 2818 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 2820 */	NdrFcShort( 0x1 ),	/* 1 */
/* 2822 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2824 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hSCManager */

/* 2826 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 2828 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2830 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter InfoLevel */

/* 2832 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2834 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2836 */	0xe,		/* FC_ENUM32 */
			0x0,		/* 0 */

	/* Parameter dwServiceType */

/* 2838 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2840 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2842 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceState */

/* 2844 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2846 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2848 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter lpBuffer */

/* 2850 */	NdrFcShort( 0x113 ),	/* Flags:  must size, must free, out, simple ref, */
/* 2852 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2854 */	NdrFcShort( 0x4d2 ),	/* Type Offset=1234 */

	/* Parameter cbBufSize */

/* 2856 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 2858 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2860 */	NdrFcShort( 0x512 ),	/* 1298 */

	/* Parameter pcbBytesNeeded */

/* 2862 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2864 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2866 */	NdrFcShort( 0x520 ),	/* Type Offset=1312 */

	/* Parameter lpServicesReturned */

/* 2868 */	NdrFcShort( 0x2112 ),	/* Flags:  must free, out, simple ref, srv alloc size=8 */
/* 2870 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 2872 */	NdrFcShort( 0x52e ),	/* Type Offset=1326 */

	/* Parameter lpResumeIndex */

/* 2874 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 2876 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 2878 */	NdrFcShort( 0x538 ),	/* Type Offset=1336 */

	/* Parameter pszGroupName */

/* 2880 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2882 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 2884 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Return value */

/* 2886 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2888 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 2890 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RSendTSMessage */

/* 2892 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2894 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2898 */	NdrFcShort( 0x2b ),	/* 43 */
/* 2900 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2902 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 2904 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2906 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2908 */	NdrFcShort( 0x8 ),	/* 8 */
/* 2910 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x1,		/* 1 */
/* 2912 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 2914 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2916 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2918 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 2920 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 2922 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2924 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RCreateServiceWOW64A */


	/* Return value */

/* 2926 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 2928 */	NdrFcLong( 0x0 ),	/* 0 */
/* 2932 */	NdrFcShort( 0x2c ),	/* 44 */
/* 2934 */	NdrFcShort( 0x44 ),	/* x86 Stack size/offset = 68 */
/* 2936 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 2938 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 2940 */	NdrFcShort( 0x4c ),	/* 76 */
/* 2942 */	NdrFcShort( 0x5c ),	/* 92 */
/* 2944 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x10,		/* 16 */
/* 2946 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 2948 */	NdrFcShort( 0x0 ),	/* 0 */
/* 2950 */	NdrFcShort( 0x2 ),	/* 2 */
/* 2952 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 2954 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 2956 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 2958 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter lpServiceName */

/* 2960 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2962 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 2964 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpDisplayName */

/* 2966 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2968 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 2970 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwDesiredAccess */

/* 2972 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2974 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 2976 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceType */

/* 2978 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2980 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 2982 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwStartType */

/* 2984 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 2986 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 2988 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwErrorControl */

/* 2990 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 2992 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 2994 */	NdrFcShort( 0x1f2 ),	/* Type Offset=498 */

	/* Parameter lpBinaryPathName */

/* 2996 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 2998 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 3000 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpLoadOrderGroup */

/* 3002 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 3004 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 3006 */	NdrFcShort( 0x5e ),	/* Type Offset=94 */

	/* Parameter lpdwTagId */

/* 3008 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3010 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 3012 */	NdrFcShort( 0x7c ),	/* Type Offset=124 */

	/* Parameter lpDependencies */

/* 3014 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 3016 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 3018 */	NdrFcShort( 0x546 ),	/* 1350 */

	/* Parameter dwDependSize */

/* 3020 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3022 */	NdrFcShort( 0x30 ),	/* x86 Stack size/offset = 48 */
/* 3024 */	NdrFcShort( 0x1d8 ),	/* Type Offset=472 */

	/* Parameter lpServiceStartName */

/* 3026 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3028 */	NdrFcShort( 0x34 ),	/* x86 Stack size/offset = 52 */
/* 3030 */	NdrFcShort( 0xa4 ),	/* Type Offset=164 */

	/* Parameter lpPassword */

/* 3032 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 3034 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 3036 */	NdrFcShort( 0x550 ),	/* 1360 */

	/* Parameter dwPwSize */

/* 3038 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 3040 */	NdrFcShort( 0x3c ),	/* x86 Stack size/offset = 60 */
/* 3042 */	NdrFcShort( 0x134 ),	/* Type Offset=308 */

	/* Parameter lpServiceHandle */

/* 3044 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3046 */	NdrFcShort( 0x40 ),	/* x86 Stack size/offset = 64 */
/* 3048 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RCreateServiceWOW64W */


	/* Return value */

/* 3050 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3052 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3056 */	NdrFcShort( 0x2d ),	/* 45 */
/* 3058 */	NdrFcShort( 0x44 ),	/* x86 Stack size/offset = 68 */
/* 3060 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 3062 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3064 */	NdrFcShort( 0x4c ),	/* 76 */
/* 3066 */	NdrFcShort( 0x5c ),	/* 92 */
/* 3068 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x10,		/* 16 */
/* 3070 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 3072 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3074 */	NdrFcShort( 0x2 ),	/* 2 */
/* 3076 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 3078 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 3080 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3082 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter lpServiceName */

/* 3084 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3086 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3088 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpDisplayName */

/* 3090 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3092 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 3094 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwDesiredAccess */

/* 3096 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3098 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 3100 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwServiceType */

/* 3102 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3104 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 3106 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwStartType */

/* 3108 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3110 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 3112 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwErrorControl */

/* 3114 */	NdrFcShort( 0x10b ),	/* Flags:  must size, must free, in, simple ref, */
/* 3116 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 3118 */	NdrFcShort( 0x98 ),	/* Type Offset=152 */

	/* Parameter lpBinaryPathName */

/* 3120 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3122 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 3124 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpLoadOrderGroup */

/* 3126 */	NdrFcShort( 0x1a ),	/* Flags:  must free, in, out, */
/* 3128 */	NdrFcShort( 0x24 ),	/* x86 Stack size/offset = 36 */
/* 3130 */	NdrFcShort( 0x5e ),	/* Type Offset=94 */

	/* Parameter lpdwTagId */

/* 3132 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3134 */	NdrFcShort( 0x28 ),	/* x86 Stack size/offset = 40 */
/* 3136 */	NdrFcShort( 0x7c ),	/* Type Offset=124 */

	/* Parameter lpDependencies */

/* 3138 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 3140 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 3142 */	NdrFcShort( 0x55a ),	/* 1370 */

	/* Parameter dwDependSize */

/* 3144 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3146 */	NdrFcShort( 0x30 ),	/* x86 Stack size/offset = 48 */
/* 3148 */	NdrFcShort( 0x5a ),	/* Type Offset=90 */

	/* Parameter lpServiceStartName */

/* 3150 */	NdrFcShort( 0xb ),	/* Flags:  must size, must free, in, */
/* 3152 */	NdrFcShort( 0x34 ),	/* x86 Stack size/offset = 52 */
/* 3154 */	NdrFcShort( 0xa4 ),	/* Type Offset=164 */

	/* Parameter lpPassword */

/* 3156 */	NdrFcShort( 0x88 ),	/* Flags:  in, by val, */
/* 3158 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 3160 */	NdrFcShort( 0x564 ),	/* 1380 */

	/* Parameter dwPwSize */

/* 3162 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 3164 */	NdrFcShort( 0x3c ),	/* x86 Stack size/offset = 60 */
/* 3166 */	NdrFcShort( 0x134 ),	/* Type Offset=308 */

	/* Parameter lpServiceHandle */

/* 3168 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3170 */	NdrFcShort( 0x40 ),	/* x86 Stack size/offset = 64 */
/* 3172 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RQueryServiceTagInfo */


	/* Return value */

/* 3174 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3176 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3180 */	NdrFcShort( 0x2e ),	/* 46 */
/* 3182 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3184 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 3186 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3188 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3190 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3192 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x1,		/* 1 */
/* 3194 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3196 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3198 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3200 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 3202 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3204 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3206 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RNotifyServiceStatusChange */


	/* Return value */

/* 3208 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3210 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3214 */	NdrFcShort( 0x2f ),	/* 47 */
/* 3216 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 3218 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 3220 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3222 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 3224 */	NdrFcShort( 0x68 ),	/* 104 */
/* 3226 */	NdrFcShort( 0xa0 ),	/* 160 */
/* 3228 */	0x46,		/* Oi2 Flags:  clt must size, has return, has ext, */
			0x7,		/* 7 */
/* 3230 */	0x8,		/* 8 */
			0x5,		/* Ext Flags:  new corr desc, srv corr check, */
/* 3232 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3234 */	NdrFcShort( 0x1 ),	/* 1 */
/* 3236 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 3238 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 3240 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3242 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter NotifyParams */

/* 3244 */	NdrFcShort( 0x8b ),	/* Flags:  must size, must free, in, by val, */
/* 3246 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3248 */	NdrFcShort( 0x5e0 ),	/* Type Offset=1504 */

	/* Parameter pClientProcessGuid */

/* 3250 */	NdrFcShort( 0x10a ),	/* Flags:  must free, in, simple ref, */
/* 3252 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 3254 */	NdrFcShort( 0x5f8 ),	/* Type Offset=1528 */

	/* Parameter pSCMProcessGuid */

/* 3256 */	NdrFcShort( 0x4112 ),	/* Flags:  must free, out, simple ref, srv alloc size=16 */
/* 3258 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 3260 */	NdrFcShort( 0x5f8 ),	/* Type Offset=1528 */

	/* Parameter pfCreateRemoteQueue */

/* 3262 */	NdrFcShort( 0x2150 ),	/* Flags:  out, base type, simple ref, srv alloc size=8 */
/* 3264 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 3266 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter phNotify */

/* 3268 */	NdrFcShort( 0x110 ),	/* Flags:  out, simple ref, */
/* 3270 */	NdrFcShort( 0x18 ),	/* x86 Stack size/offset = 24 */
/* 3272 */	NdrFcShort( 0x60c ),	/* Type Offset=1548 */

	/* Return value */

/* 3274 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3276 */	NdrFcShort( 0x1c ),	/* x86 Stack size/offset = 28 */
/* 3278 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RGetNotifyResults */

/* 3280 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3282 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3286 */	NdrFcShort( 0x30 ),	/* 48 */
/* 3288 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 3290 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 3292 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3294 */	0x2,		/* 2 */
			0x0,		/* 0 */
/* 3296 */	NdrFcShort( 0x24 ),	/* 36 */
/* 3298 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3300 */	0x45,		/* Oi2 Flags:  srv must size, has return, has ext, */
			0x3,		/* 3 */
/* 3302 */	0x8,		/* 8 */
			0x3,		/* Ext Flags:  new corr desc, clt corr check, */
/* 3304 */	NdrFcShort( 0x2 ),	/* 2 */
/* 3306 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3308 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hNotify */

/* 3310 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 3312 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3314 */	NdrFcShort( 0x610 ),	/* Type Offset=1552 */

	/* Parameter ppNotifyParams */

/* 3316 */	NdrFcShort( 0x2013 ),	/* Flags:  must size, must free, out, srv alloc size=8 */
/* 3318 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3320 */	NdrFcShort( 0x614 ),	/* Type Offset=1556 */

	/* Return value */

/* 3322 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3324 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3326 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RCloseNotifyHandle */

/* 3328 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3330 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3334 */	NdrFcShort( 0x31 ),	/* 49 */
/* 3336 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 3338 */	0x30,		/* FC_BIND_CONTEXT */
			0xe0,		/* Ctxt flags:  via ptr, in, out, */
/* 3340 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3342 */	0x2,		/* 2 */
			0x0,		/* 0 */
/* 3344 */	NdrFcShort( 0x38 ),	/* 56 */
/* 3346 */	NdrFcShort( 0x5c ),	/* 92 */
/* 3348 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x3,		/* 3 */
/* 3350 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3352 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3354 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3356 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter phNotify */

/* 3358 */	NdrFcShort( 0x118 ),	/* Flags:  in, out, simple ref, */
/* 3360 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3362 */	NdrFcShort( 0x64e ),	/* Type Offset=1614 */

	/* Parameter pfApcFired */

/* 3364 */	NdrFcShort( 0x2150 ),	/* Flags:  out, base type, simple ref, srv alloc size=8 */
/* 3366 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3368 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 3370 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3372 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3374 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RControlServiceExA */

/* 3376 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3378 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3382 */	NdrFcShort( 0x32 ),	/* 50 */
/* 3384 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 3386 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 3388 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3390 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 3392 */	NdrFcShort( 0x34 ),	/* 52 */
/* 3394 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3396 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x4,		/* 4 */
/* 3398 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3400 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3402 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3404 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 3406 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 3408 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3410 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwControl */

/* 3412 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3414 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3416 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwInfoLevel */

/* 3418 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3420 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3422 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 3424 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3426 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 3428 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RControlServiceExW */

/* 3430 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3432 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3436 */	NdrFcShort( 0x33 ),	/* 51 */
/* 3438 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 3440 */	0x30,		/* FC_BIND_CONTEXT */
			0x40,		/* Ctxt flags:  in, */
/* 3442 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3444 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 3446 */	NdrFcShort( 0x34 ),	/* 52 */
/* 3448 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3450 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x4,		/* 4 */
/* 3452 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3454 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3456 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3458 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter hService */

/* 3460 */	NdrFcShort( 0x8 ),	/* Flags:  in, */
/* 3462 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3464 */	NdrFcShort( 0xa ),	/* Type Offset=10 */

	/* Parameter dwControl */

/* 3466 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3468 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3470 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Parameter dwInfoLevel */

/* 3472 */	NdrFcShort( 0x48 ),	/* Flags:  in, base type, */
/* 3474 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3476 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Return value */

/* 3478 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3480 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 3482 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RSendPnPMessage */

/* 3484 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3486 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3490 */	NdrFcShort( 0x34 ),	/* 52 */
/* 3492 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3494 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 3496 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3498 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3500 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3502 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x1,		/* 1 */
/* 3504 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3506 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3508 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3510 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 3512 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3514 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3516 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RValidatePnPService */


	/* Return value */

/* 3518 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3520 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3524 */	NdrFcShort( 0x35 ),	/* 53 */
/* 3526 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3528 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 3530 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3532 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3534 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3536 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x1,		/* 1 */
/* 3538 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3540 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3542 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3544 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 3546 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3548 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3550 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure ROpenServiceStatusHandle */


	/* Return value */

/* 3552 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3554 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3558 */	NdrFcShort( 0x36 ),	/* 54 */
/* 3560 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3562 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 3564 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3566 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3568 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3570 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x1,		/* 1 */
/* 3572 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3574 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3576 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3578 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 3580 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3582 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3584 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

	/* Procedure RFunction55 */


	/* Return value */

/* 3586 */	0x0,		/* 0 */
			0x48,		/* Old Flags:  */
/* 3588 */	NdrFcLong( 0x0 ),	/* 0 */
/* 3592 */	NdrFcShort( 0x37 ),	/* 55 */
/* 3594 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 3596 */	0x32,		/* FC_BIND_PRIMITIVE */
			0x0,		/* 0 */
/* 3598 */	NdrFcShort( 0x0 ),	/* x86 Stack size/offset = 0 */
/* 3600 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3602 */	NdrFcShort( 0x8 ),	/* 8 */
/* 3604 */	0x44,		/* Oi2 Flags:  has return, has ext, */
			0x1,		/* 1 */
/* 3606 */	0x8,		/* 8 */
			0x1,		/* Ext Flags:  new corr desc, */
/* 3608 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3610 */	NdrFcShort( 0x0 ),	/* 0 */
/* 3612 */	NdrFcShort( 0x0 ),	/* 0 */

	/* Parameter BindingHandle */

/* 3614 */	NdrFcShort( 0x70 ),	/* Flags:  out, return, base type, */
/* 3616 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 3618 */	0x8,		/* FC_LONG */
			0x0,		/* 0 */

			0x0
        }
    };

static const svcctl_MIDL_TYPE_FORMAT_STRING svcctl__MIDL_TypeFormatString =
    {
        0,
        {
			NdrFcShort( 0x0 ),	/* 0 */
/*  2 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/*  4 */	NdrFcShort( 0x2 ),	/* Offset= 2 (6) */
/*  6 */	0x30,		/* FC_BIND_CONTEXT */
			0xe1,		/* Ctxt flags:  via ptr, in, out, can't be null */
/*  8 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 10 */	0x30,		/* FC_BIND_CONTEXT */
			0x41,		/* Ctxt flags:  in, can't be null */
/* 12 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 14 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 16 */	NdrFcShort( 0x2 ),	/* Offset= 2 (18) */
/* 18 */	
			0x15,		/* FC_STRUCT */
			0x3,		/* 3 */
/* 20 */	NdrFcShort( 0x1c ),	/* 28 */
/* 22 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 24 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 26 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 28 */	0x8,		/* FC_LONG */
			0x5b,		/* FC_END */
/* 30 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 32 */	NdrFcShort( 0x2 ),	/* Offset= 2 (34) */
/* 34 */	0x30,		/* FC_BIND_CONTEXT */
			0xa0,		/* Ctxt flags:  via ptr, out, */
/* 36 */	0x1,		/* 1 */
			0x1,		/* 1 */
/* 38 */	
			0x11, 0x0,	/* FC_RP */
/* 40 */	NdrFcShort( 0x2 ),	/* Offset= 2 (42) */
/* 42 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 44 */	NdrFcShort( 0x1 ),	/* 1 */
/* 46 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 48 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 50 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 52 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 54 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 56 */	NdrFcLong( 0x0 ),	/* 0 */
/* 60 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 64 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 66 */	NdrFcShort( 0x2 ),	/* Offset= 2 (68) */
/* 68 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 70 */	NdrFcLong( 0x0 ),	/* 0 */
/* 74 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 78 */	
			0x11, 0x0,	/* FC_RP */
/* 80 */	NdrFcShort( 0xffc2 ),	/* Offset= -62 (18) */
/* 82 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 84 */	NdrFcShort( 0x2 ),	/* Offset= 2 (86) */
/* 86 */	0x30,		/* FC_BIND_CONTEXT */
			0xe1,		/* Ctxt flags:  via ptr, in, out, can't be null */
/* 88 */	0x1,		/* 1 */
			0x0,		/* 0 */
/* 90 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 92 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 94 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 96 */	0x8,		/* FC_LONG */
			0x5c,		/* FC_PAD */
/* 98 */	
			0x12, 0x0,	/* FC_UP */
/* 100 */	NdrFcShort( 0x2 ),	/* Offset= 2 (102) */
/* 102 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 104 */	NdrFcShort( 0x1 ),	/* 1 */
/* 106 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 108 */	NdrFcShort( 0x20 ),	/* x86 Stack size/offset = 32 */
/* 110 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 112 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 114 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 116 */	NdrFcLong( 0x0 ),	/* 0 */
/* 120 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 124 */	
			0x12, 0x0,	/* FC_UP */
/* 126 */	NdrFcShort( 0x2 ),	/* Offset= 2 (128) */
/* 128 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 130 */	NdrFcShort( 0x1 ),	/* 1 */
/* 132 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 134 */	NdrFcShort( 0x2c ),	/* x86 Stack size/offset = 44 */
/* 136 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 138 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 140 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 142 */	NdrFcLong( 0x0 ),	/* 0 */
/* 146 */	NdrFcLong( 0x202 ),	/* 514 */
/* 150 */	
			0x11, 0x8,	/* FC_RP [simple_pointer] */
/* 152 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 154 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 156 */	NdrFcLong( 0x0 ),	/* 0 */
/* 160 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 164 */	
			0x12, 0x0,	/* FC_UP */
/* 166 */	NdrFcShort( 0x2 ),	/* Offset= 2 (168) */
/* 168 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 170 */	NdrFcShort( 0x1 ),	/* 1 */
/* 172 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 174 */	NdrFcShort( 0x38 ),	/* x86 Stack size/offset = 56 */
/* 176 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 178 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 180 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 182 */	NdrFcLong( 0x0 ),	/* 0 */
/* 186 */	NdrFcLong( 0x202 ),	/* 514 */
/* 190 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 192 */	NdrFcShort( 0x2 ),	/* Offset= 2 (194) */
/* 194 */	0x30,		/* FC_BIND_CONTEXT */
			0xa0,		/* Ctxt flags:  via ptr, out, */
/* 196 */	0x0,		/* 0 */
			0x1,		/* 1 */
/* 198 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 200 */	NdrFcLong( 0x0 ),	/* 0 */
/* 204 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 208 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 210 */	NdrFcShort( 0x2 ),	/* Offset= 2 (212) */
/* 212 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 214 */	NdrFcLong( 0x0 ),	/* 0 */
/* 218 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 222 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 224 */	NdrFcShort( 0x2 ),	/* Offset= 2 (226) */
/* 226 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 228 */	NdrFcLong( 0x0 ),	/* 0 */
/* 232 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 236 */	
			0x11, 0x0,	/* FC_RP */
/* 238 */	NdrFcShort( 0x2 ),	/* Offset= 2 (240) */
/* 240 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 242 */	NdrFcShort( 0x1 ),	/* 1 */
/* 244 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 246 */	NdrFcShort( 0x10 ),	/* x86 Stack size/offset = 16 */
/* 248 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 250 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 252 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 254 */	NdrFcLong( 0x0 ),	/* 0 */
/* 258 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 262 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 264 */	NdrFcShort( 0x2 ),	/* Offset= 2 (266) */
/* 266 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 268 */	NdrFcLong( 0x0 ),	/* 0 */
/* 272 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 276 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 278 */	NdrFcShort( 0x2 ),	/* Offset= 2 (280) */
/* 280 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 282 */	NdrFcLong( 0x0 ),	/* 0 */
/* 286 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 290 */	
			0x12, 0x0,	/* FC_UP */
/* 292 */	NdrFcShort( 0x2 ),	/* Offset= 2 (294) */
/* 294 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 296 */	NdrFcLong( 0x0 ),	/* 0 */
/* 300 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 304 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 306 */	NdrFcShort( 0x2 ),	/* Offset= 2 (308) */
/* 308 */	0x30,		/* FC_BIND_CONTEXT */
			0xa0,		/* Ctxt flags:  via ptr, out, */
/* 310 */	0x0,		/* 0 */
			0x0,		/* 0 */
/* 312 */	
			0x11, 0x0,	/* FC_RP */
/* 314 */	NdrFcShort( 0x2 ),	/* Offset= 2 (316) */
/* 316 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 318 */	NdrFcShort( 0x1 ),	/* 1 */
/* 320 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 322 */	NdrFcShort( 0x8 ),	/* x86 Stack size/offset = 8 */
/* 324 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 326 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 328 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 330 */	NdrFcLong( 0x0 ),	/* 0 */
/* 334 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 338 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 340 */	NdrFcShort( 0x2 ),	/* Offset= 2 (342) */
/* 342 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 344 */	NdrFcLong( 0x0 ),	/* 0 */
/* 348 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 352 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 354 */	NdrFcLong( 0x0 ),	/* 0 */
/* 358 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 362 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 364 */	NdrFcShort( 0x2 ),	/* Offset= 2 (366) */
/* 366 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 368 */	NdrFcLong( 0x0 ),	/* 0 */
/* 372 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 376 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 378 */	NdrFcLong( 0x0 ),	/* 0 */
/* 382 */	NdrFcLong( 0x400 ),	/* 1024 */
/* 386 */	
			0x12, 0x0,	/* FC_UP */
/* 388 */	NdrFcShort( 0x16 ),	/* Offset= 22 (410) */
/* 390 */	
			0x16,		/* FC_PSTRUCT */
			0x3,		/* 3 */
/* 392 */	NdrFcShort( 0x4 ),	/* 4 */
/* 394 */	
			0x4b,		/* FC_PP */
			0x5c,		/* FC_PAD */
/* 396 */	
			0x46,		/* FC_NO_REPEAT */
			0x5c,		/* FC_PAD */
/* 398 */	NdrFcShort( 0x0 ),	/* 0 */
/* 400 */	NdrFcShort( 0x0 ),	/* 0 */
/* 402 */	0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 404 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 406 */	
			0x5b,		/* FC_END */

			0x8,		/* FC_LONG */
/* 408 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 410 */	
			0x1b,		/* FC_CARRAY */
			0x3,		/* 3 */
/* 412 */	NdrFcShort( 0x4 ),	/* 4 */
/* 414 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 416 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 418 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 420 */	
			0x4b,		/* FC_PP */
			0x5c,		/* FC_PAD */
/* 422 */	
			0x48,		/* FC_VARIABLE_REPEAT */
			0x49,		/* FC_FIXED_OFFSET */
/* 424 */	NdrFcShort( 0x4 ),	/* 4 */
/* 426 */	NdrFcShort( 0x0 ),	/* 0 */
/* 428 */	NdrFcShort( 0x1 ),	/* 1 */
/* 430 */	NdrFcShort( 0x0 ),	/* 0 */
/* 432 */	NdrFcShort( 0x0 ),	/* 0 */
/* 434 */	0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 436 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 438 */	
			0x5b,		/* FC_END */

			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 440 */	0x0,		/* 0 */
			NdrFcShort( 0xffcd ),	/* Offset= -51 (390) */
			0x5b,		/* FC_END */
/* 444 */	
			0x11, 0x0,	/* FC_RP */
/* 446 */	NdrFcShort( 0x2 ),	/* Offset= 2 (448) */
/* 448 */	
			0x25,		/* FC_C_WSTRING */
			0x44,		/* FC_STRING_SIZED */
/* 450 */	0x20,		/* Corr desc:  parameter,  */
			0x59,		/* FC_CALLBACK */
/* 452 */	NdrFcShort( 0x0 ),	/* 0 */
/* 454 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 456 */	
			0x11, 0x8,	/* FC_RP [simple_pointer] */
/* 458 */	0x8,		/* FC_LONG */
			0x5c,		/* FC_PAD */
/* 460 */	
			0x11, 0x0,	/* FC_RP */
/* 462 */	NdrFcShort( 0x2 ),	/* Offset= 2 (464) */
/* 464 */	
			0x25,		/* FC_C_WSTRING */
			0x44,		/* FC_STRING_SIZED */
/* 466 */	0x20,		/* Corr desc:  parameter,  */
			0x59,		/* FC_CALLBACK */
/* 468 */	NdrFcShort( 0x1 ),	/* 1 */
/* 470 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 472 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 474 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 476 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 478 */	NdrFcLong( 0x0 ),	/* 0 */
/* 482 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 486 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 488 */	NdrFcLong( 0x0 ),	/* 0 */
/* 492 */	NdrFcLong( 0x202 ),	/* 514 */
/* 496 */	
			0x11, 0x8,	/* FC_RP [simple_pointer] */
/* 498 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 500 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 502 */	NdrFcLong( 0x0 ),	/* 0 */
/* 506 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 510 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 512 */	NdrFcLong( 0x0 ),	/* 0 */
/* 516 */	NdrFcLong( 0x202 ),	/* 514 */
/* 520 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 522 */	NdrFcLong( 0x0 ),	/* 0 */
/* 526 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 530 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 532 */	NdrFcShort( 0x2 ),	/* Offset= 2 (534) */
/* 534 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 536 */	NdrFcLong( 0x0 ),	/* 0 */
/* 540 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 544 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 546 */	NdrFcShort( 0x2 ),	/* Offset= 2 (548) */
/* 548 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 550 */	NdrFcLong( 0x0 ),	/* 0 */
/* 554 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 558 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 560 */	NdrFcLong( 0x0 ),	/* 0 */
/* 564 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 568 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 570 */	NdrFcShort( 0x2 ),	/* Offset= 2 (572) */
/* 572 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 574 */	NdrFcLong( 0x0 ),	/* 0 */
/* 578 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 582 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 584 */	NdrFcShort( 0x2 ),	/* Offset= 2 (586) */
/* 586 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 588 */	NdrFcLong( 0x0 ),	/* 0 */
/* 592 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 596 */	
			0x12, 0x0,	/* FC_UP */
/* 598 */	NdrFcShort( 0x2 ),	/* Offset= 2 (600) */
/* 600 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 602 */	NdrFcLong( 0x0 ),	/* 0 */
/* 606 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 610 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 612 */	NdrFcLong( 0x0 ),	/* 0 */
/* 616 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 620 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 622 */	NdrFcShort( 0x2 ),	/* Offset= 2 (624) */
/* 624 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 626 */	NdrFcLong( 0x0 ),	/* 0 */
/* 630 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 634 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 636 */	NdrFcLong( 0x0 ),	/* 0 */
/* 640 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 644 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 646 */	NdrFcShort( 0x2 ),	/* Offset= 2 (648) */
/* 648 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 650 */	NdrFcLong( 0x0 ),	/* 0 */
/* 654 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 658 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 660 */	NdrFcLong( 0x0 ),	/* 0 */
/* 664 */	NdrFcLong( 0x400 ),	/* 1024 */
/* 668 */	
			0x12, 0x0,	/* FC_UP */
/* 670 */	NdrFcShort( 0x16 ),	/* Offset= 22 (692) */
/* 672 */	
			0x16,		/* FC_PSTRUCT */
			0x3,		/* 3 */
/* 674 */	NdrFcShort( 0x4 ),	/* 4 */
/* 676 */	
			0x4b,		/* FC_PP */
			0x5c,		/* FC_PAD */
/* 678 */	
			0x46,		/* FC_NO_REPEAT */
			0x5c,		/* FC_PAD */
/* 680 */	NdrFcShort( 0x0 ),	/* 0 */
/* 682 */	NdrFcShort( 0x0 ),	/* 0 */
/* 684 */	0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 686 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 688 */	
			0x5b,		/* FC_END */

			0x8,		/* FC_LONG */
/* 690 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 692 */	
			0x1b,		/* FC_CARRAY */
			0x3,		/* 3 */
/* 694 */	NdrFcShort( 0x4 ),	/* 4 */
/* 696 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 698 */	NdrFcShort( 0x4 ),	/* x86 Stack size/offset = 4 */
/* 700 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 702 */	
			0x4b,		/* FC_PP */
			0x5c,		/* FC_PAD */
/* 704 */	
			0x48,		/* FC_VARIABLE_REPEAT */
			0x49,		/* FC_FIXED_OFFSET */
/* 706 */	NdrFcShort( 0x4 ),	/* 4 */
/* 708 */	NdrFcShort( 0x0 ),	/* 0 */
/* 710 */	NdrFcShort( 0x1 ),	/* 1 */
/* 712 */	NdrFcShort( 0x0 ),	/* 0 */
/* 714 */	NdrFcShort( 0x0 ),	/* 0 */
/* 716 */	0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 718 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 720 */	
			0x5b,		/* FC_END */

			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 722 */	0x0,		/* 0 */
			NdrFcShort( 0xffcd ),	/* Offset= -51 (672) */
			0x5b,		/* FC_END */
/* 726 */	
			0x11, 0x0,	/* FC_RP */
/* 728 */	NdrFcShort( 0x2 ),	/* Offset= 2 (730) */
/* 730 */	
			0x22,		/* FC_C_CSTRING */
			0x44,		/* FC_STRING_SIZED */
/* 732 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x54,		/* FC_DEREFERENCE */
/* 734 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 736 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 738 */	
			0x11, 0x0,	/* FC_RP */
/* 740 */	NdrFcShort( 0x2 ),	/* Offset= 2 (742) */
/* 742 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 744 */	NdrFcLong( 0x0 ),	/* 0 */
/* 748 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 752 */	
			0x11, 0x0,	/* FC_RP */
/* 754 */	NdrFcShort( 0x2 ),	/* Offset= 2 (756) */
/* 756 */	
			0x22,		/* FC_C_CSTRING */
			0x44,		/* FC_STRING_SIZED */
/* 758 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x54,		/* FC_DEREFERENCE */
/* 760 */	NdrFcShort( 0xc ),	/* x86 Stack size/offset = 12 */
/* 762 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 764 */	
			0x11, 0x0,	/* FC_RP */
/* 766 */	NdrFcShort( 0x2 ),	/* Offset= 2 (768) */
/* 768 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 770 */	NdrFcLong( 0x0 ),	/* 0 */
/* 774 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 778 */	
			0x11, 0xc,	/* FC_RP [alloced_on_stack] [simple_pointer] */
/* 780 */	0x8,		/* FC_LONG */
			0x5c,		/* FC_PAD */
/* 782 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 784 */	NdrFcLong( 0x0 ),	/* 0 */
/* 788 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 792 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 794 */	NdrFcShort( 0x2 ),	/* Offset= 2 (796) */
/* 796 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 798 */	NdrFcLong( 0x0 ),	/* 0 */
/* 802 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 806 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 808 */	NdrFcShort( 0x2 ),	/* Offset= 2 (810) */
/* 810 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 812 */	NdrFcLong( 0x0 ),	/* 0 */
/* 816 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 820 */	
			0x12, 0x0,	/* FC_UP */
/* 822 */	NdrFcShort( 0x2 ),	/* Offset= 2 (824) */
/* 824 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 826 */	NdrFcLong( 0x0 ),	/* 0 */
/* 830 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 834 */	
			0x2b,		/* FC_NON_ENCAPSULATED_UNION */
			0x9,		/* FC_ULONG */
/* 836 */	0x9,		/* Corr desc: FC_ULONG */
			0x0,		/*  */
/* 838 */	NdrFcShort( 0xfffc ),	/* -4 */
/* 840 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 842 */	NdrFcShort( 0x2 ),	/* Offset= 2 (844) */
/* 844 */	NdrFcShort( 0x4 ),	/* 4 */
/* 846 */	NdrFcShort( 0x7 ),	/* 7 */
/* 848 */	NdrFcLong( 0x1 ),	/* 1 */
/* 852 */	NdrFcShort( 0x28 ),	/* Offset= 40 (892) */
/* 854 */	NdrFcLong( 0x2 ),	/* 2 */
/* 858 */	NdrFcShort( 0x26 ),	/* Offset= 38 (896) */
/* 860 */	NdrFcLong( 0x3 ),	/* 3 */
/* 864 */	NdrFcShort( 0x64 ),	/* Offset= 100 (964) */
/* 866 */	NdrFcLong( 0x4 ),	/* 4 */
/* 870 */	NdrFcShort( 0x5e ),	/* Offset= 94 (964) */
/* 872 */	NdrFcLong( 0x5 ),	/* 5 */
/* 876 */	NdrFcShort( 0x58 ),	/* Offset= 88 (964) */
/* 878 */	NdrFcLong( 0x6 ),	/* 6 */
/* 882 */	NdrFcShort( 0x5c ),	/* Offset= 92 (974) */
/* 884 */	NdrFcLong( 0x7 ),	/* 7 */
/* 888 */	NdrFcShort( 0x4c ),	/* Offset= 76 (964) */
/* 890 */	NdrFcShort( 0xffff ),	/* Offset= -1 (889) */
/* 892 */	
			0x12, 0x0,	/* FC_UP */
/* 894 */	NdrFcShort( 0xff22 ),	/* Offset= -222 (672) */
/* 896 */	
			0x12, 0x0,	/* FC_UP */
/* 898 */	NdrFcShort( 0x24 ),	/* Offset= 36 (934) */
/* 900 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 902 */	NdrFcLong( 0x0 ),	/* 0 */
/* 906 */	NdrFcLong( 0x400 ),	/* 1024 */
/* 910 */	
			0x15,		/* FC_STRUCT */
			0x3,		/* 3 */
/* 912 */	NdrFcShort( 0x8 ),	/* 8 */
/* 914 */	0xe,		/* FC_ENUM32 */
			0x8,		/* FC_LONG */
/* 916 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 918 */	
			0x1b,		/* FC_CARRAY */
			0x3,		/* 3 */
/* 920 */	NdrFcShort( 0x8 ),	/* 8 */
/* 922 */	0x19,		/* Corr desc:  field pointer, FC_ULONG */
			0x0,		/*  */
/* 924 */	NdrFcShort( 0xc ),	/* 12 */
/* 926 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 928 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 930 */	NdrFcShort( 0xffec ),	/* Offset= -20 (910) */
/* 932 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 934 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 936 */	NdrFcShort( 0x14 ),	/* 20 */
/* 938 */	NdrFcShort( 0x0 ),	/* 0 */
/* 940 */	NdrFcShort( 0xc ),	/* Offset= 12 (952) */
/* 942 */	0x8,		/* FC_LONG */
			0x36,		/* FC_POINTER */
/* 944 */	0x36,		/* FC_POINTER */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 946 */	0x0,		/* 0 */
			NdrFcShort( 0xffd1 ),	/* Offset= -47 (900) */
			0x36,		/* FC_POINTER */
/* 950 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 952 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 954 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 956 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 958 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 960 */	
			0x12, 0x0,	/* FC_UP */
/* 962 */	NdrFcShort( 0xffd4 ),	/* Offset= -44 (918) */
/* 964 */	
			0x12, 0x0,	/* FC_UP */
/* 966 */	NdrFcShort( 0x2 ),	/* Offset= 2 (968) */
/* 968 */	
			0x15,		/* FC_STRUCT */
			0x3,		/* 3 */
/* 970 */	NdrFcShort( 0x4 ),	/* 4 */
/* 972 */	0x8,		/* FC_LONG */
			0x5b,		/* FC_END */
/* 974 */	
			0x12, 0x0,	/* FC_UP */
/* 976 */	NdrFcShort( 0x18 ),	/* Offset= 24 (1000) */
/* 978 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 980 */	NdrFcLong( 0x0 ),	/* 0 */
/* 984 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 988 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 990 */	NdrFcShort( 0x1 ),	/* 1 */
/* 992 */	0x19,		/* Corr desc:  field pointer, FC_ULONG */
			0x0,		/*  */
/* 994 */	NdrFcShort( 0x0 ),	/* 0 */
/* 996 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 998 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 1000 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 1002 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1004 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1006 */	NdrFcShort( 0x8 ),	/* Offset= 8 (1014) */
/* 1008 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 1010 */	NdrFcShort( 0xffe0 ),	/* Offset= -32 (978) */
/* 1012 */	0x36,		/* FC_POINTER */
			0x5b,		/* FC_END */
/* 1014 */	
			0x12, 0x0,	/* FC_UP */
/* 1016 */	NdrFcShort( 0xffe4 ),	/* Offset= -28 (988) */
/* 1018 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 1020 */	NdrFcShort( 0xc ),	/* 12 */
/* 1022 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1024 */	NdrFcShort( 0xa ),	/* Offset= 10 (1034) */
/* 1026 */	0x8,		/* FC_LONG */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1028 */	0x0,		/* 0 */
			NdrFcShort( 0xff3d ),	/* Offset= -195 (834) */
			0x36,		/* FC_POINTER */
/* 1032 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 1034 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 1036 */	
			0x22,		/* FC_C_CSTRING */
			0x5c,		/* FC_PAD */
/* 1038 */	
			0x2b,		/* FC_NON_ENCAPSULATED_UNION */
			0x9,		/* FC_ULONG */
/* 1040 */	0x9,		/* Corr desc: FC_ULONG */
			0x0,		/*  */
/* 1042 */	NdrFcShort( 0xfffc ),	/* -4 */
/* 1044 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 1046 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1048) */
/* 1048 */	NdrFcShort( 0x4 ),	/* 4 */
/* 1050 */	NdrFcShort( 0x7 ),	/* 7 */
/* 1052 */	NdrFcLong( 0x1 ),	/* 1 */
/* 1056 */	NdrFcShort( 0x28 ),	/* Offset= 40 (1096) */
/* 1058 */	NdrFcLong( 0x2 ),	/* 2 */
/* 1062 */	NdrFcShort( 0x26 ),	/* Offset= 38 (1100) */
/* 1064 */	NdrFcLong( 0x3 ),	/* 3 */
/* 1068 */	NdrFcShort( 0xff98 ),	/* Offset= -104 (964) */
/* 1070 */	NdrFcLong( 0x4 ),	/* 4 */
/* 1074 */	NdrFcShort( 0xff92 ),	/* Offset= -110 (964) */
/* 1076 */	NdrFcLong( 0x5 ),	/* 5 */
/* 1080 */	NdrFcShort( 0xff8c ),	/* Offset= -116 (964) */
/* 1082 */	NdrFcLong( 0x6 ),	/* 6 */
/* 1086 */	NdrFcShort( 0xff90 ),	/* Offset= -112 (974) */
/* 1088 */	NdrFcLong( 0x7 ),	/* 7 */
/* 1092 */	NdrFcShort( 0xff80 ),	/* Offset= -128 (964) */
/* 1094 */	NdrFcShort( 0xffff ),	/* Offset= -1 (1093) */
/* 1096 */	
			0x12, 0x0,	/* FC_UP */
/* 1098 */	NdrFcShort( 0xfd3c ),	/* Offset= -708 (390) */
/* 1100 */	
			0x12, 0x0,	/* FC_UP */
/* 1102 */	NdrFcShort( 0xc ),	/* Offset= 12 (1114) */
/* 1104 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1106 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1110 */	NdrFcLong( 0x400 ),	/* 1024 */
/* 1114 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 1116 */	NdrFcShort( 0x14 ),	/* 20 */
/* 1118 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1120 */	NdrFcShort( 0xc ),	/* Offset= 12 (1132) */
/* 1122 */	0x8,		/* FC_LONG */
			0x36,		/* FC_POINTER */
/* 1124 */	0x36,		/* FC_POINTER */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1126 */	0x0,		/* 0 */
			NdrFcShort( 0xffe9 ),	/* Offset= -23 (1104) */
			0x36,		/* FC_POINTER */
/* 1130 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 1132 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 1134 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 1136 */	
			0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 1138 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 1140 */	
			0x12, 0x0,	/* FC_UP */
/* 1142 */	NdrFcShort( 0xff20 ),	/* Offset= -224 (918) */
/* 1144 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 1146 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1148 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1150 */	NdrFcShort( 0x0 ),	/* Offset= 0 (1150) */
/* 1152 */	0x8,		/* FC_LONG */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1154 */	0x0,		/* 0 */
			NdrFcShort( 0xff8b ),	/* Offset= -117 (1038) */
			0x5b,		/* FC_END */
/* 1158 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1160 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1164 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 1168 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1170 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1172) */
/* 1172 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1174 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1178 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 1182 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1184 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1188 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 1192 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1194 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1196) */
/* 1196 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1198 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1202 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 1206 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1208 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1212 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 1216 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1218 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1220) */
/* 1220 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1222 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1226 */	NdrFcLong( 0x2000 ),	/* 8192 */
/* 1230 */	
			0x11, 0x0,	/* FC_RP */
/* 1232 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1234) */
/* 1234 */	
			0x1b,		/* FC_CARRAY */
			0x0,		/* 0 */
/* 1236 */	NdrFcShort( 0x1 ),	/* 1 */
/* 1238 */	0x29,		/* Corr desc:  parameter, FC_ULONG */
			0x0,		/*  */
/* 1240 */	NdrFcShort( 0x14 ),	/* x86 Stack size/offset = 20 */
/* 1242 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 1244 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 1246 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1248 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1252 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1256 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1258 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1260) */
/* 1260 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1262 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1266 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1270 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1272 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1274) */
/* 1274 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1276 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1280 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1284 */	
			0x12, 0x0,	/* FC_UP */
/* 1286 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1288) */
/* 1288 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1290 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1294 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1298 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1300 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1304 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1308 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1310 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1312) */
/* 1312 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1314 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1318 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1322 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1324 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1326) */
/* 1326 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1328 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1332 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1336 */	
			0x12, 0x0,	/* FC_UP */
/* 1338 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1340) */
/* 1340 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1342 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1346 */	NdrFcLong( 0x40000 ),	/* 262144 */
/* 1350 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1352 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1356 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 1360 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1362 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1366 */	NdrFcLong( 0x202 ),	/* 514 */
/* 1370 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1372 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1376 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 1380 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1382 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1386 */	NdrFcLong( 0x202 ),	/* 514 */
/* 1390 */	
			0x2b,		/* FC_NON_ENCAPSULATED_UNION */
			0x9,		/* FC_ULONG */
/* 1392 */	0x9,		/* Corr desc: FC_ULONG */
			0x0,		/*  */
/* 1394 */	NdrFcShort( 0xfffc ),	/* -4 */
/* 1396 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 1398 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1400) */
/* 1400 */	NdrFcShort( 0x4 ),	/* 4 */
/* 1402 */	NdrFcShort( 0x2 ),	/* 2 */
/* 1404 */	NdrFcLong( 0x1 ),	/* 1 */
/* 1408 */	NdrFcShort( 0xa ),	/* Offset= 10 (1418) */
/* 1410 */	NdrFcLong( 0x2 ),	/* 2 */
/* 1414 */	NdrFcShort( 0x32 ),	/* Offset= 50 (1464) */
/* 1416 */	NdrFcShort( 0xffff ),	/* Offset= -1 (1415) */
/* 1418 */	
			0x12, 0x0,	/* FC_UP */
/* 1420 */	NdrFcShort( 0x16 ),	/* Offset= 22 (1442) */
/* 1422 */	
			0x1d,		/* FC_SMFARRAY */
			0x0,		/* 0 */
/* 1424 */	NdrFcShort( 0x10 ),	/* 16 */
/* 1426 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 1428 */	
			0x15,		/* FC_STRUCT */
			0x3,		/* 3 */
/* 1430 */	NdrFcShort( 0x24 ),	/* 36 */
/* 1432 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 1434 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 1436 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 1438 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 1440 */	0x8,		/* FC_LONG */
			0x5b,		/* FC_END */
/* 1442 */	
			0x15,		/* FC_STRUCT */
			0x7,		/* 7 */
/* 1444 */	NdrFcShort( 0x58 ),	/* 88 */
/* 1446 */	0xb,		/* FC_HYPER */
			0x8,		/* FC_LONG */
/* 1448 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 1450 */	NdrFcShort( 0xffe4 ),	/* Offset= -28 (1422) */
/* 1452 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 1454 */	NdrFcShort( 0xffe0 ),	/* Offset= -32 (1422) */
/* 1456 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 1458 */	NdrFcShort( 0xffe2 ),	/* Offset= -30 (1428) */
/* 1460 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 1462 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 1464 */	
			0x12, 0x0,	/* FC_UP */
/* 1466 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1468) */
/* 1468 */	
			0x16,		/* FC_PSTRUCT */
			0x7,		/* 7 */
/* 1470 */	NdrFcShort( 0x60 ),	/* 96 */
/* 1472 */	
			0x4b,		/* FC_PP */
			0x5c,		/* FC_PAD */
/* 1474 */	
			0x46,		/* FC_NO_REPEAT */
			0x5c,		/* FC_PAD */
/* 1476 */	NdrFcShort( 0x5c ),	/* 92 */
/* 1478 */	NdrFcShort( 0x5c ),	/* 92 */
/* 1480 */	0x12, 0x8,	/* FC_UP [simple_pointer] */
/* 1482 */	
			0x25,		/* FC_C_WSTRING */
			0x5c,		/* FC_PAD */
/* 1484 */	
			0x5b,		/* FC_END */

			0xb,		/* FC_HYPER */
/* 1486 */	0x8,		/* FC_LONG */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1488 */	0x0,		/* 0 */
			NdrFcShort( 0xffbd ),	/* Offset= -67 (1422) */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1492 */	0x0,		/* 0 */
			NdrFcShort( 0xffb9 ),	/* Offset= -71 (1422) */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1496 */	0x0,		/* 0 */
			NdrFcShort( 0xffbb ),	/* Offset= -69 (1428) */
			0x8,		/* FC_LONG */
/* 1500 */	0x8,		/* FC_LONG */
			0x8,		/* FC_LONG */
/* 1502 */	0x8,		/* FC_LONG */
			0x5b,		/* FC_END */
/* 1504 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 1506 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1508 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1510 */	NdrFcShort( 0x0 ),	/* Offset= 0 (1510) */
/* 1512 */	0x8,		/* FC_LONG */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1514 */	0x0,		/* 0 */
			NdrFcShort( 0xff83 ),	/* Offset= -125 (1390) */
			0x5b,		/* FC_END */
/* 1518 */	
			0x11, 0x0,	/* FC_RP */
/* 1520 */	NdrFcShort( 0x8 ),	/* Offset= 8 (1528) */
/* 1522 */	
			0x1d,		/* FC_SMFARRAY */
			0x0,		/* 0 */
/* 1524 */	NdrFcShort( 0x8 ),	/* 8 */
/* 1526 */	0x2,		/* FC_CHAR */
			0x5b,		/* FC_END */
/* 1528 */	
			0x15,		/* FC_STRUCT */
			0x3,		/* 3 */
/* 1530 */	NdrFcShort( 0x10 ),	/* 16 */
/* 1532 */	0x8,		/* FC_LONG */
			0x6,		/* FC_SHORT */
/* 1534 */	0x6,		/* FC_SHORT */
			0x4c,		/* FC_EMBEDDED_COMPLEX */
/* 1536 */	0x0,		/* 0 */
			NdrFcShort( 0xfff1 ),	/* Offset= -15 (1522) */
			0x5b,		/* FC_END */
/* 1540 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1542 */	NdrFcShort( 0xfff2 ),	/* Offset= -14 (1528) */
/* 1544 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1546 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1548) */
/* 1548 */	0x30,		/* FC_BIND_CONTEXT */
			0xa0,		/* Ctxt flags:  via ptr, out, */
/* 1550 */	0x2,		/* 2 */
			0x1,		/* 1 */
/* 1552 */	0x30,		/* FC_BIND_CONTEXT */
			0x41,		/* Ctxt flags:  in, can't be null */
/* 1554 */	0x2,		/* 2 */
			0x0,		/* 0 */
/* 1556 */	
			0x11, 0x14,	/* FC_RP [alloced_on_stack] [pointer_deref] */
/* 1558 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1560) */
/* 1560 */	
			0x12, 0x0,	/* FC_UP */
/* 1562 */	NdrFcShort( 0x22 ),	/* Offset= 34 (1596) */
/* 1564 */	0xb7,		/* FC_RANGE */
			0x8,		/* 8 */
/* 1566 */	NdrFcLong( 0x0 ),	/* 0 */
/* 1570 */	NdrFcLong( 0x1000 ),	/* 4096 */
/* 1574 */	
			0x21,		/* FC_BOGUS_ARRAY */
			0x3,		/* 3 */
/* 1576 */	NdrFcShort( 0x0 ),	/* 0 */
/* 1578 */	0x9,		/* Corr desc: FC_ULONG */
			0x0,		/*  */
/* 1580 */	NdrFcShort( 0xfffc ),	/* -4 */
/* 1582 */	NdrFcShort( 0x1 ),	/* Corr flags:  early, */
/* 1584 */	NdrFcLong( 0xffffffff ),	/* -1 */
/* 1588 */	NdrFcShort( 0x0 ),	/* Corr flags:  */
/* 1590 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 1592 */	NdrFcShort( 0xffa8 ),	/* Offset= -88 (1504) */
/* 1594 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 1596 */	
			0x1a,		/* FC_BOGUS_STRUCT */
			0x3,		/* 3 */
/* 1598 */	NdrFcShort( 0x4 ),	/* 4 */
/* 1600 */	NdrFcShort( 0xffe6 ),	/* Offset= -26 (1574) */
/* 1602 */	NdrFcShort( 0x0 ),	/* Offset= 0 (1602) */
/* 1604 */	0x4c,		/* FC_EMBEDDED_COMPLEX */
			0x0,		/* 0 */
/* 1606 */	NdrFcShort( 0xffd6 ),	/* Offset= -42 (1564) */
/* 1608 */	0x5c,		/* FC_PAD */
			0x5b,		/* FC_END */
/* 1610 */	
			0x11, 0x4,	/* FC_RP [alloced_on_stack] */
/* 1612 */	NdrFcShort( 0x2 ),	/* Offset= 2 (1614) */
/* 1614 */	0x30,		/* FC_BIND_CONTEXT */
			0xe1,		/* Ctxt flags:  via ptr, in, out, can't be null */
/* 1616 */	0x2,		/* 2 */
			0x0,		/* 0 */

			0x0
        }
    };

static const GENERIC_BINDING_ROUTINE_PAIR BindingRoutines[ GENERIC_BINDING_TABLE_SIZE ] = 
        {
        {
            (GENERIC_BINDING_ROUTINE)RPC_SERVICE_STATUS_HANDLE_bind,
            (GENERIC_UNBIND_ROUTINE)RPC_SERVICE_STATUS_HANDLE_unbind
         }
        ,{
            (GENERIC_BINDING_ROUTINE)SVCCTL_HANDLEW_bind,
            (GENERIC_UNBIND_ROUTINE)SVCCTL_HANDLEW_unbind
         }
        ,{
            (GENERIC_BINDING_ROUTINE)SVCCTL_HANDLEA_bind,
            (GENERIC_UNBIND_ROUTINE)SVCCTL_HANDLEA_unbind
         }
        
        };


static void __RPC_USER svcctl_RGetServiceDisplayNameWExprEval_0000( PMIDL_STUB_MESSAGE pStubMsg )
{
    #pragma pack(4)
    struct _PARAM_STRUCT
        {
        
        /* [in] */ SC_RPC_HANDLE hSCManager;
        
        /* [string][in] */ LPCWSTR lpServiceName;
        
        /* [size_is][string][out] */ LPWSTR lpDisplayName;
        
        /* [out][in] */ DWORD *lpcchBuffer;
        DWORD _RetVal;
        };
    #pragma pack()
    struct _PARAM_STRUCT *pS	=	( struct _PARAM_STRUCT * )pStubMsg->StackTop;
    
    pStubMsg->Offset = 0;
    pStubMsg->MaxCount = ( unsigned long ) ( ( *pS->lpcchBuffer + 1 )  );
}

static void __RPC_USER svcctl_RGetServiceKeyNameWExprEval_0001( PMIDL_STUB_MESSAGE pStubMsg )
{
    #pragma pack(4)
    struct _PARAM_STRUCT
        {
        
        /* [in] */ SC_RPC_HANDLE hSCManager;
        
        /* [string][in] */ LPCWSTR lpDisplayName;
        
        /* [size_is][string][out] */ LPWSTR lpServiceName;
        
        /* [out][in] */ DWORD *lpcchBuffer;
        DWORD _RetVal;
        };
    #pragma pack()
    struct _PARAM_STRUCT *pS	=	( struct _PARAM_STRUCT * )pStubMsg->StackTop;
    
    pStubMsg->Offset = 0;
    pStubMsg->MaxCount = ( unsigned long ) ( ( *pS->lpcchBuffer + 1 )  );
}

static const EXPR_EVAL ExprEvalRoutines[] = 
    {
    svcctl_RGetServiceDisplayNameWExprEval_0000
    ,svcctl_RGetServiceKeyNameWExprEval_0001
    };


static const unsigned short svcctl_FormatStringOffsetTable[] =
    {
    0,
    42,
    96,
    138,
    186,
    252,
    312,
    360,
    408,
    450,
    498,
    564,
    678,
    810,
    882,
    966,
    1026,
    1086,
    1146,
    1206,
    1260,
    1320,
    1380,
    1446,
    1560,
    1692,
    1764,
    1848,
    1908,
    1968,
    2028,
    2088,
    2142,
    2202,
    2262,
    2316,
    2406,
    2454,
    2502,
    2568,
    2634,
    2700,
    2796,
    2892,
    2926,
    3050,
    3174,
    3208,
    3280,
    3328,
    3376,
    3430,
    3484,
    3518,
    3552,
    3586
    };


static const MIDL_STUB_DESC svcctl_StubDesc = 
    {
    (void *)& svcctl___RpcClientInterface,
    MIDL_user_allocate,
    MIDL_user_free,
    &svcctl__MIDL_AutoBindHandle,
    0,
    BindingRoutines,
    ExprEvalRoutines,
    0,
    svcctl__MIDL_TypeFormatString.Format,
    1, /* -error bounds_check flag */
    0x50002, /* Ndr library version */
    0,
    0x70001f4, /* MIDL Version 7.0.500 */
    0,
    0,
    0,  /* notify & notify_flag routine table */
    0x1, /* MIDL flag */
    0, /* cs routines */
    0,   /* proxy/server info */
    0
    };
#pragma optimize("", on )
#pragma warning(pop)