/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       UTIL.C
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  ZeroAccess support routines.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"

#pragma comment(lib, "ws2_32.lib")

/*
* SfuDecodeStream
*
* Purpose:
*
* Decode ZeroAccess stream using given key.
*
*/
VOID SfuDecodeStream(
	_Inout_ unsigned char *stream,
	_In_ size_t size,
	_In_ unsigned long key
	)
{
	unsigned long *p = (unsigned long *)stream;

	size >>= 2;
	while (size > 0) {
		*p ^= key;
		key = _rotl(key, 1);
		p++;
		size--;
	}
}

/*
* SfuWriteBufferToFile
*
* Purpose:
*
* Create new file (or open existing) and write (append) buffer to it.
*
*/
ULONG_PTR SfuWriteBufferToFile(
	_In_ PWSTR lpFileName,
	_In_ PVOID Buffer,
	_In_ SIZE_T Size,
	_In_ BOOL Flush,
	_In_ BOOL Append
	)
{
	NTSTATUS          Status;
	DWORD             dwFlag;
	HANDLE             hFile = NULL;
	OBJECT_ATTRIBUTES  attr;
	UNICODE_STRING     NtFileName;
	IO_STATUS_BLOCK    IoStatus;
	LARGE_INTEGER      Position;
	ACCESS_MASK        DesiredAccess;
	PLARGE_INTEGER     pPosition = NULL;
	ULONG_PTR          nBlocks, BlockIndex, BytesWritten = 0;
	ULONG              BlockSize, RemainingSize;
	PBYTE              ptr = (PBYTE)Buffer;

	if (RtlDosPathNameToNtPathName_U(lpFileName, &NtFileName, NULL, NULL) == FALSE)
		return 0;

	DesiredAccess = FILE_WRITE_ACCESS | SYNCHRONIZE;
	dwFlag = FILE_OVERWRITE_IF;

	if (Append == TRUE) {
		DesiredAccess |= FILE_READ_ACCESS;
		dwFlag = FILE_OPEN_IF;
	}

	InitializeObjectAttributes(&attr, &NtFileName, OBJ_CASE_INSENSITIVE, 0, NULL);

	__try {
		Status = NtCreateFile(&hFile, DesiredAccess, &attr,
			&IoStatus, NULL, FILE_ATTRIBUTE_NORMAL, 0, dwFlag,
			FILE_SYNCHRONOUS_IO_NONALERT | FILE_NON_DIRECTORY_FILE, NULL, 0);

		if (!NT_SUCCESS(Status))
			__leave;

		pPosition = NULL;

		if (Append == TRUE) {
			Position.LowPart = FILE_WRITE_TO_END_OF_FILE;
			Position.HighPart = -1;
			pPosition = &Position;
		}

		BlockSize = 0x7FFFFFFF;
		nBlocks = (Size / BlockSize);
		for (BlockIndex = 0; BlockIndex < nBlocks; BlockIndex++) {

			Status = NtWriteFile(hFile, 0, NULL, NULL, &IoStatus, ptr, BlockSize, pPosition, NULL);
			if (!NT_SUCCESS(Status))
				__leave;

			ptr += BlockSize;
			BytesWritten += IoStatus.Information;
		}
		RemainingSize = Size % BlockSize;
		if (RemainingSize != 0) {
			Status = NtWriteFile(hFile, 0, NULL, NULL, &IoStatus, ptr, RemainingSize, pPosition, NULL);
			if (!NT_SUCCESS(Status))
				__leave;
			BytesWritten += IoStatus.Information;
		}

	}
	__finally {
		if (hFile != NULL) {
			if (Flush == TRUE) NtFlushBuffersFile(hFile, &IoStatus);
			NtClose(hFile);
		}
		RtlFreeUnicodeString(&NtFileName);
	}
	return BytesWritten;
}

/*
* SfuQueryEnvironmentVariableOffset
*
* Purpose:
*
* Return offset to the given environment variable.
*
*/
LPWSTR SfuQueryEnvironmentVariableOffset(
	PUNICODE_STRING Value
	)
{
	UNICODE_STRING   str1;
	PWCHAR           EnvironmentBlock, ptr;

	EnvironmentBlock = RtlGetCurrentPeb()->ProcessParameters->Environment;
	ptr = EnvironmentBlock;

	do {
		if (*ptr == 0)
			return 0;

		RtlSecureZeroMemory(&str1, sizeof(str1));
		RtlInitUnicodeString(&str1, ptr);
		if (RtlPrefixUnicodeString(Value, &str1, TRUE))
			break;
		
		ptr += _strlen(ptr) + 1;

	} while (1);

	return (ptr + Value->Length / sizeof(WCHAR));
}

