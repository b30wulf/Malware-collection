

/* this ALWAYS GENERATED file contains the definitions for the interfaces */


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


/* verify that the <rpcndr.h> version is high enough to compile this file*/
#ifndef __REQUIRED_RPCNDR_H_VERSION__
#define __REQUIRED_RPCNDR_H_VERSION__ 475
#endif

#include "rpc.h"
#include "rpcndr.h"

#ifndef __RPCNDR_H_VERSION__
#error this stub requires an updated version of <rpcndr.h>
#endif // __RPCNDR_H_VERSION__


#ifndef __svcctl_h_h__
#define __svcctl_h_h__

#if defined(_MSC_VER) && (_MSC_VER >= 1020)
#pragma once
#endif

/* Forward Declarations */ 

#ifdef __cplusplus
extern "C"{
#endif 


/* interface __MIDL_itf_svcctl_0000_0000 */
/* [local] */ 

#ifndef _DTYP_IDL
#define _DTYP_IDL
#ifndef _WINDEF_H
typedef int BOOL;

typedef int *PBOOL;

typedef int *LPBOOL;

typedef unsigned char BYTE;

typedef unsigned char *PBYTE;

typedef unsigned char *LPBYTE;

typedef unsigned long DWORD;

typedef unsigned long *PDWORD;

typedef unsigned long *LPDWORD;

#endif
#ifndef _BASETSD_H
typedef unsigned int DWORD32;

typedef unsigned __int64 DWORD64;

#endif
#ifndef _WINDEF_H
typedef int INT;

typedef int *LPINT;

#endif
#ifndef _BASETSD_H
typedef signed char INT8;

typedef short INT16;

typedef int INT32;

typedef __int64 INT64;

#endif
#ifndef _BASETSD_H
typedef int LONG32;

typedef __int64 LONG64;

#endif
typedef unsigned __int64 QWORD;

#ifndef _WINNT_
typedef short SHORT;

#endif
typedef __int64 TIME;

#ifndef _WINNT_
typedef unsigned char CHAR;

typedef unsigned char *PCHAR;

typedef unsigned char UCHAR;

typedef unsigned char *PUCHAR;

#endif
#ifndef _WINDEF_H
typedef unsigned int UINT;

#endif
#ifndef _BASETSD_H
typedef unsigned char UINT8;

typedef unsigned short UINT16;

typedef unsigned int UINT32;

typedef unsigned __int64 UINT64;

#endif
#ifndef _WINNT_
typedef unsigned long ULONG;

typedef unsigned long *PULONG;

#endif
#ifndef _BASETSD_H
typedef unsigned int ULONG32;

typedef unsigned __int64 ULONG64;

#endif
#ifndef _WINNT_
typedef unsigned __int64 ULONGLONG;

typedef unsigned short USHORT;

#endif
#ifndef _WINDEF_H
typedef unsigned short WORD;

typedef unsigned short *PWORD;

typedef unsigned short *LPWORD;

#endif
#ifndef _WINNT_
typedef long LONG;

typedef long *PLONG;

#endif
#ifndef _WINDEF_H
typedef long *LPLONG;

#endif
#ifndef _WINNT_
typedef __int64 LONGLONG;

#endif
#ifndef _WINDEF_H
typedef float FLOAT;

#endif
#ifndef __wtypes_h__
typedef double DOUBLE;

#endif
#ifndef _WINNT_
typedef BYTE BOOLEAN;

typedef BYTE *PBOOLEAN;

#endif
#ifndef _BASETSD_H
typedef LONG LONG_PTR;

typedef ULONG ULONG_PTR;

typedef ULONG_PTR SIZE_T;

typedef ULONG_PTR DWORD_PTR;

#endif
typedef DWORD NET_API_STATUS;

#ifndef _WINNT_
typedef ULONGLONG DWORDLONG;

typedef ULONGLONG *PDWORDLONG;

#endif
typedef DWORD HCALL;

#ifndef _WINNT_
typedef void *HANDLE;

typedef void *PVOID;

#endif
#ifndef __WINE_RPCDCE_H
typedef void *RPC_BINDING_HANDLE;

#endif
typedef /* [context_handle] */ void *PCONTEXT_HANDLE;

typedef PCONTEXT_HANDLE *PPCONTEXT_HANDLE;

#ifndef _WINNT_
typedef wchar_t WCHAR;

typedef wchar_t *PWCHAR;

#if 0
typedef wchar_t UNICODE;

#endif
typedef const unsigned char *LPCSTR;

typedef const wchar_t *LPCWSTR;

typedef unsigned char *PSTR;

typedef unsigned char *LPSTR;

typedef wchar_t *LPWSTR;

typedef wchar_t *PWSTR;

#endif
typedef const wchar_t *LMCSTR;

typedef WCHAR *LMSTR;

#ifndef __wtypes_h__
typedef WCHAR *BSTR;

#endif
#if 0
typedef LPCSTR LPCTSTR;

typedef LPSTR LPTSTR;

typedef CHAR TCHAR;

#endif
#if 0
typedef struct _FILETIME
    {
    DWORD dwLowDateTime;
    DWORD dwHighDateTime;
    } 	FILETIME;

typedef struct _FILETIME *PFILETIME;

typedef struct _FILETIME *LPFILETIME;

typedef struct _GUID
    {
    DWORD Data1;
    WORD Data2;
    WORD Data3;
    BYTE Data4[ 8 ];
    } 	GUID;

typedef struct _GUID UUID;

typedef struct _GUID *PGUID;

typedef struct _LARGE_INTEGER
    {
    LONGLONG QuadPart;
    } 	LARGE_INTEGER;

typedef struct _LARGE_INTEGER *PLARGE_INTEGER;

typedef DWORD LCID;

#endif
typedef struct _RPC_UNICODE_STRING
    {
    USHORT Length;
    USHORT MaximumLength;
    LPWSTR Buffer;
    } 	RPC_UNICODE_STRING;

typedef struct _RPC_UNICODE_STRING *PRPC_UNICODE_STRING;

#if 0
typedef struct _SYSTEMTIME
    {
    WORD wYear;
    WORD wMonth;
    WORD wDayOfWeek;
    WORD wDay;
    WORD wHour;
    WORD wMinute;
    WORD wSecond;
    WORD wMilliseconds;
    } 	SYSTEMTIME;

typedef struct _SYSTEMTIME *PSYSTEMTIME;

typedef struct _UINT128
    {
    UINT64 lower;
    UINT64 upper;
    } 	UINT128;

typedef struct _UINT128 *PUINT128;

typedef struct _ULARGE_INTEGER
    {
    ULONGLONG QuadPart;
    } 	ULARGE_INTEGER;

typedef struct _ULARGE_INTEGER *PULARGE_INTEGER;

typedef struct _UNICODE_STRING
    {
    USHORT Length;
    USHORT MaximumLength;
    LPWSTR Buffer;
    } 	UNICODE_STRING;

typedef struct _UNICODE_STRING *PUNICODE_STRING;

#endif
#if 0
typedef struct _SID_IDENTIFIER_AUTHORITY
    {
    BYTE Value[ 6 ];
    } 	SID_IDENTIFIER_AUTHORITY;

typedef struct _SID
    {
    BYTE Revision;
    BYTE SubAuthorityCount;
    SID_IDENTIFIER_AUTHORITY IdentifierAuthority;
    DWORD SubAuthority[ 1 ];
    } 	SID;

typedef struct _SID *PSID;

typedef struct _ACCESS_MASK
    {
    DWORD ACCESS_MASK;
    } 	ACCESS_MASK;

typedef struct _ACCESS_MASK *PACCESS_MASK;

typedef struct _ACE_HEADER
    {
    UCHAR AceType;
    UCHAR AceFlags;
    USHORT AceSize;
    } 	ACE_HEADER;

typedef struct _ACE_HEADER *PACE_HEADER;

typedef struct _ACCESS_ALLOWED_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	ACCESS_ALLOWED_ACE;

typedef struct _ACCESS_ALLOWED_ACE *PACCESS_ALLOWED_ACE;

typedef struct _ACCESS_ALLOWED_OBJECT_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD Flags;
    GUID ObjectType;
    GUID InheritedObjectType;
    DWORD SidStart;
    } 	ACCESS_ALLOWED_OBJECT_ACE;

