#include "stdafx.h"
#include <Iphlpapi.h>
#include <Winsock2.h>
#include "BinNames.h"
#include "common.h"

#pragma comment(lib, "Wtsapi32.lib")
#pragma comment(lib, "iphlpapi.lib")
#pragma comment(lib, "ws2_32.lib")

void XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1)
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
}

//获取进程用户函数： 
LPCTSTR GetProcessUserName(DWORD dwID)
{ 
	HANDLE hProcess=OpenProcess(PROCESS_QUERY_INFORMATION,FALSE,dwID); 
	if( hProcess==NULL ) 
		return NULL; 

	HANDLE hToken =NULL; 
	BOOL bResult =FALSE; 
	DWORD dwSize =0; 

	static TCHAR szUserName[256]={0}; 
	TCHAR szDomain[256]={0}; 
	DWORD dwDomainSize=256; 
	DWORD dwNameSize=256; 

	SID_NAME_USE    SNU; 
	PTOKEN_USER pTokenUser=NULL; 
	__try 
	{ 
		if( !OpenProcessToken(hProcess,TOKEN_QUERY,&hToken) ) 
		{ 
			bResult = FALSE; 
			__leave; 
		} 

		if( !GetTokenInformation(hToken,TokenUser,pTokenUser,dwSize,&dwSize) ) 
		{ 
			if( GetLastError() != ERROR_INSUFFICIENT_BUFFER ) 
			{ 
				bResult = FALSE ; 
				__leave; 
			} 
		} 

		pTokenUser = NULL; 
		pTokenUser = (PTOKEN_USER)malloc(dwSize); 
		if( pTokenUser == NULL ) 
		{ 
			bResult = FALSE; 
			__leave; 
		} 

		if( !GetTokenInformation(hToken,TokenUser,pTokenUser,dwSize,&dwSize) ) 
		{ 
			bResult = FALSE; 
			__leave; 
		} 

		if( LookupAccountSid(NULL,pTokenUser->User.Sid,szUserName,&dwNameSize,szDomain,&dwDomainSize,&SNU) != 0 ) 
		{ 
			return szUserName; 
		} 
	} 
	__finally 
	{ 
		if( pTokenUser!=NULL ) 
			free(pTokenUser); 
	} 

	return NULL; 
}

std::wstring GetSystemVersionCode()
{
	TCHAR szVerCode[255];
	OSVERSIONINFOEX osvix;

	ZeroMemory(&osvix,sizeof(osvix));
	osvix.dwOSVersionInfoSize=sizeof(osvix);

	GetVersionEx(reinterpret_cast<LPOSVERSIONINFO>(&osvix));
	
	wsprintf(szVerCode,_T("(NT %d.%d Build %d) "),osvix.dwMajorVersion,osvix.dwMinorVersion,osvix.dwBuildNumber);

	return szVerCode;
}

