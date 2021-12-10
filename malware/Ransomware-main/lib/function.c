#include <stdlib.h>
#include <openssl/evp.h>
#include <fcntl.h>
#include <string.h>
#include <sys/stat.h>
#include <unistd.h>
#include "function.h"

char* linkStr(char* s1, char* s2, int isPath){
  // Windows backslash Unix-like slash
  char* separator = "/";
  int length = isPath ? strlen(s1)+strlen(s2)+strlen(separator)+1 : strlen(s1)+strlen(s2)+1;
  int size = isPath ? sizeof(s1)+sizeof(s2)+sizeof(separator)+1 : sizeof(s1)+sizeof(s2)+1;
  char* newStr = (char*) calloc(length, size);
  strcat(newStr,s1);
  if(isPath)
    strcat(newStr,separator);
  strcat(newStr,s2);
  return newStr;
}

char* removeLastChars(char* str, int n){
  char* newStr = (char*) malloc(strlen(str));
  strcpy(newStr,str);
  newStr[strlen(newStr)-n] = '\0';
  return newStr;
}

void deleteFile(char *path){
  int BUF_SIZE = 4096;
  struct stat path_buff;

  if (stat(path, &path_buff) == -1)
    return;

  off_t fileSize = path_buff.st_size;

  // O_WRONLY Apre un file per la scrittura. La posizione e' all'inizio del file.
  int file = open(path, O_WRONLY);

  if (file == -1)
      return;

  void *buf = malloc(BUF_SIZE);
  // fill buf con 0
  memset(buf, 0, BUF_SIZE);
  ssize_t ret = 0;
  off_t shift = 0;
  while((ret = write(file, buf, ((fileSize - shift >BUF_SIZE) ? BUF_SIZE : (fileSize - shift)))) > 0)
    shift += ret;

  close(file);
  free(buf);
  if (ret == -1)
      return;

  remove(path);
}
