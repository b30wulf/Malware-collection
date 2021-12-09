#include "stdafx.h"
#include "file/MyFile.h"
#include "common.h"

typedef std::map<tstring,tstring> ModPathMap;

static ModPathMap g_modulePath;

tstring GetModFilePath(LPCTSTR moduleName)
{
	tstring binFilePath;

	ModPathMap::iterator it = g_modulePath.find(moduleName);

	if (it != g_modulePath.end())
	{
		return it->second;
	}

	HMODULE hMod = NULL;
	if (NULL != moduleName)
	{
		hMod = ::GetModuleHandle(moduleName);
	}

	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize + 1] = '\0';
	binFilePath = baseName;

	g_modulePath.insert(MAKE_PAIR(ModPathMap,moduleName,binFilePath));

	return g_modulePath[moduleName].c_str();	
}

LPCTSTR GetBinFilepath()
{
	static tstring binFilepath;

	if (binFilepath.size() > 0)
	{
		return binFilepath.c_str();
	}

	binFilepath = GetModFilePath(_T("servant.dll"));

	return binFilepath.c_str();
}

BOOL XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1)
{
	LPBYTE lpOutput = (LPBYTE) lpEncrypted;
	LPBYTE lpSource = (LPBYTE) lpPlain;

	UINT last1 = factor1;
	UINT last2 = factor0;

	for (DWORD i = 0; i < dwPlainLen; i++)
	{
		BYTE xorchar = (last1 + last2) % MAXBYTE;
		last2 = last1;
		last1 = xorchar;

		*lpOutput = (*lpSource) ^ xorchar;
		lpOutput++;
		lpSource++;
	}

	return TRUE;
}

tstring FormatSizeWithUnit( UINT64 size )
{
#define UNITS_COUNT 5
	static LPCTSTR units[] = {_T("B"), _T("KB"), _T("MB"), _T("GB"), _T("TB")};

	double nextSize = (double)size / 1024;
	double curSize = (double)size;
	int unitIndex = 0;
	while (unitIndex < UNITS_COUNT - 1 && nextSize > 1)
	{
		curSize = nextSize;
		nextSize /= 1024;

		unitIndex++;
	}

	TCHAR buffer[20] = {0};
	_stprintf_s(buffer, _T("%.2f %s"), curSize, units[unitIndex]);

	return buffer;
}

tstring FormstSizeWithComma( UINT64 size )
{
	tstring result;
	return result;
}

tstring TransferLanguage(DWORD cid)
{
	std::wstring strLang;

	switch(cid)
	{
	case 0x409:
		strLang = _T("USA");
		break;
	case 0xC09:
		strLang = _T("ENA");
		break;
	case 0xC04:
		strLang = _T("ZHH");
		break;
	case 0x1404:
		strLang = _T("ZHM");
		break;
	case 0x804:
		strLang = _T("CHS");
		break;
	case 0x1004:
		strLang = _T("ZHI");
		break;
	case 0x404:
		strLang = _T("CHT");
		break;
	case 0x827:
		strLang = _T("LTC");
		break;
	case 0x40F:
		strLang = _T("ISL");
		break;
	case 0x421:
		strLang = _T("IND");
		break;
	case 0x410:
		strLang = _T("ITA");
		break;
	case 0x810:
		strLang = _T("ITS");
		break;
	case 0x411:
		strLang = _T("JPN");
		break;
	case 0x44B:
		strLang = _T("KAN");
		break;
	case 0x43F:
		strLang = _T("KKZ");
		break;
	case 0x457:
		strLang = _T("KNK");
		break;
	case 0x412:
		strLang = _T("KOR");
		break;
	case 0x2809:
		strLang = _T("ENL");
		break;
	case 0x1009:
		strLang = _T("ENC");
		break;
	case 0x2409:
		strLang = _T("ENB");
		break;
	case 0x1809:
		strLang = _T("ENI");
		break;
	case 0x2009:
		strLang = _T("ENJ");
		break;
	case 0x1409:
		strLang = _T("ENZ");
		break;
	case 0x3409:
		strLang = _T("ENP");
		break;
	case 0x1C09:
		strLang = _T("ENS");
		break;
	case 0x2C09:
		strLang = _T("ENT");
		break;
	case 0x809:
		strLang = _T("ENG");
		break;
	case 0x3009:
		strLang = _T("ENW");
		break;
	case 0x425:
		strLang = _T("ETI");
		break;
	case 0x438:
		strLang = _T("FOS");
		break;
	case 0x429:
		strLang = _T("FAR");
		break;
	case 0x40B:
		strLang = _T("FIN");
		break;
	case 0x80C:
		strLang = _T("FRB");
		break;
	case 0xC0C:
		strLang = _T("FRC");
		break;
	case 0x40C:
		strLang = _T("FRA");
		break;
	case 0x140C:
		strLang = _T("FRL");
		break;
	case 0x180C:
		strLang = _T("FRM");
		break;
	case 0x100C:
		strLang = _T("FRS");
		break;
	default:
		strLang = _T("None");
		break;
	}
	return strLang;
}

BOOL ReadDataFile( LPCTSTR datafile, CommData& data )
{
	tstring filepath = datafile;
	if (filepath.find(':') == tstring::npos)
	{
		filepath = GetBinFilepath() + filepath;
	}

	MyFile file;
	if (! file.Open(filepath.c_str(), GENERIC_READ, OPEN_EXISTING, FILE_SHARE_READ))
	{
		errorLog(_T("open file [%s] failed"), filepath.c_str());
		return FALSE;
	}

	ByteBuffer content;
	if (! file.ReadAll(content))
	{
		errorLog(_T("readall [%s] failed"), filepath.c_str());
		return FALSE;
	}

	data.SetByteData(content, content.Size());

	return TRUE;

}