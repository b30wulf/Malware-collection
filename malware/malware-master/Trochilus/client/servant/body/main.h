#pragma once

#include "../../../common/CommNames.h"

#ifdef SERVANT_EXPORTS
#	define SERVANT_API extern "C" 
#else
#	define SERVANT_API extern "C" __declspec(dllimport)
#endif

SERVANT_API void InitServant();

SERVANT_API void DeinitServant();

SERVANT_API void InstallService();

SERVANT_API GUID GetCID();