#ifndef CRYPT
#define CRTPY
char* generate_key(int length);
void encrypt(FILE *in, FILE *out, char *key, char *iv);
void decrypt(FILE *in, FILE *out, char *key, char *iv);
#endif
