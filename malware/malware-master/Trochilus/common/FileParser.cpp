#include "StdAfx.h"
#include "FileParser.h"


CFileParser::CFileParser(void)
{
}


CFileParser::~CFileParser(void)
{
}

BOOL CFileParser::Write( LPCTSTR filepath,int nSize,std::wstring MD5,ByteBuffer& buffer )
{
	BOOL ret = FALSE;

	FILE_OPTIONS options;

	do 
	{
		if (!GetFileCurStatus(filepath,options))
			break;

		ret = PutFilecContent(filepath,nSize,buffer);

		if (ret)
			options.nCurSel += nSize;

		UpdateFileStatus(filepath,options);

	} while (FALSE);

	return ret;
}

int CFileParser::Read( LPCTSTR filepath,int nOffset,int nSize,std::wstring& MD5 ,ByteBuffer& buffer )
{
	int ret = 0;

	ret = GetFileMD5(filepath,&MD5);
	
	if (!ret)
		return 0;

	ret = GetFileContent(filepath,nOffset,nSize,buffer);

	return ret;
}

BOOL CFileParser::IsFileFinish(LPCTSTR filepath)
{
	FILE_OPTIONS options;
	BOOL ret = GetFileCurStatus(filepath,options);

	if (ret)
	{
		if ( options.nCurSel == options.nTotalSize )
		{
			DeleteFile((std::wstring(filepath)+OPTIONS_EXT).c_str());
			return TRUE;
		}
	}
	
	return FALSE;
}

int CFileParser::GetFileContent( LPCTSTR filepath,INT64 nPos,int nSize ,ByteBuffer& buffer )
{
	MyFile file;
	int size;
	int ret = 0;;

	if (!file.Open(filepath,GENERIC_READ))
		return 0;

	size = GetFileSize(file,0);
	DWORD dwRet = SetFilePointer(file,nPos,0,FILE_BEGIN );

	if (dwRet != HFILE_ERROR)
	{
		DWORD dwRet = file.Read(nSize,buffer);
			ret = dwRet;
	}

	file.Close();

	return ret;
}

BOOL CFileParser::PutFilecContent(LPCTSTR filepath,int nSize ,ByteBuffer& buffer)
{
	MyFile file;
	file.Open(filepath,GENERIC_WRITE,OPEN_ALWAYS);
	DWORD dwRet = SetFilePointer(file,0,0,FILE_END );

	BOOL ret = FALSE;

	do 
	{
		if (dwRet != HFILE_ERROR)
		{
			ret = file.Write(buffer,nSize);
			if (ret)
				break;
		}
	} while (FALSE);

	file.Close();

	return ret;
}

BOOL CFileParser::IsFileExist(LPCTSTR filepath)
{
	WIN32_FIND_DATA FindFileData;
	HANDLE hFind;

	hFind = FindFirstFile(filepath, &FindFileData);

	if (hFind == INVALID_HANDLE_VALUE) 
		return false;
	else
	{
		FindClose(hFind);
		return true;
	}
	return false;
}

BOOL CFileParser::GetFileCurStatus(LPCTSTR filepath,FILE_OPTIONS& options)
{
	BOOL ret = FALSE;
	
	std::wstring strCfg = filepath;
	strCfg += OPTIONS_EXT;

	ret = IsFileExist(strCfg.c_str());

	if (!ret)
	{
		options.nTotalSize = 0;
		options.nCurSel = 0;
		return TRUE;
	}

	MyFile file;
	
	if (!file.Open(strCfg.c_str(),GENERIC_READ))
		return ret;

	do 
	{
		int nSize = GetFileSize(file,0);

		if (nSize != OPTIONS_SIZE)
		{
			ret = FALSE;
			break;
		}
		ByteBuffer buf;

		nSize = file.Read(OPTIONS_SIZE,buf);
		
		if (nSize != OPTIONS_SIZE)
		{
			ret = FALSE;
			break;
		}

		memcpy(&options,(LPBYTE)buf,OPTIONS_SIZE);

	} while (FALSE);

	file.Close();
	return ret;
}

BOOL CFileParser::CreateFileStatus( LPCTSTR filepath,LPCTSTR md5,int nTotal )
{
	BOOL ret = FALSE;

	std::wstring strCfg = filepath;
	strCfg += OPTIONS_EXT;
	
	MyFile filecgf;

	ret = filecgf.Open(strCfg.c_str(),GENERIC_WRITE,CREATE_ALWAYS);

	if (!ret)
		return ret;

	FILE_OPTIONS options;

	options.nCurSel = 0;
	lstrcpyA(options.szMD5,t2a(md5));

	options.nTotalSize = nTotal;
	lstrcpyA(options.szFileName,t2a(filepath));

	filecgf.Write((LPBYTE)&options,OPTIONS_SIZE);
	filecgf.Close();

	return TRUE;
}

BOOL CFileParser::UpdateFileStatus(LPCTSTR filepath,FILE_OPTIONS& options)
{
	BOOL ret = FALSE;

	std::wstring strCfg = filepath;
	strCfg += OPTIONS_EXT;

	MyFile file;

	if (!file.Open(strCfg.c_str(),GENERIC_WRITE))
		return ret;

	do 
	{
		int nSize = GetFileSize(file,0);

		if (nSize != OPTIONS_SIZE)
			break;

		ByteBuffer buf;

		ret = file.Write((LPBYTE)&options,OPTIONS_SIZE);

	} while (FALSE);

	file.Close();
	return ret;
}