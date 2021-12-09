/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       MD5.C
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  ZeroAccess Fast MD5 support routines.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"

PMD5Init MD5Init = NULL;
PMD5Update MD5Update = NULL;
PMD5Final MD5Final = NULL;

/*
* SfInitMD5
*
* Purpose:
*
* Load function pointers for quick MD5.
*
*/
BOOLEAN SfInitMD5(
	VOID
	)
{
	HMODULE hLib;
		
	if (
		(MD5Init != NULL) &&
		(MD5Update != NULL) &&
		(MD5Final != NULL)
		)
	{
		return TRUE;
	}
		
	hLib = GetModuleHandle(TEXT("ntdll.dll"));
	if (hLib == NULL)
		return FALSE;

	MD5Init = (PMD5Init)GetProcAddress(hLib, "MD5Init");
	MD5Update = (PMD5Update)GetProcAddress(hLib, "MD5Update");
	MD5Final = (PMD5Final)GetProcAddress(hLib, "MD5Final");
	return TRUE;
}
