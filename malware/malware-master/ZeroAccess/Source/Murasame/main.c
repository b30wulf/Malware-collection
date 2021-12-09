/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       MAIN.C
*
*  VERSION:     1.01
*
*  DATE:        20 Jan 2016
*
*  Murasame program entry point.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "..\shared\global.h"
#include "..\shared\cui.h"
#include "..\shared\gdip.h"

#include <windows.h>
#include <Shlwapi.h>

#pragma comment(lib, "Shlwapi.lib")

HANDLE g_ConOut = NULL;
WCHAR BE = 0xFEFF;
BOOL g_ConsoleOutput = FALSE;

#define T_SFEXTRACTTITLE L"Sirefef/ZeroAccess 3 extractor v1.0 (18/01/16)"
#define T_SFEXTRACTUSAGE L"Usage: zaextract inputfile [outputfile] hexkey\n\r\te.g. zaextract dropper.bin extracted.bin 0x12345678\r\n"
#define T_SFEXTRACTED    L"File extracted "
#define T_SFEXTRACTFAIL  L"\r\nError while extracting file"
#define T_SFINITFAILED   L"Required GDI+ routines cannot be found"
#define T_SFPRESSANYKEY  L"\r\nPress Enter to exit"

/*
* SfExtractDropper
*
* Purpose:
*
* Extract Sirefef/ZeroAccess from image resource.
*
* CNG variant
*
*/
UINT SfExtractDropper(
	LPWSTR lpCommandLine
	)
{
	BOOL                  cond = FALSE, bSuccess = FALSE;
	ULONG                 c, uKey = 0, imagesz;
	WCHAR                 szInputFile[MAX_PATH + 1];
	WCHAR                 szOutputFile[MAX_PATH + 1];
	WCHAR                 szKey[MAX_PATH];
	PVOID                 ImageBase = NULL, EncryptedData = NULL, DecryptedData = NULL;
	IStream              *pImageStream;
	ULONG_PTR             gdiplusToken = 0;
	GdiplusStartupInput   input;
	GdiplusStartupOutput  output;
	PVOID                 BitmapPtr = NULL;
	GdiPlusBitmapData     BitmapData;
	GdiPlusRect           rect;
	SIZE_T                sz;
	PULONG                ptr, i_ptr;
	
	//input file
	c = 0;
	RtlSecureZeroMemory(szInputFile, sizeof(szInputFile));
	GetCommandLineParam(lpCommandLine, 1, (LPWSTR)&szInputFile, MAX_PATH, &c);
	if (c == 0) {
		SfcuiPrintText(g_ConOut,
			T_SFEXTRACTUSAGE,
			g_ConsoleOutput, FALSE);
		return (UINT)-1;
	}

	//output file
	c = 0;
	RtlSecureZeroMemory(&szOutputFile, sizeof(szOutputFile));
	GetCommandLineParam(lpCommandLine, 2, (LPWSTR)&szOutputFile, MAX_PATH, &c);
	if (c == 0) {
		_strcpy(szOutputFile, TEXT("extracted.bin"));
	}

	//key
	c = 0;
	RtlSecureZeroMemory(&szKey, sizeof(szKey));
	GetCommandLineParam(lpCommandLine, 3, (LPWSTR)&szKey, MAX_PATH, &c);
	if ((c == 0) || (c > 10)) {
		SfcuiPrintText(g_ConOut,
			T_SFEXTRACTUSAGE,
			g_ConsoleOutput, FALSE);
		return (UINT)-1;
	}

	c = 0;
	if (locase_w(szKey[1]) == 'x') {
		c = 2;
	} 
	uKey = hextoul(&szKey[c]);

	do {

		ImageBase = SfuCreateFileMappingNoExec(szInputFile);
		if (ImageBase == NULL)
			break;

		c = 0;
		EncryptedData = SfLdrQueryResourceData(1, ImageBase, &c);
		if ((EncryptedData == NULL) || (c == 0))
			break;

		pImageStream = SHCreateMemStream((BYTE *)EncryptedData, (UINT)c);
		if (pImageStream == NULL)
			break;

		RtlSecureZeroMemory(&input, sizeof(input));
		RtlSecureZeroMemory(&output, sizeof(output));
		input.GdiplusVersion = 1;

		if (GdiplusStartup(&gdiplusToken, &input, &output) != GdiplusOk)
			break;

		BitmapPtr = NULL;
		if (GdipCreateBitmapFromStream(pImageStream, &BitmapPtr) != GdiplusOk)
			break;

		RtlSecureZeroMemory(&rect, sizeof(rect));
		
		if (
			(GdipGetImageWidth(BitmapPtr, (UINT *)&rect.Width) == GdiplusOk) &&
			(GdipGetImageHeight(BitmapPtr, (UINT *)&rect.Height) == GdiplusOk)
			)
		{
			RtlSecureZeroMemory(&BitmapData, sizeof(BitmapData));
			if (GdipBitmapLockBits(BitmapPtr, &rect, ImageLockModeRead, PixelFormat32bppARGB, &BitmapData) == GdiplusOk) {

				c = (rect.Width * rect.Height);
				
				imagesz = sizeof(ULONG) * c;
				sz = imagesz;
				DecryptedData = NULL;
				NtAllocateVirtualMemory(NtCurrentProcess(), &DecryptedData, 0, &sz, MEM_COMMIT | MEM_RESERVE, PAGE_READWRITE);
				if (DecryptedData) {
					
					i_ptr = (PULONG)BitmapData.Scan0;
					ptr = DecryptedData;				
					while (c > 0) {
						*ptr = *i_ptr ^ uKey;
						ptr++;
						i_ptr++;
						c--;
					}

					bSuccess = (SfuWriteBufferToFile(szOutputFile, DecryptedData, imagesz, FALSE, FALSE) == imagesz);

					sz = 0;
					NtFreeVirtualMemory(NtCurrentProcess(), &DecryptedData, &sz, MEM_RELEASE);
				}
				GdipBitmapUnlockBits(BitmapPtr, &BitmapData);
			}
		}

	} while (cond);

	if (bSuccess == FALSE) {
		SfcuiPrintText(g_ConOut,
			T_SFEXTRACTFAIL,
			g_ConsoleOutput, FALSE);
	}
	else
	{
		SfcuiPrintText(g_ConOut,
			szOutputFile,
			g_ConsoleOutput, TRUE);
		SfcuiPrintText(g_ConOut,
			T_SFEXTRACTED,
			g_ConsoleOutput, TRUE);
	}

	if (BitmapPtr != NULL) {
		GdipDisposeImage(&BitmapPtr);
	}

	if (gdiplusToken != 0) {
		GdiplusShutdown(gdiplusToken);
	}

	if (pImageStream != NULL) {
		pImageStream->lpVtbl->Release(pImageStream);
	}

	if (ImageBase != NULL) {
		NtUnmapViewOfSection(NtCurrentProcess(), ImageBase);
	}
	return 0;
}

