/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       CAB.H
*
*  VERSION:     1.01
*
*  DATE:        18 Jan 2016
*
*  Common header file for ZeroAccess cabinet extraction support.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/
#pragma once

#include <fdi.h>

typedef struct _CABDATA {
	LONG Size;
	PUCHAR Buffer;
	LONG Offset;
} CABDATA, *PCABDATA;

PVOID SfcabExtractMemory(
	PVOID CabPtr,
	ULONG CabSize,
	PULONG ExtractedBytes
	);
