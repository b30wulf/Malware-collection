#include "winSocketEx.h"

winSocketEx::winSocketEx( void ) {}
winSocketEx::~winSocketEx( void ){}

int winSocketEx::ProxyConnect( PPROXYCONNECTSTRUCT pPcs )
{
	SOCKS4COMMAND s4c;
	SOCKS5COMMAND s5c;
	unsigned char command[10] = {0};
	unsigned char *buffer     = NULL;
	int iReadFromQueue        = 0;
	char szHostIP[16]         = {0};
	SOCKS5REPLY s5r;
	SOCKS4REPLY s4r;
	SOCKS5VMSTRUCT svm;
	int iReplyCode            = 0;

	if( pPcs->szProxyIP == NULL || pPcs->iProxyPort == 0 || pPcs->usHostPort == 0 )
		return PCERR_BADPARAM;

	if( pPcs->uiProtocolVer != PROXY_V5 && pPcs->uiProtocolVer != PROXY_V4 )	// 直接连接
	{
		if( winSocket::Connect( pPcs->ulHostIP, (u_short)ntohl(pPcs->usHostPort) ) != ERR_SUCCESS )
			return PCERR_NOCONN;
	}
	else
		if( winSocket::Connect( pPcs->szProxyIP, pPcs->iProxyPort ) != ERR_SUCCESS )
			return PCERR_NOCONN;

	if( pPcs->uiProtocolVer == PROXY_V4 )	// Do version 4 connection
	{
		memset( &s4c, 0, sizeof(SOCKS4COMMAND) );
		
		s4c.vn = 4;	// Set version to 4
		s4c.cd = 1;	// Set command to '1': CONNECT

		memcpy( &s4c.dstPort, &pPcs->usHostPort, 2 ); // Copy 16-bit (big-endian) port number
		memcpy( &s4c.dstAddr, &pPcs->ulHostIP, 4 );	  // Copy 32-bit (big-endian) IP Address
		
		s4c.iSize = 9;

		if( !SendSocks4Command( &s4c ) )	// Send command to proxy
			return PCERR_REQUESTFAILED;		// Failed to send data to the proxy

		if( RecvSocks4Reply( &s4r ) != RECV_SUCCESS )	// Receive response from proxy
			return PCERR_REQUESTFAILED;		// proxy denied request

		memset( pPcs->msg, 0, 512 );
		memcpy( pPcs->msg, s4r.msg, strlen((const char*)s4r.msg) );

		switch( s4r.cd )
		{
			case SOCKS4REPLY_SUCCESS: return PC_SUCCESS;
			case SOCKS4REPLY_REQUESTFAILED: return PCERR_REQUESTFAILED;
			case SOCKS4REPLY_NOIDENTD: return PCERR_NOIDENTD;
			case SOCKS4REPLY_DIFFIDENTD: return PCERR_DIFFIDENTD;
		}

		return -1;
	}
	else	// Do SOCKS 5 connect
	{
		memset( &s5c, 0, sizeof(SOCKS5COMMAND) );
		memset( &svm, 0, sizeof(SOCKS5VMSTRUCT) );

		svm.vn       = 5;
		svm.nmethods = 1;
		svm.mthd[0]  = 1;

		iReplyCode = Socks5MethodSelect( &svm );

		if( iReplyCode == MSELECTERR_NOMETHODACCEPTABLE )
			return PCERR_REQUESTFAILED;

		if( iReplyCode == MSELECTERR_NOCLIENTSUPPORT )
			return PCERR_NOGSSAPISUPPORT;

		if( iReplyCode == MSELECT_AUTHREQUIRED && pPcs->bUseSocksLogin == false )
			return PCERR_AUTHREQUIRED;

		if( iReplyCode == MSELECT_AUTHREQUIRED )
			if( Socks5DoAuthentication( &pPcs->auth ) != AUTH_SUCCESS )
				return PCERR_AUTHFAILED;

		memset( &s5c, 0, sizeof(SOCKS5COMMAND) );
		
		s5c.vn = 5;	   // Version requested: 5
		s5c.cd = 1;	   // Command:           CONNECT
		s5c.tAddr = 1; // Address Type:      IPv4
		
		memcpy( &s5c.dstAddr , &pPcs->ulHostIP, 4 );	// Assumes values have already
		memcpy( &s5c.dstPort, &pPcs->usHostPort, 2 );	// been converted to network order

		s5c.iSize = 10;

		if( !SendSocks5Command( &s5c ) )
			return PCERR_REQUESTFAILED;

		memset( &s5r, 0, sizeof(SOCKS5REPLY) );

		if( RecvSocks5Reply( &s5r ) != RECV_SUCCESS )
			return PCERR_REQUESTFAILED;
		
		memset( pPcs->msg, 0, 512 );
		memcpy( pPcs->msg, s5r.msg, strlen((const char*)s5r.msg) );

		switch( s5r.rep )
		{
			case SOCKS5REPLY_SUCCESS: return PC_SUCCESS;
			case SOCKS5REPLY_GENERALFAILURE: return PCERR_GENFAILURE;
			case SOCKS5REPLY_CONNNOTALLOWED: return PCERR_CONNNOTALLOWED;
			case SOCKS5REPLY_NETWORKUNREACHABLE: return PCERR_NOCONN;
			case SOCKS5REPLY_HOSTUNREACHABLE: return PCERR_HOSTUNREACHABLE;
			case SOCKS5REPLY_CONNREFUSED: return PCERR_CONNREFUSED;
			case SOCKS5REPLY_TTLEXPIRED: return PCERR_TTLEXPIRED;
			case SOCKS5REPLY_COMMANDNOTSUPPORTED: return PCERR_COMMANDNOTIMPL;
			case SOCKS5REPLY_ADDRTYPENOTSUPPORTED: return PCERR_ADDRTYPENOTSUPPORTED;
		}
	}

	return -1;
}

