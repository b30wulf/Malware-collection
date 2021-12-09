/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       GUI.H
*
*  VERSION:     1.00
*
*  DATE:        17 Jan 2016
*
*  Yuudachi GUI support routines header file.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/

#pragma once

typedef struct _ZA_GUI_CONTEXT {
	HINSTANCE hInstance;
	HWND MainWindow;
	HWND OutputWindow;
	HWND StatusBar;
	BOOL bShutdown;
} ZA_GUI_CONTEXT, *PZA_GUI_CONTEXT;

extern ZA_GUI_CONTEXT g_guictx;

#define GUI_EVENT_ERROR             0
#define GUI_EVENT_CONNECTION        1
#define GUI_EVENT_PACKET_RECV       2
#define GUI_EVENT_PACKET_SEND       3
#define GUI_EVENT_DOWNLOAD_FILE     4
#define GUI_EVENT_FILE_HEADER       5
#define GUI_EVENT_PEER_HEADER       6
#define GUI_EVENT_NEWROUND          7
#define GUI_EVENT_PACKET_HEADER     8
#define GUI_EVENT_INFORMATION       100
#define GUI_EVENT_THREAD_STARTED    1000
#define GUI_EVENT_THREAD_TERMINATED 2000

void SfUImain(
	VOID
	);

VOID SfUIAddEvent(
	_In_opt_ PVOID ScanContext,
	_In_ ULONG Event,
	_In_opt_ LPWSTR lpValue
	);
