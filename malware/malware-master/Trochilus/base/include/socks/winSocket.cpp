#include "winSocket.h"

winSocket::winSocket() : m_hSocket(0),
                         m_wVersion(0),
                         m_ErrorNumber(0)		
{
	WSADATA wsaD;	// Structure initialsed by WSAStartup
	m_wVersion = MAKEWORD(2,2);	// Version number requested

	memset( m_LastError, 0, ERR_MAXLENGTH );	// Nullify last error
	memset( &m_sockaddr, 0, sizeof( m_sockaddr ) );	// Nullify structures
	memset( &m_rsockaddr, 0, sizeof( m_rsockaddr ) );

	int result = WSAStartup( m_wVersion, &wsaD );	// Initialize Winsock
	
	if( result != 0 ) // WSAStartup failed
	{
        set_LastError( "WSAStartup failed!", WSAGetLastError() );
        return;
	}
}

winSocket::~winSocket() { WSACleanup();	/* Un-initialise Winsock*/ }

int winSocket::Create( void )
{
	if ( (m_hSocket = socket( AF_INET, SOCK_STREAM, IPPROTO_TCP )) == INVALID_SOCKET )
	{
        set_LastError( "socket() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}

	return ERR_SUCCESS;	
}

int winSocket::Close( void )
{
	if ( closesocket( m_hSocket ) == SOCKET_ERROR )
	{
        set_LastError( "closesocket() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}

	memset( &m_sockaddr, 0, sizeof( sockaddr_in ) );
	memset( &m_rsockaddr, 0, sizeof( sockaddr_in ) );

	return ERR_SUCCESS;
}

long winSocket::DNS(char *strRemote)
{
	hostent *hostEnt = NULL;
	hostEnt = gethostbyname( strRemote );//根据域名获取地址

	if( hostEnt != NULL )
		return ((in_addr*)hostEnt->h_addr)->s_addr;
	return 0;
}
int winSocket::Connect( long lIPAddress, unsigned int iPort )
{
	if(!lIPAddress) return ERR_WSAERROR;

	m_sockaddr.sin_addr.s_addr = lIPAddress;
	m_sockaddr.sin_family = AF_INET;
	m_sockaddr.sin_port = htons( iPort );

	if( connect( m_hSocket, (SOCKADDR*)&m_sockaddr, sizeof( m_sockaddr ) ) == SOCKET_ERROR )
	{
        set_LastError( "connect() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}

	return ERR_SUCCESS;
}

int winSocket::Listen( int iQueuedConnections )
{
	if( iQueuedConnections == 0 )
		return ERR_BADPARAM;

	if( listen( m_hSocket, iQueuedConnections ) == SOCKET_ERROR )
	{
        set_LastError( "listen() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}

	return ERR_SUCCESS;
}


int winSocket::Send( char* strData, int iLen )
{
	if( strData == NULL || iLen == 0 )
		return ERR_BADPARAM;

	if( send( m_hSocket, strData, iLen, 0 ) == SOCKET_ERROR )
	{
        set_LastError( "send() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}
	
	return ERR_SUCCESS;
}


int winSocket::Receive( char* strData, int iLen )
{
	if( strData == NULL )
		return ERR_BADPARAM;

	int len = 0;
	int ret = 0;
	
	ret = recv( m_hSocket, strData, iLen, 0 );

	if ( ret == SOCKET_ERROR )
	{
        set_LastError( "recv() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}

	return ret;
}

int winSocket::Bind( char* strIP, unsigned int iPort )
{
	if( strlen( strIP ) == 0 || iPort == 0 )
		return ERR_BADPARAM;

	memset( &m_sockaddr,0, sizeof( m_sockaddr ) );
	m_sockaddr.sin_family = AF_INET;
	m_sockaddr.sin_addr.s_addr = inet_addr( strIP );
	m_sockaddr.sin_port = htons( iPort );

	if ( bind( m_hSocket, (SOCKADDR*)&m_sockaddr, sizeof( m_sockaddr ) ) == SOCKET_ERROR )
	{
        set_LastError( "bind() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}
	return ERR_SUCCESS;
}

int winSocket::Accept( SOCKET s )
{	
	int Len = sizeof( m_rsockaddr );

	memset( &m_rsockaddr, 0, sizeof( m_rsockaddr ) );

	if( ( m_hSocket = accept( s, (SOCKADDR*)&m_rsockaddr, &Len ) ) == INVALID_SOCKET )
	{
        set_LastError( "accept() failed", WSAGetLastError() );
        return ERR_WSAERROR;
	}

	return ERR_SUCCESS;
}


int winSocket::set_SendTimeout( int ms )
{
	if( ms < 0 )
		return ERR_BADPARAM;

	if( setsockopt( m_hSocket, SOL_SOCKET, SO_SNDTIMEO, (char*)&ms, sizeof( ms ) ) == SOCKET_ERROR )
	{
		set_LastError( "setsockopt() failed.", WSAGetLastError() );
		return ERR_WSAERROR;
	}

	return ERR_SUCCESS;
}

int winSocket::set_RecvTimeout( int ms )
{
	if( ms < 0 )
		return ERR_BADPARAM;

	if( setsockopt( m_hSocket, SOL_SOCKET, SO_RCVTIMEO, (char*)&ms, sizeof( ms ) ) == SOCKET_ERROR )
	{
		set_LastError( "setsockopt() failed.", WSAGetLastError() );
		return ERR_WSAERROR;
	}

	return ERR_SUCCESS;
}

void winSocket::set_LastError( char* newError, int errNum )
{
	memset( m_LastError, 0, ERR_MAXLENGTH ); 
	memcpy( m_LastError, newError, strlen( newError ) );
	m_LastError[strlen(newError)+1] = '\0';
	m_ErrorNumber = errNum;
}

void winSocket::get_LastError( char* strBuffer, int* iErrNum )
{
	int len = strlen( m_LastError );

	if( len > 0 )
	{
        memset( strBuffer, 0, len );
        memcpy( strBuffer, m_LastError, len );
        strBuffer[len+1] = '\0';
        *iErrNum = m_ErrorNumber;
	}
}
