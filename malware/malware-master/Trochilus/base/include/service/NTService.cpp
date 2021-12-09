/////////////////////////////////////////////////////////////////////////////
// Copyright (C) 1997 by Joerg Koenig and the ADG mbH, Mannheim, Germany
// All rights reserved
//
// Distribute freely, except: don't remove my name from the source or
// documentation (don't take credit for my work), mark your changes (don't
// get me blamed for your possible bugs), don't alter or remove this
// notice.
// No warrantee of any kind, express or implied, is included with this
// software; use at your own risk, responsibility for damages (if any) to
// anyone resulting from the use of this software rests entirely with the
// user.
//
// Send bug reports, bug fixes, enhancements, requests, flames, etc., and
// I'll try to keep a version up to date.  I can be reached as follows:
//    J.Koenig@adg.de                 (company site)
//    Joerg.Koenig@rhein-neckar.de    (private site)
/////////////////////////////////////////////////////////////////////////////
//
// MODIFIED BY TODD C. WILSON FOR THE ROAD RUNNER NT LOGIN SERVICE.
// HOWEVER, THESE MODIFICATIONS ARE BROADER IN SCOPE AND USAGE AND CAN BE USED
// IN OTHER PROJECTS WITH NO CHANGES.
// MODIFIED LINES FLAGGED/BRACKETED BY "//!! TCW MOD"
//
/////////////////////////////////////////////////////////////////////////////


// last revised: $Date: 2008/12/25 08:47:13 $, $Revision: 1.2 $


/////////////////////////////////////////////////////////////////////////////
// Acknoledgements:
//	o	Thanks to Victor Vogelpoel (VictorV@Telic.nl) for his bug-fixes
//		and enhancements.
//	o	Thanks to Todd C. Wilson (todd@mediatec.com) for the
//		"service" on Win95
//
// Changes:
//	01/21/99
//	o	Bug fixed in "DeregisterApplicationLog()"
//		thanks to Grahame Willis (grahamew@questsoftware.com.au):
//
//	04/30/98
//	o	Added two more switches to handle command line arguments:
//		-e will force a running service to stop (corresponding
//		method in this class: virtual BOOL EndService();) and
//		-s will force the service to start (method:
//		virtual BOOL StartupService())
//
//	02/05/98
//	o	Added the methods "RegisterApplicationLog()" and
//		"DeregisterApplicationLog()" (both virtual). The first one will be
//		called from "InstallService()" and creates some registry-entries
//		for a better event-log. The second one removes these entries when
//		the service will uninstall (see "RemoveService()")
//	o	The service now obtains the security identifier of the current user
//		and uses this SID for event-logging.
//	o	The memory allocated by "CommandLineToArgvW()" will now release
//		(UNICODE version only)
//	o	The service now uses a simple message catalogue for a nicer
//		event logging

#include "stdafx.h"

#include <io.h>
#include <fcntl.h>

#include "NTService.h"
#include "NTServiceEventLogMsg.h"


#ifndef RSP_SIMPLE_SERVICE
	#define RSP_SIMPLE_SERVICE 1
#endif
#ifndef RSP_UNREGISTER_SERVICE
	#define RSP_UNREGISTER_SERVICE 0
#endif

#ifndef MY_SERVICE_CONTROL_STOP
#	define MY_SERVICE_CONTROL_STOP	SERVICE_CONTROL_STOP
#endif


BOOL CNTService :: m_bInstance = FALSE;

static CNTService * gpTheService = 0;			// the one and only instance

CNTService * AfxGetService() { return gpTheService; }




static LPCTSTR gszAppRegKey = TEXT("SYSTEM\\CurrentControlSet\\Services\\EventLog\\Application\\");
static LPCTSTR gszWin95ServKey=TEXT("Software\\Microsoft\\Windows\\CurrentVersion\\RunServices");	//!! TCW MOD

#ifdef _UNICODE
	#define ConsolePrintf ConsolePrintfW
#else
	#define ConsolePrintf printf	
#endif

#ifdef _UNICODE
	void ConsolePrintfW(LPCWSTR Format, ...)
	{
		WCHAR Msg[1024];

		va_list ap;

		va_start(ap, Format);
		int nChars = _vsntprintf_s(Msg, sizeof(Msg)/sizeof(Msg[0]), _TRUNCATE, Format, ap);
		if (nChars > 0)
		{
			Msg[sizeof(Msg)/sizeof(Msg[0]) - 1] = L'\0';
			
			DWORD CharsWritten;

			::WriteConsoleW(::GetStdHandle(STD_OUTPUT_HANDLE), 
							Msg, nChars, &CharsWritten, NULL);
		}
	}
#endif
/////////////////////////////////////////////////////////////////////////////
// class CNTService -- construction/destruction

