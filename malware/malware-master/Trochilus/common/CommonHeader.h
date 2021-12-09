#pragma once

#include <TCHAR.h>
#include <stdlib.h>
#include <malloc.h> 
#include "CommonDefines.h"
#include "ods.h"
#include "AutoCleanup.h"
#include "tstring.h"

#ifdef OUTPUT_LOG
#	define errorLogE ErrorODSE
#	define errorLog ErrorODS
#	define infoLogE InfoODSE
#	define infoLog InfoODS
#	define debugLogE DebugODSE
#	define debugLog DebugODS
#else
#	define errorLogE 
#	define errorLog 
#	define infoLogE 
#	define infoLog 
#	define debugLogE 
#	define debugLog 
#endif

//针对servantshell的开关
#ifndef _DEBUG
#	define USE_ENCRYPTED_CORE
#endif

//针对servant的开关
#ifndef _DEBUG
#	define DATMODULE
#endif
//#define DATMODULE

#ifdef DATMODULE
#	define DECRYPT_MODULE	//是否对模块dll进行解密
#else
#	define USE_SYS_API		//是否实用系统API来装载模块dll
#endif
