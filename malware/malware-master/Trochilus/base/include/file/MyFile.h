#pragma once
#include "../AutoCleanup.h"

class MyFile
{
public:
	MyFile();
	~MyFile();

	BOOL Open(LPCTSTR filepath, DWORD dwDesiredAccess = GENERIC_READ, DWORD dwCreationDisposition = OPEN_EXISTING, DWORD dwShareMode = FILE_SHARE_READ, DWORD dwAttrs = FILE_ATTRIBUTE_NORMAL);
	void Close();

	operator HANDLE();
	DWORD Read(DWORD dwToRead, ByteBuffer& buffer);
	BOOL Write(const LPBYTE pContent, DWORD dwSize);
	BOOL ReadAll(ByteBuffer& buffer);

private:
	Handle	m_handle;
};