CNTService :: CNTService( LPCTSTR lpServiceName, LPCTSTR lpDisplayName )
	: m_lpServiceName(lpServiceName)
	, m_lpDisplayName(lpDisplayName ? lpDisplayName : lpServiceName)
	, m_dwCheckPoint(0)
	, m_dwErr(0)
	, m_bDebug(FALSE)
	, m_sshStatusHandle(0)
	, m_dwControlsAccepted(SERVICE_ACCEPT_STOP)
	, m_pUserSID(0)
	, m_fConsoleReady(FALSE)
	// parameters to the "CreateService()" function:
	, m_dwDesiredAccess(SERVICE_ALL_ACCESS)
	, m_dwServiceType(SERVICE_WIN32_OWN_PROCESS | SERVICE_INTERACTIVE_PROCESS)
	, m_dwStartType(SERVICE_AUTO_START)
	, m_dwErrorControl(SERVICE_ERROR_NORMAL)
	, m_pszLoadOrderGroup(0)
	, m_dwTagID(0)
	, m_pszDependencies(0)
	, m_pszStartName(0)
	, m_pszPassword(0)
{
	_ASSERTE( ! m_bInstance );

	OSVERSIONINFO vi;
	vi.dwOSVersionInfoSize=sizeof(vi);  // init this.
	GetVersionEx(&vi);      //lint !e534
	m_bWinNT = (vi.dwPlatformId == VER_PLATFORM_WIN32_NT);

	m_bInstance = TRUE;
	gpTheService = this;
	
	// SERVICE_STATUS members that rarely change
	m_ssStatus.dwServiceType = SERVICE_WIN32_OWN_PROCESS;
	m_ssStatus.dwServiceSpecificExitCode = 0;

	if( m_bWinNT ) {
		/////////////////////////////////////////////////////////////////////////
		// Providing a SID (security identifier) was contributed by Victor
		// Vogelpoel (VictorV@Telic.nl).
		// The code from Victor was slightly modified.

		// Get security information of current user
		BYTE	security_identifier_buffer[ 4096 ];
		DWORD	dwSizeSecurityIdBuffer = sizeof( security_identifier_buffer );
		PSID	user_security_identifier = NULL;

		TCHAR sUserName[ 256 ];
		DWORD dwSizeUserName  =  255;

		TCHAR sDomainName[ 256 ];
		DWORD dwSizeDomainName = 255;

		SID_NAME_USE sidTypeSecurityId;

		::ZeroMemory( sUserName, sizeof( sUserName ) );
		::ZeroMemory( sDomainName, sizeof( sDomainName ) );
		::ZeroMemory( security_identifier_buffer, dwSizeSecurityIdBuffer );

		::GetUserName( sUserName, &dwSizeUserName );

		if( ::LookupAccountName(
					0,
					sUserName,
					&security_identifier_buffer,
					&dwSizeSecurityIdBuffer,
					sDomainName,
					&dwSizeDomainName,
					&sidTypeSecurityId
				)) {
			if( ::IsValidSid( PSID(security_identifier_buffer) ) ) {
				DWORD dwSidLen = ::GetLengthSid(PSID(security_identifier_buffer));
				m_pUserSID = PSID(new BYTE [dwSidLen]);
				::CopySid(dwSidLen, m_pUserSID, security_identifier_buffer);
				_ASSERTE(::EqualSid(m_pUserSID, security_identifier_buffer));
			}
		}
	}
	/////////////////////////////////////////////////////////////////////////
}


CNTService :: ~CNTService() {
	_ASSERTE( m_bInstance );
	delete [] LPBYTE(m_pUserSID);
	m_bInstance = FALSE;
	gpTheService = 0;
}



/////////////////////////////////////////////////////////////////////////////
// class CNTService -- overridables

#define NEXT_ARG ((((*Argv)[2])==TEXT('\0'))?(--Argc,*++Argv):(*Argv)+2)


BOOL CNTService :: RegisterService( int argc, TCHAR ** argv ) {
	BOOL (CNTService::* fnc)() = &CNTService::StartDispatcher;
    DWORD Argc;
    LPTSTR * Argv;
/*
#ifdef UNICODE
    Argv = CommandLineToArgvW(GetCommandLineW(), &Argc );
#else
    Argc = (DWORD) argc;
    Argv = argv;
#endif
*/
	Argc = (DWORD) argc;
	Argv = argv;

    while( ++Argv, --Argc ) {
		if( Argv[0][0] == TEXT('-') ) {
			switch( Argv[0][1] ) {
				case TEXT('i'):	// install the service
					fnc = &CNTService::InstallService;
					break;
				case TEXT('l'):	// login-account (only useful with -i)
					m_pszStartName = NEXT_ARG;
					break;
				case TEXT('p'):	// password (only useful with -i)
					m_pszPassword = NEXT_ARG;
					break;
				case TEXT('u'):	// uninstall the service
					fnc = &CNTService::RemoveService;
					break;
				case TEXT('s'):	// start the service
					fnc = &CNTService::StartupService;
					break;
				case TEXT('e'):	// end the service
					fnc = &CNTService::EndService;
					break;
				case TEXT('d'):	// debug the service
				case TEXT('f'):	//!! TCW MOD faceless non-service (Win95) mode
					#ifdef UNICODE
						//::GlobalFree((HGLOBAL)Argv);
					#endif

					m_bDebug = TRUE;
					// pass original parameters to DebugService()
					return DebugService(argc, argv,(Argv[0][1]==TEXT('f'))); //!! TCW MOD faceless non-service (Win95) mode
			}
		}
	}

#ifdef UNICODE
	//::GlobalFree((HGLOBAL)Argv);
#endif

	//!! TCW MOD START - if Win95, run as faceless app.
	if( fnc == &CNTService::StartDispatcher && OsIsWin95() ) {
		// act as if -f was passed anyways.
		m_bDebug = TRUE;
		return DebugService(argc, argv, TRUE);
	}
	//!! TCW MOD END - if Win95, run as faceless app.

	return (this->*fnc)();
}