typedef struct _ACCESS_ALLOWED_OBJECT_ACE *PACCESS_ALLOWED_OBJECT_ACE;

typedef struct _ACCESS_DENIED_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	ACCESS_DENIED_ACE;

typedef struct _ACCESS_DENIED_ACE *PACCESS_DENIED_ACE;

typedef struct _ACCESS_ALLOWED_CALLBACK_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	ACCESS_ALLOWED_CALLBACK_ACE;

typedef struct _ACCESS_ALLOWED_CALLBACK_ACE *PACCESS_ALLOWED_CALLBACK_ACE;

typedef struct _ACCESS_DENIED_CALLBACK_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	ACCESS_DENIED_CALLBACK_ACE;

typedef struct _ACCESS_DENIED_CALLBACK_ACE *PACCESS_DENIED_CALLBACK_ACE;

typedef struct _ACCESS_ALLOWED_CALLBACK_OBJECT_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD Flags;
    GUID ObjectType;
    GUID InheritedObjectType;
    DWORD SidStart;
    } 	ACCESS_ALLOWED_CALLBACK_OBJECT_ACE;

typedef struct _ACCESS_ALLOWED_CALLBACK_OBJECT_ACE *PACCESS_ALLOWED_CALLBACK_OBJECT_ACE;

typedef struct _ACCESS_DENIED_CALLBACK_OBJECT_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD Flags;
    GUID ObjectType;
    GUID InheritedObjectType;
    DWORD SidStart;
    } 	ACCESS_DENIED_CALLBACK_OBJECT_ACE;

