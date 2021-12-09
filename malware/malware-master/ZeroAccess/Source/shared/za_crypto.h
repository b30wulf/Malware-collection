/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       ZA_CRYPTO.H
*
*  VERSION:     1.01
*
*  DATE:        18 Jan 2016
*
*  ZeroAccess cryptography.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/
#pragma once

NTSTATUS SfcIsFileLegit(
	_In_ LPWSTR lpFileName,
	_In_ PBYTE BotKey,
	_In_ DWORD BotKeySize
	);

BOOL SfcValidateFileHeader(
	_In_ HCRYPTPROV hCryptoProv,
	_In_ HCRYPTKEY hCryptKey,
	_In_ ZA_FILEHEADER *FileHeader
	);