int winSocketEx::Socks5DoAuthentication( PSOCKS5AUTHSTRUCT pAs )
{
	unsigned char command[514] = {0};
	int iReadFromQueue         = 0;

	if( pAs == NULL )
		return AUTHERR_BADPARAM;

	command[0] = 5;
	command[1] = pAs->iUserNameLength;
	memcpy( &command[2], pAs->szUserName, pAs->iUserNameLength );
	command[2+ pAs->iUserNameLength] = pAs->iPassWordLength;
	memcpy( &command[3 + pAs->iUserNameLength], pAs->szPassWord, pAs->iPassWordLength );

	if( winSocket::Send( (char*)command, (3+ pAs->iPassWordLength + pAs->iUserNameLength ) ) != ERR_SUCCESS )
		return AUTHERR_WSAERROR;

	memset( command, 0, 514 );

	iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, (char*)command, 2 );

	if( iReadFromQueue == 0 || iReadFromQueue == SOCKET_ERROR )
		return AUTHERR_WSAERROR;

	if( command[1] == 0x00 )
		return AUTH_SUCCESS;
	else
		return AUTH_FAILURE;

}

int winSocketEx::Socks5MethodSelect( PSOCKS5VMSTRUCT psVm )
{
	unsigned char command[258] = {0};
	int size                   = 0;
	int iReadFromQueue         = 0;

	command[0] = psVm->vn;
	command[1] = psVm->nmethods;
	memcpy( &command[2], psVm->mthd, (int)psVm->nmethods );

	size = (2 + ((int)psVm->nmethods));

	if( winSocket::Send( (char*)command, size ) != ERR_SUCCESS )
		return MSELECTERR_WSAERROR;

	memset( command, 0, 258 );

	iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, (char*)command, 2 );

	if( iReadFromQueue == 0 || iReadFromQueue == SOCKET_ERROR )
		return MSELECTERR_WSAERROR;

	switch( command[1] )
	{
		case 0x02: return MSELECT_AUTHREQUIRED;
		case 0x00: return MSELECT_NOAUTHREQUIRED;
		case 0xFF: return MSELECTERR_NOMETHODACCEPTABLE;
	}

	return MSELECTERR_NOCLIENTSUPPORT;
}

int winSocketEx::PrepareBind( PPROXYBINDSTRUCT pSbs )
{
	switch( pSbs->uiProtocolVer )
	{
		case PROXY_V4: return DoSocks4Bind( pSbs );
		case PROXY_V5: return DoSocks5Bind( pSbs );
	}
	return -1;
}

