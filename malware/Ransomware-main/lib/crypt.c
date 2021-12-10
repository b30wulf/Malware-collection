#include <openssl/evp.h>
#include "crypt.h"

/*
struct stat {
               dev_t     st_dev;         ID of device containing file
               ino_t     st_ino;         Inode number
               mode_t    st_mode;        File type and mode
               nlink_t   st_nlink;       Number of hard links
               uid_t     st_uid;         User ID of owner
               gid_t     st_gid;         Group ID of owner
               dev_t     st_rdev;        Device ID (if special file)
               off_t     st_size;        Total size, in bytes
               blksize_t st_blksize;     Block size for filesystem I/O
               blkcnt_t  st_blocks;      Number of 512B blocks allocated

               struct timespec st_atim;  Time of last access
               struct timespec st_mtim;  Time of last modification
               struct timespec st_ctim;  Time of last status change

           #define st_atime st_atim.tv_sec
           #define st_mtime st_mtim.tv_sec
           #define st_ctime st_ctim.tv_sec
           };
*/

char* generate_key(int length){
    char* charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,.-#@$%&(){};'?!";
    char *randomString = (char*) malloc(sizeof(char) * (length +1));
    int key;

    if (randomString) {
        for (int n = 0; n < length; n++) {
            key = rand() % (int)(sizeof(charset) -1);
            randomString[n] = charset[key];
        }
        randomString[length] = '\0';
    }

    return randomString;
}

void encrypt(FILE *in, FILE *out, char *key, char *iv){
    int chunk_size = 512;
    unsigned char inbuf[chunk_size];
    unsigned char outbuf[chunk_size + EVP_MAX_BLOCK_LENGTH];
    int inlen;
    int outlen;

    EVP_CIPHER_CTX *ctx = EVP_CIPHER_CTX_new();

    EVP_CIPHER_CTX_init(ctx);
    EVP_CipherInit_ex(ctx, EVP_bf_cbc(), NULL, NULL, NULL, 1); // 1 encrypt - 0 decrypt
    EVP_CIPHER_CTX_set_key_length(ctx, 16);
    EVP_CipherInit_ex(ctx, NULL, NULL, (const unsigned char*) key, (const unsigned char*) iv, 1);
    while(1){
        inlen = fread(inbuf, 1, chunk_size, in);
        if(inlen <= 0)   break;
        if(!EVP_CipherUpdate(ctx, outbuf, &outlen, inbuf, inlen)){
            /* Error */
            EVP_CIPHER_CTX_cleanup(ctx);
            return;
        }
        fwrite(outbuf, 1, outlen, out);
    }
    if(!EVP_CipherFinal_ex(ctx, outbuf, &outlen)){
        /* Error */
        EVP_CIPHER_CTX_cleanup(ctx);
        return;
    }
    fwrite(outbuf, 1, outlen, out);
    EVP_CIPHER_CTX_cleanup(ctx);
    rewind(in);
    rewind(out);
}

void decrypt(FILE *in, FILE *out, char *key, char *iv){
    int chunk_size = 512;
    unsigned char inbuf[chunk_size];
    unsigned char outbuf[chunk_size + EVP_MAX_BLOCK_LENGTH];
    int inlen;
    int outlen;

    EVP_CIPHER_CTX *ctx = EVP_CIPHER_CTX_new();

    EVP_CIPHER_CTX_init(ctx);
    EVP_CipherInit_ex(ctx, EVP_bf_cbc(), NULL, NULL, NULL, 0); // 1 encrypt - 0 decrypt
    EVP_CIPHER_CTX_set_key_length(ctx, 16);
    EVP_CipherInit_ex(ctx, NULL, NULL, (const unsigned char*) key, (const unsigned char*) iv, 0);
    while(1){
        inlen = fread(inbuf, 1, chunk_size, in);
        if(inlen <= 0) break;
        if(!EVP_CipherUpdate(ctx, outbuf, &outlen, inbuf, inlen)){
            /* Error */
            EVP_CIPHER_CTX_cleanup(ctx);
            return;
        }
        fwrite(outbuf, 1, outlen, out);
    }
    if(!EVP_CipherFinal_ex(ctx, outbuf, &outlen)){
        /* Error */
        EVP_CIPHER_CTX_cleanup(ctx);
        return;
    }
    fwrite(outbuf, 1, outlen, out);
    EVP_CIPHER_CTX_cleanup(ctx);
    rewind(in);
    rewind(out);
}
