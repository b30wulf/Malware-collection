#include "stdafx.h"
#include "file/MyFile.h"
#include "BinNames.h"
#include "common.h"
#include "ClientmodManager.h"

ClientmodManager::ClientmodManager()
{

}

ClientmodManager::~ClientmodManager()
{

}

BOOL ClientmodManager::Init()
{
	m_modDirpath = GetBinFilepath();
	m_modDirpath += MASTER_MODULE_PATH;
	MakeUpDirs(m_modDirpath.c_str());

	EnumModules(m_miMap);
	CheckModules(m_miMap);

	return TRUE;
}

void ClientmodManager::Deinit()
{

}

BOOL ClientmodManager::GetModuleDatFilepath( LPCTSTR moduleName, tstring& filepath )
{
	ModuleInfoMap::iterator iter = m_miMap.find(moduleName);
	if (iter == m_miMap.end()) return FALSE;

	filepath = m_modDirpath;
	filepath += moduleName;
	filepath += MODULE_POSTFIX;

	return TRUE;
}

void ClientmodManager::ListAllModuleNames( TStringVector& modnameList )
{
	ModuleInfoMap::iterator iter = m_miMap.begin();
	for (; iter != m_miMap.end(); iter++)
	{
		if (iter->first == _T("servant")) continue;
		modnameList.push_back(iter->first);
	}
}

void ClientmodManager::EnumModules( ModuleInfoMap& miMap ) const
{
	//遍历模块目录，记录dat和dll的时间
	tstring findstr = m_modDirpath;
	findstr += _T("*");

	WIN32_FIND_DATA finddata = {0};
	HANDLE hFind = ::FindFirstFile(findstr.c_str(), &finddata);
	if (INVALID_HANDLE_VALUE == hFind) return;

	do 
	{
		if (finddata.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) continue;

		tstring filename = finddata.cFileName;
		tstring::size_type pos = filename.find('.');
		if (pos == tstring::npos || filename.back() == '.') continue;

		tstring postfix = filename.substr(pos + 1);
		filename = filename.substr(0, pos);
		makeLower(filename);
		makeLower(postfix);

		ModuleInfoMap::iterator finditer = miMap.find(filename.c_str());
		if (finditer == miMap.end())
		{
			MODULE_INFO info = {0};
			std::pair<ModuleInfoMap::iterator, bool> res = miMap.insert(ModuleInfoMap::value_type(filename.c_str(), info));
			if (res.second) finditer = res.first;
		}
		if (finditer == miMap.end()) continue;

		if (postfix == _T("dat"))
		{
			finditer->second.datLastWriteTime.HighPart = finddata.ftLastWriteTime.dwHighDateTime;
			finditer->second.datLastWriteTime.LowPart = finddata.ftLastWriteTime.dwLowDateTime;
		}
		else if (postfix == _T("dll"))
		{
			finditer->second.dllLastWriteTime.HighPart = finddata.ftLastWriteTime.dwHighDateTime;
			finditer->second.dllLastWriteTime.LowPart = finddata.ftLastWriteTime.dwLowDateTime;
		}
	} while (::FindNextFile(hFind, &finddata));

	::FindClose(hFind);
}

void ClientmodManager::CheckModules( ModuleInfoMap& miMap ) const
{
	ModuleInfoMap::iterator iter = miMap.begin();
	while (iter != miMap.end())
	{
		const tstring& modname = iter->first;
		const MODULE_INFO& info = iter->second;

		//如果dll比较新，则重新制作dat文件
		if (info.dllLastWriteTime.QuadPart > info.datLastWriteTime.QuadPart)
		{
			if (! FormatModule(modname.c_str()))
			{
				errorLog(_T("format module [%s] failed"), modname.c_str());
			}
		}

		iter++;
	}
}

BOOL ClientmodManager::FormatModule( LPCTSTR modname ) const
{
	tstring dllfilepath = m_modDirpath;
	dllfilepath += modname;
	dllfilepath += _T(".dll");

	tstring datfilepath = m_modDirpath;
	datfilepath += modname;
	datfilepath += ENCRYPTED_MODULE_POSTFIX;

	//删除dat文件
	::DeleteFile(datfilepath.c_str());

	//读取dll文件内容，并异或加密
	MyFile dllFile;
	if (! dllFile.Open(dllfilepath.c_str(), GENERIC_READ, OPEN_EXISTING, FILE_SHARE_READ))
	{
		errorLog(_T("open [%s] failed"), dllfilepath.c_str());
		return FALSE;
	}
	
	ByteBuffer content;
	if (! dllFile.ReadAll(content))
	{
		errorLog(_T("read file content failed"));
		return FALSE;
	}

	if (! XorFibonacciCrypt(content, content.Size(), content, 3, 5))
	{
		errorLog(_T("crypt mod [%s] failed"), modname);
		return FALSE;
	}

	//创建dat文件，并写入
	MyFile datFile;
	if (! datFile.Open(datfilepath.c_str(), GENERIC_ALL, CREATE_ALWAYS, 0))
	{
		errorLog(_T("open datfile failed[%s]"), modname);
		return FALSE;
	}

	DWORD dwWritten = 0;
	if (! ::WriteFile(datFile, (LPBYTE)content, content.Size(), &dwWritten, NULL)
		|| dwWritten != content.Size())
	{
		errorLogE(_T("write dat file failed[%s]"), modname);
		return FALSE;
	}

	return TRUE;
}

