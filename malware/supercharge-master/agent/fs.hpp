#ifndef FS
#define FS 

#include "supercharge.hpp"
//#include <filesystem> // C++ 17 only.
// void sendFile(std::string filename);

//std::string sendFile(char* filename);

int changeDirectory(char* to);
char* cDir(); // Return the current Directory name.
#endif