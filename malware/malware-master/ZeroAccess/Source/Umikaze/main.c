/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       MAIN.C
*
*  VERSION:     1.01
*
*  DATE:        20 Jan 2016
*
*  Umikaze program entry point.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "..\shared\global.h"
#include "..\shared\cui.h"

HANDLE g_ConOut = NULL;
WCHAR BE = 0xFEFF;
BOOL g_ConsoleOutput = FALSE;

#define T_SFDECODETITLE L"Sirefef/ZeroAccess 3 peer list decoder v1.0 (10/01/16)"
#define T_SFDECODEUSAGE L"Usage: zadecode peerlist_filename [type 32 or 64, default 32]\n\r\te.g. zadecode s32 32\r\n"
#define T_SFDECODEMODE  L"Wrong mode, possible values 32 or 64\r\n"
#define T_SFUNSUCCESSF  L"Error generating list"
#define T_SFBADDATA     L"File has wrong structure or damaged"
#define T_SFGENERATED   L"File generated "
#define T_SFPRESSANYKEY L"\r\nPress Enter to exit"

/*
* SfDecodePeerList
*
* Purpose:
*
* Decode peer list to file, ZA v3 variant.
*
*/
NTSTATUS SfDecodePeerList(
	LPWSTR lpInFileName,
	LPWSTR lpOutFileName,
	ULONG uType
	)
{
	BOOL                       cond = FALSE;
	NTSTATUS                   status = STATUS_UNSUCCESSFUL;
	HANDLE                     hFile = NULL;
	OBJECT_ATTRIBUTES          obja;
	IO_STATUS_BLOCK            iost;
	UNICODE_STRING             NtFileName;
	FILE_STANDARD_INFORMATION  fsi;
	PUCHAR                     FileBuffer = NULL;
	
	ULONG         i, j, c, Port;
	PZA_PEERINFO  peer;
	LARGE_INTEGER ftime;
	SYSTEMTIME    st1;
	WCHAR         text[MAX_PATH + 1];

	RtlSecureZeroMemory(&NtFileName, sizeof(NtFileName));

	do {
		//open input file
		if (RtlDosPathNameToNtPathName_U(lpInFileName, &NtFileName, NULL, NULL) == FALSE)
			break;

		InitializeObjectAttributes(&obja, &NtFileName, OBJ_CASE_INSENSITIVE, 0, NULL);
		status = NtCreateFile(&hFile, FILE_READ_ACCESS | SYNCHRONIZE, &obja, &iost, NULL, 0,
			FILE_SHARE_READ, FILE_OPEN,
			FILE_SYNCHRONOUS_IO_NONALERT | FILE_NON_DIRECTORY_FILE, NULL, 0);
		if (!NT_SUCCESS(status))
			break;

		//get file size
		status = NtQueryInformationFile(hFile, &iost, &fsi,
			sizeof(FILE_STANDARD_INFORMATION),
			FileStandardInformation);
		if (!NT_SUCCESS(status))
			break;

		c = fsi.EndOfFile.LowPart % sizeof(ZA_PEERINFO);
		if (c != 0) {
			status = STATUS_BAD_DATA;
			break;
		}

		FileBuffer = RtlAllocateHeap(NtCurrentPeb()->ProcessHeap, HEAP_ZERO_MEMORY, fsi.EndOfFile.LowPart);
		if (FileBuffer == NULL)
			break;

		//read file to buffer
		status = NtReadFile(hFile, NULL, NULL, NULL, &iost, FileBuffer, fsi.EndOfFile.LowPart, NULL, NULL);
		if (!NT_SUCCESS(status))
			break;

		//close input file
		NtClose(hFile);
		hFile = NULL;
		RtlFreeUnicodeString(&NtFileName);

		//create output file
		if (RtlDosPathNameToNtPathName_U(lpOutFileName, &NtFileName, NULL, NULL) == FALSE)
			break;

		InitializeObjectAttributes(&obja, &NtFileName, OBJ_CASE_INSENSITIVE, 0, NULL);
		status = NtCreateFile(&hFile, FILE_WRITE_ACCESS | SYNCHRONIZE, &obja, &iost, NULL, 0,
			0, FILE_OVERWRITE_IF,
			FILE_SYNCHRONOUS_IO_NONALERT | FILE_NON_DIRECTORY_FILE, NULL, 0);
		if (!NT_SUCCESS(status))
			break;
		
		NtWriteFile(hFile, NULL, NULL, NULL, &iost, &BE, sizeof(BE), NULL, NULL);

		c = fsi.EndOfFile.LowPart / sizeof(ZA_PEERINFO);
		for (i = 0, j = 0; i < c; i += 1, j += sizeof(ZA_PEERINFO)) {

			peer = (ZA_PEERINFO *)&FileBuffer[j];

			RtlSecureZeroMemory(&text, sizeof(text));
			RtlIpv4AddressToStringW((struct in_addr*)&peer->IP, (PWSTR)&text);

			_strcat(text, TEXT(":"));

			Port = 0x4000 + (peer->Port);
			if (uType == 64) Port += 0x4000;
			ultostr(Port, _strend(text));
			_strcat(text, TEXT(" "));

			RtlSecondsSince1980ToTime((peer->TimeStamp * 3600) - 0xbf000000, &ftime);
			RtlSecureZeroMemory(&st1, sizeof(st1));
			if (FileTimeToSystemTime((PFILETIME)&ftime, &st1)) {
				ultostr(st1.wDay, _strend(text));
				_strcat(text, TEXT("/"));
				ultostr(st1.wMonth, _strend(text));
				_strcat(text, TEXT("/"));
				ultostr(st1.wYear, _strend(text));
				_strcat(text, TEXT(" "));
				ultostr(st1.wHour, _strend(text));
				_strcat(text, TEXT(":"));
				ultostr(st1.wMinute, _strend(text));
				_strcat(text, TEXT(":"));
				ultostr(st1.wSecond, _strend(text));
			}
			_strcat(text, TEXT("\r\n"));
			status = NtWriteFile(hFile, NULL, NULL, NULL, &iost, text, (DWORD)_strlen(text) * sizeof(WCHAR), NULL, NULL);
		}

	} while (cond);

	if (hFile) NtClose(hFile);
	if (FileBuffer) RtlFreeHeap(NtCurrentPeb()->ProcessHeap, 0, FileBuffer);
	if (NtFileName.Buffer) RtlFreeUnicodeString(&NtFileName);

	return status;
}

