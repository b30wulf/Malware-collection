#ifndef _WINSOCKETEX_H
#define _WINSOCKETEX_H

#include "winSocket.h"

#define PROXY_V5 0				// Protocol versions supported //
#define PROXY_V4 1

typedef struct tagSOCKS5AUTHSTRUCT {
	char *szUserName;			// Null terminated user name
	char *szPassWord;			// Null terminated password
	int  iUserNameLength;		// Length of user name, in bytes
	int  iPassWordLength;		// Length of password in bytes
} SOCKS5AUTHSTRUCT, *PSOCKS5AUTHSTRUCT;

typedef struct tagPROXYCONNECTSTRUCT {
	unsigned int uiProtocolVer;	// Version to use (4 or 5)
	unsigned int iProxyPort;	// Port to connect on proxy machine
	char *szProxyIP;			// Dotted quad IP address of proxy machine
	unsigned long ulHostIP;		// Remote host address (network order)
	unsigned short usHostPort;	// Remote host port (network order)
	bool bUseSocksLogin;		// Boolean switch for login/pass or not
	SOCKS5AUTHSTRUCT auth;      // User name, password, and associated lengths
	char msg[512];              // Any string data that is passed back
} PROXYCONNECTSTRUCT, *PPROXYCONNECTSTRUCT;

typedef struct tagPROXYBINDSTRUCT {
	unsigned int uiProtocolVer; // Version to use (4 or 5)
	unsigned long ulIPAddress;  // IP address
	unsigned short usPort;      // Port
	unsigned int iProxyPort;    // Port number to connect on proxy machine	
	unsigned int iProxyBindPort;// Port number returned by proxy
	bool bUseSocksLogin;		// Boolean switch for login/pass or not
	SOCKS5AUTHSTRUCT auth;      // User name, password, and associated lengths
	char *szProxyIP;            // Dotted quad IP address of proxy machine
} PROXYBINDSTRUCT, *PPROXYBINDSTRUCT;

typedef struct tagSOCKS4COMMAND {
	unsigned char vn;		  // Version
	unsigned char cd;		  // Command
	unsigned char dstPort[2]; // Port field (network order)
	unsigned char dstAddr[4]; // IP field (network order)
	unsigned char ucUserID[2];// User ID (2 octets)
	unsigned char ucNull;	  // Must be set to zero
	int           iSize;      // Size of data being sent to server, in bytes
} SOCKS4COMMAND, *PSOCKS4COMMAND;

typedef struct tagSOCKS5COMMAND {
	unsigned char vn;         // Version
	unsigned char cd;         // Command
	unsigned char flag;		  // Command dependant flag
	unsigned char tAddr;	  // Address type
	unsigned char dstAddr[4]; // IP field (network order)
	unsigned char dstPort[2]; // Port field (network order)
	int           iSize;      // Size of data being sent to server, in bytes
} SOCKS5COMMAND, *PSOCKS5COMMAND;

typedef struct tagSOCKS5VMSTRUCT {
	unsigned char vn;		 // Version
	unsigned char nmethods;  // Number of methods being requested
	unsigned char mthd[255]; // Array of requested method identifiers
} SOCKS5VMSTRUCT, *PSOCKS5VMSTRUCT;

typedef struct tagSOCKS4REPLY {
	unsigned char vn;		  // Version (should be 0)
	unsigned char cd;	      // Response code (see SOCKS4REPLY_* below)
	unsigned char dstPort[2]; // 16-bit network ordered port number
	unsigned char dstAddr[4]; // 32-bit network ordered internet address
	unsigned char msg[512]; // String message
} SOCKS4REPLY, *PSOCKS4REPLY;

typedef struct tagSOCKS5REPLY {
	unsigned char vn;	      // Version number
	unsigned char rep;        // Reply code (see SOCKS5REPLY_* below)
	unsigned char rsv;	      // Reserved
	unsigned char tAddr;	  // Address type
	unsigned char bndAddr[4]; // Internet address
	unsigned char bndPort[2]; // Port number
	unsigned char msg[512];   // String message
} SOCKS5REPLY, *PSOCKS5REPLY;

