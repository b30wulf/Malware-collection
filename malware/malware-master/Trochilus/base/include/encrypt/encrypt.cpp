#include "stdafx.h"
#include "encrypt/encrypt.h"

void Byte2HEX(const LPBYTE bytes, DWORD dwSize, std::wstring* pHexString)
{
	if (NULL == bytes || NULL == pHexString) return;

	tostringstream toss;

	for (DWORD dwIndex = 0; dwIndex < dwSize; dwIndex++)
	{
		BYTE dwByte = bytes[dwIndex];

		BYTE number[2] = {dwByte / 16, dwByte % 16};
		for (int i = 0; i < 2; i++)
		{
			if (number[i] >= 0 && number[i] <= 9)
			{
				toss << (TCHAR)('0' + number[i]);
			}
			else
			{
				switch (number[i])
				{
				case 10:
					toss << 'a';
					break;
				case 11:
					toss << 'b';
					break;
				case 12:
					toss << 'c';
					break;
				case 13:
					toss << 'd';
					break;
				case 14:
					toss << 'e';
					break;
				case 15:
					toss << 'f';
					break;
				}
			}
		}
	}

	*pHexString = toss.str().c_str();
}

BOOL GetFileMD5(LPCTSTR filepath, std::wstring* pMd5String)
{
	if (NULL == filepath || NULL == pMd5String) return FALSE;

	HANDLE hFile = ::CreateFile(filepath, GENERIC_READ, FILE_SHARE_READ, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
	if (INVALID_HANDLE_VALUE == hFile)
	{
		return FALSE;
	}

	MD5_CTX md5;
	BYTE pBuffer[MD5_READ_BUFFER_LENGTH] = {0};
	DWORD dwRead = 0;
	while (::ReadFile(hFile, pBuffer, MD5_READ_BUFFER_LENGTH, &dwRead, NULL) && dwRead > 0)
	{
		md5.MD5Update(pBuffer, dwRead);
	}

	::CloseHandle(hFile);

	BYTE md5Byte[16] = {0};
	md5.MD5Final(md5Byte);

	Byte2HEX(md5Byte, 16, pMd5String);

	return TRUE;
}