/*
* SfuBuildBotPath
*
* Purpose:
*
* Return full path to bot in both variants.
*
*/
BOOL SfuBuildBotPath(
	_Inout_ PZA_BOT_PATH Context
	)
{
	BOOL                           cond = FALSE, bResult = FALSE;
	OBJECT_ATTRIBUTES              obja;
	UNICODE_STRING                 ustr1, ustr2;
	WCHAR                          szRegBuffer[MAX_PATH + 1];
	HANDLE                         ProcessHeap; 
	HANDLE                         hKey = NULL;
	NTSTATUS                       status;
	KEY_VALUE_PARTIAL_INFORMATION *pki = NULL;
	LPWSTR                         lpEnv;
	ULONG                          memIO = 0;
	LPWSTR                         lpLocalBotName, lpPFilesBotName;
	PVOID                          Wow64Information = NULL;

	GUID sfGUID;

	if (Context == NULL)
		return bResult;

	ProcessHeap = RtlGetCurrentPeb()->ProcessHeap;

	RtlSecureZeroMemory(&ustr1, sizeof(ustr1));

	do {

		if (!SfInitMD5())
			break;

		RtlSecureZeroMemory(&sfGUID, sizeof(sfGUID));
		SfuCalcVolumeMD5((BYTE*)&sfGUID);

		status = NtQueryInformationProcess(NtCurrentProcess(), ProcessWow64Information, 
			&Wow64Information, sizeof(PVOID), NULL);
		if (!NT_SUCCESS(status))
			break;

		//query current user registry string
		if (!NT_SUCCESS(RtlFormatCurrentUserKeyPath(&ustr1)))
			break;

		lpLocalBotName = Context->szBotPathLocal;
		lpPFilesBotName = Context->szBotPathPFiles;

		RtlSecureZeroMemory(&szRegBuffer, sizeof(szRegBuffer));
		wsprintf(szRegBuffer, T_SHELL_FOLDERS_KEY, ustr1.Buffer);

		RtlFreeUnicodeString(&ustr1);
		
		//open User Shell Folders key to query Local AppData value
		RtlSecureZeroMemory(&ustr2, sizeof(ustr2));
		RtlInitUnicodeString(&ustr2, szRegBuffer);
		InitializeObjectAttributes(&obja, &ustr2, OBJ_CASE_INSENSITIVE, NULL, NULL);
		status = NtOpenKey(&hKey, KEY_READ, &obja);
		if (!NT_SUCCESS(status))
			break;

		//query value size
		RtlInitUnicodeString(&ustr2, T_LOCAL_APPDATA_VALUE);
		NtQueryValueKey(hKey, &ustr2, KeyValuePartialInformation,
			NULL, 0, &memIO);

		if (memIO == 0)
			break;

		pki = RtlAllocateHeap(ProcessHeap, HEAP_ZERO_MEMORY, memIO);
		if (pki == NULL)
			break;
		
		//query value
		status = NtQueryValueKey(hKey, &ustr2, KeyValuePartialInformation,
			pki, memIO, &memIO);

		if (!NT_SUCCESS(status)) 
			break;

		RtlInitUnicodeString(&ustr2, (WCHAR*)pki->Data);
		memIO = 0;

		//expand environment variable inside value
		RtlSecureZeroMemory(&szRegBuffer, sizeof(szRegBuffer));
		ustr1.Buffer = szRegBuffer;
		ustr1.Length = 0;
		ustr1.MaximumLength = sizeof(szRegBuffer);

		status = RtlExpandEnvironmentStrings_U(NULL, &ustr2, &ustr1, &memIO);
		if (!NT_SUCCESS(status)) {
			ustr1.Buffer = NULL;
			break;
		}

		//build result string
		_strcpy(lpLocalBotName, T_GLOBAL_LINK);
		_strcat(lpLocalBotName, szRegBuffer);
		
		wsprintf(_strend(lpLocalBotName), T_SIREFEF_DIRECTORY,
			sfGUID.Data1, sfGUID.Data2, sfGUID.Data3,
			sfGUID.Data4[0],
			sfGUID.Data4[1],
			sfGUID.Data4[2],
			sfGUID.Data4[3],
			sfGUID.Data4[4],
			sfGUID.Data4[5],
			sfGUID.Data4[6],
			sfGUID.Data4[7]);

		ustr1.Buffer = NULL;

		_strcpy(lpPFilesBotName, T_GLOBAL_LINK);
		
		if (Wow64Information == NULL) {
			lpEnv = L"ProgramFiles=";
		}
		else {
			lpEnv = L"ProgramFiles(x86)=";
		}

		RtlInitUnicodeString(&ustr2, lpEnv);
		lpEnv = SfuQueryEnvironmentVariableOffset(&ustr2);
		if (lpEnv) {
			_strcat(lpPFilesBotName, lpEnv);

			wsprintf(_strend(lpPFilesBotName), T_SIREFEF_DIRECTORY,
				sfGUID.Data1, sfGUID.Data2, sfGUID.Data3,
				sfGUID.Data4[0],
				sfGUID.Data4[1],
				sfGUID.Data4[2],
				sfGUID.Data4[3],
				sfGUID.Data4[4],
				sfGUID.Data4[5],
				sfGUID.Data4[6],
				sfGUID.Data4[7]);
		}

		bResult = TRUE;

	} while (cond);

	if (hKey != NULL) {
		NtClose(hKey);
	}

	if (ustr1.Buffer != NULL) {
		RtlFreeUnicodeString(&ustr1);
	}

	if (pki != NULL) {
		RtlFreeHeap(ProcessHeap, 0, pki);
	}
	return bResult;
}

