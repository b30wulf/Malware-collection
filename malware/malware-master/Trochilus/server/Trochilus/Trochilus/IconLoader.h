#pragma once
class CIconLoader
{
	DECLARE_SINGLETON(CIconLoader)
public:
	HICON LoadIcon(CString strPath);
private:
	typedef std::map<CString,HICON> IconMap;
	IconMap m_IconMap;
};

