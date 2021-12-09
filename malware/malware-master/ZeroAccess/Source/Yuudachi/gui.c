/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       GUI.C
*
*  VERSION:     1.01
*
*  DATE:        22 Jan 2016
*
*  Yuudachi GUI support routines.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/
#define OEMRESOURCE
#include "..\shared\global.h"
#include "p2p.h"
#include "gui.h"

#include <commctrl.h>
#pragma comment(lib, "ComCtl32.Lib")

static const WCHAR	T_SFWNDTITLE[] = TEXT("ZeroAccess monitor");
static const WCHAR	T_SFMAINWNDCLASS[] = TEXT("za root class");

ZA_GUI_CONTEXT g_guictx;

/*
* SfUIAddEvent
*
* Purpose:
*
* Output event.
*
*/
VOID SfUIAddEvent(
	_In_opt_ PVOID ScanContext,
	_In_ ULONG Event,
	_In_opt_ LPWSTR lpValue
	)
{
	LVITEM     lvitem;
	INT        index;
	ULONG      n;
	LPWSTR     lpEvent;
	WCHAR      szBuffer[MAX_PATH];
	ZA_SCANCTX *pCtx = (ZA_SCANCTX*)ScanContext;

	switch (Event) {

	case GUI_EVENT_ERROR:
		lpEvent = TEXT("Error");
		break;
	case GUI_EVENT_CONNECTION:
		lpEvent = TEXT("Connection");
		break;
	case GUI_EVENT_PACKET_RECV:
		lpEvent = TEXT("PacketReceived");
		break;
	case GUI_EVENT_PACKET_SEND:
		lpEvent = TEXT("PacketSend");
		break;
	case GUI_EVENT_DOWNLOAD_FILE:
		lpEvent = TEXT("FileDownload");
		break;
	case GUI_EVENT_FILE_HEADER:
		lpEvent = TEXT("FileHeader");
		break;
	case GUI_EVENT_PEER_HEADER:
		lpEvent = TEXT("PeerHeader");
		break;
	case GUI_EVENT_NEWROUND:
		lpEvent = TEXT("NewRound");
		break;
	case GUI_EVENT_PACKET_HEADER:
		lpEvent = TEXT("PacketHeader");
		break;
	case GUI_EVENT_INFORMATION:
		lpEvent = TEXT("Information");
		break;
	case GUI_EVENT_THREAD_STARTED:
	case GUI_EVENT_THREAD_TERMINATED:
		lpEvent = TEXT("Thread");
		break;
	default:
		lpEvent = TEXT("UnnamedEvent");
		break;
	}

	//Event
	RtlSecureZeroMemory(&lvitem, sizeof(lvitem));
	lvitem.mask = LVIF_TEXT;
	lvitem.iSubItem = 0;
	lvitem.iItem = MAXINT;
	lvitem.iImage = 0;
	lvitem.pszText = lpEvent;
	index = ListView_InsertItem(g_guictx.OutputWindow, &lvitem);

	//Value
	lvitem.mask = LVIF_TEXT;
	lvitem.iSubItem = 1;
	lvitem.pszText = lpValue;
	lvitem.iItem = index;
	ListView_SetItem(g_guictx.OutputWindow, &lvitem);

	RtlSecureZeroMemory(szBuffer, sizeof(szBuffer));
	_strcpy(szBuffer, TEXT("TotalEvents: "));
	ultostr(ListView_GetItemCount(g_guictx.OutputWindow), _strend(szBuffer));
	SendMessage(g_guictx.StatusBar, SB_SETTEXT, (WPARAM)0, (LPARAM)&szBuffer);

	if (pCtx) {
		_strcpy(szBuffer, TEXT("Peers: "));	
		n = RtlNumberGenericTableElementsAvl(&pCtx->PeersTable);
		ultostr(n, _strend(szBuffer));
		SendMessage(g_guictx.StatusBar, SB_SETTEXT, (WPARAM)1, (LPARAM)&szBuffer);

		_strcpy(szBuffer, TEXT("Peers in dump: "));
		n = RtlNumberGenericTableElementsAvl(&pCtx->PeersTableDump);
		ultostr(n, _strend(szBuffer));
		SendMessage(g_guictx.StatusBar, SB_SETTEXT, (WPARAM)2, (LPARAM)&szBuffer);

		_strcpy(szBuffer, TEXT("Files: "));
		ultostr(pCtx->NumberOfFiles, _strend(szBuffer));
		SendMessage(g_guictx.StatusBar, SB_SETTEXT, (WPARAM)3, (LPARAM)&szBuffer);
	}
	ListView_RedrawItems(g_guictx.OutputWindow, ListView_GetItemCount(g_guictx.OutputWindow), -1);
	UpdateWindow(g_guictx.OutputWindow);
}