/*
* SfuWhoisInit
*
* Purpose:
*
* Establish connection with freegeoip whois service.
*
*/
SOCKET SfuWhoisInit(
	VOID
	)
{
	SOCKET           Socket = 0;
	WSADATA          wsaData;
	struct addrinfo  *result = NULL;
	struct addrinfo  hints;
	struct addrinfo  *ptr = NULL;

	if (WSAStartup(MAKEWORD(2, 2), &wsaData) != 0) {
		return INVALID_SOCKET;
	}

	RtlSecureZeroMemory(&hints, sizeof(hints));
	hints.ai_family = AF_INET;
	hints.ai_socktype = SOCK_STREAM;
	
	if (getaddrinfo("freegeoip.net", "80", &hints, &result) != 0)
		return INVALID_SOCKET;

	for (ptr = result; ptr != NULL; ptr = ptr->ai_next) {
		if (ptr->ai_family == AF_INET) {

			Socket = socket(ptr->ai_family, ptr->ai_socktype, ptr->ai_protocol);
			if (Socket == INVALID_SOCKET)
				continue;

			if (connect(Socket, ptr->ai_addr, (int)ptr->ai_addrlen) == SOCKET_ERROR)
				continue;
			
			break;
		}
	}

	freeaddrinfo(ptr);
	return Socket;
}

/*
* SfuWhoisClose
*
* Purpose:
*
* Close whois request socket.
*
*/
VOID SfuWhoisClose(
	_In_ SOCKET Socket
	)
{
	if (Socket != INVALID_SOCKET) {
		closesocket(Socket);
	}
}

/*
* SfuWhois
*
* Purpose:
*
* Send whois query and return actual result data as unicode string.
*
*/
BOOL SfuWhois(
	_In_ UINT_PTR WhoisSocket,
	_In_ ZA_PEERINFO *Peer,
	_Inout_ UNICODE_STRING *ReturnedInfo
	)
{
	BYTE*               pIP;
	int                 r = 0;
	unsigned long	    p = 0, c, i;
	unsigned __int64	ContentLength = 0;
	char                Buffer[4096];

	ANSI_STRING Src;
	BOOL bResult = FALSE;

	if (
		(Peer == NULL) ||
		(WhoisSocket == INVALID_SOCKET) ||
		(ReturnedInfo == NULL)
		)
	{
		return bResult;
	}

	pIP = (BYTE*)&Peer->IP;
	
	RtlSecureZeroMemory(&Buffer, sizeof(Buffer));
	wsprintfA(Buffer, "GET /csv/%u.%u.%u.%u HTTP/1.1\r\nHost: freegeoip.net\r\nConnection: Keep-Alive\r\n\r\n",
		pIP[0], pIP[1], pIP[2], pIP[3]
		);

	send(WhoisSocket, Buffer, (DWORD)_strlen_a(Buffer), 0);

	do {
		RtlSecureZeroMemory(Buffer, sizeof(Buffer));

		r = recv(WhoisSocket, Buffer, 4096, 0);
		if (r <= 0) 
			break;

		if ((_strncmpi_a("HTTP/1.0 200 ", Buffer, 13) != 0) && (_strncmpi_a("HTTP/1.1 200 ", Buffer, 13) != 0))
			break;

		c = r;
		i = 0;
		do {
			p = i;
			while ((Buffer[i] != '\r') && (i < c))
				i++;
			if (p == i) {
				i += 2;
				break;
			}
			i += 2;
			if (_strncmpi_a("Content-Length: ", &Buffer[p], 16) == 0)
				ContentLength = strtou64_a(&Buffer[p + 16]);

		} while (i < c);

		if ((ContentLength < 20) || (ContentLength > 1024))
			break;

		RtlSecureZeroMemory(&Src, sizeof(Src));
		RtlInitString(&Src, &Buffer[i]);
		if (NT_SUCCESS(RtlAnsiStringToUnicodeString(ReturnedInfo, &Src, TRUE)))
			bResult = TRUE;

		r = 0;

	} while (r > 0);

	return bResult;
}

