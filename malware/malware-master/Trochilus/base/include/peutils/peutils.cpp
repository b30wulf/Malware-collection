#include "stdafx.h"
#include "peutils.h"

#define IS_CODE_BEGIN(_data) (((~(_data)) & 0xFFFF) == 0xB7BF)
#define IS_CODE_END(_data) (((~(_data)) & 0xFFFF) == 0xB4BC)
#define CODE_MARK_SIZE sizeof(WORD)
#define NOP 0x90

namespace PEUtils
{
	//搜索PE文件代码段，将以CODE_MARK_BEGIN开头，以CODE_MARK_END结尾的代码，全部清理为NOP
	void CleanCode(LPVOID lpBase)
	{
		IMAGE_DOS_HEADER* pDosHeader = (IMAGE_DOS_HEADER*) lpBase;
		IMAGE_NT_HEADERS* pNtHeaders = (IMAGE_NT_HEADERS*) ((LPBYTE)lpBase + pDosHeader->e_lfanew);

		IMAGE_SECTION_HEADER* pSectionheader = IMAGE_FIRST_SECTION(pNtHeaders);	//第一个区块
		for (WORD i = 0; i < pNtHeaders->FileHeader.NumberOfSections; i++, pSectionheader++)
		{
			if ((pSectionheader->Characteristics & IMAGE_SCN_CNT_CODE)
				&& (pSectionheader->Characteristics & IMAGE_SCN_MEM_EXECUTE)
				&& pSectionheader->SizeOfRawData > 0)
			{
				LPBYTE p = (LPBYTE)lpBase + pSectionheader->PointerToRawData;

				BOOL bFoundBegin = FALSE;
				DWORD dwBeginOffset = 0;
				DWORD dwCodeSize = 0;

				for (DWORD j = 0; j < pSectionheader->SizeOfRawData - CODE_MARK_SIZE; j++, p++)
				{
					WORD data = *(LPWORD)p;
					if (! bFoundBegin && IS_CODE_BEGIN(data))
					{
						bFoundBegin = TRUE;
						dwBeginOffset = pSectionheader->PointerToRawData + j;
					}
					else if (bFoundBegin && IS_CODE_END(data))
					{
						dwCodeSize = pSectionheader->PointerToRawData + j - dwBeginOffset + CODE_MARK_SIZE;

						debugLog(_T("clean 0x%X(%u) - 0x%X"), dwBeginOffset, dwCodeSize, dwBeginOffset + dwCodeSize);
						memset((LPBYTE)lpBase + dwBeginOffset, NOP, dwCodeSize);

						bFoundBegin = FALSE;
						dwBeginOffset = 0;
					}
				}
			}
		}
	}

	BOOL ReplaceIIDName( LPVOID lpBase, LPCSTR pTargetName, LPCSTR pReplaceName )
	{
		//替换的名称长度应该 小于等于 被替换的名称
		int iTargetLen = (int) strlen(pTargetName);
		int iReplaceLen = (int) strlen(pReplaceName);
		if (iReplaceLen > iTargetLen) return FALSE;

		IMAGE_DOS_HEADER* pDosHeader = (IMAGE_DOS_HEADER*) lpBase;
		IMAGE_NT_HEADERS* pNtHeaders = (IMAGE_NT_HEADERS*) ((LPBYTE)lpBase + pDosHeader->e_lfanew);

		//查找输入表所在区段,并计算虚拟地址和文件便宜的差值 lk
		LONG lk = 0;
		DWORD dwIATVA = pNtHeaders->OptionalHeader.DataDirectory[IMAGE_DIRECTORY_ENTRY_IMPORT].VirtualAddress;	//输入表RVA地址
		IMAGE_SECTION_HEADER* pSectionheader = IMAGE_FIRST_SECTION(pNtHeaders);	//第一个区块
		BOOL bFound = FALSE;
		for (WORD i = 0; i < pNtHeaders->FileHeader.NumberOfSections; i++, pSectionheader++)
		{
			if (dwIATVA >= pSectionheader->VirtualAddress && dwIATVA < pSectionheader->VirtualAddress + pSectionheader->SizeOfRawData)
			{
				lk = pSectionheader->PointerToRawData - pSectionheader->VirtualAddress;
				bFound = TRUE;
				break;
			}
		}
		if (! bFound) 
		{
			errorLog(_T("find seg failed"));
			return FALSE;
		}

		//定位输入表在文件中的位置,遍历所有IID结构，查找目标dll名称并替换
		BOOL bReplaced = FALSE;
		IMAGE_IMPORT_DESCRIPTOR* pImportDest = (IMAGE_IMPORT_DESCRIPTOR*) ((LPBYTE)lpBase + dwIATVA + lk);
		BOOL bEmpty = TRUE;
		do 
		{
			CHAR* pName = (CHAR*) ((LPBYTE)lpBase + (LONG)pImportDest->Name + lk);
			if (strcmp(pName, pTargetName) == 0)
			{
				strcpy(pName, pReplaceName);
				bReplaced = TRUE;
				break;
			}

			pImportDest++;

			//检查pImportDest是否是全NULL数据，判断是否结尾
			bEmpty = TRUE;
			for (int i = 0; bEmpty && i < sizeof(IMAGE_IMPORT_DESCRIPTOR); i++) bEmpty = (*((LPBYTE)pImportDest + i) == 0);
		} while (! bEmpty);

		return bReplaced;
	}
}
