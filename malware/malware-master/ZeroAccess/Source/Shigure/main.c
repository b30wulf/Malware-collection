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
*  Shigure program entry point.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma comment(lib, "bcrypt.lib")

#include "..\shared\global.h"
#include "..\shared\cui.h"
#include "..\shared\cab.h"

#include <Bcrypt.h>

HANDLE g_ConOut = NULL;
WCHAR BE = 0xFEFF;
BOOL g_ConsoleOutput = FALSE;

#define T_SFDECRYPTTITLE L"Sirefef/ZeroAccess 3 decryptor v1.0 (10/01/16)"
#define T_SFDECRYPTUSAGE L"Usage: zadecrypt inputfile [outputfile]\n\r\te.g. zadecrypt in.dll out.bin\r\n"
#define T_SFDECRYPTED    L"File decrypted "
#define T_SFDECRYPTFAIL  L"\r\nError while decrypting file"
#define T_SFPRESSANYKEY  L"\r\nPress Enter to exit"

/*
* SfDecryptPayload
*
* Purpose:
*
* Decrypt container from resource using as hash md5 from file header bytes.
*
* CNG variant
*
*/
UINT SfDecryptPayload(
	LPWSTR lpParameter
	)
{
	BOOL                cond = FALSE, bSuccess = FALSE;
	PBYTE               cng_object, hashdata, decrypted, enc_data, extracted;
	ULONG               obj_sz, rlen, hdatasz, enc_data_size;
	BCRYPT_ALG_HANDLE   h_alg = NULL;
	BCRYPT_HASH_HANDLE  h_hash = NULL;
	BCRYPT_KEY_HANDLE   h_rc4key = NULL;
	NTSTATUS            status;
	HANDLE              pheap = NULL;
	PIMAGE_FILE_HEADER  fheader;
	PVOID               pdll = NULL;
	WCHAR               InputFile[MAX_PATH + 1], OutputFile[MAX_PATH + 1];

	rlen = 0;
	RtlSecureZeroMemory(InputFile, sizeof(InputFile));
	GetCommandLineParam(lpParameter, 1, InputFile, MAX_PATH, &rlen);
	if (rlen == 0) {
		SfcuiPrintText(g_ConOut,
			T_SFDECRYPTUSAGE,
			g_ConsoleOutput, FALSE);
		return (UINT)-1;
	}

	do {

		rlen = 0;
		GetCommandLineParam(lpParameter, 2, OutputFile, MAX_PATH, &rlen);
		
		if (rlen == 0)
			_strcpy(OutputFile, TEXT("out.bin"));
		
		pdll = SfuCreateFileMappingNoExec(InputFile);
		if (pdll == NULL)
			break;

		enc_data_size = 0;
		enc_data = SfLdrQueryResourceData(2, pdll, &enc_data_size);
		if (enc_data == NULL)
			break;

		fheader = &(RtlImageNtHeader(pdll)->FileHeader);

		status = BCryptOpenAlgorithmProvider(&h_alg, BCRYPT_MD5_ALGORITHM, NULL, 0);
		if (!NT_SUCCESS(status))
			break;
		obj_sz = 0;
		rlen = 0;
		status = BCryptGetProperty(h_alg, BCRYPT_OBJECT_LENGTH, (PUCHAR)&obj_sz, sizeof(obj_sz), &rlen, 0);
		if (!NT_SUCCESS(status))
			break;

		hdatasz = 0;
		rlen = 0;
		status = BCryptGetProperty(h_alg, BCRYPT_HASH_LENGTH, (PUCHAR)&hdatasz, sizeof(hdatasz), &rlen, 0);
		if (!NT_SUCCESS(status))
			break;

		pheap = HeapCreate(0, 0, 0);
		if (pheap == NULL)
			break;

		cng_object = HeapAlloc(pheap, HEAP_ZERO_MEMORY, obj_sz);
		if (cng_object == NULL)
			break;

		hashdata = HeapAlloc(pheap, HEAP_ZERO_MEMORY, hdatasz);
		if (hashdata == NULL)
			break;

		status = BCryptCreateHash(h_alg, &h_hash, cng_object, obj_sz, NULL, 0, 0);
		if (!NT_SUCCESS(status))
			break;

		status = BCryptHashData(h_hash, (PUCHAR)fheader, sizeof(IMAGE_FILE_HEADER), 0);
		if (!NT_SUCCESS(status))
			break;

		status = BCryptFinishHash(h_hash, hashdata, hdatasz, 0);
		if (!NT_SUCCESS(status))
			break;

		BCryptDestroyHash(h_hash);
		BCryptCloseAlgorithmProvider(h_alg, 0);
		HeapFree(pheap, 0, cng_object);
		h_alg = NULL;
		h_hash = NULL;

		status = BCryptOpenAlgorithmProvider(&h_alg, BCRYPT_RC4_ALGORITHM, NULL, 0);
		if (!NT_SUCCESS(status))
			break;

		obj_sz = 0;
		rlen = 0;
		status = BCryptGetProperty(h_alg, BCRYPT_OBJECT_LENGTH, (PUCHAR)&obj_sz, sizeof(obj_sz), &rlen, 0);
		if (!NT_SUCCESS(status))
			break;

		cng_object = HeapAlloc(pheap, HEAP_ZERO_MEMORY, obj_sz);
		if (cng_object == NULL)
			break;

		status = BCryptGenerateSymmetricKey(h_alg, &h_rc4key, cng_object, obj_sz, hashdata, hdatasz, 0);
		if (!NT_SUCCESS(status))
			break;

		decrypted = HeapAlloc(pheap, HEAP_ZERO_MEMORY, enc_data_size);
		if (decrypted == NULL)
			break;

		rlen = 0;
		status = BCryptEncrypt(h_rc4key, enc_data, enc_data_size, NULL, NULL, 0, decrypted, enc_data_size, &rlen, 0);
		if (!NT_SUCCESS(status))
			break;

		bSuccess = FALSE;
		enc_data_size = rlen;
		rlen = 0;
		extracted = SfcabExtractMemory(decrypted, enc_data_size, &rlen);
		if (extracted) {

			if (SfuWriteBufferToFile(OutputFile, extracted, rlen, FALSE, FALSE) == rlen) {
				bSuccess = TRUE;
			}
			LocalFree(extracted);
		}
		else {
			//failed to extract, drop cab as is
			if (SfuWriteBufferToFile(OutputFile, decrypted, enc_data_size, FALSE, FALSE) == enc_data_size) {
				bSuccess = TRUE;
			}
		}

		if (bSuccess) {

			SfcuiPrintText(g_ConOut,
				T_SFDECRYPTED,
				g_ConsoleOutput, FALSE);

			SfcuiPrintText(g_ConOut,
				OutputFile,
				g_ConsoleOutput, FALSE);
		}

	} while (cond);

	if (bSuccess == FALSE) {

		SfcuiPrintText(g_ConOut,
			T_SFDECRYPTFAIL,
			g_ConsoleOutput, FALSE);
		
	}

	if (h_rc4key != NULL)
		BCryptDestroyKey(h_rc4key);

	if (h_hash != NULL)
		BCryptDestroyHash(h_hash);

	if (h_alg != NULL)
		BCryptCloseAlgorithmProvider(h_alg, 0);

	if (pheap != NULL)
		HeapDestroy(pheap);

	if (pdll != 0)
		NtUnmapViewOfSection(NtCurrentProcess(), (PVOID)pdll);

	return 0;
}

/*
* SfMain
*
* Purpose:
*
* Shigure main.
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

		SetConsoleTitle(T_SFDECRYPTTITLE);
		SetConsoleMode(g_ConOut, ENABLE_LINE_INPUT | ENABLE_ECHO_INPUT | ENABLE_PROCESSED_OUTPUT);
		if (g_ConsoleOutput == FALSE) {
			WriteFile(g_ConOut, &BE, sizeof(WCHAR), &dwTemp, NULL);
		}

		uResult = SfDecryptPayload(GetCommandLine());

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
