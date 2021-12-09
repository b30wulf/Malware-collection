// MemLoadDll.h: interface for the CMemLoadDll class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_MEMLOADDLL_H__750C4158_467F_454F_AC91_36C0501B39CD__INCLUDED_)
#define AFX_MEMLOADDLL_H__750C4158_467F_454F_AC91_36C0501B39CD__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

typedef   BOOL (__stdcall *ProcDllMain)(HINSTANCE, DWORD, LPVOID);

class CMemLoadDll
{
public:
	CMemLoadDll();
	~CMemLoadDll();

public:
	BOOL	MemLoadLibrary(void* lpFileData, int nDataLength);  // Dll file data buffer
	FARPROC	MemGetProcAddress(LPCSTR lpProcName);
	BOOL	IsLoadOk();

private:
	BOOL m_bIsLoadOk;
	BOOL CheckDataValide(void* lpFileData, int nDataLength);
	int  CalcTotalImageSize();
	void CopyDllDatas(void* pDest, void* pSrc);
	BOOL FillRavAddress(void* pImageBase);
	void DoRelocation(void* pNewBase);
	int  GetAlignedSize(int nOrigin, int nAlignment); 

private:
	ProcDllMain m_pDllMain;
	
private:
	void*					m_pImageBase;
	PIMAGE_DOS_HEADER		m_pDosHeader;
	PIMAGE_NT_HEADERS		m_pNTHeader;
	PIMAGE_SECTION_HEADER	m_pSectionHeader;
};

#endif // !defined(AFX_MEMLOADDLL_H__750C4158_467F_454F_AC91_36C0501B39CD__INCLUDED_)
