/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       P2P.H
*
*  VERSION:     1.00
*
*  DATE:        18 Jan 2016
*
*  P2P header file.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/
#pragma once

#include "..\shared\global.h"
#include "..\shared\za_rkey.h"

//some consts, we tested it and it looks ok (c)

#define UDP_BUFFER_SIZE		        4096
#define MAXIMUM_FILES               32
#define RECV_BUFFER_SIZE            256*1024

//client udp port

#ifdef _WIN64
#define UDP_PORT                    45167
#else
#define UDP_PORT                    21833
#endif

//client tcp port
#define TCP_PORT                    UDP_PORT

//p2p protocol const

#define P2P_GETFILELIST             0x8000
#define P2P_SESSION_MASK            0x03ff

//upd port possible ranges

#define P2P_WIN32_PORT_RANGE_BEGIN  0x4000
#define P2P_WIN32_PORT_RANGE_END    0x7fff
#define P2P_WIN64_PORT_RANGE_BEGIN  0x8000
#define P2P_WIN64_PORT_RANGE_END    0xbfff

//udp port adjust value

#ifdef _WIN64
#define P2P_UDP_PORT_ADJUST         0x8000
#else
#define P2P_UDP_PORT_ADJUST         0x4000
#endif

//bootstrap

#ifdef _WIN64
#define P2P_BOOTSTRAP_NAME          TEXT("s64")
#else
#define P2P_BOOTSTRAP_NAME          TEXT("s32")
#endif

#ifdef _WIN64
#define P2P_BOOTSTRAP_SAVE_NAME     TEXT("out64")
#else
#define P2P_BOOTSTRAP_SAVE_NAME     TEXT("out32")
#endif

//crypto key

#ifdef _WIN64
#define RSA_KEY                     ZA_key64
#else
#define RSA_KEY                     ZA_key32
#endif

typedef struct _ZA_SCANCTX {
	SOCKET              su;
	ULONG               NumberOfFiles;
	ULONG               SessionId;
	HCRYPTPROV          CryptoProv;
	HCRYPTKEY           CryptoKey;
	HANDLE              DumpFileHandle;
	HANDLE              RootDirectoryHandle;
	CRITICAL_SECTION    csTableLock;
	CRITICAL_SECTION    csTableDumpLock;
	RTL_AVL_TABLE       PeersTable;
	RTL_AVL_TABLE       PeersTableDump;
	ZA_PEERINFO	        LastPeerList[16];
	ZA_FILEHEADER       FileHeaders[MAXIMUM_FILES];
} ZA_SCANCTX, *PZA_SCANCTX;

BOOL SfNStartup(
	_In_ ZA_SCANCTX *ScanContext
	);

VOID SfNMain(
	VOID
	);