typedef struct _ACCESS_DENIED_CALLBACK_OBJECT_ACE *PACCESS_DENIED_CALLBACK_OBJECT_ACE;

typedef struct _SYSTEM_AUDIT_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	SYSTEM_AUDIT_ACE;

typedef struct _SYSTEM_AUDIT_ACE *PSYSTEM_AUDIT_ACE;

typedef struct _SYSTEM_AUDIT_CALLBACK_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	SYSTEM_AUDIT_CALLBACK_ACE;

typedef struct _SYSTEM_AUDIT_CALLBACK_ACE *PSYSTEM_AUDIT_CALLBACK_ACE;

typedef struct _SYSTEM_MANDATORY_LABEL_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD SidStart;
    } 	SYSTEM_MANDATORY_LABEL_ACE;

typedef struct _SYSTEM_MANDATORY_LABEL_ACE *PSYSTEM_MANDATORY_LABEL_ACE;

typedef struct _SYSTEM_AUDIT_CALLBACK_OBJECT_ACE
    {
    ACE_HEADER Header;
    ACCESS_MASK Mask;
    DWORD Flags;
    GUID ObjectType;
    GUID InheritedObjectType;
    DWORD SidStart;
    } 	SYSTEM_AUDIT_CALLBACK_OBJECT_ACE;

typedef struct _SYSTEM_AUDIT_CALLBACK_OBJECT_ACE *PSYSTEM_AUDIT_CALLBACK_OBJECT_ACE;

typedef struct _ACL
    {
    UCHAR AclRevision;
    UCHAR Sbz1;
    USHORT AclSize;
    USHORT AceCount;
    USHORT Sbz2;
    } 	ACL;

typedef struct _ACL *PACL;

typedef struct _SECURITY_DESCRIPTOR
    {
    UCHAR Revision;
    UCHAR Sbz1;
    USHORT Control;
    ULONG Owner;
    ULONG Group;
    ULONG Sacl;
    ULONG Dacl;
    } 	SECURITY_DESCRIPTOR;

typedef struct _SECURITY_DESCRIPTOR *PSECURITY_DESCRIPTOR;

typedef DWORD SECURITY_INFORMATION;

typedef DWORD *PSECURITY_INFORMATION;

#endif
typedef struct _RPC_SID
    {
    UCHAR Revision;
    UCHAR SubAuthorityCount;
    SID_IDENTIFIER_AUTHORITY IdentifierAuthority;
    DWORD SubAuthority[ 1 ];
    } 	RPC_SID;

typedef struct _RPC_SID *PRPC_SID;

#endif /* _DTYP_IDL */
#define	MAX_SERVICE_NAME_LENGTH	( 256 )

#define	SC_MAX_DEPEND_SIZE	( ( 4 * 1024 )  )

#define	SC_MAX_NAME_LENGTH	( ( MAX_SERVICE_NAME_LENGTH + 1 )  )

#define	SC_MAX_PATH_LENGTH	( ( 32 * 1024 )  )

#define	SC_MAX_PWD_SIZE	( 514 )

#define	SC_MAX_COMPUTER_NAME_LENGTH	( 1024 )

#define	SC_MAX_ACCOUNT_NAME_LENGTH	( ( 2 * 1024 )  )

#define	SC_MAX_COMMENT_LENGTH	( 128 )

#define	SC_MAX_ARGUMENT_LENGTH	( 1024 )

#define	SC_MAX_ARGUMENTS	( 1024 )

typedef /* [handle] */ LPSTR SVCCTL_HANDLEA;

typedef /* [handle] */ LPWSTR SVCCTL_HANDLEW;

typedef /* [handle] */ ULONG_PTR RPC_SERVICE_STATUS_HANDLE;

typedef /* [context_handle] */ PVOID SC_RPC_HANDLE;

typedef SC_RPC_HANDLE *LPSC_RPC_HANDLE;

typedef /* [context_handle] */ PVOID SC_RPC_LOCK;

typedef SC_RPC_LOCK *LPSC_RPC_LOCK;

typedef /* [context_handle] */ PVOID SC_NOTIFY_RPC_HANDLE;

typedef SC_NOTIFY_RPC_HANDLE *LPSC_NOTIFY_RPC_HANDLE;

typedef /* [range] */ DWORD BOUNDED_DWORD_4K;

typedef BOUNDED_DWORD_4K *LPBOUNDED_DWORD_4K;

typedef /* [range] */ DWORD BOUNDED_DWORD_8K;

typedef BOUNDED_DWORD_8K *LPBOUNDED_DWORD_8K;

typedef /* [range] */ DWORD BOUNDED_DWORD_256K;

typedef BOUNDED_DWORD_256K *LPBOUNDED_DWORD_256K;

