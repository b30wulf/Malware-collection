/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2006 - 2016
*
*  TITLE:       EA.C
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  ZeroAccess EA support routines.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"
#include "ea.h"

//NextOffset 0, EaNameLength 3, EaName VER
BYTE g_ZaFileEa[] = { 0x00, 0x00, 0x00, 0x00, 0x03, 0x56, 0x45, 0x52, 0x00 };

/*
* SfNtfsQueryFileHeaderFromEa
*
* Purpose:
*
* Read ZA_FILEHEADER, required for file verification, from file.
*
*/
BOOL SfNtfsQueryFileHeaderFromEa(
	_In_ HANDLE hFile,
	_Inout_ ZA_FILEHEADER *FileHeader
	)
{
	BOOL                      bResult;
	NTSTATUS                  status;
	IO_STATUS_BLOCK           IoStatusBlock;
	FILE_FULL_EA_INFORMATION  *EaFullInfo;
	FILE_GET_EA_INFORMATION   *EaGetInfo;
	BYTE                      Buffer[ZA_EASIZE];

	RtlSecureZeroMemory(Buffer, sizeof(Buffer));
	EaFullInfo = (FILE_FULL_EA_INFORMATION *)&Buffer;
	EaGetInfo = (FILE_GET_EA_INFORMATION *)&g_ZaFileEa;

	status = NtQueryEaFile(hFile, &IoStatusBlock, EaFullInfo, ZA_EASIZE,
		FALSE, EaGetInfo, sizeof(g_ZaFileEa), NULL, FALSE);

	if ((!NT_SUCCESS(status)) || (EaFullInfo->EaValueLength != sizeof(ZA_FILEHEADER))) {
		bResult = FALSE;
	}
	else {
		RtlCopyMemory(FileHeader, (LPBYTE)(EaFullInfo->EaName + EaFullInfo->EaNameLength + 1), sizeof(ZA_FILEHEADER));
		bResult = TRUE;
	}
	return bResult;
}

/*
* SfNtfsSetFileHeaderToEa
*
* Purpose:
*
* Write ZA_FILEHEADER, required for file verification, to file.
*
*/
BOOL SfNtfsSetFileHeaderToEa(
	_In_ HANDLE hFile,
	_In_ ZA_FILEHEADER *FileHeader
	)
{
	NTSTATUS                  status;
	FILE_FULL_EA_INFORMATION *EaFullInfo;
	IO_STATUS_BLOCK           IoStatusBlock;
	BYTE                      Buffer[ZA_EASIZE];//152

	RtlSecureZeroMemory(Buffer, sizeof(Buffer));
	EaFullInfo = (FILE_FULL_EA_INFORMATION*)&Buffer;

	EaFullInfo->Flags = 0;
	_strcpy_a(EaFullInfo->EaName, "VER");
	EaFullInfo->EaNameLength = 3;
	EaFullInfo->EaValueLength = sizeof(ZA_FILEHEADER);
	EaFullInfo->NextEntryOffset = 0;

	RtlCopyMemory((LPBYTE)(EaFullInfo->EaName + EaFullInfo->EaNameLength + 1),
		FileHeader, sizeof(ZA_FILEHEADER));

	status = NtSetEaFile(hFile, &IoStatusBlock, EaFullInfo, ZA_EASIZE);
	return (NT_SUCCESS(status));
}

