/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       ZA.H
*
*  VERSION:     1.00
*
*  DATE:        17 Jan 2016
*
*  ZeroAccess common structures and definitions used within all projects.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

typedef struct _ZA_PEERINFO {
	ULONG   IP;
	union {
		ULONG PortAndTimeStamp;
		struct {
			ULONG   Port : 14;
			ULONG   TimeStamp : 18;
		} DUMMYSTRUCTNAME;
	} DUMMYUNIONNAME;
} ZA_PEERINFO, *PZA_PEERINFO;

typedef struct _ZA_PACKETHEADER {
	ULONG   CRC;     // CRC32
	ULONG   Command; // getL, retL
	ULONG   SessionID;   // crypto-random
	USHORT  Opt1;
	USHORT  Opt2;
} ZA_PACKETHEADER, *PZA_PACKETHEADER;

typedef struct _ZA_FILEHEADER {
	ULONG	Name;
	ULONG	Time;
	ULONG	Size;
	BYTE	Signature[128];
} ZA_FILEHEADER, *PZA_FILEHEADER;

typedef struct _ZA_PACKET {
	ZA_PACKETHEADER     Header;
	ZA_PEERINFO         PeerList[16];
} ZA_PACKET, *PZA_PACKET;

typedef struct _ZA_CALLHOME {
	ULONG   BotID;
	ULONG   AffMod;
	BYTE    Country[2];
	BYTE    OsVer;
	BYTE    OsFlag;
	ULONG   AffId;
	ULONG   CRC; //CRC32
} ZA_CALLHOME, *PZA_CALLHOME;