BOOL CNTService :: StartDispatcher() {
    // Default implementation creates a single threaded service.
	// Override this method and provide more table entries for
	// a multithreaded service (one entry for each thread).
	SERVICE_TABLE_ENTRY dispatchTable[] =
    {
        { LPTSTR(m_lpServiceName), (LPSERVICE_MAIN_FUNCTION)ServiceMain },
        { 0, 0 }
    };

	BOOL bRet = StartServiceCtrlDispatcher(dispatchTable);
	if( ! bRet ) {
		TCHAR szBuf[256];
        AddToMessageLog(GetLastErrorText(szBuf,255));
	}

	return bRet;
}


BOOL CNTService :: InstallService() {
    TCHAR szPath[1024];

	SetupConsole();	//!! TCW MOD - have to show the console here for the
					// diagnostic or error reason: orignal class assumed
					// that we were using _main for entry (a console app).
					// This particular usage is a Windows app (no console),
					// so we need to create it. Using SetupConsole with _main
					// is ok - does nothing, since you only get one console.

	if( GetModuleFileName( 0, szPath, 1023 ) == 0 ) {
		TCHAR szErr[256];
		ConsolePrintf(TEXT("Unable to install %s - %s\r\n"), m_lpDisplayName, GetLastErrorText(szErr, 256));
		return FALSE;
	}

	BOOL bRet = FALSE;

	if( OsIsWin95() ) {	//!! TCW MOD - code added to install as Win95 service
		// Create a key for that application and insert values for
		// "EventMessageFile" and "TypesSupported"
		HKEY hKey = 0;
		LONG lRet = ERROR_SUCCESS;
		if( ::RegCreateKey(HKEY_LOCAL_MACHINE, gszWin95ServKey , &hKey) == ERROR_SUCCESS ) {
			lRet =	::RegSetValueEx(
						hKey,				// handle of key to set value for
						m_lpServiceName,	// address of value to set (NAME OF SERVICE)
						0,					// reserved
						REG_EXPAND_SZ,		// flag for value type
						(CONST BYTE*)szPath,// address of value data
						(DWORD)(_tcslen(szPath) + 1)	// size of value data
					);
			::RegCloseKey(hKey);
			bRet=TRUE;
		}
	} else {
		// Real NT services go here.
		SC_HANDLE schSCManager =	OpenSCManager(
										0,						// machine (NULL == local)
										0,						// database (NULL == default)
										SC_MANAGER_ALL_ACCESS	// access required
									);
		if( schSCManager ) {
			SC_HANDLE schService =	CreateService(
										schSCManager,
										m_lpServiceName,
										m_lpDisplayName,
										m_dwDesiredAccess,
										m_dwServiceType,
										m_dwStartType,
										m_dwErrorControl,
										szPath,
										m_pszLoadOrderGroup,
										((m_dwServiceType == SERVICE_KERNEL_DRIVER ||
										  m_dwServiceType == SERVICE_FILE_SYSTEM_DRIVER) &&
										 (m_dwStartType == SERVICE_BOOT_START ||
										  m_dwStartType == SERVICE_SYSTEM_START)) ?
											&m_dwTagID : 0,
										m_pszDependencies,
										m_pszStartName,
										m_pszPassword
									);

			if( schService ) {
				ConsolePrintf(TEXT("%s installed.\r\n"), m_lpDisplayName );
				CloseServiceHandle(schService);
				bRet = TRUE;
			} else {
				TCHAR szErr[256];
				ConsolePrintf(TEXT("CreateService failed - %s\r\n"), GetLastErrorText(szErr, 256));
			}

			CloseServiceHandle(schSCManager);
		 } else {
			TCHAR szErr[256];
			ConsolePrintf(TEXT("OpenSCManager failed - %s\r\n"), GetLastErrorText(szErr,256));
		}

		if( bRet ) {
			// installation succeeded. Now register the message file
			RegisterApplicationLog(
				szPath,		// the path to the application itself
				EVENTLOG_ERROR_TYPE | EVENTLOG_WARNING_TYPE | EVENTLOG_INFORMATION_TYPE // supported types
			);

			AddToMessageLog(TEXT("Service installed"),EVENTLOG_INFORMATION_TYPE);
		}
	}	//!! TCW MOD

	return bRet;
}


