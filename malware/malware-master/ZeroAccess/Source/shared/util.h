/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       UTIL.H
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  ZeroAccess support routines header file.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

#define T_SIREFEF_DIRECTORY    L"\\Google\\Desktop\\Install\\{%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x}\\#.\\"
#define T_SHELL_FOLDERS_KEY    L"%wS\\Software\\Microsoft\\Windows\\CurrentVersion\\Explorer\\User Shell Folders"
#define T_LOCAL_APPDATA_VALUE  L"Local AppData"
#define T_GLOBAL_LINK          L"\\GLOBAL??\\"

typedef struct _ZA_BOT_PATH {
	WCHAR szBotPathLocal[MAX_PATH + 1];
	WCHAR szBotPathPFiles[MAX_PATH + 1];
} ZA_BOT_PATH, *PZA_BOT_PATH;

VOID SfuDecodeStream(
	_Inout_ unsigned char *stream,
	_In_ size_t size,
	_In_ unsigned long key
	);

BOOL SfuBuildBotPath(
	_Inout_ PZA_BOT_PATH Context
	);

ULONG_PTR SfuWriteBufferToFile(
	_In_ PWSTR lpFileName,
	_In_ PVOID Buffer,
	_In_ SIZE_T Size,
	_In_ BOOL Flush,
	_In_ BOOL Append
	);

BOOL SfuWhois(
	_In_ UINT_PTR WhoisSocket,
	_In_ ZA_PEERINFO *Peer,
	_Inout_ UNICODE_STRING *ReturnedInfo
	);

SOCKET SfuWhoisInit(
	VOID
	);

VOID SfuWhoisClose(
	_In_ SOCKET Socket
	);

BOOLEAN SfuCalcVolumeMD5(
	_Inout_ PBYTE MD5Hash
	);

PVOID SfuCreateFileMappingNoExec(
	_In_ LPWSTR lpFileName
	);

PVOID SfuGetSystemInfo(
	_In_ SYSTEM_INFORMATION_CLASS InfoClass
	);

BOOL SfuElevatePriv(
	VOID
	);

VOID SftListThreadPriv(
	VOID
	);

NTSTATUS SfuLoadPeerList(
	_In_ OBJECT_ATTRIBUTES *ObjectAttributes,
	_In_ ZA_PEERINFO **PeerList,
	_In_ PULONG NumberOfPeers
	);

BOOL SfuCreateDirectory(
	_In_ OBJECT_ATTRIBUTES *ObjectAttributes
	);