/*
* SfProcessCmdLine
*
* Purpose:
*
* Parse command line and do the job.
*
*/
UINT SfProcessCmdLine(
	LPWSTR lpCommandLine
	)
{
	NTSTATUS  status;
	ULONG     rlen, uType = 32;
	WCHAR     textbuf[MAX_PATH + 1], textbuf2[MAX_PATH * 2];
	WCHAR     szMode[MAX_PATH + 1];

	//path
	rlen = 0;
	RtlSecureZeroMemory(&textbuf, sizeof(textbuf));
	GetCommandLineParam(lpCommandLine, 1, (LPWSTR)&textbuf, sizeof(textbuf), &rlen);
	if (rlen == 0) {

		SfcuiPrintText(g_ConOut, 
			T_SFDECODEUSAGE,
			g_ConsoleOutput, FALSE);

		return (UINT)-1;
	}

	//type
	rlen = 0;
	RtlSecureZeroMemory(&szMode, sizeof(szMode));
	GetCommandLineParam(lpCommandLine, 2, (LPWSTR)&szMode, sizeof(szMode), &rlen);
	if (rlen == 0) {
		uType = 32;
	}
	else {
		uType = strtoul(szMode);
		if (uType != 32 && uType != 64) {

			SfcuiPrintText(g_ConOut,
				T_SFDECODEMODE,
				g_ConsoleOutput, FALSE);

			return (UINT)-2;
		}
	}

	_strcpy(textbuf2, textbuf);

	if (uType == 32) {
		_strcat(textbuf2, L".d32.txt");
	}
	else {
		_strcat(textbuf2, L".d64.txt");
	}

	status = SfDecodePeerList(textbuf, textbuf2, uType);
	switch (status) {

	
	case STATUS_BAD_DATA:

		SfcuiPrintText(g_ConOut,
			T_SFBADDATA,
			g_ConsoleOutput, FALSE);

		return (UINT)-3;
		break;

	case STATUS_SUCCESS:

		SfcuiPrintText(g_ConOut,
			T_SFGENERATED,
			g_ConsoleOutput, FALSE);

		SfcuiPrintText(g_ConOut,
			textbuf2,
			g_ConsoleOutput, FALSE);

		break;

	default:
		SfcuiPrintText(g_ConOut,
			T_SFUNSUCCESSF,
			g_ConsoleOutput, FALSE);

		return (UINT)-4;
		break;
	}

	return 0;
}

/*
* SfMain
*
* Purpose:
*
* Umikaze main.
*
*/
void SfMain(
	VOID
	)
{
	BOOL         cond = FALSE;
	UINT         uResult = 0;
	DWORD        dwTemp;
	HANDLE       StdIn;
	INPUT_RECORD inp1;

	__security_init_cookie();

	do {

		g_ConOut = GetStdHandle(STD_OUTPUT_HANDLE);
		if (g_ConOut == INVALID_HANDLE_VALUE) {
			uResult = (UINT)-1;
			break;
		}

		g_ConsoleOutput = TRUE;
		if (!GetConsoleMode(g_ConOut, &dwTemp)) {
			g_ConsoleOutput = FALSE;
		}
		
		SetConsoleTitle(T_SFDECODETITLE);
		SetConsoleMode(g_ConOut, ENABLE_LINE_INPUT | ENABLE_ECHO_INPUT | ENABLE_PROCESSED_OUTPUT);
		if (g_ConsoleOutput == FALSE) {
			WriteFile(g_ConOut, &BE, sizeof(WCHAR), &dwTemp, NULL);
		}

		uResult = SfProcessCmdLine(GetCommandLine());

		if (g_ConsoleOutput) {

			SfcuiPrintText(g_ConOut,
				T_SFPRESSANYKEY,
				TRUE, FALSE);

			StdIn = GetStdHandle(STD_INPUT_HANDLE);
			if (StdIn != INVALID_HANDLE_VALUE) {
				RtlSecureZeroMemory(&inp1, sizeof(inp1));
				ReadConsoleInput(StdIn, &inp1, 1, &dwTemp);
				ReadConsole(StdIn, &BE, sizeof(BE), &dwTemp, NULL);
			}
		}

	} while (cond);

	ExitProcess(uResult);
}