/*
* SfUIMainWindowResize
*
* Purpose:
*
* Main window WM_SIZE handler.
*
*/
VOID SfUIMainWindowResize(
	VOID
	)
{
	RECT r1, StatusBarRect;
	LONG sizeY;

	SendMessage(g_guictx.StatusBar, WM_SIZE, 0, 0);

	RtlSecureZeroMemory(&StatusBarRect, sizeof(StatusBarRect));
	GetWindowRect(g_guictx.StatusBar, &StatusBarRect);

	if (g_guictx.OutputWindow) {

		RtlSecureZeroMemory(&r1, sizeof(r1));
		GetClientRect(g_guictx.MainWindow, &r1);

		sizeY = StatusBarRect.bottom - StatusBarRect.top;

		SetWindowPos(g_guictx.OutputWindow, NULL, 0, 0,
			r1.right,
			r1.bottom - sizeY,
			SWP_NOMOVE | SWP_NOZORDER);
	}
}

/*
* SfUIMainWindowProc
*
* Purpose:
*
* Main window message handler.
*
*/
LRESULT CALLBACK SfUIMainWindowProc(HWND hwnd, UINT uMsg, WPARAM wParam, LPARAM lParam)
{
	switch (uMsg) {

	case WM_GETMINMAXINFO:
		if (lParam) {
			((PMINMAXINFO)lParam)->ptMinTrackSize.x = 400;
			((PMINMAXINFO)lParam)->ptMinTrackSize.y = 256;
		}
		break;

	case WM_SIZE:
		if (!IsIconic(hwnd)) {
			SfUIMainWindowResize();
		}
		break;

	case WM_CLOSE:
		InterlockedExchange((PLONG)&g_guictx.bShutdown, (LONG)TRUE);
		PostQuitMessage(0);
		break;

	default:
		break;
	}
	return DefWindowProc(hwnd, uMsg, wParam, lParam);
}