#if 0
typedef struct _SERVICE_STATUS
    {
    DWORD dwServiceType;
    DWORD dwCurrentState;
    DWORD dwControlsAccepted;
    DWORD dwWin32ExitCode;
    DWORD dwServiceSpecificExitCode;
    DWORD dwCheckPoint;
    DWORD dwWaitHint;
    } 	SERVICE_STATUS;

typedef struct _SERVICE_STATUS *LPSERVICE_STATUS;

typedef struct _SERVICE_STATUS_PROCESS
    {
    DWORD dwServiceType;
    DWORD dwCurrentState;
    DWORD dwControlsAccepted;
    DWORD dwWin32ExitCode;
    DWORD dwServiceSpecificExitCode;
    DWORD dwCheckPoint;
    DWORD dwWaitHint;
    DWORD dwProcessId;
    DWORD dwServiceFlags;
    } 	SERVICE_STATUS_PROCESS;

typedef struct _SERVICE_STATUS_PROCESS *LPSERVICE_STATUS_PROCESS;

typedef struct _ENUM_SERVICE_STATUSA
    {
    LPSTR lpServiceName;
    LPSTR lpDisplayName;
    SERVICE_STATUS ServiceStatus;
    } 	ENUM_SERVICE_STATUSA;

typedef struct _ENUM_SERVICE_STATUSA *LPENUM_SERVICE_STATUSA;

typedef struct _ENUM_SERVICE_STATUSW
    {
    LPWSTR lpServiceName;
    LPWSTR lpDisplayName;
    SERVICE_STATUS ServiceStatus;
    } 	ENUM_SERVICE_STATUSW;

typedef struct _ENUM_SERVICE_STATUSW *LPENUM_SERVICE_STATUSW;

typedef struct _ENUM_SERVICE_STATUS_PROCESSA
    {
    LPSTR lpServiceName;
    LPSTR lpDisplayName;
    SERVICE_STATUS_PROCESS ServiceStatusProcess;
    } 	ENUM_SERVICE_STATUS_PROCESSA;

typedef struct _ENUM_SERVICE_STATUS_PROCESSA *LPENUM_SERVICE_STATUS_PROCESSA;

typedef struct _ENUM_SERVICE_STATUS_PROCESSW
    {
    LPWSTR lpServiceName;
    LPWSTR lpDisplayName;
    SERVICE_STATUS_PROCESS ServiceStatusProcess;
    } 	ENUM_SERVICE_STATUS_PROCESSW;

typedef struct _ENUM_SERVICE_STATUS_PROCESSW *LPENUM_SERVICE_STATUS_PROCESSW;

typedef struct _QUERY_SERVICE_CONFIGA
    {
    DWORD dwServiceType;
    DWORD dwStartType;
    DWORD dwErrorControl;
    LPSTR lpBinaryPathName;
    LPSTR lpLoadOrderGroup;
    DWORD dwTagId;
    LPSTR lpDependencies;
    LPSTR lpServiceStartName;
    LPSTR lpDisplayName;
    } 	QUERY_SERVICE_CONFIGA;

typedef struct _QUERY_SERVICE_CONFIGA *LPQUERY_SERVICE_CONFIGA;

typedef struct _QUERY_SERVICE_CONFIGW
    {
    DWORD dwServiceType;
    DWORD dwStartType;
    DWORD dwErrorControl;
    LPWSTR lpBinaryPathName;
    LPWSTR lpLoadOrderGroup;
    DWORD dwTagId;
    LPWSTR lpDependencies;
    LPWSTR lpServiceStartName;
    LPWSTR lpDisplayName;
    } 	QUERY_SERVICE_CONFIGW;

typedef struct _QUERY_SERVICE_CONFIGW *LPQUERY_SERVICE_CONFIGW;

typedef struct _QUERY_SERVICE_LOCK_STATUSA
    {
    DWORD fIsLocked;
    LPSTR lpLockOwner;
    DWORD dwLockDuration;
    } 	QUERY_SERVICE_LOCK_STATUSA;

typedef struct _QUERY_SERVICE_LOCK_STATUSA *LPQUERY_SERVICE_LOCK_STATUSA;

typedef struct _QUERY_SERVICE_LOCK_STATUSW
    {
    DWORD fIsLocked;
    LPWSTR lpLockOwner;
    DWORD dwLockDuration;
    } 	QUERY_SERVICE_LOCK_STATUSW;

typedef struct _QUERY_SERVICE_LOCK_STATUSW *LPQUERY_SERVICE_LOCK_STATUSW;

typedef /* [v1_enum] */ 
enum _SC_ACTION_TYPE
    {	SC_ACTION_NONE	= 0,
	SC_ACTION_RESTART	= 1,
	SC_ACTION_REBOOT	= 2,
	SC_ACTION_RUN_COMMAND	= 3
    } 	SC_ACTION_TYPE;

typedef struct _SC_ACTION
    {
    SC_ACTION_TYPE Type;
    DWORD Delay;
    } 	SC_ACTION;

typedef struct _SC_ACTION *LPSC_ACTION;