BOOL CNTService :: RemoveService() {
	BOOL bRet = FALSE;

	SetupConsole();	//!! TCW MOD - have to show the console here for the
					// diagnostic or error reason: orignal class assumed
					// that we were using _main for entry (a console app).
					// This particular usage is a Windows app (no console),
					// so we need to create it. Using SetupConsole with _main
					// is ok - does nothing, since you only get one console.


	if( OsIsWin95() ) {	//!! TCW MOD - code added to install as Win95 service
		HKEY hKey = 0;
		LONG lRet = ERROR_SUCCESS;
		if( ::RegCreateKey(HKEY_LOCAL_MACHINE, gszWin95ServKey , &hKey) == ERROR_SUCCESS ) {
			lRet = ::RegDeleteValue(hKey, m_lpServiceName);
			::RegCloseKey(hKey);
			bRet=TRUE;
		}
	} else {
		// Real NT services go here.
		SC_HANDLE schSCManager = OpenSCManager(
									0,						// machine (NULL == local)
									0,						// database (NULL == default)
									SC_MANAGER_ALL_ACCESS	// access required
								);
		if( schSCManager ) {
			SC_HANDLE schService =	OpenService(
										schSCManager,
										m_lpServiceName,
										SERVICE_ALL_ACCESS
									);

			if( schService ) {
				// try to stop the service
				if( ControlService(schService, SERVICE_CONTROL_STOP, &m_ssStatus) ) {
					ConsolePrintf(TEXT("Stopping %s."), m_lpDisplayName);
					Sleep(1000);

					while( QueryServiceStatus(schService, &m_ssStatus) ) {
						if( m_ssStatus.dwCurrentState == SERVICE_STOP_PENDING ) {
							ConsolePrintf(TEXT("."));
							Sleep( 1000 );
						} else
							break;
					}

					if( m_ssStatus.dwCurrentState == SERVICE_STOPPED )
						ConsolePrintf(TEXT("\n%s stopped.\r\n"), m_lpDisplayName);
						 else
							  ConsolePrintf(TEXT("\n%s failed to stop.\r\n"), m_lpDisplayName);
				}

				// now remove the service
				if( DeleteService(schService) ) {
					ConsolePrintf(TEXT("%s removed.\r\n"), m_lpDisplayName);
					bRet = TRUE;
				} else {
					TCHAR szErr[256];
					ConsolePrintf(TEXT("DeleteService failed - %s\r\n"), GetLastErrorText(szErr,256));
				}

				CloseServiceHandle(schService);
			} else {
				TCHAR szErr[256];
				ConsolePrintf(TEXT("OpenService failed - %s\r\n"), GetLastErrorText(szErr,256));
			}

			  CloseServiceHandle(schSCManager);
		 } else {
			TCHAR szErr[256];
			ConsolePrintf(TEXT("OpenSCManager failed - %s\r\n"), GetLastErrorText(szErr,256));
		}

		if( bRet )
			DeregisterApplicationLog();
	}

	return bRet;
}


BOOL CNTService :: EndService() {
	BOOL bRet = FALSE;

	SC_HANDLE schSCManager = ::OpenSCManager(
								0,						// machine (NULL == local)
								0,						// database (NULL == default)
								SC_MANAGER_ALL_ACCESS	// access required
							);
	if( schSCManager ) {
		SC_HANDLE schService =	::OpenService(
									schSCManager,
									m_lpServiceName,
									SERVICE_ALL_ACCESS
								);

		if( schService ) {
			// try to stop the service
			if( ::ControlService(schService, SERVICE_CONTROL_STOP, &m_ssStatus) ) {
				ConsolePrintf(TEXT("Stopping %s."), m_lpDisplayName);
				::Sleep(1000);

				while( ::QueryServiceStatus(schService, &m_ssStatus) ) {
					if( m_ssStatus.dwCurrentState == SERVICE_STOP_PENDING ) {
						ConsolePrintf(TEXT("."));
						::Sleep( 1000 );
					} else
						break;
				}

				if( m_ssStatus.dwCurrentState == SERVICE_STOPPED )
					bRet = TRUE, ConsolePrintf(TEXT("\n%s stopped.\r\n"), m_lpDisplayName);
                else
                    ConsolePrintf(TEXT("\n%s failed to stop.\r\n"), m_lpDisplayName);
			}

			::CloseServiceHandle(schService);
		} else {
			TCHAR szErr[256];
			ConsolePrintf(TEXT("OpenService failed - %s\r\n"), GetLastErrorText(szErr,256));
		}

        ::CloseServiceHandle(schSCManager);
    } else {
		TCHAR szErr[256];
		ConsolePrintf(TEXT("OpenSCManager failed - %s\r\n"), GetLastErrorText(szErr,256));
	}

	return bRet;
}


