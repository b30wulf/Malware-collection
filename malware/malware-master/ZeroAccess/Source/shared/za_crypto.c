/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       ZA_CRYPTO.C
*
*  VERSION:     1.01
*
*  DATE:        18 Jan 2016
*
*  ZeroAccess routines used for cryptography purposes.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#include "global.h"
#include "ea.h"

/*
* SfcVerifyFile
*
* Purpose:
*
* Verify file to be legit ZeroAccess signed binary.
*
*/
BOOL SfcVerifyFile(
	_In_ HCRYPTPROV  hProv,
	_In_ HCRYPTKEY hKey,
	_In_ MD5_CTX *ctx,
	_In_ PBYTE Image,
	_In_ DWORD ImageSize
	)
{
	HCRYPTHASH          lh_hash = 0;
	ULONG               CRC, SignSize = 0;
	BYTE                e_sign[128];
	PBYTE               p_resource_sign;
	PIMAGE_NT_HEADERS32 phdr;
	BOOL                bResult = FALSE;
	LDR_RESOURCE_INFO   resInfo;

	phdr = (PIMAGE_NT_HEADERS32)RtlImageNtHeader(Image);
	while (phdr != NULL) {

		resInfo.Type = (ULONG_PTR)RT_RCDATA; //type
		resInfo.Name = 1;           //id
		resInfo.Lang = 0;          //lang

		p_resource_sign = SfLdrQueryResourceDataEx(Image, &resInfo, &SignSize);
		if (p_resource_sign == NULL)
			break;

		if (SignSize != 128)
			break;

		if (!CryptCreateHash(hProv, CALG_MD5, 0, 0, &lh_hash))
			break;

		CRC = phdr->OptionalHeader.CheckSum;

		memcpy(e_sign, p_resource_sign, sizeof(e_sign));
		memset(p_resource_sign, 0, sizeof(e_sign));

		phdr->OptionalHeader.CheckSum = 0;

		MD5Update(ctx, Image, ImageSize);

		phdr->OptionalHeader.CheckSum = CRC;
		memcpy(p_resource_sign, e_sign, sizeof(e_sign));

		MD5Final(ctx);

		if (!CryptSetHashParam(lh_hash, HP_HASHVAL, (const BYTE *)&ctx->digest, 0)) {
			CryptDestroyHash(lh_hash);
			break;
		}

		bResult = CryptVerifySignatureW(lh_hash, (const BYTE *)&e_sign, sizeof(e_sign), hKey, 0, 0);
		CryptDestroyHash(lh_hash);
		break;
	}
	return bResult;
}

