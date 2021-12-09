#pragma once

typedef void (*FnUdpMsgHandler)(SOCKADDR_IN addr, SOCKET listenSock, const LPBYTE pData, DWORD dwDataSize, LPVOID lpParameter);
