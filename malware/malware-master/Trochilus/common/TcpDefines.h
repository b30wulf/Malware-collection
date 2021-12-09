#pragma once
#include <map>

#define TCP_FLAG 0xAFAFBFBF


//TCP包头
typedef struct
{
	DWORD flag;
	int nSize;
}TCP_HEADER,*PTCPHEADER;

//TCP黏包数据结构
typedef struct
{
	TCP_HEADER header;
	int nCurSize;
	PBYTE buffer;
}TCP_PACKET,*PTCP_PACKET;

typedef std::map<SOCKET, TCP_PACKET> TcpPacketMap;;