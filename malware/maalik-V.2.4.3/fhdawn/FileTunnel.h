/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/

// This is simple code to write and read data from file

#ifndef FILE_TUNNEL_
#define FILE_TUNNEL_

#include "fhdawn.h"

#define OUTPUTFILE "output.png" // This is a text file

void WriteOutput(const char* output)
{
    DWORD attributes = GetFileAttributes(OUTPUTFILE);
    if (attributes != FILE_ATTRIBUTE_HIDDEN)
    {
        SetFileAttributes(OUTPUTFILE, attributes + FILE_ATTRIBUTE_HIDDEN);
    }
    
    FILE* fs;
    if ((fs = fopen(OUTPUTFILE, "w+a")) != NULL)    
    {
        fputs(output, fs);
        fclose(fs);
    }
}

char* GetInputOutput()
{
    char* buffer = 0;
    long length;
    FILE* f;
    if ((f = fopen(OUTPUTFILE, "rb")) != NULL)
    {
        fseek(f, 0, SEEK_END);
        length = ftell(f);
        fseek(f, 0, SEEK_SET);
        buffer = malloc(length);
        if (buffer)
        {
            fread(buffer, 1, length, f);
        }
        fclose(f);
    }
    else {
        buffer = malloc(200);
        snprintf(buffer, 200, "Output file not found.");
    }
    return buffer;
}

#endif // !FILE_TUNNEL_