/*
* SfcIsFileLegit
*
* Purpose:
*
* Verify file to be legit ZeroAccess signed binary.
*
* Verification algorithm (as for current version)
*
* 1. Open dll file, read it to the allocated buffer, read extended attribute VER, 
*    containing retL packet data regarding file FileName, TimeStamp, FileSize, 
*    Signature (unusued in this verification);
*
* 2. Import required RSA key (hardcoded in the bot);
* 
* 3. Calc MD5 for FileName+TimeStamp+FileSize values;
* 
* 4. Find resource [1] in dll file, which is embedded signature used to check;
*
* 5. Remember PE header CRC value, set it to zero in PE file buffer;
* 
* 6. Copy embedded signature [1] to preallocated buffer, zero it in PE file buffer;
*
* 7. Update MD5 for PE file buffer (excluding PE CRC and signature);
*
* 8. Use result MD5 as hash value; 
*
* 9. Verify embedded signature.
*
* If anything from the above fail - file is not legit by ZeroAccess opinion.
*
* If you copy ZeroAccess downloaded files without copying EA data, it cannot be verified.
*
*/
NTSTATUS SfcIsFileLegit(
	_In_ LPWSTR lpFileName,
	_In_ PBYTE BotKey,
	_In_ DWORD BotKeySize
	)
{
	BOOL                cond = FALSE;
	PVOID               pBuffer;
	MD5_CTX             context;
	ZA_FILEHEADER       zaHeader;
	HCRYPTPROV          lh_prov = 0;
	HCRYPTKEY           lh_key = 0;
	HANDLE              hFile = NULL;
	NTSTATUS            status = STATUS_UNSUCCESSFUL;
	OBJECT_ATTRIBUTES   ObjectAttributes;
	IO_STATUS_BLOCK     IoStatusBlock;
	UNICODE_STRING      usFileName;
	SIZE_T              memIO = 0;

	if (
		(lpFileName == NULL) ||
		(BotKey == NULL) ||
		(BotKeySize == 0)
		)
	{
		return status;
	}


	RtlSecureZeroMemory(&usFileName, sizeof(usFileName));

	do {

		if (RtlDosPathNameToNtPathName_U(lpFileName, &usFileName, NULL, NULL) == FALSE)
			break;

		InitializeObjectAttributes(&ObjectAttributes, &usFileName, OBJ_CASE_INSENSITIVE, NULL, NULL);
		status = NtOpenFile(&hFile, FILE_GENERIC_READ, &ObjectAttributes, &IoStatusBlock,
			FILE_SHARE_READ | FILE_SHARE_WRITE | FILE_SHARE_DELETE,
			FILE_NON_DIRECTORY_FILE | FILE_SYNCHRONOUS_IO_NONALERT
			);

		if (!NT_SUCCESS(status))
			break;

		RtlFreeUnicodeString(&usFileName);

		RtlSecureZeroMemory(&zaHeader, sizeof(zaHeader));
		if (!SfNtfsQueryFileHeaderFromEa(hFile, &zaHeader)) {
			status = STATUS_EA_LIST_INCONSISTENT;
			break;
		}

		status = STATUS_UNSUCCESSFUL;
		memIO = zaHeader.Size;
		pBuffer = NULL;
		if (
			(NT_SUCCESS(NtAllocateVirtualMemory(NtCurrentProcess(), &pBuffer, 0, &memIO, MEM_COMMIT | MEM_RESERVE, PAGE_READWRITE))) &&
			(pBuffer != NULL)
			)
		{
			if (NT_SUCCESS(NtReadFile(hFile, NULL, NULL, NULL, &IoStatusBlock, pBuffer, zaHeader.Size, NULL, NULL))) {
				if (CryptAcquireContext(&lh_prov, NULL, MS_DEF_PROV, PROV_RSA_FULL, CRYPT_VERIFYCONTEXT)) {
					if (CryptImportKey(lh_prov, (const BYTE *)BotKey, BotKeySize, 0, 0, &lh_key)) {
						RtlSecureZeroMemory(&context, sizeof(context));
						MD5Init(&context);
						MD5Update(&context, (UCHAR*)&zaHeader, (UINT)3 * sizeof(ULONG)); //note: ZA_FILEHEADER without signature
						if (SfcVerifyFile(lh_prov, lh_key, &context, pBuffer, zaHeader.Size))
							status = STATUS_SUCCESS;

						CryptDestroyKey(lh_key);
					}
					CryptReleaseContext(lh_prov, 0);
				}
			}
			memIO = 0;
			NtFreeVirtualMemory(NtCurrentProcess(), &pBuffer, &memIO, MEM_RELEASE);
		}
		NtClose(hFile);
		hFile = NULL;

	} while (cond);

	if (hFile != NULL) NtClose(hFile);

	if (usFileName.Buffer != NULL) {
		RtlFreeUnicodeString(&usFileName);
	}
	return status;
}

/*
* SfcValidateFileHeader
*
* Purpose:
*
* Verify fileheader from retL packet.
*
*/
BOOL SfcValidateFileHeader(
	_In_ HCRYPTPROV hCryptoProv,
	_In_ HCRYPTKEY hCryptKey,
	_In_ ZA_FILEHEADER *FileHeader
	)
{
	BOOL bResult, cond = FALSE;
	HCRYPTHASH   hCryptHash = 0;
	MD5_CTX      ctx;

	bResult = FALSE;

	if (FileHeader == NULL)
		return FALSE;

	do {

		if (!CryptCreateHash(hCryptoProv, CALG_MD5, 0, 0, &hCryptHash))
			break;

		MD5Init(&ctx);
		MD5Update(&ctx, (UCHAR*)FileHeader, (UINT)3 * sizeof(ULONG));
		MD5Final(&ctx);

		if (!CryptSetHashParam(hCryptHash, HP_HASHVAL, (const BYTE *)&ctx.digest, 0))
			break;
		
		bResult = CryptVerifySignatureW(hCryptHash, (const BYTE *)&FileHeader->Signature, sizeof(FileHeader->Signature), hCryptKey, 0, 0);

	} while (cond);

	if (hCryptHash != 0) {
		CryptDestroyHash(hCryptHash);
	}
	return bResult;
}
