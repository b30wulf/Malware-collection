/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       EA.H
*
*  VERSION:     1.01
*
*  DATE:        19 Jan 2016
*
*  ZeroAccess EA support routines header file.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

#define ZA_EASIZE  sizeof(FILE_FULL_EA_INFORMATION) + sizeof(ZA_FILEHEADER) //152 bytes

BOOL SfNtfsQueryFileHeaderFromEa(
	_In_ HANDLE hFile,
	_Inout_ ZA_FILEHEADER *FileHeader
	);

BOOL SfNtfsSetFileHeaderToEa(
	_In_ HANDLE hFile,
	_In_ ZA_FILEHEADER *FileHeader
	);