/*
* SfuCalcVolumeMD5
*
* Purpose:
*
* Calculate MD5 from system volume information.
*
*/
BOOLEAN SfuCalcVolumeMD5(
	_Inout_ PBYTE MD5Hash
	)
{
	OBJECT_ATTRIBUTES           obja;
	IO_STATUS_BLOCK             iost;
	UNICODE_STRING              str;
	NTSTATUS                    Status;
	BOOLEAN                     result = FALSE;
	HANDLE                      hVolume = NULL;
	FILE_FS_VOLUME_INFORMATION  fsVolumeInfo;
	MD5_CTX                     ctx;

	if (MD5Hash == NULL)
		return result;

	RtlSecureZeroMemory(&str, sizeof(str));
	RtlInitUnicodeString(&str, L"\\systemroot");
	InitializeObjectAttributes(&obja, &str, OBJ_CASE_INSENSITIVE, NULL, NULL);

	Status = NtOpenFile(&hVolume, FILE_GENERIC_READ, &obja, &iost,
		FILE_SHARE_READ | FILE_SHARE_WRITE | FILE_SHARE_DELETE, FILE_SYNCHRONOUS_IO_NONALERT);

	if (NT_SUCCESS(Status)) {

		Status = NtQueryVolumeInformationFile(hVolume, &iost, &fsVolumeInfo,
			sizeof(FILE_FS_VOLUME_INFORMATION), FileFsVolumeInformation);

		if ((NT_SUCCESS(Status) || Status == STATUS_BUFFER_OVERFLOW)) {
			fsVolumeInfo.VolumeCreationTime.HighPart ^= 0x1010101;
			MD5Init(&ctx);
			MD5Update(&ctx, (unsigned char*)&fsVolumeInfo.VolumeCreationTime, sizeof(LARGE_INTEGER));
			MD5Final(&ctx);
			RtlCopyMemory(MD5Hash, &ctx.buf, 16);
			result = TRUE;
		}
		NtClose(hVolume);
	}
	return result;
}

/*
* SfuCreateFileMappingNoExec
*
* Purpose:
*
* Map file as non executable image.
*
*/
PVOID SfuCreateFileMappingNoExec(
	_In_ LPWSTR lpFileName
	)
{
	BOOL                   cond = FALSE;
	NTSTATUS               status;
	UNICODE_STRING         usFileName;
	HANDLE                 hFile = NULL, hSection = NULL;
	OBJECT_ATTRIBUTES      obja;
	IO_STATUS_BLOCK        iost;
	SIZE_T                 ViewSize = 0;
	PVOID                  Data = NULL;

	RtlSecureZeroMemory(&usFileName, sizeof(usFileName));

	do {

		if (RtlDosPathNameToNtPathName_U(lpFileName, &usFileName, NULL, NULL) == FALSE)
			break;

		InitializeObjectAttributes(&obja, &usFileName, OBJ_CASE_INSENSITIVE, NULL, NULL);

		status = NtOpenFile(&hFile, FILE_READ_ACCESS | SYNCHRONIZE,
			&obja, &iost, FILE_SHARE_READ,
			FILE_NON_DIRECTORY_FILE | FILE_SYNCHRONOUS_IO_NONALERT);
		if (!NT_SUCCESS(status))
			break;

		status = NtCreateSection(&hSection, SECTION_ALL_ACCESS, NULL,
			NULL, PAGE_READONLY, SEC_IMAGE_NO_EXECUTE, hFile);
		if (!NT_SUCCESS(status))
			break;

		status = NtMapViewOfSection(hSection, NtCurrentProcess(),
			(PVOID)&Data, 0, 0, NULL, &ViewSize, ViewUnmap, 0, PAGE_READONLY);
		if (!NT_SUCCESS(status))
			break;

	} while (cond);

	if (hFile != NULL) {
		NtClose(hFile);
	}
	if (hSection != NULL) {
		NtClose(hSection);
	}
	if (usFileName.Buffer != NULL) {
		RtlFreeUnicodeString(&usFileName);
	}
	return Data;
}