BOOL CNTService :: StartupService() {
	BOOL bRet = FALSE;

	SC_HANDLE schSCManager = ::OpenSCManager(
								0,						// machine (NULL == local)
								0,						// database (NULL == default)
								SC_MANAGER_ALL_ACCESS	// access required
							);
	if( schSCManager ) {
		SC_HANDLE schService =	::OpenService(
									schSCManager,
									m_lpServiceName,
									SERVICE_ALL_ACCESS
								);

		if( schService ) {
			// try to start the service
			ConsolePrintf(TEXT("Starting up %s."), m_lpDisplayName);
			if( ::StartService(schService, 0, 0) ) {
				Sleep(1000);

				while( ::QueryServiceStatus(schService, &m_ssStatus) ) {
					if( m_ssStatus.dwCurrentState == SERVICE_START_PENDING ) {
						ConsolePrintf(TEXT("."));
						Sleep( 1000 );
					} else
						break;
				}

				if( m_ssStatus.dwCurrentState == SERVICE_RUNNING )
					bRet = TRUE, ConsolePrintf(TEXT("\n%s started.\r\n"), m_lpDisplayName);
                else
                    ConsolePrintf(TEXT("\n%s failed to start.\r\n"), m_lpDisplayName);
			} else {
				// StartService failed
				TCHAR szErr[256];
				ConsolePrintf(TEXT("\n%s failed to start: %s\r\n"), m_lpDisplayName, GetLastErrorText(szErr,256));
			}

			::CloseServiceHandle(schService);
		} else {
			TCHAR szErr[256];
			ConsolePrintf(TEXT("OpenService failed - %s\r\n"), GetLastErrorText(szErr,256));
		}

        ::CloseServiceHandle(schSCManager);
    } else {
		TCHAR szErr[256];
		ConsolePrintf(TEXT("OpenSCManager failed - %s\r\n"), GetLastErrorText(szErr,256));
	}

	return bRet;
}


////////////////////////////////////////////////////////////////////////////
//!! TCW MOD - faceless window procedure for usage within Win95 (mostly),
// but can be invoked under NT by using -f
LRESULT CALLBACK _FacelessWndProc_( HWND hwnd, UINT uMsg, WPARAM wParam, LPARAM lParam ) {
	if (uMsg==WM_QUERYENDSESSION || uMsg==WM_ENDSESSION || uMsg==WM_QUIT) {
		if (lParam==NULL || uMsg==WM_QUIT) {
			DestroyWindow(hwnd);	// kill me
			if (AfxGetService()!=NULL)
				AfxGetService()->Stop();	// stop me.
			return TRUE;
		}
	}
	return DefWindowProc(hwnd,uMsg,wParam,lParam);
}
////////////////////////////////////////////////////////////////////////////


BOOL CNTService :: DebugService(int argc, TCHAR ** argv, BOOL faceless) {
    DWORD dwArgc;
    LPTSTR *lpszArgv;
/*
#ifdef UNICODE
    lpszArgv = CommandLineToArgvW(GetCommandLineW(), &(dwArgc) );
#else
    dwArgc   = (DWORD) argc;
    lpszArgv = argv;
#endif
*/
	dwArgc   = (DWORD) argc;
	lpszArgv = argv;

	if( !faceless ) {	//!! TCW MOD - no faceless, so give it a face.
		SetupConsole();	//!! TCW MOD - make the console for debugging
	   ConsolePrintf(TEXT("Debugging %s.\r\n"), m_lpDisplayName);

		SetConsoleCtrlHandler(ControlHandler, TRUE);
	}

	//!! TCW MOD START - if Win95, register server
	typedef DWORD (WINAPI *fp_RegServProc)(DWORD dwProcessId,DWORD dwType);
	fp_RegServProc fncptr=NULL;

	if( faceless /*&& OsIsWin95()*/ ) {
		WNDCLASS wndclass;
		memset(&wndclass,0,sizeof(WNDCLASS));
		wndclass.lpfnWndProc = _FacelessWndProc_;
		wndclass.hInstance = HINSTANCE(::GetModuleHandle(0));
		wndclass.lpszClassName = TEXT("RRL__FacelessWndProc_");
		ATOM atom = ::RegisterClass(&wndclass);
		HWND hwnd = ::CreateWindow(wndclass.lpszClassName,TEXT(""),0,0,0,0,0,0,0,wndclass.hInstance,0);
		HMODULE hModule = ::GetModuleHandle(TEXT("kernel32.dll"));
		// punch F1 on "RegisterServiceProcess" for what it does and when to use it.
		fncptr=(fp_RegServProc)::GetProcAddress(hModule, "RegisterServiceProcess");
		if (fncptr!=NULL)
			(*fncptr)(0, RSP_SIMPLE_SERVICE);
	}
	//!! TCW MOD END - if Win95, register server


    Run(dwArgc, lpszArgv);

#ifdef UNICODE
	//::GlobalFree((HGLOBAL)lpszArgv);
#endif

	if (fncptr!=NULL)		//!! TCW MOD - if it's there, remove it: our run is over
		(*fncptr)(0, RSP_UNREGISTER_SERVICE);

	return TRUE;
}


void CNTService :: Pause() {
}


void CNTService :: Continue() {
}

void CNTService :: Shutdown() {
}


/////////////////////////////////////////////////////////////////////////////
// class CNTService -- default handlers

