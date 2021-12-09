#pragma once
#include <map>
#include "MessageDefines.h"

class CommData
{
public:
	CommData();
	~CommData();

	void SetMsgID(MSGID msgid);
	MSGID GetMsgID() const;
	void SetUsingPoolThread();
	BOOL UsingPoolThread() const;
	void SetSerialID(MSGSERIALID serialID);
	MSGSERIALID GetSerialID() const;
	void SetClientID(LPCTSTR clientid);
	LPCTSTR GetClientID() const;

	void Reply(const CommData& data);

	void SetData(LPCTSTR name, LPCTSTR value);
	void SetData(LPCTSTR name, UINT64 value);
	BOOL GetStrData(LPCTSTR name, tstring& value) const;
	BOOL GetUint64Data(LPCTSTR name, UINT64& value) const;
	void SetByteData(const LPBYTE pData, DWORD dwSize);
	const ByteBuffer& GetByteData() const;
	
	BOOL Parse(const LPBYTE pData, DWORD dwSize);
	void Serialize(ByteBuffer& buffer) const;
	void ListNames(TStringVector& names) const;

private:
	typedef std::map<tstring, tstring> KeyValueMap;

private:
	MSGID		m_msgid;
	MSGSERIALID	m_serialid;
	BOOL		m_bUsePoolThread;
	tstring		m_clientid;
	KeyValueMap	m_map;
	ByteBuffer	m_byteData;
};

//为命令处理函数提供便利的宏
#define PARSE_COMMDATA(pData, dwSize) CommData commData; if (! commData.Parse(pData, dwSize)) return FALSE;
#define DECLARE_STR_PARAM(_name) tstring _name; if (! commData.GetStrData(a2t(""#_name), _name)) return FALSE;
#define DECLARE_UINT64_PARAM(_name) UINT64 _name; if (! commData.GetUint64Data(a2t(""#_name), _name)) return FALSE;
#define SEND_REPLY(_reply) ByteBuffer _toSendBuffer;_reply.Serialize(_toSendBuffer);SendMsg((LPBYTE)_toSendBuffer, _toSendBuffer.Size());

#define DECLARE_STR_PARAM_API(_name) tstring _name; if (! commData.GetStrData(a2t(""#_name), _name)) {SetMasterLastError(MASTERROR_NO_EXPECTED_DATA); return FALSE;}
