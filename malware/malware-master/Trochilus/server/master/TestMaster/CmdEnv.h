#pragma once
#include <map>

#define DECLARE_SETER_GETER(_name)	\
	void Set##_name(LPCTSTR val) {SetVar(a2t(""#_name), val);};	\
	LPCTSTR Get##_name() {return GetVal(a2t(""#_name));};

class CmdEnv
{
public:
	CmdEnv();
	~CmdEnv();

	void SetVar(LPCTSTR name, LPCTSTR val);
	LPCTSTR GetVal(LPCTSTR name);

	DECLARE_SETER_GETER(Clientid);

private:
	typedef std::map<tstring, tstring> VarMap;

private:
	VarMap	m_varMap;
};
