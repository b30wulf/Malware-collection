#ifndef _CONFIG_H_
#define _CONFIG_H_

namespace Config
{
#define CFG_DCT_MODVER_SECTION	"modver"
#define CFG_DCT_INJECT_SECTION	"inject"
#define CFG_DCT_MODULES_SECTION	"modules"
#define CFG_DCT_MAIN_SECTION	"main"
#define CFG_DCT_MAIN_SRVURLS	"srvurls"
#define CFG_DCT_MAIN_SRVDELAY	"srvdelay"
#define CFG_DCT_MAIN_SRVRETRY	"srvretry"
#define CFG_DCT_MAIN_VERSION	"mainver"
#define CFG_DCT_MAIN_BUILDID	"buildid"

	extern CHAR ConfigFileName[MAX_PATH];
	extern PVOID ConfigBuffer;
	extern DWORD ConfigSize;

	VOID ReadConfig();

	DWORD ReadInt(PCHAR Section, PCHAR Variable);
	BOOLEAN WriteInt(PCHAR Section, PCHAR Name, DWORD Int);

	BOOLEAN ReadString(PCHAR Section, PCHAR Variable, PCHAR Value, DWORD Size);
	BOOLEAN WriteString(PCHAR Section, PCHAR Variable, PCHAR Value);

	BOOLEAN RegWriteString(PCHAR String, PCHAR Value);
	BOOLEAN RegReadString(PCHAR String, PCHAR Value, DWORD dwValue);
};

#endif
