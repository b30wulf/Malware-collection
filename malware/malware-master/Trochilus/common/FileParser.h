#pragma once
#include "encrypt/encrypt.h"
#include "file/MyFile.h"

#define OPTIONS_EXT _T(".cfg")

#define MAX_BLOCK_SIZE 1024*32

//断点传输配置文件
typedef struct
{
	char szMD5[255];
	char szFileName[255];
	int nTotalSize;
	int nCurSel;
}FILE_OPTIONS,*PFILE_OPTIONS;

#define OPTIONS_SIZE sizeof(FILE_OPTIONS)

class CFileParser
{
	DECLARE_SINGLETON(CFileParser)

public:

	//写入文件块
	BOOL Write(LPCTSTR filepath,int nSize,std::wstring MD5,ByteBuffer& buffer);
	
	//读取文件块
	int Read(LPCTSTR filepath,int nOffset,int nSize,std::wstring& MD5 ,ByteBuffer& buffer);

	//判断文件是否传输完成
	BOOL IsFileFinish(LPCTSTR filepath);

	//得到当前传输状态
	BOOL GetFileCurStatus(LPCTSTR filepath,FILE_OPTIONS& options);

	//得到文件内容
	int GetFileContent(LPCTSTR filepath,INT64 nPos,int nSize ,ByteBuffer& buffer);

	//写入文件内容
	BOOL PutFilecContent(LPCTSTR filepath,int nSize ,ByteBuffer& buffer);

	//更新当前文件传输状态
	BOOL UpdateFileStatus(LPCTSTR filepath,FILE_OPTIONS& options);

	//创建临时文件
	BOOL CreateFileStatus(LPCTSTR filepath,LPCTSTR md5,int nTotal);

	//判断文件是否存在
	BOOL IsFileExist(LPCTSTR filepath);
};

