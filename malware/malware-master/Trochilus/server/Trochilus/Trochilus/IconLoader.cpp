#include "StdAfx.h"
#include "IconLoader.h"

CIconLoader::CIconLoader()
{

}
CIconLoader::~CIconLoader()
{

}

HICON CIconLoader::LoadIcon(CString strPath)
{
	SHFILEINFO shFileInfo;

	int nPos = strPath.Find(_T("."));

	strPath = strPath.Mid(nPos,strPath.GetLength()-nPos);

	IconMap::iterator it = m_IconMap.find(strPath);

	if (it != m_IconMap.end())
	{
		return it->second;
	}
	else
	{
		SHGetFileInfo(strPath,FILE_ATTRIBUTE_NORMAL,&shFileInfo,sizeof(SHFILEINFO),
			SHGFI_USEFILEATTRIBUTES|SHGFI_DISPLAYNAME|SHGFI_ICON|SHGFI_SMALLICON|SHGFI_TYPENAME);
		m_IconMap.insert(MAKE_PAIR(IconMap,strPath,shFileInfo.hIcon));

		return shFileInfo.hIcon;
	}
}