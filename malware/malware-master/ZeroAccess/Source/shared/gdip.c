/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       GDIP.C
*
*  VERSION:     1.00
*
*  DATE:        18 Jan 2016
*
*  GDI+ support.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"
#include "gdip.h"

BOOL SfInitGdiPlus(
	VOID
	)
{
	HANDLE  hGdiPlus;

	hGdiPlus = LoadLibraryEx(TEXT("gdiplus.dll"), 0, LOAD_LIBRARY_SEARCH_SYSTEM32);
	if (hGdiPlus == NULL)
		return FALSE;

	GdiplusStartup = (pfnGdiplusStartup)GetProcAddress(hGdiPlus, "GdiplusStartup");
	if (GdiplusStartup == NULL)
		return FALSE;

	GdiplusShutdown = (pfnGdiplusShutdown)GetProcAddress(hGdiPlus, "GdiplusShutdown");
	if (GdiplusShutdown == NULL)
		return FALSE;

	GdipCreateBitmapFromStream = (pfnGdipCreateBitmapFromStream)GetProcAddress(hGdiPlus, "GdipCreateBitmapFromStream");
	if (GdipCreateBitmapFromStream == NULL)
		return FALSE;

	GdipDisposeImage = (pfnGdipDisposeImage)GetProcAddress(hGdiPlus, "GdipDisposeImage");
	if (GdipDisposeImage == NULL)
		return FALSE;

	GdipGetImageWidth = (pfnGdipGetImageWidth)GetProcAddress(hGdiPlus, "GdipGetImageWidth");
	if (GdipGetImageWidth == NULL)
		return FALSE;

	GdipGetImageHeight = (pfnGdipGetImageHeight)GetProcAddress(hGdiPlus, "GdipGetImageHeight");
	if (GdipGetImageHeight == NULL)
		return FALSE;

	GdipBitmapLockBits = (pfnGdipBitmapLockBits)GetProcAddress(hGdiPlus, "GdipBitmapLockBits");
	if (GdipBitmapLockBits == NULL)
		return FALSE;

	GdipBitmapUnlockBits = (pfnGdipBitmapUnlockBits)GetProcAddress(hGdiPlus, "GdipBitmapUnlockBits");
	if (GdipBitmapUnlockBits == NULL)
		return FALSE;

	return TRUE;
}