WIN_VER_DETAIL GetWindowsVersion()
{
	static WIN_VER_DETAIL osversion = WINDOWS_VERSION_NONE; 

	if (osversion != WINDOWS_VERSION_NONE) return osversion;

	OSVERSIONINFOEX osvi = {0};
	BOOL bOsVersionInfoEx;

	//先尝试GetVersionEx使用OSVERSIONINFOEX结构
	//如果失败，就尝试使用OSVERSIONINFO结构
	osvi.dwOSVersionInfoSize = sizeof(OSVERSIONINFOEX);
	if(! (bOsVersionInfoEx = GetVersionEx((OSVERSIONINFO*) &osvi)))
	{
		osvi.dwOSVersionInfoSize = sizeof (OSVERSIONINFO);
		if (! GetVersionEx((OSVERSIONINFO *) &osvi)) 
		{
			osversion = WINDOWS_VERSION_NONE;
			return osversion;
		}
	}

	SYSTEM_INFO si;
	ZeroMemory(&si, sizeof(SYSTEM_INFO));
	GetSystemInfo(&si);

	switch (osvi.dwPlatformId)
	{
		//测试是否是 Windows NT 产品家族
	case VER_PLATFORM_WIN32_NT:
		//监测具体的产品
		if (osvi.dwMajorVersion == 6 && osvi.dwMinorVersion == 0)
		{
			if(osvi.wProductType == VER_NT_WORKSTATION)
			{
				osversion = WINDOWS_VERSION_VISTA;
			}
			else
			{
				osversion = WINDOWS_VERSION_2K8;
			}
		}
		else if (osvi.dwMajorVersion == 6 && osvi.dwMinorVersion == 1)
		{
			if (osvi.wProductType == VER_NT_WORKSTATION)
			{
				osversion = WINDOWS_VERSION_7;
			}
			else
			{
				osversion = WINDOWS_VERSION_2K8;
			}
		}
		else if (osvi.dwMajorVersion == 6 && osvi.dwMinorVersion == 2)
		{
			if (osvi.wProductType == VER_NT_WORKSTATION)
			{
				osversion = WINDOWS_VERSION_8;
			}
			else
			{
				osversion = WINDOWS_VERSION_2012;
			}
		}
		else if (osvi.dwMajorVersion == 5 && osvi.dwMinorVersion == 2)
		{
			if( GetSystemMetrics(SM_SERVERR2) )
			{
				osversion = WINDOWS_VERSION_2K3_SP1_SP2;
			}
			else if( osvi.wProductType == VER_NT_WORKSTATION && 
				si.wProcessorArchitecture == PROCESSOR_ARCHITECTURE_AMD64)
			{
				//osversion = OSVERSION_XPP_X64;
				osversion = WINDOWS_VERSION_XP;
			}
			else
			{
				osversion = WINDOWS_VERSION_2K3;
			}
		}
		else if (osvi.dwMajorVersion == 5 && osvi.dwMinorVersion == 1)
		{
			osversion = WINDOWS_VERSION_XP;
		}

		if (osvi.dwMajorVersion == 5 && osvi.dwMinorVersion == 0)
		{
			osversion = WINDOWS_VERSION_2K;
		}
		if ( osvi.dwMajorVersion <= 4 )
		{
			osversion = WINDOWS_VERSION_NT;
		}
		break;

		//测试是否是Windows Me/98/95.
	case VER_PLATFORM_WIN32_WINDOWS:
		if (osvi.dwMajorVersion == 4 && osvi.dwMinorVersion == 0)
		{
			if (osvi.szCSDVersion[1]=='C' || osvi.szCSDVersion[1]=='B')
			{
				osversion = WINDOWS_VERSION_95_OSR2;
			}
			else
			{
				osversion = WINDOWS_VERSION_95;
			}
		} 
		else if (osvi.dwMajorVersion == 4 && osvi.dwMinorVersion == 10)
		{
			if ( osvi.szCSDVersion[1]=='A' || osvi.szCSDVersion[1]=='B')
			{
				osversion = WINDOWS_VERSION_98_SE;
			}
			else
			{
				osversion = WINDOWS_VERSION_98;
			}
		} 
		else if (osvi.dwMajorVersion == 4 && osvi.dwMinorVersion == 90)
		{
			osversion = WINDOWS_VERSION_ME;
		} 
		break;
	case VER_PLATFORM_WIN32s:
		//result += _T("Microsoft Win32s");
		osversion = WINDOWS_VERSION_NONE;
		break;
	}

	return osversion; 
}

BOOL IsWow64()
{
	BOOL bIsWow64 = FALSE;

	typedef BOOL (WINAPI *LPFN_ISWOW64PROCESS) (HANDLE, PBOOL);
	LPFN_ISWOW64PROCESS fnIsWow64Process;

	fnIsWow64Process = (LPFN_ISWOW64PROCESS)GetProcAddress(
		GetModuleHandle(TEXT("kernel32")),"IsWow64Process");

	if (NULL != fnIsWow64Process)
	{
		if (! fnIsWow64Process(GetCurrentProcess(), &bIsWow64))
		{
			errorLogE(_T("IsWow64Process failed."));
		}
	}

	return bIsWow64;
}

