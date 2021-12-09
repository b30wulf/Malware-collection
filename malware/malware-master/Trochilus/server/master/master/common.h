#pragma once
#include "tstring.h"
#include "../../../common/CommData.h"

//获取本进程中某个模块的所属目录，返回值以\结尾
tstring GetModFilePath(LPCTSTR moduleName);

//获取servant.dll所属目录，返回值以\结尾
LPCTSTR GetBinFilepath();

//异或加解密
BOOL XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1);

//格式化大小
tstring FormatSizeWithUnit(UINT64 size);

//使用逗号进行大小格式化
tstring FormstSizeWithComma(UINT64 size);

//读取文件到data中
BOOL ReadDataFile(LPCTSTR datafile, CommData& data);

//转换语言
tstring TransferLanguage(DWORD cid);