typedef /* [v1_enum] */ 
enum _SC_ENUM_TYPE
    {	SC_ENUM_PROCESS_INFO	= 0
    } 	SC_ENUM_TYPE;

typedef struct _SERVICE_DESCRIPTIONA
    {
    LPSTR lpDescription;
    } 	SERVICE_DESCRIPTIONA;

typedef struct _SERVICE_DESCRIPTIONA *LPSERVICE_DESCRIPTIONA;

typedef struct _SERVICE_DESCRIPTIONW
    {
    LPWSTR lpDescription;
    } 	SERVICE_DESCRIPTIONW;

typedef struct _SERVICE_DESCRIPTIONW *LPSERVICE_DESCRIPTIONW;

typedef struct _SERVICE_FAILURE_ACTIONSA
    {
    DWORD dwResetPeriod;
    LPSTR lpRebootMsg;
    LPSTR lpCommand;
    DWORD cActions;
    SC_ACTION *lpsaActions;
    } 	SERVICE_FAILURE_ACTIONSA;

typedef struct _SERVICE_FAILURE_ACTIONSA *LPSERVICE_FAILURE_ACTIONSA;

typedef struct _SERVICE_FAILURE_ACTIONSW
    {
    DWORD dwResetPeriod;
    LPWSTR lpRebootMsg;
    LPWSTR lpCommand;
    DWORD cActions;
    SC_ACTION *lpsaActions;
    } 	SERVICE_FAILURE_ACTIONSW;

typedef struct _SERVICE_FAILURE_ACTIONSW *LPSERVICE_FAILURE_ACTIONSW;


typedef struct _SERVICE_DELAYED_AUTO_START_INFO
    {
    BOOL fDelayedAutoStart;
    } 	SERVICE_DELAYED_AUTO_START_INFO;

typedef struct _SERVICE_DELAYED_AUTO_START_INFO *LPSERVICE_DELAYED_AUTO_START_INFO;

typedef struct _SERVICE_FAILURE_ACTIONS_FLAG
    {
    BOOL fFailureActionsOnNonCrashFailures;
    } 	SERVICE_FAILURE_ACTIONS_FLAG;

typedef struct _SERVICE_FAILURE_ACTIONS_FLAG *LPSERVICE_FAILURE_ACTIONS_FLAG;

typedef struct _SERVICE_SID_INFO
    {
    DWORD dwServicesSidType;
    } 	SERVICE_SID_INFO;

typedef struct _SERVICE_SID_INFO *LPSERVICE_SID_INFO;
#endif
typedef struct _SERVICE_RPC_REQUIRED_PRIVILEGES_INFO
    {
    DWORD cbRequiredPrivileges;
    PBYTE pRequiredPrivileges;
    } 	SERVICE_RPC_REQUIRED_PRIVILEGES_INFO;

typedef struct _SERVICE_RPC_REQUIRED_PRIVILEGES_INFO *LPSERVICE_RPC_REQUIRED_PRIVILEGES_INFO;

#define _SERVICE_PRESHUTDOWN_INFO_DEFINED_
#ifndef _SERVICE_PRESHUTDOWN_INFO_DEFINED_
#define _SERVICE_PRESHUTDOWN_INFO_DEFINED_
typedef struct _SERVICE_PRESHUTDOWN_INFO
    {
    DWORD dwPreshutdownTimeout;
    } 	SERVICE_PRESHUTDOWN_INFO;

typedef struct _SERVICE_PRESHUTDOWN_INFO *LPSERVICE_PRESHUTDOWN_INFO;

#endif

typedef struct _SC_RPC_CONFIG_INFOA
    {
    DWORD dwInfoLevel;
    /* [switch_type] */ union 
        {
        LPSERVICE_DESCRIPTIONA psd;
        LPSERVICE_FAILURE_ACTIONSA psfa;
        LPSERVICE_DELAYED_AUTO_START_INFO psda;
        LPSERVICE_FAILURE_ACTIONS_FLAG psfaf;
        LPSERVICE_SID_INFO pssid;
        LPSERVICE_RPC_REQUIRED_PRIVILEGES_INFO psrp;
        LPSERVICE_PRESHUTDOWN_INFO psps;
        } 	DUMMYUNIONNAME;
    LPSTR lpDescription;
    } 	SC_RPC_CONFIG_INFOA;

typedef struct _SC_RPC_CONFIG_INFOA *LPSC_RPC_CONFIG_INFOA;

typedef struct _SC_RPC_CONFIG_INFOW
    {
    DWORD dwInfoLevel;
    /* [switch_type] */ union 
        {
        LPSERVICE_DESCRIPTIONW psd;
        LPSERVICE_FAILURE_ACTIONSW psfa;
        LPSERVICE_DELAYED_AUTO_START_INFO psda;
        LPSERVICE_FAILURE_ACTIONS_FLAG psfaf;
        LPSERVICE_SID_INFO pssid;
        LPSERVICE_RPC_REQUIRED_PRIVILEGES_INFO psrp;
        LPSERVICE_PRESHUTDOWN_INFO psps;
        } 	DUMMYUNIONNAME;
    } 	SC_RPC_CONFIG_INFOW;