#define SOCKS4REPLY_SUCCESS       90
#define SOCKS4REPLY_REQUESTFAILED 91
#define SOCKS4REPLY_NOIDENTD      92
#define SOCKS4REPLY_DIFFIDENTD    93

#define SOCKS5REPLY_SUCCESS              0
#define SOCKS5REPLY_GENERALFAILURE       1
#define SOCKS5REPLY_CONNNOTALLOWED       2
#define SOCKS5REPLY_NETWORKUNREACHABLE   3
#define SOCKS5REPLY_HOSTUNREACHABLE      4
#define SOCKS5REPLY_CONNREFUSED          5
#define SOCKS5REPLY_TTLEXPIRED           6
#define SOCKS5REPLY_COMMANDNOTSUPPORTED  7
#define SOCKS5REPLY_ADDRTYPENOTSUPPORTED 8

#define PCERR_NOCONN		       0		// Error return codes for ProxyConnect //
#define PCERR_REQUESTFAILED        1
#define PCERR_AUTHREQUIRED         2
#define PCERR_AUTHTYPEUNKNOWN      3
#define PCERR_AUTHFAILED           4
#define PCERR_AUTHNOLOGON          5
#define PCERR_BADPARAM             6
#define PCERR_GENFAILURE           7
#define PCERR_CONNNOTALLOWED       8
#define PCERR_HOSTUNREACHABLE      9
#define PCERR_COMMANDNOTIMPL       10
#define PCERR_TTLEXPIRED           11
#define PCERR_ADDRTYPENOTSUPPORTED 12
#define PCERR_CONNREFUSED          13
#define PCERR_NOIDENTD             14
#define PCERR_DIFFIDENTD           15
#define PCERR_NOGSSAPISUPPORT      16
#define PC_SUCCESS                 17		// Success code //

#define RECVERR_WSAERROR      0
#define RECV_SUCCESS          1

#define BINDERR_REQUESTFAILED   0
#define BINDERR_NOCONNECTION    1
#define BINDERR_WSAERROR        2
#define BINDERR_BADPARAM        3
#define BINDERR_NOGSSAPISUPPORT 4
#define BINDERR_AUTHREQUIRED    5
#define BINDERR_AUTHFAILED      6
#define BIND_SUCCESS            7

#define MSELECTERR_WSAERROR           0 // Error codes for SOCKS5 method selection //
#define MSELECTERR_NOCLIENTSUPPORT    1 // <--- GSSAPI is not supported, feel free to add it and tell me!
#define MSELECTERR_NOMETHODACCEPTABLE 2
#define MSELECT_AUTHREQUIRED          3
#define MSELECT_NOAUTHREQUIRED        4

#define AUTH_SUCCESS     1
#define AUTH_FAILURE     0
#define AUTHERR_BADPARAM 2
#define AUTHERR_WSAERROR 3

class winSocketEx : public winSocket
{
public:													// Public Methods //
	winSocketEx( void );
	~winSocketEx( void );
	int  ProxyConnect( PPROXYCONNECTSTRUCT pPcs );
	int  PrepareBind( PPROXYBINDSTRUCT pSbs );
	BOOL PostBind( int iProtocolVer  );
private:				// Private Methods //
	BOOL SendSocks4Command( PSOCKS4COMMAND pCmd );
	BOOL SendSocks5Command( PSOCKS5COMMAND pCmd );
	int  RecvSocks4Reply( PSOCKS4REPLY );			
	int  RecvSocks5Reply( PSOCKS5REPLY );
	int  DoSocks4Bind( PPROXYBINDSTRUCT pSbs );			    // Private Members //
	int  DoSocks5Bind( PPROXYBINDSTRUCT pSbs );
	int  Socks5MethodSelect( PSOCKS5VMSTRUCT );
	int  Socks5DoAuthentication( PSOCKS5AUTHSTRUCT pAs );
};

#endif /* _WINSOCKETEX_H */