/*
* SftListThreadPriv
*
* Purpose:
*
* Test unit for thread elevation check.
*
*/
VOID SftListThreadPriv(
	VOID
	)
{
	DWORD              dwLen;
	bool               bRes;
	HANDLE             hToken;
	BYTE               *Buffer;
	TOKEN_PRIVILEGES   *pPrivs;
	WCHAR              text[MAX_PATH];

	if (!OpenThreadToken(NtCurrentThread(), TOKEN_QUERY, FALSE, &hToken))
		return;

	dwLen = 0;
	bRes = GetTokenInformation(
		hToken,
		TokenPrivileges,
		NULL,
		0,
		&dwLen
		);

	Buffer = LocalAlloc(LPTR, dwLen);
	if (Buffer) {

		bRes = GetTokenInformation(
			hToken,
			TokenPrivileges,
			Buffer,
			dwLen,
			&dwLen
			);

		pPrivs = (TOKEN_PRIVILEGES*)Buffer;
		for (DWORD i = 0; i < pPrivs->PrivilegeCount; i++) {
			if (pPrivs->Privileges[i].Attributes & SE_PRIVILEGE_ENABLED) {
				text[0] = 0;
				ultostr(pPrivs->Privileges[i].Luid.LowPart, text);
				_strcat(text, TEXT("\r\n"));
				OutputDebugString(text);
			}
		}
		LocalFree(Buffer);
	}
	CloseHandle(hToken);
}

/*
* SfuGetSystemInfo
*
* Purpose:
*
* Wrapper for NtQuerySystemInformation.
*
*/
PVOID SfuGetSystemInfo(
	_In_ SYSTEM_INFORMATION_CLASS InfoClass
	)
{
	INT			c = 0;
	PVOID		Buffer = NULL;
	ULONG		Size = 0x1000;
	NTSTATUS	status;
	ULONG       memIO;
	PVOID       hHeap = NtCurrentPeb()->ProcessHeap;

	do {
		Buffer = RtlAllocateHeap(hHeap, HEAP_ZERO_MEMORY, (SIZE_T)Size);
		if (Buffer != NULL) {
			status = NtQuerySystemInformation(InfoClass, Buffer, Size, &memIO);
		}
		else {
			return NULL;
		}
		if (status == STATUS_INFO_LENGTH_MISMATCH) {
			RtlFreeHeap(hHeap, 0, Buffer);
			Size *= 2;
			c++;
			if (c > 100) {
				status = STATUS_SECRET_TOO_LONG;
				break;
			}
		}
	} while (status == STATUS_INFO_LENGTH_MISMATCH);

	if (NT_SUCCESS(status)) {
		return Buffer;
	}

	if (Buffer) {
		RtlFreeHeap(hHeap, 0, Buffer);
	}
	return NULL;
}