BOOL GetLocalIPList(TStringVector& localIPList)
{
	localIPList.clear();

	//获取ip列表
	DWORD dwBytes = 0;
	::GetIpAddrTable(NULL, &dwBytes, TRUE);

	PMIB_IPADDRTABLE pTable = (PMIB_IPADDRTABLE) malloc(dwBytes);
	if (NO_ERROR != ::GetIpAddrTable(pTable, &dwBytes, TRUE))
	{
		errorLog(_T("Getipaddrtable failed. E%u"), ::WSAGetLastError());
		free(pTable);
		return FALSE;
	}

	for (DWORD i = 0; i < pTable->dwNumEntries; i++)
	{
		MIB_IPADDRROW& row = pTable->table[i];

		//如果该地址在断开连接的网卡上,或者正在被删除,则不予处理
		if ((row.wType & MIB_IPADDR_DISCONNECTED) || (row.wType & MIB_IPADDR_DELETED))
		{
			continue;
		}

		struct in_addr addr;
		addr.S_un.S_addr = row.dwAddr;
		std::string curlocalip(inet_ntoa(addr));

		//忽略一些无效地址
		if (curlocalip == "127.0.0.1"
			|| curlocalip.find("169.254") == 0) 
		{
			continue;
		}

		localIPList.push_back(s2ws(curlocalip));
	}

	free(pTable);

	return TRUE;
}

/*
BOOL GetProcessUsername(DWORD dwProcessID, tstring& username)
{
	BOOL fResult  = FALSE;
	HANDLE hProc  = NULL;
	HANDLE hToken = NULL;
	TOKEN_USER *pTokenUser = NULL;

	__try
	{
		// Open the process with PROCESS_QUERY_INFORMATION access
		hProc = ::OpenProcess(PROCESS_QUERY_INFORMATION, FALSE, dwProcessID);
		if (hProc == NULL)
		{
			__leave;
		}
		fResult = ::OpenProcessToken(hProc, TOKEN_QUERY, &hToken);
		if(!fResult)  
		{
			__leave;
		}

		DWORD dwNeedLen = 0;  
		fResult = ::GetTokenInformation(hToken,TokenUser, NULL, 0, &dwNeedLen);
		if (dwNeedLen > 0)
		{
			pTokenUser = (TOKEN_USER*)new BYTE[dwNeedLen];
			fResult = ::GetTokenInformation(hToken,
				TokenUser,
				pTokenUser,
				dwNeedLen,
				&dwNeedLen);
			if (!fResult)
			{
				__leave;
			}
		}
		else
		{
			__leave;
		}

		SID_NAME_USE sn;
		TCHAR szDomainName[MAX_PATH];
		DWORD dwDmLen = MAX_PATH;
		TCHAR szUserName[MAX_PATH] = {0};
		DWORD nNameLen = MAX_PATH;
		fResult = ::LookupAccountSid(NULL,
			pTokenUser->User.Sid,
			szUserName,
			&nNameLen,
			szDomainName,
			&dwDmLen,
			&sn);
		if (fResult) username = szUserName;
	}
	__finally
	{
		if (hProc) ::CloseHandle(hProc);
		if (hToken) ::CloseHandle(hToken);
		if (pTokenUser) delete[] (char*)pTokenUser;
	}

	return fResult;
}*/

BOOL GetLogonUserList( SessionInfoList& sessionList )
{
	PWTS_SESSION_INFO pSessionInfo = NULL;
	DWORD dwSessionCount= 0;
	if (! ::WTSEnumerateSessions(
		WTS_CURRENT_SERVER_HANDLE,  // local
		0,              // Reserved
		1,              // The version of the enumeration request. This parameter must be 1.
		&pSessionInfo,        // _out_
		&dwSessionCount        // _out_  
		))
	{
		errorLogE(_T("Enum sessions failed."));
		return FALSE;
	}

	for (UINT i = 0; i < dwSessionCount; i++)
	{
		if(pSessionInfo[i].SessionId == 65536) continue;

		//查询用户名
		tstring username;
		LPTSTR pBuffer = NULL;
		DWORD dwBufferLen = 0;
		if (! WTSQuerySessionInformation(WTS_CURRENT_SERVER_HANDLE, pSessionInfo[i].SessionId, WTSUserName, &pBuffer, &dwBufferLen))
		{
			errorLogE(_T("query username failed."));
			continue;
		}
		username = pBuffer;
		WTSFreeMemory(pBuffer);

		MY_SESSION_INFO info;
		info.username = username;
		info.state = pSessionInfo[i].State;
		info.sessionId = pSessionInfo[i].SessionId;
		info.winStationName = pSessionInfo[i].pWinStationName;
		sessionList.push_back(info);
	}
	
	::WTSFreeMemory(pSessionInfo);

	return TRUE;
}

