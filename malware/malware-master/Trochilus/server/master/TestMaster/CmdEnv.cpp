#include "stdafx.h"
#include "CmdEnv.h"

CmdEnv::CmdEnv()
{

}

CmdEnv::~CmdEnv()
{

}

void CmdEnv::SetVar( LPCTSTR name, LPCTSTR val )
{
	m_varMap[name] = val;
}

LPCTSTR CmdEnv::GetVal( LPCTSTR name )
{
	VarMap::iterator iter = m_varMap.find(name);
	if (iter != m_varMap.end())
	{
		return iter->second.c_str();
	}
	else
	{
		return _T("");
	}
}
