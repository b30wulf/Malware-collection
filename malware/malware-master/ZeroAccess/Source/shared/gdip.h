/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       GDIP.H
*
*  VERSION:     1.00
*
*  DATE:        18 Jan 2016
*
*  Common header file for GDI+.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

typedef enum {
	GdiplusOk = 0,
	GdiplusGenericError = 1,
	GdiplusInvalidParameter = 2,
	GdiplusOutOfMemory = 3,
	GdiplusObjectBusy = 4,
	GdiplusInsufficientBuffer = 5,
	GdiplusNotImplemented = 6,
	GdiplusWin32Error = 7,
	GdiplusWrongState = 8,
	GdiplusAborted = 9,
	GdiplusFileNotFound = 10,
	GdiplusValueOverflow = 11,
	GdiplusAccessDenied = 12,
	GdiplusUnknownImageFormat = 13,
	GdiplusFontFamilyNotFound = 14,
	GdiplusFontStyleNotFound = 15,
	GdiplusNotTrueTypeFont = 16,
	GdiplusUnsupportedGdiplusVersion = 17,
	GdiplusNotInitialized = 18,
	GdiplusPropertyNotFound = 19,
	GdiplusPropertyNotSupported = 20,
	GdiplusProfileNotFound = 21
} GDI_STATUS;

typedef struct _GdiplusStartupInput {
	UINT32         GdiplusVersion;
	PVOID          DebugEventCallback;
	BOOL           SuppressBackgroundThread;
	BOOL           SuppressExternalCodecs;
} GdiplusStartupInput, *PGdiplusStartupInput;

typedef struct _GdiplusStartupOutput {
	PVOID          NotificationHook;
	PVOID          NotificationUnhook;
} GdiplusStartupOutput, *PGdiplusStartupOutput;

typedef struct _GdiPlusRect {
	INT X;
	INT Y;
	INT Width;
	INT Height;
} GdiPlusRect, *PGdiPlusRect;

typedef struct _GdiPlusBitmapData {
	UINT Width;
	UINT Height;
	INT Stride;
	UINT PixelFormat;
	VOID* Scan0;
	UINT_PTR Reserved;
} GdiPlusBitmapData, *PGdiPlusBitmapData;

typedef GDI_STATUS(WINAPI *pfnGdiplusStartup)(
	_Out_ ULONG_PTR *token,
	_In_  const GdiplusStartupInput *input,
	_Out_ GdiplusStartupOutput *output
	);

typedef void (WINAPI *pfnGdiplusShutdown)(
	_In_  ULONG_PTR token
	);

typedef GDI_STATUS(WINAPI *pfnGdipCreateBitmapFromStream)(
	IStream* stream,
	void **bitmap
	);

typedef GDI_STATUS(WINAPI *pfnGdipGetImageWidth)(
	void *image,
	UINT *width
	);

typedef GDI_STATUS(WINAPI *pfnGdipGetImageHeight)(
	void *image,
	UINT *height
	);

typedef GDI_STATUS(WINAPI *pfnGdipDisposeImage)(
	void *image
	);

typedef GDI_STATUS(WINAPI *pfnGdipBitmapLockBits)(
	void* bitmap,
	CONST GdiPlusRect* rect,
	UINT flags,
	INT format,
	void* lockedBitmapData
	);

typedef GDI_STATUS(WINAPI *pfnGdipBitmapUnlockBits)(
	void* bitmap,
	void* lockedBitmapData
	);

typedef enum
{
	ImageLockModeRead = 0x0001,
	ImageLockModeWrite = 0x0002,
	ImageLockModeUserInputBuf = 0x0004
} ImageLockMode;

#define    PixelFormatIndexed      0x00010000 // Indexes into a palette
#define    PixelFormatGDI          0x00020000 // Is a GDI-supported format
#define    PixelFormatAlpha        0x00040000 // Has an alpha component
#define    PixelFormatPAlpha       0x00080000 // Pre-multiplied alpha
#define    PixelFormatExtended     0x00100000 // Extended color 16 bits/channel
#define    PixelFormatCanonical    0x00200000 
#define    PixelFormatUndefined       0
#define    PixelFormatDontCare        0
#define    PixelFormat1bppIndexed     (1 | ( 1 << 8) | PixelFormatIndexed | PixelFormatGDI)
#define    PixelFormat4bppIndexed     (2 | ( 4 << 8) | PixelFormatIndexed | PixelFormatGDI)
#define    PixelFormat8bppIndexed     (3 | ( 8 << 8) | PixelFormatIndexed | PixelFormatGDI)
#define    PixelFormat16bppGrayScale  (4 | (16 << 8) | PixelFormatExtended)
#define    PixelFormat16bppRGB555     (5 | (16 << 8) | PixelFormatGDI)
#define    PixelFormat16bppRGB565     (6 | (16 << 8) | PixelFormatGDI)
#define    PixelFormat16bppARGB1555   (7 | (16 << 8) | PixelFormatAlpha | PixelFormatGDI)
#define    PixelFormat24bppRGB        (8 | (24 << 8) | PixelFormatGDI)
#define    PixelFormat32bppRGB        (9 | (32 << 8) | PixelFormatGDI)
#define    PixelFormat32bppARGB       (10 | (32 << 8) | PixelFormatAlpha | PixelFormatGDI | PixelFormatCanonical)
#define    PixelFormat32bppPARGB      (11 | (32 << 8) | PixelFormatAlpha | PixelFormatPAlpha | PixelFormatGDI)
#define    PixelFormat48bppRGB        (12 | (48 << 8) | PixelFormatExtended)
#define    PixelFormat64bppARGB       (13 | (64 << 8) | PixelFormatAlpha  | PixelFormatCanonical | PixelFormatExtended)
#define    PixelFormat64bppPARGB      (14 | (64 << 8) | PixelFormatAlpha  | PixelFormatPAlpha | PixelFormatExtended)
#define    PixelFormat32bppCMYK       (15 | (32 << 8))
#define    PixelFormatMax             16

pfnGdiplusStartup GdiplusStartup;
pfnGdiplusShutdown GdiplusShutdown;
pfnGdipCreateBitmapFromStream GdipCreateBitmapFromStream;
pfnGdipDisposeImage GdipDisposeImage;
pfnGdipGetImageWidth GdipGetImageWidth;
pfnGdipGetImageHeight GdipGetImageHeight;
pfnGdipBitmapLockBits GdipBitmapLockBits;
pfnGdipBitmapUnlockBits GdipBitmapUnlockBits;

BOOL SfInitGdiPlus(
	VOID
	);
