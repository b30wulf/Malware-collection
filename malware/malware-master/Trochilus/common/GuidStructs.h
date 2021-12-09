#pragma once
#include "AutoCleanup.h"

//包序列号
#define CPSERIAL ULONG

#pragma pack(1)
//标识客户端的id
typedef struct CPGUID {
	DWORD	data1;
	USHORT	data2;
	USHORT	data3;
	UCHAR	data4[8];

	struct CPGUID& operator=(const GUID& guid)
	{
		this->data1 = guid.Data1;
		this->data2 = guid.Data2;
		this->data3 = guid.Data3;
		memcpy(this->data4, guid.Data4, sizeof(this->data4));
		return *this;
	}

	GUID GetGUID() const
	{
		GUID guid;
		guid.Data1 = this->data1;
		guid.Data2 = this->data2;
		guid.Data3 = this->data3;
		memcpy(guid.Data4, this->data4, sizeof(this->data4));

		return guid;
	}

	bool operator<(const struct CPGUID& another) const
	{
		const LPBYTE p = (const LPBYTE) this;
		const LPBYTE ap = (const LPBYTE) &another;
		for (int i = 0; i < 16; i++)
		{
			if (p[i] > ap[i]) return false;
			else if (p[i] < ap[i]) return true;
		}

		return false;
	}

	bool operator==(const struct CPGUID& another) const
	{
		return 0 == memcmp(this, &another, sizeof(CPGUID));
	}
} CPGUID;

//包头
typedef struct  
{
	CPGUID		guid;
	BYTE		cmd;
	CPSERIAL	serial;
	DWORD		index;
	DWORD		size;
} CP_HEADER;
#define CPHEADER_SIZE sizeof(CP_HEADER)
#define PACKET_SIZE(_pPacket) (CPHEADER_SIZE + _pPacket->header.size)

#define CPCMD_DATA_MORE		0	//还有更多的数据
#define CPCMD_DATA_END		1	//这个包是最后一个包
#define CPCMD_RESEND		2	//index为期望的index，size为已收到的数据大小
#define CPCMD_ABORT_SEND	3	//中止传输
#define CPCMD_NO_DATA		4	//这个包中没有数据，只是为了应答

//数据包
typedef struct 
{
	CP_HEADER	header;
	BYTE		data[1];

	void ToByteBuffer(ByteBuffer& byteData)
	{
		DWORD dwSize = PACKET_SIZE(this);
		byteData.Alloc(dwSize);
		memcpy((LPBYTE)byteData, this, dwSize);
	}
} CP_PACKET, *PCP_PACKET;
#pragma pack()
