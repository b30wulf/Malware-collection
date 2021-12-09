// TestMaster.cpp : 定义控制台应用程序的入口点。
//

#include "stdafx.h"
#include "CommandManager.h"
#include "TestMaster.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// 唯一的应用程序对象

CWinApp theApp;

using namespace std;

void RawMain()
{
	CommandManager::GetInstanceRef().Init();

	//StartMasterWorking();

	char cmdline[512] = {0};
	printf("type exit to end.\n");
	printf("Master>");
	gets_s(cmdline);
	CStringA cmd(cmdline);
	cmd.Trim();
	while (cmd != "exit")
	{
		if (cmd.GetLength() > 0)
		{
			tstring replyText;
			BOOL bOK = CommandManager::GetInstanceRef().Execute(CString(cmd), replyText);
			if (bOK)
			{
				printf("%s\n", t2a(replyText.c_str()));
			}
			else
			{
				printf("Error : %s\n", t2a(replyText.c_str()));
			}
		}

		ZeroMemory(cmdline, sizeof(cmdline));
		printf("Master>");
		gets_s(cmdline);
		cmd = cmdline;
		cmd.Trim();
	}
}

int _tmain(int argc, TCHAR* argv[], TCHAR* envp[])
{
	int nRetCode = 0;

	HMODULE hModule = ::GetModuleHandle(NULL);

	if (hModule != NULL)
	{
		// 初始化 MFC 并在失败时显示错误
		if (!AfxWinInit(hModule, NULL, ::GetCommandLine(), 0))
		{
			// TODO: 更改错误代码以符合您的需要
			_tprintf(_T("错误: MFC 初始化失败\n"));
			nRetCode = 1;
		}
		else
		{
			// TODO: 在此处为应用程序的行为编写代码。
			RawMain();
			//system("")
		}
	}
	else
	{
		// TODO: 更改错误代码以符合您的需要
		_tprintf(_T("错误: GetModuleHandle 失败\n"));
		nRetCode = 1;
	}

	return nRetCode;
}
