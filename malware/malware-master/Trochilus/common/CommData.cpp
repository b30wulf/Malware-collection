#include "stdafx.h"
#include "CommData.h"

static LPCTSTR MSGID_NAME			= _T("__msgid");
static LPCTSTR USING_POOL_THREAD	= _T("__upt");
static LPCTSTR SERIALID_NAME		= _T("__serial");
static LPCTSTR BYTEDATA_NAME		= _T("__data");
static LPCTSTR SERIAL_BYTESECTION	= _T("\n__data=");

CommData::CommData()
	: m_msgid(INVALID_MSGID)
	, m_serialid(0)
	, m_bUsePoolThread(FALSE)
{

}

CommData::~CommData()
{

}

void CommData::SetMsgID( MSGID msgid )
{
	m_msgid = msgid;
}

void CommData::SetData( LPCTSTR name, LPCTSTR value )
{
	m_map[name] = value;
}

void CommData::SetData( LPCTSTR name, UINT64 value )
{
	TCHAR buffer[64] = {0};
	_stprintf_s(buffer, _T("%I64u"), value);
	m_map[name] = buffer;
}

BOOL CommData::GetStrData( LPCTSTR name, tstring& value ) const
{
	KeyValueMap::const_iterator finditer = m_map.find(name);
	if (finditer == m_map.end())
	{
		return FALSE;
	}

	value = finditer->second;

	return TRUE;
}

BOOL CommData::GetUint64Data( LPCTSTR name, UINT64& value ) const
{
	tstring strVal;
	if (! GetStrData(name, strVal))
	{
		return FALSE;
	}

	if (1 == _stscanf_s(strVal.c_str(), _T("%I64u"), &value))
	{
		return TRUE;
	}

	return FALSE;
}

BOOL CommData::Parse( const LPBYTE pData, DWORD dwSize )
{
	typedef enum
	{
		PARSE_READING_NAME = 0,
		PARSE_READING_VALUE,
	} PARSE_STATUS;

	TCHAR* p = (TCHAR*) pData;

	PARSE_STATUS parseStatus = PARSE_READING_NAME;
	tstring name;
	tstring val;
	for (DWORD index = 0; index < (dwSize/sizeof(TCHAR)); index++, p++)
	{
		TCHAR b = *p;
		
		if (_T('\r') == b) 
		{
			continue;
		}
		else if (_T('\n') == b) 
		{
			if (name.size() > 0) m_map[name.c_str()] = val.c_str();
			name.clear();
			val.clear();

			parseStatus = PARSE_READING_NAME;
		}
		else if (_T('=') == b)
		{
			if (name == BYTEDATA_NAME)
			{
				LONG dataLength = dwSize - index - 1;
				if (dataLength > 0)
				{
					m_byteData.Alloc(dataLength);
					memcpy((LPBYTE)m_byteData, p + 1, dataLength);
				}
				
				name.clear();
				break;
			}

			parseStatus = PARSE_READING_VALUE;
		}
		else
		{
			if (PARSE_READING_NAME == parseStatus)
			{
				name += b;
			}
			else if (PARSE_READING_VALUE == parseStatus)
			{
				val += b;
			}
		}
	}

	if (name.size() > 0) m_map[name.c_str()] = val.c_str();

	/*std::string temp((LPCSTR)pData, dwSize);
	tstring src = s2ws(temp);

 	TStringVector parts;
 	splitByChar(src.c_str(), parts, '\n');
 
 	m_map.clear();
 	TStringVector::iterator iter = parts.begin();
 	for (; iter != parts.end(); iter++)
 	{
 		tstring& line = *iter;
 		trim(line, '\r');
 		if (line.size() == 0) continue;
 
 		tstring::size_type pos = line.find('=');
 		if (pos == tstring::npos) continue;
 
 		tstring name = line.substr(0, pos);
 		tstring val;
 		if (line.size() > pos + 1) val = line.substr(pos + 1);
 
 		m_map[name] = val;
 	}*/

	m_msgid = INVALID_MSGID;
	GetUint64Data(MSGID_NAME, m_msgid);

	GetUint64Data(SERIALID_NAME, m_serialid);

	tstring upt;
	GetStrData(USING_POOL_THREAD, upt);
	if (upt == _T("true")) m_bUsePoolThread = TRUE;

	GetStrData(_T("clientid"),m_clientid);

	return (INVALID_MSGID != m_msgid);
}

void CommData::Serialize( ByteBuffer& buffer ) const
{
	static DWORD s_dwByteSectionSize = lstrlen(SERIAL_BYTESECTION)*sizeof(TCHAR);

	tostringstream ss;

	ss << MSGID_NAME << _T("=") << m_msgid;
	ss << _T("\n") << SERIALID_NAME << _T("=") << m_serialid;
	if (m_bUsePoolThread) ss << _T("\n") << USING_POOL_THREAD << _T("=true");

	KeyValueMap::const_iterator iter = m_map.begin();
	for (; iter != m_map.end(); iter++)
	{
		if (_tcscmp(SERIALID_NAME, iter->first.c_str()) == 0
			|| _tcscmp(MSGID_NAME, iter->first.c_str()) == 0
			|| _tcscmp(USING_POOL_THREAD, iter->first.c_str()) == 0) continue;
		ss << _T("\n") << iter->first.c_str() << _T("=") << iter->second.c_str();
	}

	tstring wdata = ss.str();
	DWORD dwSize = wdata.size()*sizeof(TCHAR);

	DWORD dwDataSectionSize = 0;
 	if (m_byteData.Size() > 0) 
 	{
 		dwDataSectionSize = s_dwByteSectionSize + m_byteData.Size();
 	}
	buffer.Alloc(dwSize + dwDataSectionSize);
	memcpy((LPBYTE)buffer, wdata.c_str(), dwSize);
	
 	if (m_byteData.Size() > 0)
 	{
 		LPBYTE pTarget = (LPBYTE) buffer;
 		pTarget += dwSize;
 
 		memcpy(pTarget, SERIAL_BYTESECTION, s_dwByteSectionSize);
 		pTarget += s_dwByteSectionSize;
 
 		memcpy(pTarget, (const LPBYTE)m_byteData, m_byteData.Size());
 	}
}

MSGID CommData::GetMsgID() const
{
	return m_msgid;
}

void CommData::SetUsingPoolThread()
{
	m_bUsePoolThread = TRUE;
}

BOOL CommData::UsingPoolThread() const
{
	return m_bUsePoolThread;
}

void CommData::SetSerialID( MSGSERIALID serialID )
{
	m_serialid = serialID;
}

MSGSERIALID CommData::GetSerialID() const
{
	return m_serialid;
}

void CommData::ListNames( TStringVector& names ) const
{
	KeyValueMap::const_iterator iter = m_map.begin();
	for (; iter != m_map.end(); iter++)
	{
		names.push_back(iter->first);
	}
}

void CommData::Reply( const CommData& data )
{
	m_msgid = data.m_msgid;
	m_serialid = data.m_serialid;
}

void CommData::SetClientID( LPCTSTR clientid )
{
	m_clientid = clientid;
	SetData(_T("clientid"),clientid);
}

LPCTSTR CommData::GetClientID() const
{
	return m_clientid.c_str();
}

const ByteBuffer& CommData::GetByteData() const
{
	return m_byteData;
}

void CommData::SetByteData( const LPBYTE pData, DWORD dwSize )
{
	m_byteData.Alloc(dwSize);
	memcpy((LPBYTE)m_byteData, pData, dwSize);
}