/*
* SfuAdjustCurrentThreadPriv
*
* Purpose:
*
* Impersonate thread and adjust privileges.
*
*/
BOOL SfuAdjustCurrentThreadPriv(
	PCLIENT_ID SourceThread
	)
{
	BOOL                         cond = FALSE;
	NTSTATUS                     status = STATUS_UNSUCCESSFUL;
	HANDLE			             hThread = NULL, hToken = NULL;
	OBJECT_ATTRIBUTES            obja;
	SECURITY_QUALITY_OF_SERVICE  SecurityQos;
	TOKEN_PRIVILEGES             *NewState = NULL;
	ULONG                        uLen;

	do {

		InitializeObjectAttributes(&obja, NULL, 0, NULL, NULL);
		status = NtOpenThread(&hThread, THREAD_DIRECT_IMPERSONATION, &obja, SourceThread);
		if (!NT_SUCCESS(status))
			break;

		SecurityQos.Length = sizeof(SecurityQos);
		SecurityQos.ImpersonationLevel = SecurityImpersonation;
		SecurityQos.ContextTrackingMode = SECURITY_DYNAMIC_TRACKING;
		SecurityQos.EffectiveOnly = FALSE;
		status = NtImpersonateThread(NtCurrentThread(), hThread, &SecurityQos);
		if (!NT_SUCCESS(status))
			break;

		status = NtOpenThreadTokenEx(NtCurrentThread(), TOKEN_ADJUST_PRIVILEGES, FALSE, 0, &hToken);
		if (!NT_SUCCESS(status))
			break;

		uLen = sizeof(TOKEN_PRIVILEGES) + (6 * sizeof(LUID_AND_ATTRIBUTES));

		NewState = RtlAllocateHeap(NtCurrentPeb()->ProcessHeap, HEAP_ZERO_MEMORY, uLen);
		if (NewState == NULL)
			break;

		NewState->PrivilegeCount = 6;

		NewState->Privileges[0].Luid.LowPart = SE_TCB_PRIVILEGE;
		NewState->Privileges[0].Luid.HighPart = 0;
		NewState->Privileges[0].Attributes = SE_PRIVILEGE_ENABLED_BY_DEFAULT | SE_PRIVILEGE_ENABLED;

		NewState->Privileges[1].Luid.LowPart = SE_TAKE_OWNERSHIP_PRIVILEGE;
		NewState->Privileges[1].Luid.HighPart = 0;
		NewState->Privileges[1].Attributes = SE_PRIVILEGE_ENABLED_BY_DEFAULT | SE_PRIVILEGE_ENABLED;

		NewState->Privileges[2].Luid.LowPart = SE_RESTORE_PRIVILEGE;
		NewState->Privileges[2].Luid.HighPart = 0;
		NewState->Privileges[2].Attributes = SE_PRIVILEGE_ENABLED_BY_DEFAULT | SE_PRIVILEGE_ENABLED;

		NewState->Privileges[3].Luid.LowPart = SE_DEBUG_PRIVILEGE;
		NewState->Privileges[3].Luid.HighPart = 0;
		NewState->Privileges[3].Attributes = SE_PRIVILEGE_ENABLED_BY_DEFAULT | SE_PRIVILEGE_ENABLED;

		NewState->Privileges[4].Luid.LowPart = SE_LOAD_DRIVER_PRIVILEGE;
		NewState->Privileges[4].Luid.HighPart = 0;
		NewState->Privileges[4].Attributes = SE_PRIVILEGE_ENABLED_BY_DEFAULT | SE_PRIVILEGE_ENABLED;

		NewState->Privileges[5].Luid.LowPart = SE_SECURITY_PRIVILEGE;
		NewState->Privileges[5].Luid.HighPart = 0;
		NewState->Privileges[5].Attributes = SE_PRIVILEGE_ENABLED_BY_DEFAULT | SE_PRIVILEGE_ENABLED;
		
		status = NtAdjustPrivilegesToken(hToken, FALSE, NewState, 0, NULL, NULL);

	} while (cond);

	if (hToken != NULL) NtClose(hToken);
	if (hThread != NULL) NtClose(hThread);
	if (NewState != NULL) RtlFreeHeap(NtCurrentPeb()->ProcessHeap, 0, NewState);

	return NT_SUCCESS(status);
}

