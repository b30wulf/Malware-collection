#pragma once

namespace PEUtils
{
#define CODE_MARK_BEGIN()	\
	_asm inc eax			\
 	_asm dec eax			
#define CODE_MARK_END()	\
 	_asm inc ebx		\
 	_asm dec ebx

	//搜索PE文件代码段，将以CODE_MARK_BEGIN开头，以CODE_MARK_END结尾的代码，全部清理为NOP
	//lpBase为PE文件的FileMapping首地址
	void CleanCode(LPVOID lpBase);

	//替换PE文件里引入表中的dll名称，需要pTargetName的字符个数 >= pReplaceName的字符个数
	//lpBase为PE文件的FileMapping首地址
	BOOL ReplaceIIDName(LPVOID lpBase, LPCSTR pTargetName, LPCSTR pReplaceName);
}
