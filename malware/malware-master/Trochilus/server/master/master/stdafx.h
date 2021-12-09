// stdafx.h : 标准系统包含文件的包含文件，
// 或是经常使用但不常更改的
// 特定于项目的包含文件
//

#pragma once

#include "targetver.h"

#define WIN32_LEAN_AND_MEAN             //  从 Windows 头文件中排除极少使用的信息
#define _ATL_CSTRING_EXPLICIT_CONSTRUCTORS      // 某些 CString 构造函数将是显式的

#ifndef VC_EXTRALEAN
#define VC_EXTRALEAN            //  从 Windows 头文件中排除极少使用的信息
#endif

#include <afx.h>
#include <afxwin.h>         // MFC 核心组件和标准组件
#include <afxext.h>         // MFC 扩展
#ifndef _AFX_NO_OLE_SUPPORT
#include <afxdtctl.h>           // MFC 对 Internet Explorer 4 公共控件的支持
#endif
#ifndef _AFX_NO_AFXCMN_SUPPORT
#include <afxcmn.h>                     // MFC 对 Windows 公共控件的支持
#endif // _AFX_NO_AFXCMN_SUPPORT

#include <iostream>
// Windows 头文件:
#include <windows.h>

#define MAKE_PAIR(_a,b,c) _a::value_type((b),(c))

// TODO: 在此处引用程序需要的其他头文件
#include "tstring.h"
#include "CommonDefines.h"
#include "AutoCleanup.h"

#include "ods.h"
#include "master.h"
#include "midutils.h"
#include "Exports.h"

#pragma comment(lib,"midutils.lib")

#define OUTPUT_LOG

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

#ifndef _DEBUG
#	define DATMODULE
#endif

#define TCPMSGSIZE	(64 * 1024)