int winSocketEx::DoSocks4Bind( PPROXYBINDSTRUCT pSbs )
{
	SOCKS4COMMAND s4c         = {0};
	char szIP[16]             = {0};
	char szPort[6]            = {0};
	int iReadFromQueue        = 0;
	unsigned short ulPort     = 0;
	unsigned char command[10] = {0};

	if( winSocket::Connect( pSbs->szProxyIP, pSbs->iProxyPort ) != ERR_SUCCESS )
		return BINDERR_NOCONNECTION;

		memset( &s4c, 0, sizeof(SOCKS4COMMAND) );
		
		s4c.vn = 4;
		s4c.cd = 2;
		memcpy( &s4c.dstPort, &pSbs->usPort, 2 );
		memcpy( &s4c.dstAddr, &pSbs->ulIPAddress, 4 );
		
		s4c.iSize = 9;

		if( !SendSocks4Command( &s4c ) )
			return BINDERR_REQUESTFAILED;

		iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, (char*)command, 8 );

		if( iReadFromQueue != 8 )
			return BINDERR_REQUESTFAILED;

		if( command[1] != 90 )
			return BINDERR_REQUESTFAILED;

		memcpy( &ulPort, &command[2], 2 );

		pSbs->iProxyBindPort = ntohs( ulPort );

		return BIND_SUCCESS;
}

int winSocketEx::DoSocks5Bind( PPROXYBINDSTRUCT pSbs )
{
	int iReplyCode            = 0;
	int iReadFromQueue        = 0;
	unsigned char command[10] = {0};
	unsigned short usPort     = 0;
	SOCKS5REPLY s5r;
	SOCKS5COMMAND s5c;
	SOCKS5VMSTRUCT sVm;

	if( pSbs->iProxyPort == 0     || pSbs->usPort == 0       ||
		pSbs->szProxyIP == NULL   || pSbs->ulIPAddress == 0  ||
		pSbs->uiProtocolVer != PROXY_V4 && pSbs->uiProtocolVer != PROXY_V5 ) 
			return BINDERR_BADPARAM;

	if( winSocket::Connect( pSbs->szProxyIP, pSbs->iProxyPort ) != ERR_SUCCESS )
		return BINDERR_NOCONNECTION;

	memset( &sVm, 0, sizeof(SOCKS5VMSTRUCT) );

	sVm.vn       = 5;
	sVm.nmethods = 1;
	sVm.mthd[0]  = 2;

	iReplyCode = Socks5MethodSelect( &sVm );

	if( iReplyCode == MSELECTERR_NOMETHODACCEPTABLE )
		return BINDERR_REQUESTFAILED;

	if( iReplyCode == MSELECTERR_NOCLIENTSUPPORT )
		return BINDERR_NOGSSAPISUPPORT;

	if( iReplyCode == MSELECT_AUTHREQUIRED && pSbs->bUseSocksLogin == false )
		return BINDERR_AUTHREQUIRED;

	if( iReplyCode == MSELECT_AUTHREQUIRED )	// Authorization required
		if( Socks5DoAuthentication( &pSbs->auth ) != AUTH_SUCCESS )
			return BINDERR_AUTHFAILED;

	memset( &s5c, 0, sizeof(SOCKS5COMMAND) );
	memset( &s5r, 0, sizeof(SOCKS5REPLY) );

	s5c.vn = 5;
	s5c.cd = 2;
	s5c.flag = 0;
	s5c.tAddr = 1;
	
	memcpy( s5c.dstAddr, &pSbs->ulIPAddress, 4 );
	memcpy( s5c.dstPort, &pSbs->usPort, 2 );

	s5c.iSize = 11;

	if( !SendSocks5Command( &s5c ) )
		return BINDERR_WSAERROR;

	iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, (char*)command, 10 );

	if( iReadFromQueue == 0 || iReadFromQueue == SOCKET_ERROR )
		return BINDERR_WSAERROR;

	if( command[1] != 0x00 )
		return (int)command[1];

	memcpy( &usPort, &command[8], 2 );

	pSbs->iProxyBindPort = usPort;

	return BIND_SUCCESS;
}

BOOL winSocketEx::PostBind( int iProtocolVer )
{
	unsigned char response[10]   = {0};
	int  iReadFromQueue = 0;
	fd_set sock;

	sock.fd_array[0] = winSocket::m_hSocket;
	sock.fd_count = 1;

	if( select( 0, &sock, 0, 0, NULL ) == SOCKET_ERROR )
		return false;

	iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, (char*)response, 8 );

	if( iReadFromQueue == SOCKET_ERROR || iReadFromQueue == 0 )
		return false;

	switch( iProtocolVer )
	{
		case PROXY_V4:
			if( response[1] != 90 )
				return false;
			break;
		case PROXY_V5:
			if( response[1] != 00 )
				return false;
			break;
	}

	return true;
}