typedef struct _SC_RPC_CONFIG_INFOW *LPSC_RPC_CONFIG_INFOW;

typedef struct _SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_1
    {
    ULONGLONG ullThreadId;
    DWORD dwNotifyMask;
    UCHAR CallbackAddressArray[ 16 ];
    UCHAR CallbackParamAddressArray[ 16 ];
    SERVICE_STATUS_PROCESS ServiceStatus;
    DWORD dwNotificationStatus;
    DWORD dwSequence;
    } 	SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_1;

typedef struct _SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_1 *PSERVICE_NOTIFY_STATUS_CHANGE_PARAMS_1;

typedef struct _SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2
    {
    ULONGLONG ullThreadId;
    DWORD dwNotifyMask;
    UCHAR CallbackAddressArray[ 16 ];
    UCHAR CallbackParamAddressArray[ 16 ];
    SERVICE_STATUS_PROCESS ServiceStatus;
    DWORD dwNotificationStatus;
    DWORD dwSequence;
    DWORD dwNotificationTriggered;
    PWSTR pszServiceNames;
    } 	SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2;

typedef struct _SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2 *PSERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2;

typedef struct _SC_RPC_NOTIFY_PARAMS
    {
    DWORD dwInfoLevel;
    /* [switch_type] */ union 
        {
        PSERVICE_NOTIFY_STATUS_CHANGE_PARAMS_1 pStatusChangeParam1;
        PSERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2 pStatusChangeParam2;
        } 	;
    } 	SC_RPC_NOTIFY_PARAMS;

typedef struct _SC_RPC_NOTIFY_PARAMS *LPSC_RPC_NOTIFY_PARAMS;

typedef struct _SC_RPC_NOTIFY_PARAMS_LIST
    {
    BOUNDED_DWORD_4K cElements;
    SC_RPC_NOTIFY_PARAMS NotifyParamsArray[ 1 ];
    } 	SC_RPC_NOTIFY_PARAMS_LIST;

typedef struct _SC_RPC_NOTIFY_PARAMS_LIST *PSC_RPC_NOTIFY_PARAMS_LIST;

#if 0
typedef /* [v1_enum] */ 
enum _SC_STATUS_TYPE
    {	SC_STATUS_PROCESS_INFO	= 0
    } 	SC_STATUS_TYPE;

typedef struct _SERVICE_CONTROL_STATUS_REASON_IN_PARAMSA
    {
    DWORD dwReason;
    LPSTR pszComment;
    } 	SERVICE_CONTROL_STATUS_REASON_IN_PARAMSA;

typedef struct _SERVICE_CONTROL_STATUS_REASON_IN_PARAMSA *LPSERVICE_CONTROL_STATUS_REASON_IN_PARAMSA;

typedef struct _SERVICE_CONTROL_STATUS_REASON_IN_PARAMSW
    {
    DWORD dwReason;
    LPWSTR pszComment;
    } 	SERVICE_CONTROL_STATUS_REASON_IN_PARAMSW;

typedef struct _SERVICE_CONTROL_STATUS_REASON_IN_PARAMSW *LPSERVICE_CONTROL_STATUS_REASON_IN_PARAMSW;

typedef struct _SERVICE_CONTROL_STATUS_REASON_OUT_PARAMS
    {
    SERVICE_STATUS_PROCESS ServiceStatus;
    } 	SERVICE_CONTROL_STATUS_REASON_OUT_PARAMS;

typedef struct _SERVICE_CONTROL_STATUS_REASON_OUT_PARAMS *LPSERVICE_CONTROL_STATUS_REASON_OUT_PARAMS;

typedef SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2 SERVICE_NOTIFY_STATUS_CHANGE_PARAMS;

typedef SERVICE_NOTIFY_STATUS_CHANGE_PARAMS_2 *LPSERVICE_NOTIFY_STATUS_CHANGE_PARAMS;

#endif

typedef struct _STRING_PTRSA
    {
    LPSTR StringPtr;
    } 	STRING_PTRSA;

typedef struct _STRING_PTRSA *PSTRING_PTRSA;

typedef struct _STRING_PTRSA *LPSTRING_PTRSA;

typedef struct _STRING_PTRSW
    {
    LPWSTR StringPtr;
    } 	STRING_PTRSW;

typedef struct _STRING_PTRSW *PSTRING_PTRSW;

typedef struct _STRING_PTRSW *LPSTRING_PTRSW;

extern RPC_IF_HANDLE __MIDL_itf_svcctl_0000_0000_v0_0_c_ifspec;
extern RPC_IF_HANDLE __MIDL_itf_svcctl_0000_0000_v0_0_s_ifspec;