void WINAPI CNTService :: ServiceMain(DWORD dwArgc, LPTSTR *lpszArgv) {
	_ASSERTE( gpTheService != 0 );

	// register our service control handler:
	gpTheService->m_sshStatusHandle =	RegisterServiceCtrlHandler(
											gpTheService->m_lpServiceName,
											CNTService::ServiceCtrl
										);

	if( gpTheService->m_sshStatusHandle )

		// report the status to the service control manager.
		if( gpTheService->ReportStatus(SERVICE_START_PENDING) ){
			gpTheService->Run( dwArgc, lpszArgv );}

	// try to report the stopped status to the service control manager.
	if( gpTheService->m_sshStatusHandle )
		gpTheService->ReportStatus(SERVICE_STOPPED);
}


void WINAPI CNTService :: ServiceCtrl(DWORD dwCtrlCode) {
	_ASSERTE( gpTheService != 0 );

	// Handle the requested control code.
	switch( dwCtrlCode ) {
		case MY_SERVICE_CONTROL_STOP:
			// Stop the service.
			gpTheService->m_ssStatus.dwCurrentState = SERVICE_STOP_PENDING;
			gpTheService->Stop();
			break;

		case SERVICE_CONTROL_PAUSE:
			gpTheService->m_ssStatus.dwCurrentState = SERVICE_PAUSE_PENDING;
			gpTheService->Pause();
			break;

		case SERVICE_CONTROL_CONTINUE:
			gpTheService->m_ssStatus.dwCurrentState = SERVICE_CONTINUE_PENDING;
			gpTheService->Continue();
			break;

		case SERVICE_CONTROL_SHUTDOWN:
			gpTheService->Shutdown();
			break;

		case SERVICE_CONTROL_INTERROGATE:
			// Update the service status.
			gpTheService->ReportStatus(gpTheService->m_ssStatus.dwCurrentState);
			break;

		default:
			break;
	}

}


BOOL WINAPI CNTService :: ControlHandler(DWORD dwCtrlType) {
	_ASSERTE(gpTheService != 0);
	switch( dwCtrlType ) {
		case CTRL_BREAK_EVENT:  // use Ctrl+C or Ctrl+Break to simulate
		case CTRL_C_EVENT:      // SERVICE_CONTROL_STOP in debug mode
			ConsolePrintf(TEXT("Stopping %s.\r\n"), gpTheService->m_lpDisplayName);
			// gpTheService->Stop();
			return TRUE;
	}
	return FALSE;
}


/////////////////////////////////////////////////////////////////////////////
// class CNTService -- helpers

//!! TCW MOD - added DWORD dwErrExit for error exit value. Defaults to zero
BOOL CNTService :: ReportStatus(
						DWORD dwCurrentState,
						DWORD dwWaitHint,
						DWORD dwErrExit ) {
	BOOL fResult = TRUE;

	if( !m_bDebug ) { // when debugging we don't report to the SCM
        if( dwCurrentState == SERVICE_START_PENDING)
            m_ssStatus.dwControlsAccepted = 0;
        else
            m_ssStatus.dwControlsAccepted = m_dwControlsAccepted;

        m_ssStatus.dwCurrentState = dwCurrentState;
        m_ssStatus.dwWin32ExitCode = NO_ERROR;
        m_ssStatus.dwWaitHint = dwWaitHint;

			//!! TCW MOD START - added code to support error exiting
			m_ssStatus.dwServiceSpecificExitCode = dwErrExit;
			if (dwErrExit!=0)
				m_ssStatus.dwWin32ExitCode = ERROR_SERVICE_SPECIFIC_ERROR;
			//!! TCW MOD END - added code to support error exiting

        if( dwCurrentState == SERVICE_RUNNING ||
            dwCurrentState == SERVICE_STOPPED )
            m_ssStatus.dwCheckPoint = 0;
        else
            m_ssStatus.dwCheckPoint = ++m_dwCheckPoint;

        // Report the status of the service to the service control manager.
        if (!(fResult = SetServiceStatus( m_sshStatusHandle, &m_ssStatus))) 
		{
            AddToMessageLog(TEXT("SetServiceStatus() failed"));
        }
    }

    return fResult;
}


void CNTService :: AddToMessageLog(LPTSTR lpszMsg, WORD wEventType, DWORD dwEventID) {
	m_dwErr = GetLastError();

	// use default message-IDs
	if( dwEventID == DWORD(-1) ) {
		switch( wEventType ) {
			case EVENTLOG_ERROR_TYPE:
				dwEventID = MSG_ERROR_1;
				break;
			case EVENTLOG_WARNING_TYPE:
				dwEventID = MSG_WARNING_1;
				break;
			case EVENTLOG_INFORMATION_TYPE:
				dwEventID = MSG_INFO_1;
				break;
			case EVENTLOG_AUDIT_SUCCESS:
				dwEventID = MSG_INFO_1;
				break;
			case EVENTLOG_AUDIT_FAILURE:
				dwEventID = MSG_INFO_1;
				break;
			default:
				dwEventID = MSG_INFO_1;
				break;
		}
	}

	// Use event logging to log the error.
	HANDLE hEventSource = RegisterEventSource(0, m_lpServiceName);

	if( hEventSource != 0 ) {
		LPCTSTR lpszMessage = lpszMsg;

		ReportEvent(
			hEventSource,	// handle of event source
			wEventType,		// event type
			0,				// event category
			dwEventID,		// event ID
			m_pUserSID,		// current user's SID
			1,				// STRINGs in lpszStrings
			0,				// no bytes of raw data
			&lpszMessage,	// array of error STRINGs
			0				// no raw data
		);

		::DeregisterEventSource(hEventSource);
    }
}


