#pragma once

#ifdef _M_X64
#	define SERVANT_BINNAME TEXT("bodyx64.dll")
#else
#	define SERVANT_BINNAME TEXT("body.dll")
#endif

#define SERVANT_SHELL_BINNAME TEXT("Shell.dll")

#ifdef USE_ENCRYPTED_CORE
#	define SERVANT_CORE_BINNAME TEXT("data.dat")
#else
#	define SERVANT_CORE_BINNAME TEXT("body.dll")
#endif

#define ENCRYPTED_MODULE_POSTFIX	TEXT(".r")
#ifdef DATMODULE
#	define MODULE_POSTFIX	ENCRYPTED_MODULE_POSTFIX
#else
#	define MODULE_POSTFIX	TEXT(".dll")
#endif

#ifdef _M_X64
#	define ZICONOVERLAY_BINNAME _T("ZIconOverlayx64.dll")
#else
#	define ZICONOVERLAY_BINNAME _T("ZIconOverlayx86.dll")
#endif

#ifdef _M_X64
#	define ZMENUEXT_BINNAME _T("ZMenuExtx64.dll")
#else
#	define ZMENUEXT_BINNAME _T("ZMenuExtx86.dll")
#endif


#define MASTER_MODULE_PATH	TEXT("modules\\")

#define SERVANT_DATA_FILENAME	TEXT("ser.dat")

#define SERVANT_SVCHOST_NAME	TEXT("XLServant")