#ifndef __svcctl_INTERFACE_DEFINED__
#define __svcctl_INTERFACE_DEFINED__

/* interface svcctl */
/* [explicit_handle][endpoint][unique][version][uuid] */ 

DWORD RCloseServiceHandle( 
    /* [out][in] */ LPSC_RPC_HANDLE hSCObject);

DWORD RControlService( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwControl,
    /* [out] */ LPSERVICE_STATUS lpServiceStatus);

DWORD RDeleteService( 
    /* [in] */ SC_RPC_HANDLE hService);

DWORD RLockServiceDatabase( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [out] */ LPSC_RPC_LOCK lpLock);

DWORD RQueryServiceObjectSecurity( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SECURITY_INFORMATION dwSecurityInformation,
    /* [size_is][out] */ LPBYTE lpSecurityDescriptor,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded);

DWORD RSetServiceObjectSecurity( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwSecurityInformation,
    /* [size_is][in] */ LPBYTE lpSecurityDescriptor,
    /* [in] */ DWORD dwSecuityDescriptorSize);

DWORD RQueryServiceStatus( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [out] */ LPSERVICE_STATUS lpServiceStatus);

DWORD RSetServiceStatus( 
    /* [in] */ RPC_SERVICE_STATUS_HANDLE hServiceStatus,
    /* [in] */ LPSERVICE_STATUS lpServiceStatus);

DWORD RUnlockServiceDatabase( 
    /* [out][in] */ LPSC_RPC_LOCK Lock);

DWORD RNotifyBootConfigStatus( 
    /* [unique][string][in] */ SVCCTL_HANDLEW lpMachineName,
    /* [in] */ DWORD BootAcceptable);

DWORD RI_ScSetServiceBitsW( 
    /* [in] */ RPC_SERVICE_STATUS_HANDLE hServiceStatus,
    /* [in] */ DWORD dwServiceBits,
    /* [in] */ BOOL bSetBitsOn,
    /* [in] */ BOOL bUpdateImmediately,
    /* [unique][string][in] */ LPWSTR lpString);

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
    /* [unique][string][in] */ LPWSTR lpDisplayName);

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
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle);

DWORD REnumDependentServicesW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpServices,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned);

DWORD REnumServicesStatusW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD dwBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeHandle);

DWORD ROpenSCManagerW( 
    /* [unique][string][in] */ SVCCTL_HANDLEW lpMachineName,
    /* [unique][string][in] */ LPWSTR lpDatabaseName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpScHandle);

DWORD ROpenServiceW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPWSTR lpServiceName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle);

DWORD RQueryServiceConfigW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [size_is][out] */ LPBYTE lpServiceConfig,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded);

DWORD RQueryServiceLockStatusW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [size_is][out] */ LPBYTE lpLockStatus,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_4K pcbBytesNeeded);

DWORD RStartServiceW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [range][in] */ DWORD argc,
    /* [size_is][unique][in] */ LPSTRING_PTRSW argv);

DWORD RGetServiceDisplayNameW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCWSTR lpServiceName,
    /* [size_is][string][out] */ LPWSTR lpDisplayName,
    /* [out][in] */ DWORD *lpcchBuffer);

DWORD RGetServiceKeyNameW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCWSTR lpDisplayName,
    /* [size_is][string][out] */ LPWSTR lpServiceName,
    /* [out][in] */ DWORD *lpcchBuffer);

DWORD RI_ScSetServiceBitsA( 
    /* [in] */ RPC_SERVICE_STATUS_HANDLE hServiceStatus,
    /* [in] */ DWORD dwServiceBits,
    /* [in] */ BOOL bSetBitsOn,
    /* [in] */ BOOL bUpdateImmediately,
    /* [unique][string][in] */ LPSTR lpString);

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
    /* [unique][string][in] */ LPSTR lpDisplayName);

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
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle);

DWORD REnumDependentServicesA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpServices,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned);

DWORD REnumServicesStatusA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD dwBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeHandle);

DWORD ROpenSCManagerA( 
    /* [unique][string][in] */ SVCCTL_HANDLEA lpMachineName,
    /* [unique][string][in] */ LPSTR lpDatabaseName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpScHandle);

DWORD ROpenServiceA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPSTR lpServiceName,
    /* [in] */ DWORD dwDesiredAccess,
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle);

DWORD RQueryServiceConfigA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [size_is][out] */ LPBYTE lpServiceConfig,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded);

DWORD RQueryServiceLockStatusA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [size_is][out] */ LPBYTE lpLockStatus,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_4K pcbBytesNeeded);

DWORD RStartServiceA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [range][in] */ DWORD argc,
    /* [size_is][unique][in] */ LPSTRING_PTRSA argv);

DWORD RGetServiceDisplayNameA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCSTR lpServiceName,
    /* [size_is][string][out] */ LPSTR lpDisplayName,
    /* [out][in] */ LPBOUNDED_DWORD_4K lpcchBuffer);

