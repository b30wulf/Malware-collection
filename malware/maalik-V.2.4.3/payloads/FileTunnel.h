/*
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
*/

// This is simple code to write and read data from file

#ifndef FILE_TUNNEL_
#define FILE_TUNNEL_

#include <windows.h>
#include <stdio.h>

#define OUTPUTFILE "output.png" // This is a text file

void WriteOutput(const char* output)
{
    DWORD attributes = GetFileAttributes(OUTPUTFILE);
    if (attributes != FILE_ATTRIBUTE_HIDDEN)
    {
        SetFileAttributes(OUTPUTFILE, attributes + FILE_ATTRIBUTE_HIDDEN);
    }
    
    FILE* fs;
    if ((fs = fopen(OUTPUTFILE, "w")) != NULL)
    {
        fprintf(fs, "%s", output);
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
        buffer = (char*)malloc(length);
        if (buffer)
        {
            fread(buffer, 1, length, f);
        }
        fclose(f);
        return buffer;
    }
}

#endif // !FILE_TUNNEL_