LPTSTR CNTService :: GetLastErrorText( LPTSTR lpszBuf, DWORD dwSize ) 
{
	lpszBuf[0] = TEXT('\0');

	DWORD dwRet =	::FormatMessage(
									FORMAT_MESSAGE_FROM_SYSTEM,
									NULL,
									::GetLastError(),
									LANG_NEUTRAL,
									lpszBuf,
									dwSize,
									NULL
									);
	if (dwRet != 0)
	{
		lpszBuf[dwRet - 2] = TEXT('\0');  //remove cr/nl characters
	}

	return lpszBuf;
}

/////////////////////////////////////////////////////////////////////////////
// class CNTService -- implementation

void CNTService :: RegisterApplicationLog( LPCTSTR lpszFileName, DWORD dwTypes ) 
{
	TCHAR szKey[512];

	_tcscpy_s(szKey, sizeof(szKey)/sizeof(szKey[0]), gszAppRegKey);
	_tcscat_s(szKey, sizeof(szKey)/sizeof(szKey[0]), m_lpServiceName);

	HKEY hKey = 0;
	LONG lRet = ERROR_SUCCESS;
	
	// Create a key for that application and insert values for
	// "EventMessageFile" and "TypesSupported"
	if( ::RegCreateKey(HKEY_LOCAL_MACHINE, szKey, &hKey) == ERROR_SUCCESS ) {
		lRet =	::RegSetValueEx(
					hKey,						// handle of key to set value for
					TEXT("EventMessageFile"),	// address of value to set
					0,							// reserved
					REG_EXPAND_SZ,				// flag for value type
					(CONST BYTE*)lpszFileName,	// address of value data
					(DWORD)(_tcslen(lpszFileName) + 1)	// size of value data
				);

		// Set the supported types flags.
		lRet =	::RegSetValueEx(
					hKey,					// handle of key to set value for
					TEXT("TypesSupported"),	// address of value to set
					0,						// reserved
					REG_DWORD,				// flag for value type
					(CONST BYTE*)&dwTypes,	// address of value data
					sizeof(DWORD)			// size of value data
				);
		::RegCloseKey(hKey);
	}

	// Add the service to the "Sources" value

	lRet =	::RegOpenKeyEx( 
				HKEY_LOCAL_MACHINE,	// handle of open key 
				gszAppRegKey,		// address of name of subkey to open 
				0,					// reserved 
				KEY_ALL_ACCESS,		// security access mask 
				&hKey				// address of handle of open key 
			);
	if( lRet == ERROR_SUCCESS ) {
		DWORD dwSize;

		// retrieve the size of the needed value
		lRet =	::RegQueryValueEx(
					hKey,			// handle of key to query 
					TEXT("Sources"),// address of name of value to query 
					0,				// reserved 
					0,				// address of buffer for value type 
					0,				// address of data buffer 
					&dwSize			// address of data buffer size 
				);

 		if( lRet == ERROR_SUCCESS ) 
		{
			DWORD  dwType;
			DWORD  dwNewSize = dwSize+ ((DWORD)_tcslen(m_lpServiceName) + 1) * sizeof(TCHAR);
			LPBYTE Buffer    = LPBYTE(::GlobalAlloc(GPTR, dwNewSize));

			lRet =	::RegQueryValueEx(
						hKey,			// handle of key to query 
						TEXT("Sources"),// address of name of value to query 
						0,				// reserved 
						&dwType,		// address of buffer for value type 
						Buffer,			// address of data buffer 
						&dwSize			// address of data buffer size 
					);
			if( lRet == ERROR_SUCCESS ) 
			{
				_ASSERTE(dwType == REG_MULTI_SZ);

				// check whether this service is already a known source
				register LPTSTR p;				
				for(p = LPTSTR(Buffer); *p; p += _tcslen(p)+1 ) 
				{
					if( _tcsicmp(p, m_lpServiceName) == 0 )
						break;
				}

				if (! *p) 
				{
					// We're standing at the end of the STRINGarray
					// and the service does still not exist in the "Sources".
					// Now insert it at this point.
					// Note that we have already enough memory allocated
					// (see GlobalAlloc() above). We also don't need to append
					// an additional '\0'. This is done in GlobalAlloc() above
					// too.
					#pragma  warning(push)
					#pragma  warning(disable: 4996)
						_tcscpy(p, m_lpServiceName);
					#pragma  warning(pop)

					// OK - now store the modified value back into the
					// registry.
					lRet =	::RegSetValueEx(
								hKey,			// handle of key to set value for
								TEXT("Sources"),// address of value to set
								0,				// reserved
								dwType,			// flag for value type
								Buffer,			// address of value data
								dwNewSize		// size of value data
							);
				}
			}

			::GlobalFree(HGLOBAL(Buffer));
		}

		::RegCloseKey(hKey);
	}
}