/*
* SfMain
*
* Purpose:
*
* Murasame main.
*
*/
void SfMain(
	VOID
	)
{
	BOOL         cond = FALSE;
	UINT         uResult = 0;
	DWORD        dwTemp;
	HANDLE       StdIn;
	INPUT_RECORD inp1;

	__security_init_cookie();

	do {

		g_ConOut = GetStdHandle(STD_OUTPUT_HANDLE);
		if (g_ConOut == INVALID_HANDLE_VALUE) {
			uResult = (UINT)-1;
			break;
		}

		g_ConsoleOutput = TRUE;
		if (!GetConsoleMode(g_ConOut, &dwTemp)) {
			g_ConsoleOutput = FALSE;
		}

		SetConsoleTitle(T_SFEXTRACTTITLE);
		SetConsoleMode(g_ConOut, ENABLE_LINE_INPUT | ENABLE_ECHO_INPUT | ENABLE_PROCESSED_OUTPUT);
		if (g_ConsoleOutput == FALSE) {
			WriteFile(g_ConOut, &BE, sizeof(WCHAR), &dwTemp, NULL);
		}

		if (SfInitGdiPlus()) {
			uResult = SfExtractDropper(GetCommandLine());
		}
		else {
			SfcuiPrintText(g_ConOut,
				T_SFINITFAILED,
				g_ConsoleOutput, FALSE);
		}

		if (g_ConsoleOutput) {

			SfcuiPrintText(g_ConOut,
				T_SFPRESSANYKEY,
				TRUE, FALSE);

			StdIn = GetStdHandle(STD_INPUT_HANDLE);
			if (StdIn != INVALID_HANDLE_VALUE) {
				RtlSecureZeroMemory(&inp1, sizeof(inp1));
				ReadConsoleInput(StdIn, &inp1, 1, &dwTemp);
				ReadConsole(StdIn, &BE, sizeof(BE), &dwTemp, NULL);
			}
		}

	} while (cond);

	ExitProcess(uResult);
}