/*
* SfUICreateControls
*
* Purpose:
*
* Initialize gui controls.
*
*/
void SfUICreateControls(
	HWND hwndParent
	)
{
	LVCOLUMNW   col;
	INT         status_parts[5];
	RECT        client_rect;

	GetClientRect(g_guictx.MainWindow, &client_rect);

	g_guictx.StatusBar = CreateWindowEx(0, STATUSCLASSNAME, NULL,
		WS_VISIBLE | WS_CHILD | SBARS_SIZEGRIP, 0, 
		client_rect.bottom - client_rect.top - 20, 
		client_rect.right - client_rect.left, 
		20, 
		g_guictx.MainWindow, (HMENU)1001, g_guictx.hInstance, NULL);

	if (g_guictx.StatusBar) {
		status_parts[0] = 200;
		status_parts[1] = 400;
		status_parts[2] = 600;
		status_parts[3] = 700;
		status_parts[4] = -1;
		SendMessage(g_guictx.StatusBar, SB_SETPARTS, (WPARAM)4, (LPARAM)&status_parts);
	}

	g_guictx.OutputWindow = CreateWindowEx(
		0,
		WC_LISTVIEW,
		NULL,        
		WS_CHILD | WS_VISIBLE | LVS_REPORT | LVS_SINGLESEL,
		0, 0, 0, 0,   
		hwndParent,
		(HMENU)0,   
		(HINSTANCE)g_guictx.hInstance,
		NULL);


	if (g_guictx.OutputWindow) {

		ListView_SetExtendedListViewStyle(g_guictx.OutputWindow,
			LVS_EX_FULLROWSELECT | LVS_EX_DOUBLEBUFFER | LVS_EX_GRIDLINES | LVS_EX_LABELTIP);

		RtlSecureZeroMemory(&col, sizeof(col));
		col.mask = LVCF_TEXT | LVCF_SUBITEM | LVCF_FMT | LVCF_WIDTH | LVCF_ORDER;
		col.iSubItem = 1;
		col.pszText = L"Event";
		col.fmt = LVCFMT_LEFT;
		col.iOrder = 0;
		col.iImage = - 1;
		col.cx = 120;
		ListView_InsertColumn(g_guictx.OutputWindow, 1, &col);

		col.iSubItem = 2;
		col.pszText = L"Value";
		col.iOrder = 1;
		col.cx = 600;
		ListView_InsertColumn(g_guictx.OutputWindow, 2, &col);
	}
}

/*
* SfUImain
*
* Purpose:
*
* Create main window and all components.
*
*/
void SfUImain(
	VOID
	)
{
	MSG						msg1;
	WNDCLASSEX				wincls;
	BOOL					rv = TRUE, cond = FALSE;
	ATOM					class_atom = 0;
	INITCOMMONCONTROLSEX    icex;

	RtlSecureZeroMemory(&g_guictx, sizeof(g_guictx));

	icex.dwSize = sizeof(INITCOMMONCONTROLSEX);
	icex.dwICC = ICC_LISTVIEW_CLASSES | ICC_BAR_CLASSES;
	InitCommonControlsEx(&icex);

	g_guictx.hInstance = GetModuleHandle(NULL);

	wincls.cbSize = sizeof(WNDCLASSEX);
	wincls.style = 0;
	wincls.lpfnWndProc = &SfUIMainWindowProc;
	wincls.cbClsExtra = 0;
	wincls.cbWndExtra = 0;
	wincls.hInstance = g_guictx.hInstance;
	wincls.hIcon = NULL;
	wincls.hCursor = (HCURSOR)LoadImage(NULL, MAKEINTRESOURCE(OCR_NORMAL), IMAGE_CURSOR, 0, 0, LR_SHARED);
	wincls.hbrBackground = 0;
	wincls.lpszMenuName = NULL;
	wincls.lpszClassName = T_SFMAINWNDCLASS;
	wincls.hIconSm = 0;
	
	do {
		class_atom = RegisterClassEx(&wincls);
		if (class_atom == 0)
			break;

		g_guictx.MainWindow = CreateWindowEx(0, MAKEINTATOM(class_atom), T_SFWNDTITLE,
			WS_BORDER | WS_VISIBLE | WS_OVERLAPPEDWINDOW, CW_USEDEFAULT, CW_USEDEFAULT, 800, 600, NULL, NULL, g_guictx.hInstance, NULL);

		if (g_guictx.MainWindow == NULL)
			break;

		SfUICreateControls(g_guictx.MainWindow);
		SendMessage(g_guictx.MainWindow, WM_SIZE, 0, 0);

		SfNMain();

		do {
			rv = GetMessage(&msg1, NULL, 0, 0);

			if (rv == -1)
				break;

			if (IsDialogMessage(g_guictx.MainWindow, &msg1))
				continue;

			TranslateMessage(&msg1);
			DispatchMessage(&msg1);
		} while (rv != 0);

	} while (cond);

	if (class_atom != 0)
		UnregisterClass(MAKEINTATOM(class_atom), g_guictx.hInstance);
}
