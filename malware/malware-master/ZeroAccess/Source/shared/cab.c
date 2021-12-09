/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       CAB.C
*
*  VERSION:     1.00
*
*  DATE:        18 Jan 2016
*
*  ZeroAccess cabinet extraction from memory buffer.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"
#include "cab.h"

#pragma comment(lib, "cabinet.lib")
static CABDATA g_CabParam;

/*
* fdiAlloc
*
* Purpose:
*
* Callback function to allocate memory.
*
*/
void* DIAMONDAPI fdiAlloc(
	ULONG cb
	)
{
	return LocalAlloc(LPTR, cb);
}

/*
* fdiFree
*
* Purpose:
*
* Callback function to free previously allocated memory.
*
*/
void DIAMONDAPI fdiFree(
	void HUGE *pv
	)
{
	if (pv) {
		LocalFree(pv);
	}
}

/*
* fdiClose
*
* Purpose:
*
* Callback function to release memory allocated for file memory stream.
*
*/
int DIAMONDAPI fdiClose(
	CABDATA *hf
	)
{
	LocalFree((HLOCAL)hf);
	return 0;
}

/*
* fdiOpen
*
* Purpose:
*
* Callback function to create a memory stream.
*
*/
INT_PTR DIAMONDAPI fdiOpen(
	LPSTR pszFile,
	int   oflag,
	int   pmode
	)
{
	CABDATA *Data = NULL;
	CABDATA *param;
	
	ULONG_PTR value;

	UNREFERENCED_PARAMETER(oflag);
	UNREFERENCED_PARAMETER(pmode);

#ifdef _WIN64
	value = strtou64_a(pszFile);
#else
	value = strtoul_a(pszFile);
#endif
	param = (CABDATA *)value;

	Data = (CABDATA*)LocalAlloc(LPTR, sizeof(CABDATA));
	if (Data) {
		Data->Buffer = param->Buffer;
		Data->Size = param->Size;
		Data->Offset = 0;
	}
	return (INT_PTR)Data;
}

/*
* fdiRead
*
* Purpose:
*
* Callback function to read from memory stream.
*
*/
UINT DIAMONDAPI fdiRead(
	CABDATA  *Data,
	void FAR *pv,
	UINT     cb
	)
{
	UINT bytesToRead = cb;
	
	if (cb >= (UINT)(Data->Size - Data->Offset))
		bytesToRead = Data->Size - Data->Offset;

	memcpy(pv, &Data->Buffer[Data->Offset], bytesToRead);
	Data->Offset += bytesToRead;
	return bytesToRead;
}

/*
* fdiWrite
*
* Purpose:
*
* Callback function to write to the memory stream.
*
*/
UINT fdiWrite(
	CABDATA  *Data,
	void FAR *pv,
	UINT     cb
	)
{
	if ((LONG)(Data->Offset + cb) <= Data->Size) {
		memcpy(&Data->Buffer[Data->Offset], pv, cb);
		Data->Offset += cb;
	}
	else {
		return 0;
	}
	return cb;
}

/*
* fdiSeek
*
* Purpose:
*
* Callback function to seek in memory stream.
*
*/
long fdiSeek(
	CABDATA *Data,
	long    dist,
	int     seektype
	)
{
	LONG pos = 0;

	if (seektype) {
		if (seektype != SEEK_CUR) {
			return -1;
		}
		pos = dist + Data->Offset;
	}
	else
	{
		pos = dist;
	}
	if (pos > Data->Size)
		return -1;

	Data->Offset = pos;
	return pos;
}

/*
* fdiNotify
*
* Purpose:
*
*  Callback notification function to update the application on the status of the decoder.
*
*/
INT_PTR DIAMONDAPI fdiNotify(FDINOTIFICATIONTYPE fdint, PFDINOTIFICATION pfdin)
{
	INT_PTR Result = 0;
	CABDATA *Data, *ReturnData = NULL;
	LPSTR LookupFileName;
	LONG Size;

	switch (fdint) {

	case fdintCOPY_FILE:

		if (pfdin->pv == NULL)
			break;

		Data = (CABDATA *)pfdin->pv;
		LookupFileName = (LPSTR)&Data->Size;
		Size = pfdin->cb;
		if (_strcmpi_a(LookupFileName, pfdin->psz1) == 0) {
			ReturnData = LocalAlloc(LPTR, sizeof(CABDATA));
			if (ReturnData) {
				ReturnData->Buffer = LocalAlloc(LPTR, pfdin->cb);
				if (ReturnData->Buffer == NULL) {
					LocalFree(ReturnData);
					ReturnData = NULL;
				}
				else {
					ReturnData->Offset = 0;
					ReturnData->Size = pfdin->cb;
					Data->Buffer = ReturnData->Buffer;
					Data->Size = ReturnData->Size;
				}
				return (INT_PTR)ReturnData;
			}
		}
		break;

	case fdintCLOSE_FILE_INFO: //release ReturnedInfo
		LocalFree((HLOCAL)pfdin->hf);
		Result = 1;
		break;

	default:
		break;

	}
	return Result;
}

/*
* SfcabExtractMemory
*
* Purpose:
*
* Process cabinet file in memory and extract it contents.
*
* On success returned buffer must be deallocated with LocalFree after usage.
*
*/
PVOID SfcabExtractMemory(
	PVOID CabPtr,
	ULONG CabSize,
	PULONG ExtractedBytes
	)
{

	HFDI hfdi;
	ERF erf;
	CHAR text[32];
	CHAR name[1];
	PVOID Buffer = NULL;
	CABDATA Data;
	
	if (ExtractedBytes == NULL)
		return NULL;

	__try {

		RtlSecureZeroMemory(&erf, sizeof(ERF));
		hfdi = FDICreate((PFNALLOC)fdiAlloc, (PFNFREE)fdiFree, (PFNOPEN)fdiOpen, (PFNREAD)fdiRead,
			(PFNWRITE)fdiWrite, (PFNCLOSE)fdiClose, (PFNSEEK)fdiSeek, cpu80386, &erf);

		if (hfdi) {

			g_CabParam.Buffer = CabPtr;
			g_CabParam.Size = CabSize;
			g_CabParam.Offset = 0;

			RtlSecureZeroMemory(&text, sizeof(text));
#ifdef _WIN64
			u64tostr_a((ULONG_PTR)&g_CabParam, text);
#else 
			ultostr_a((ULONG_PTR)&g_CabParam, text);
#endif

			name[0] = 0;

			Data.Size = '_';
			Data.Buffer = NULL;
			Data.Offset = 0;
			if (FDICopy(hfdi, name, text, 0, fdiNotify, 0, &Data)) {
				Buffer = Data.Buffer;
				*ExtractedBytes = Data.Size;
			}
			FDIDestroy(hfdi);
		}
	}
	__except (EXCEPTION_EXECUTE_HANDLER)
	{
		return NULL;
	}
	return Buffer;
}