BOOL IsAdministrator()
{

	BOOL bIsElevated = FALSE;
	HANDLE hToken = NULL;
	UINT16 uWinVer = LOWORD(GetVersion());
	uWinVer = MAKEWORD(HIBYTE(uWinVer),LOBYTE(uWinVer));

	if (uWinVer < 0x0600)//不是VISTA、Windows7
		return TRUE;

	if (OpenProcessToken(GetCurrentProcess(),TOKEN_QUERY,&hToken)) {

		struct {
			DWORD TokenIsElevated;
		} /*TOKEN_ELEVATION*/te;
		DWORD dwReturnLength = 0;

		if (GetTokenInformation(hToken,/*TokenElevation*/(_TOKEN_INFORMATION_CLASS)20,&te,sizeof(te),&dwReturnLength)) {
			if (dwReturnLength == sizeof(te))
				bIsElevated = te.TokenIsElevated;
		}
		CloseHandle( hToken );
	}
	return bIsElevated;
}

/*
void Byte2HEX( const LPBYTE pByteList, DWORD dwLength, tstring& hexString )
{
	hexString.clear();
	for (DWORD i = 0; i < dwLength; i++)
	{
		TCHAR buffer[3] = {0};
		_stprintf_s(buffer, _T("%02x"), pByteList[i]);

		hexString += buffer;
	}
}

void GetMD5(LPCVOID lpMem, DWORD dwSize, tstring& md5String)
{
	if (NULL == lpMem) return;

	BYTE md5Byte[16] = {0};

	MD5_CTX md5;
	md5.MD5Update((const LPBYTE)lpMem, dwSize);
	md5.MD5Final(md5Byte);

	Byte2HEX(md5Byte, 16, md5String);
}*/
void GetModFilePath(HMODULE hMod, tstring& binFilePath, tstring& filename)
{
	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize] = '\0';
	binFilePath = baseName;
	binFilePath += '\\';
	filename = &baseName[dwBaseNameSize + 1];
}

static tstring g_filePath;
LPCTSTR GetBinFilepath()
{
	g_filePath = g_ServiceInfo.szInstalPath;

	g_filePath += _T("\\");

	if (g_filePath[0] == _T('$'))
		g_filePath.erase(g_filePath.begin());

	TCHAR buf[MAX_PATH] = {0};
	ExpandEnvironmentStrings(g_filePath.c_str(),buf,MAX_PATH);

	g_filePath = buf;

	if(g_filePath[g_filePath.length()-1] != _T('\\'))
		g_filePath += _T("\\");


	return g_filePath.c_str();
}

BOOL XFC( const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1 )
{
	XorFibonacciCrypt(lpPlain, dwPlainLen, lpEncrypted, factor0, factor1);

	return TRUE;
}

BOOL AdjustTimes( LPCTSTR filepath )
{
// 	tstring me;
// 	me = GetBinFilepath();
// 	me += GetBinFilename();
// 	MyFile selfFile;
// 	if (! selfFile.Open(me.c_str(), GENERIC_READ, OPEN_EXISTING, FILE_SHARE_READ))
// 	{
// 		errorLogE(_T("open file failed[%s]"), me.c_str());
// 		return FALSE;
// 	}
// 
// 	FILETIME creationTime, lastAccessTime, lastWriteTime;
// 	if (! ::GetFileTime(selfFile, &creationTime, &lastAccessTime, &lastWriteTime))
// 	{
// 		errorLogE(_T("get file time failed."));
// 		return FALSE;
// 	}
// 
// 	MyFile targetFile;
// 	if (! targetFile.Open(filepath, GENERIC_WRITE, OPEN_EXISTING, FILE_SHARE_WRITE))
// 	{
// 		errorLogE(_T("open target[%s] failed."), filepath);
// 		return FALSE;
// 	}
// 
// 	return ::SetFileTime(targetFile, &creationTime, &lastAccessTime, &lastWriteTime);	
	return TRUE;
}