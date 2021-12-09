#include "stdafx.h"
#include "MyFile.h"

MyFile::MyFile()
{

}

MyFile::~MyFile()
{

}

BOOL MyFile::Open( LPCTSTR filepath, DWORD dwDesiredAccess, DWORD dwCreationDisposition /*= OPEN_EXISTING*/, DWORD dwShareMode /*= FILE_SHARE_READ*/, DWORD dwAttrs /*= FILE_ATTRIBUTE_NORMAL*/ )
{
	m_handle = ::CreateFile(filepath, dwDesiredAccess, dwShareMode, NULL, dwCreationDisposition, dwAttrs, NULL);
	return (m_handle.IsValid());
}

void MyFile::Close()
{
	m_handle.Close();
}

MyFile::operator HANDLE()
{
	return m_handle;
}

DWORD MyFile::Read( DWORD dwToRead, ByteBuffer& buffer )
{
	if (0 == dwToRead) return 0;

	buffer.Alloc(dwToRead);
	DWORD dwRead = 0;
	BOOL bRet = ::ReadFile(m_handle, (LPBYTE)buffer, dwToRead, &dwRead, NULL);
	if (bRet)
	{
		return dwRead;
	}
	else
	{
		return 0;
	}
}

BOOL MyFile::Write(const LPBYTE pContent, DWORD dwSize)
{
	LPBYTE p = (LPBYTE) pContent;
	DWORD dwLeft = dwSize;
	DWORD dwWritten = 0;
	while (::WriteFile(m_handle, p, dwLeft, &dwWritten, NULL) && dwWritten < dwLeft)
	{
		p += dwWritten;
		dwLeft -= dwWritten;
		dwWritten = 0;
	}
	dwLeft -= dwWritten;

	return (dwLeft == 0);
}

BOOL MyFile::ReadAll( ByteBuffer& buffer )
{
	DWORD dwFileSize = ::GetFileSize(m_handle, NULL);
	if (0 == dwFileSize) 
	{
		return TRUE;
	}

	buffer.Alloc(dwFileSize);

	LPBYTE lpPos = (LPBYTE) buffer;
	DWORD dwToRead = dwFileSize;
	DWORD dwRead = 0;
	while (::ReadFile(m_handle, lpPos, dwToRead, &dwRead, NULL) && dwRead > 0)
	{
		dwToRead -= dwRead;
		lpPos += dwRead;
	}

	if (0 == dwToRead)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
