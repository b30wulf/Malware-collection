#pragma once
#include "CmdEnv.h"

class ICmd
{
public:
	virtual BOOL Execute(const TStringVector& parts, tstring& replyText, CmdEnv& env) = 0;
};
