#pragma once
#include "ICmd.h"

class ClientsCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env );
};

class ClientinfoCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env );
};

class SelectCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env );
};

class DownloadCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class UploadCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class ListmodCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class OpenshellCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class CloseshellCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class ExecuteShellCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class InstallMod : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class AllModCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};

class SDCmd : public ICmd
{
public:
	virtual BOOL Execute( const TStringVector& parts, tstring& replyText, CmdEnv& env);
};
