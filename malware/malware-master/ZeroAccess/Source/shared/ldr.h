/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       LDR.H
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  Common header file for ZeroAccess loader routines.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

PBYTE SfLdrQueryResourceDataEx(
	_In_ PVOID ImageBase,
	_In_ CONST LDR_RESOURCE_INFO* ResourceIdPath,
	_Out_ ULONG *DataSize
	);

PBYTE SfLdrQueryResourceData(
	_In_ ULONG_PTR ResourceId,
	_In_ PVOID DllHandle,
	_In_ PULONG DataSize
	);