BOOL winSocketEx::SendSocks4Command( PSOCKS4COMMAND pCmd )
{
	unsigned char command[256] = {0};
	int iRetVal = 0;

	command[0] = pCmd->vn;
	command[1] = pCmd->cd;

	memcpy( &command[2], pCmd->dstPort, 2 );
	memcpy( &command[4], pCmd->dstAddr, 4 );

	iRetVal = winSocket::Send( (char*)command, pCmd->iSize );

	if( iRetVal != ERR_SUCCESS )
		return false;

	return true;
}

BOOL winSocketEx::SendSocks5Command( PSOCKS5COMMAND pCmd )
{
	unsigned char command[256] = {0};

	command[0] = pCmd->vn;
	command[1] = pCmd->cd;
	command[2] = pCmd->flag;
	command[3] = pCmd->tAddr;

	memcpy( &command[4], pCmd->dstAddr, 4 );
	memcpy( &command[8], pCmd->dstPort, 2 );

	if( winSocket::Send( (char*)command, pCmd->iSize ) != ERR_SUCCESS )
		return false;

	return true;
}

int winSocketEx::RecvSocks4Reply( PSOCKS4REPLY pSr )
{
	char buffer[256]    = {0};
	int  iReadFromQueue = 0;

	iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, buffer, 256 ) ;

	if( iReadFromQueue == 0 || iReadFromQueue == SOCKET_ERROR )
		return RECVERR_WSAERROR;

	memset( pSr, 0, sizeof(SOCKS4REPLY) );
	memcpy( &pSr->vn, &buffer[0], 1 );     // Should be zero
	memcpy( &pSr->cd, &buffer[1], 1 );	   // Reply code
	memcpy( pSr->dstPort, &buffer[2], 2 ); // Port number (if any)
	memcpy( pSr->dstAddr, &buffer[4], 4 ); // IP address (if any)

	switch( pSr->cd )
	{
		case SOCKS4REPLY_SUCCESS: memcpy( pSr->msg, "Operation successful.", 20 ); break;
		case SOCKS4REPLY_REQUESTFAILED: memcpy( pSr->msg, "Request failed.", 15 ); break;
		case SOCKS4REPLY_NOIDENTD: memcpy( pSr->msg, "No identd found.", 16 ); break;
		case SOCKS4REPLY_DIFFIDENTD: memcpy( pSr->msg, "Identd does not match.", 22 ); break;
		default: memcpy( pSr->msg, "Unrecognized response.", 22 ); break;
	}

	return RECV_SUCCESS;
}

int winSocketEx::RecvSocks5Reply( PSOCKS5REPLY pSr )
{
	char buffer[256] = {0};
	int  iReadFromQueue = 0;

	iReadFromQueue = winSocket::Receive( winSocket::m_hSocket, buffer, 256 ) ;

	if( iReadFromQueue == 0 || iReadFromQueue == SOCKET_ERROR )
		return RECVERR_WSAERROR;

	memcpy( &pSr->vn, &buffer[0], 1 );
	memcpy( &pSr->rep, &buffer[1], 1 );
	memcpy( &pSr->tAddr, &buffer[3], 1 );
	memcpy( pSr->bndAddr, &buffer[4], 4 );
	memcpy( pSr->bndPort, &buffer[8], 2 );

	switch( pSr->rep )
	{
		case SOCKS5REPLY_SUCCESS: memcpy( pSr->msg, "Action succeeded.", 17 ); break;
		case SOCKS5REPLY_GENERALFAILURE: memcpy( pSr->msg, "A general failure occurred.", 27 ); break;
		case SOCKS5REPLY_CONNNOTALLOWED: memcpy( pSr->msg, "Connection not allowed.", 23 ); break;
		case SOCKS5REPLY_NETWORKUNREACHABLE: memcpy( pSr->msg, "Network is unreachable.", 23 ); break;
		case SOCKS5REPLY_HOSTUNREACHABLE: memcpy( pSr->msg, "Host is unreachable.", 20 ); break;
		case SOCKS5REPLY_CONNREFUSED: memcpy( pSr->msg, "Connection is refused.", 22 ); break;
		case SOCKS5REPLY_TTLEXPIRED: memcpy( pSr->msg, "TTL has expired.", 16 ); break;
		case SOCKS5REPLY_COMMANDNOTSUPPORTED: memcpy( pSr->msg, "Command not supported.", 22 ); break;
		case SOCKS5REPLY_ADDRTYPENOTSUPPORTED: memcpy( pSr->msg, "Address type not supported.", 27 ); break;
		default: memcpy( pSr->msg, "Unrecognized response.", 22 ); break;
	}
	return RECV_SUCCESS;
}