#include "fs.hpp"

char* cDir()
{
    static char DIR[MAX_PATH];
    memset(DIR, '\0', MAX_PATH);
    GetCurrentDirectory(MAX_PATH, DIR);
    return (char*)DIR;
}


// std::string sendFile(char* filename)
// {
//     supercharge LCLASS;
//     std::ifstream file(filename, std::ios::binary);
//     std::string filebuf;
//     std::ostringstream sbuf;
//     if(file.is_open())
//     {
//         sbuf << file.rdbuf();
//     }
//     filebuf = sbuf.str();
//     file.close();
//     return filebuf;
// }


int changeDirectory(char* to)
{
    if(SetCurrentDirectoryA(to) == 0)
    {
        return GetLastError();
    } else {
        return OKCODE; 
    }
}

