#pragma once
#include <map>

class ClientmodManager
{
	DECLARE_SINGLETON(ClientmodManager);
public:
	BOOL GetModuleDatFilepath(LPCTSTR moduleName, tstring& filepath);
	void ListAllModuleNames(TStringVector& modnameList);

private:
	typedef struct  
	{
		ULARGE_INTEGER	dllLastWriteTime;
		ULARGE_INTEGER	datLastWriteTime;
	} MODULE_INFO;
	typedef std::map<tstring, MODULE_INFO> ModuleInfoMap;

private:
	void EnumModules(ModuleInfoMap& miMap) const;
	void CheckModules(ModuleInfoMap& miMap) const;
	BOOL FormatModule(LPCTSTR modname) const;

private:
	tstring			m_modDirpath;
	ModuleInfoMap	m_miMap;
};