//test ea data
unsigned char zaea[140] = {
	0x01, 0x00, 0x00, 0x00, 0x7A, 0x73, 0xB0, 0x43, 0x00, 0x06, 0x00, 0x00, 0x44, 0xAC, 0x09, 0xAA,
	0x99, 0xF3, 0x29, 0xA3, 0x21, 0xB2, 0xE7, 0x5C, 0x46, 0x43, 0xA4, 0xDE, 0x51, 0x8C, 0xE8, 0x35,
	0x64, 0x66, 0x70, 0x49, 0xFE, 0xF7, 0x86, 0xC4, 0xC5, 0x56, 0x6E, 0x20, 0xC0, 0x16, 0x27, 0xB5,
	0xFB, 0x4D, 0x17, 0x66, 0xA2, 0x86, 0x44, 0x4A, 0x36, 0x21, 0x32, 0x18, 0x5D, 0x9E, 0x6D, 0x32,
	0x61, 0x20, 0xA7, 0xE7, 0x6D, 0x04, 0x00, 0x9F, 0xC5, 0xBD, 0x8E, 0xFA, 0xFC, 0xB7, 0xD7, 0x14,
	0x81, 0x00, 0xDA, 0xDB, 0xCB, 0x36, 0x17, 0xCE, 0x84, 0x0D, 0x53, 0x46, 0x88, 0xEF, 0x1E, 0xC0,
	0xF8, 0xF0, 0xDF, 0xC1, 0x15, 0x12, 0x25, 0x63, 0x04, 0x40, 0x0A, 0x00, 0x7A, 0x88, 0x93, 0x99,
	0xC5, 0x1E, 0x52, 0x41, 0xE5, 0x18, 0xCB, 0x11, 0xA3, 0x73, 0xD0, 0xA2, 0xA3, 0x30, 0xD0, 0x47,
	0x2F, 0x0F, 0x18, 0xD5, 0x03, 0x30, 0xDD, 0xC2, 0xCB, 0x3D, 0x96, 0x34
};

NTSTATUS SfNtfsDumpFileEa(
	_In_opt_ HANDLE RootDirectory,
	_In_ LPWSTR FileName
	)
{
	NTSTATUS            status;
	HANDLE              hFile;
	IO_STATUS_BLOCK     IoStatusBlock;
	ZA_FILEHEADER       FileHeader;
	OBJECT_ATTRIBUTES   ObjectAttributes;
	UNICODE_STRING      uFileName;

	RtlSecureZeroMemory(&uFileName, sizeof(uFileName));
	RtlInitUnicodeString(&uFileName, FileName);
	InitializeObjectAttributes(&ObjectAttributes, &uFileName, OBJ_CASE_INSENSITIVE, RootDirectory, NULL);

	status = NtOpenFile(&hFile, FILE_GENERIC_READ, &ObjectAttributes, &IoStatusBlock,
		FILE_SHARE_READ | FILE_SHARE_WRITE | FILE_SHARE_DELETE, 
		FILE_SYNCHRONOUS_IO_NONALERT);

	if (!NT_SUCCESS(status))
		return status;

	RtlSecureZeroMemory(&FileHeader, sizeof(FileHeader));
	if (SfNtfsQueryFileHeaderFromEa(hFile, &FileHeader)) {
		SfuWriteBufferToFile(L"outEa.bin", &FileHeader, sizeof(FileHeader), FALSE, FALSE);
	}
	NtClose(hFile);
	return STATUS_SUCCESS;
}

BOOL TestEa(
	BOOL TestSet
	)
{
	UNICODE_STRING     usName;
	NTSTATUS           status;
	HANDLE             hFile = NULL;
	IO_STATUS_BLOCK    IoStatusBlock;
	OBJECT_ATTRIBUTES  ObjectAttributes;
	BOOL               bResult = FALSE, cond = FALSE;
	ZA_BOT_PATH        zaBotPath;

	RtlSecureZeroMemory(&usName, sizeof(usName));
	RtlSecureZeroMemory(&zaBotPath, sizeof(zaBotPath));
	SfuBuildBotPath(&zaBotPath);

	do {

		RtlInitUnicodeString(&usName, L"00000001.@");
		InitializeObjectAttributes(&ObjectAttributes, &usName, OBJ_CASE_INSENSITIVE, NULL, NULL);

		if (TestSet) {
			status = NtCreateFile(&hFile, FILE_GENERIC_WRITE, &ObjectAttributes, &IoStatusBlock, NULL, 0,
				0, FILE_OPEN,
				FILE_SYNCHRONOUS_IO_NONALERT | FILE_NON_DIRECTORY_FILE, NULL, 0);

			bResult = SfNtfsSetFileHeaderToEa(hFile, (ZA_FILEHEADER *)&zaea);

			NtClose(hFile);
		}
		else {
			bResult = SfNtfsDumpFileEa(NULL, L"out.bin");
		}

	} while (cond);


	return bResult;
}