void CNTService :: DeregisterApplicationLog() 
{
	TCHAR szKey[512];

	_tcscpy_s(szKey, sizeof(szKey)/sizeof(szKey[0]), gszAppRegKey);
	_tcscat_s(szKey, sizeof(szKey)/sizeof(szKey[0]), m_lpServiceName);

	HKEY hKey = 0;
	LONG lRet = ERROR_SUCCESS;

	lRet = ::RegDeleteKey(HKEY_LOCAL_MACHINE, szKey);

	// now we have to delete the application from the "Sources" value too.
	lRet =	::RegOpenKeyEx( 
				HKEY_LOCAL_MACHINE,	// handle of open key 
				gszAppRegKey,		// address of name of subkey to open 
				0,					// reserved 
				KEY_ALL_ACCESS,		// security access mask 
				&hKey				// address of handle of open key 
			);
	if( lRet == ERROR_SUCCESS ) {
		DWORD dwSize;

		// retrieve the size of the needed value
		lRet =	::RegQueryValueEx(
					hKey,			// handle of key to query 
					TEXT("Sources"),// address of name of value to query 
					0,				// reserved 
					0,				// address of buffer for value type 
					0,				// address of data buffer 
					&dwSize			// address of data buffer size 
				);

 		if( lRet == ERROR_SUCCESS ) 
		{
			DWORD dwType;
			LPBYTE Buffer    = LPBYTE(::GlobalAlloc(GPTR, dwSize));
			LPBYTE NewBuffer = LPBYTE(::GlobalAlloc(GPTR, dwSize));

			lRet =	::RegQueryValueEx(
						hKey,			// handle of key to query 
						TEXT("Sources"),// address of name of value to query 
						0,				// reserved 
						&dwType,		// address of buffer for value type 
						Buffer,			// address of data buffer 
						&dwSize			// address of data buffer size 
					);
			if( lRet == ERROR_SUCCESS ) 
			{
				_ASSERTE(dwType == REG_MULTI_SZ);

				// check whether this service is already a known source
				register LPTSTR p    = LPTSTR(Buffer);
				register LPTSTR pNew = LPTSTR(NewBuffer);
				BOOL bNeedSave = FALSE;	// assume the value is already correct
				for(; *p; p += _tcslen(p)+1) 
				{
					// except ourself: copy the source STRING into the destination
					if( _tcsicmp(p, m_lpServiceName) != 0 ) 
					{
						#pragma  warning(push)
						#pragma  warning(disable: 4996)
							_tcscpy(pNew, p);
						#pragma  warning(pop)

						pNew += _tcslen(pNew)+1;
					} 
					else 
					{
						bNeedSave = TRUE;		                            // *this* application found
						dwSize -= (DWORD)(_tcslen(p) + 1) * sizeof(TCHAR);	// new size of value
					}
				}

				if( bNeedSave )
				{
					// OK - now store the modified value back into the
					// registry.
					lRet =	::RegSetValueEx(
								hKey,			// handle of key to set value for
								TEXT("Sources"),// address of value to set
								0,				// reserved
								dwType,			// flag for value type
								NewBuffer,		// address of value data
								dwSize			// size of value data
							);
				}
			}

			::GlobalFree(HGLOBAL(NewBuffer));
			::GlobalFree(HGLOBAL(Buffer));			
		}

		::RegCloseKey(hKey);
	}
}

////////////////////////////////////////////////////////

//!! TCW MOD - function to create console for faceless apps if not already there
void CNTService::SetupConsole() 
{
	if( !m_fConsoleReady ) 
	{
		AllocConsole();	// you only get 1 console.

		// lovely hack to get the standard io (printf, getc, etc) to the new console. Pretty much does what the
		// C lib does for us, but when we want it, and inside of a Window'd app.
		// The ugly look of this is due to the error checking (bad return values. Remove the if xxx checks if you like it that way.
		DWORD astds[3] = {STD_OUTPUT_HANDLE,STD_ERROR_HANDLE,STD_INPUT_HANDLE};
		FILE *atrgs[3] = {stdout,stderr,stdin};

		for( register int i=0; i<3; i++ ) 
		{
			HANDLE hand = GetStdHandle(astds[i]);
			if( hand != INVALID_HANDLE_VALUE ) 
			{
				int osf=_open_osfhandle((intptr_t)hand,_O_TEXT);
				if ( osf != -1 ) 
				{
					FILE *fp = _fdopen(osf, (astds[i] == STD_INPUT_HANDLE) ? "r" : "w");
					if( fp != NULL ) 
					{
						*(atrgs[i])=*fp;
						setvbuf(fp,NULL,_IONBF,0);
					}
				}
			}
		}
		m_fConsoleReady=TRUE;
	}
}