DWORD RGetServiceKeyNameA( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [string][in] */ LPCSTR lpDisplayName,
    /* [size_is][string][out] */ LPSTR lpKeyName,
    /* [out][in] */ LPBOUNDED_DWORD_4K lpcchBuffer);

DWORD RI_ScGetCurrentGroupStateW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [unique][string][in] */ LPWSTR lpLoadOrderGroup,
    /* [out] */ LPDWORD lpState);

DWORD REnumServiceGroupW( 
    /* [in] */ SC_RPC_HANDLE hSCManager,
    /* [in] */ DWORD dwServiceType,
    /* [in] */ DWORD dwServiceState,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_256K pcbBytesNeeded,
    /* [out] */ LPBOUNDED_DWORD_256K lpServicesReturned,
    /* [unique][out][in] */ LPBOUNDED_DWORD_256K lpResumeIndex,
    /* [unique][string][in] */ LPCWSTR pszGroupName);

DWORD RChangeServiceConfig2A( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_RPC_CONFIG_INFOA Info);

DWORD RChangeServiceConfig2W( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_RPC_CONFIG_INFOW Info);

DWORD RQueryServiceConfig2A( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwInfoLevel,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded);

DWORD RQueryServiceConfig2W( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwInfoLevel,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded);

DWORD RQueryServiceStatusEx( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_STATUS_TYPE InfoLevel,
    /* [size_is][out] */ LPBYTE lpBuffer,
    /* [range][in] */ DWORD cbBufSize,
    /* [out] */ LPBOUNDED_DWORD_8K pcbBytesNeeded);

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
    /* [unique][string][in] */ LPCSTR pszGroupName);

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
    /* [unique][string][in] */ LPCWSTR pszGroupName);

DWORD RSendTSMessage( 
    /* [in] */ handle_t BindingHandle);

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
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle);

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
    /* [out] */ LPSC_RPC_HANDLE lpServiceHandle);

DWORD RQueryServiceTagInfo( 
    /* [in] */ handle_t BindingHandle);

DWORD RNotifyServiceStatusChange( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ SC_RPC_NOTIFY_PARAMS NotifyParams,
    /* [in] */ GUID *pClientProcessGuid,
    /* [out] */ GUID *pSCMProcessGuid,
    /* [out] */ PBOOL pfCreateRemoteQueue,
    /* [out] */ LPSC_NOTIFY_RPC_HANDLE phNotify);

DWORD RGetNotifyResults( 
    /* [in] */ SC_NOTIFY_RPC_HANDLE hNotify,
    /* [out] */ PSC_RPC_NOTIFY_PARAMS_LIST *ppNotifyParams);

DWORD RCloseNotifyHandle( 
    /* [out][in] */ LPSC_NOTIFY_RPC_HANDLE phNotify,
    /* [out] */ PBOOL pfApcFired);

DWORD RControlServiceExA( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwControl,
    /* [in] */ DWORD dwInfoLevel);

DWORD RControlServiceExW( 
    /* [in] */ SC_RPC_HANDLE hService,
    /* [in] */ DWORD dwControl,
    /* [in] */ DWORD dwInfoLevel);

DWORD RSendPnPMessage( 
    /* [in] */ handle_t BindingHandle);

DWORD RValidatePnPService( 
    /* [in] */ handle_t BindingHandle);

DWORD ROpenServiceStatusHandle( 
    /* [in] */ handle_t BindingHandle);

DWORD RFunction55( 
    /* [in] */ handle_t BindingHandle);



extern RPC_IF_HANDLE svcctl_v2_0_c_ifspec;
extern RPC_IF_HANDLE svcctl_v2_0_s_ifspec;
#endif /* __svcctl_INTERFACE_DEFINED__ */

/* Additional Prototypes for ALL interfaces */

handle_t __RPC_USER SVCCTL_HANDLEA_bind ( SVCCTL_HANDLEA );
void     __RPC_USER SVCCTL_HANDLEA_unbind( SVCCTL_HANDLEA,  handle_t );
handle_t __RPC_USER SVCCTL_HANDLEW_bind ( RPC_WSTR );
void     __RPC_USER SVCCTL_HANDLEW_unbind( SVCCTL_HANDLEW,  handle_t );
handle_t __RPC_USER RPC_SERVICE_STATUS_HANDLE_bind ( RPC_SERVICE_STATUS_HANDLE );
void     __RPC_USER RPC_SERVICE_STATUS_HANDLE_unbind( RPC_SERVICE_STATUS_HANDLE,  handle_t );

void __RPC_USER SC_RPC_HANDLE_rundown( SC_RPC_HANDLE );
void __RPC_USER SC_RPC_LOCK_rundown( SC_RPC_LOCK );
void __RPC_USER SC_NOTIFY_RPC_HANDLE_rundown( SC_NOTIFY_RPC_HANDLE );

/* end of Additional Prototypes */

#ifdef __cplusplus
}
#endif

#endif


