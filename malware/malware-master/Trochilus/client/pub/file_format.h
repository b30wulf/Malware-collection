#pragma once

#define MAX_BUFFER_SIZE 512*1024
//文件头
typedef struct
{
	UINT64 nFileSize; 							//文件大小
	int nNodeNum;						//分块数量
	WCHAR szFileName[MAX_PATH];//文件名
	WCHAR szMD5[255];					//校验MD5值
}FILE_HEAD,*PFILE_HEAD;

//数据节点
typedef struct
{
	int nNodeSize;						//节点大小
	int nOffset;								//节点文件偏移
}FILE_NODE,*PFILE_NODE;

//...N个数据节点