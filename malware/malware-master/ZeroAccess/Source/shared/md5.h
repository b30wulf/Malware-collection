/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       MD5.H
*
*  VERSION:     1.00
*
*  DATE:        15 Jan 2016
*
*  ZeroAccess MD5 support header file.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

typedef struct {
	ULONG i[2];
	ULONG buf[4];
	unsigned char in[64];
	unsigned char digest[16];
} MD5_CTX;

typedef VOID(WINAPI *PMD5Init) (MD5_CTX *context);
typedef VOID(WINAPI *PMD5Update)(MD5_CTX *context, const unsigned char *input, unsigned int inlen);
typedef VOID(WINAPI *PMD5Final) (MD5_CTX *context);

extern PMD5Init MD5Init;
extern PMD5Update MD5Update;
extern PMD5Final MD5Final;

BOOLEAN SfInitMD5(
	VOID
	);
