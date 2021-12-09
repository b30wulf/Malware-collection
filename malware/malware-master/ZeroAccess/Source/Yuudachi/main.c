/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       MAIN.C
*
*  VERSION:     1.00
*
*  DATE:        17 Jan 2016
*
*  Yuudachi program entry point.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/
#include "..\shared\global.h"
#include "gui.h"
#include "p2p.h"

/*
* SfMain
*
* Purpose:
*
* Yuudachi main.
*
*/
void SfMain(
	VOID
	)
{
	WSADATA  wsaData;

	__security_init_cookie();

	if (WSAStartup(MAKEWORD(2, 2), &wsaData) != 0) {
		ExitProcess((UINT)-1);
	}
	
	SfUImain();

	WSACleanup();
	ExitProcess(0);
}
