#pragma once

//TCP消息的最大长度
#ifndef TCPMSGSIZE
#	define TCPMSGSIZE	(16 * 1024)
#endif

//消息处理回调函数
typedef BOOL (*FnTcpMsgHandler)(SOCKADDR_IN addr, SOCKET clientSocket, const LPBYTE pData, DWORD dwDataSize, LPBYTE pSessionData, LPVOID lpParameter);

//连接断开通知函数
typedef enum
{
	DISCONNECTREASON_UNKNOWN = 0,
	DISCONNECTREASON_CLIENT_DISCONNECT,
	DISCONNECTREASON_OPERATION_ABORT,
} DISCONNECT_REASON;
typedef void (*FnTcpDisconnectNotify)(SOCKADDR_IN addr, SOCKET clientSocket, LPBYTE pSessionData, DISCONNECT_REASON reason, LPVOID lpParameter);