/*
* SfuElevatePriv
*
* Purpose:
*
* Attempt to elevate current thread privileges by impersonating lsass thread token and adding privilegs next.
*
*/
BOOL SfuElevatePriv(
	VOID
	)
{
	BOOLEAN                        WasEnabled;
	BOOL                           cond = FALSE, bResult = FALSE;
	NTSTATUS                       status;
	PSYSTEM_PROCESSES_INFORMATION  ProcessList = NULL, pList;
	UNICODE_STRING                 uLookupProcess;
	ULONG                          i;

	do {
		status = RtlAdjustPrivilege(SE_DEBUG_PRIVILEGE, TRUE, FALSE, &WasEnabled);
		if (!NT_SUCCESS(status))
			break;

		ProcessList = SfuGetSystemInfo(SystemProcessInformation);
		if (ProcessList == NULL)
			break;

		RtlSecureZeroMemory(&uLookupProcess, sizeof(uLookupProcess));
		RtlInitUnicodeString(&uLookupProcess, L"lsass.exe");
		pList = ProcessList;

		for (;;) {

			if (RtlEqualUnicodeString(&uLookupProcess, &pList->ImageName, TRUE)) {

				for (i = 0; i < pList->ThreadCount; i++) {
					bResult = SfuAdjustCurrentThreadPriv(&pList->Threads[i].ClientId);
					if (bResult)
						break;
				}
				break;
			}
			if (pList->NextEntryDelta == 0) {
				break;
			}
			pList = (PSYSTEM_PROCESSES_INFORMATION)(((LPBYTE)pList) + pList->NextEntryDelta);
		}

	} while (cond);

	if (ProcessList != NULL)
		RtlFreeHeap(NtCurrentPeb()->ProcessHeap, 0, ProcessList);

	return bResult;
}

/*
* SfuLoadPeerList
*
* Purpose:
*
* Load peer list from file.
*
*/
NTSTATUS SfuLoadPeerList(
	_In_ OBJECT_ATTRIBUTES *ObjectAttributes,
	_In_ ZA_PEERINFO **PeerList,
	_In_ PULONG NumberOfPeers
	)
{
	BOOL                        cond = FALSE;
	HANDLE                      hFile = NULL;
	PVOID                       pData = NULL;
	NTSTATUS                    status = STATUS_UNSUCCESSFUL;
	IO_STATUS_BLOCK             iost;
	FILE_STANDARD_INFORMATION   fsi;
	SIZE_T                      memIO;

	if ((NumberOfPeers == NULL) || (PeerList == NULL))
		return status;

	do {
		status = NtOpenFile(&hFile, FILE_READ_ACCESS | SYNCHRONIZE,
			ObjectAttributes, &iost, FILE_SHARE_READ,
			FILE_NON_DIRECTORY_FILE | FILE_SYNCHRONOUS_IO_NONALERT);

		if (!NT_SUCCESS(status))
			break;

		RtlSecureZeroMemory(&fsi, sizeof(fsi));
		status = NtQueryInformationFile(hFile, &iost, (PVOID)&fsi, sizeof(fsi), FileStandardInformation);
		if (!NT_SUCCESS(status))
			break;

		if ((fsi.EndOfFile.LowPart % sizeof(ZA_PEERINFO)) != 0) {// incomplete/damaged file
			status = STATUS_BAD_DATA;
			break;
		}

		memIO = (SIZE_T)fsi.EndOfFile.LowPart;
		NtAllocateVirtualMemory(NtCurrentProcess(), &pData, 0, &memIO, MEM_COMMIT | MEM_RESERVE, PAGE_READWRITE);
		if (pData == NULL) {
			status = STATUS_MEMORY_NOT_ALLOCATED;
			break;
		}

		status = NtReadFile(hFile, NULL, NULL, NULL, &iost, pData, fsi.EndOfFile.LowPart, NULL, NULL);
		if (NT_SUCCESS(status)) {
			*NumberOfPeers = (ULONG)(iost.Information / sizeof(ZA_PEERINFO));
			*PeerList = pData;
		}
		else {
			RtlFreeHeap(NtCurrentPeb()->ProcessHeap, 0, pData);
			*NumberOfPeers = 0;
			*PeerList = NULL;
		}

	} while (cond);

	if (hFile) NtClose(hFile);
	return status;
}

/*
* SfuCreateDirectory
*
* Purpose:
*
* Native create directory.
*
*/
BOOL SfuCreateDirectory(
	_In_ OBJECT_ATTRIBUTES *ObjectAttributes
	)
{
	NTSTATUS         status;
	HANDLE           DirectoryHandle;
	IO_STATUS_BLOCK  IoStatusBlock;

	status = NtCreateFile(&DirectoryHandle,
		FILE_GENERIC_WRITE,
		ObjectAttributes,
		&IoStatusBlock,
		NULL,
		FILE_ATTRIBUTE_NORMAL,//za use hidden+system
		FILE_SHARE_READ | FILE_SHARE_WRITE | FILE_SHARE_DELETE,
		FILE_OPEN_IF,
		FILE_DIRECTORY_FILE,
		NULL,
		0
		);
	if (!NT_SUCCESS(status)) {
		return FALSE;
	}
	NtClose(DirectoryHandle);
	return TRUE;
}
