#pragma once
#include <map>

#define UDP_FLAG 0xAFAFBFBA


//TCP°üÍ·
typedef struct
{
	DWORD flag;
	int nSize;
}UDP_HEADER,*PUDPHEADER;
