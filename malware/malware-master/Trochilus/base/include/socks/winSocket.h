#ifndef _WINSOCKET_H
#define _WINSOCKET_H

#include "winsock.h"
#include "winbase.h"

#define ERR_SUCCESS  0x00000000	// Successful
#define ERR_BADPARAM 0x80000001	// Bad argument passed
#define ERR_WSAERROR 0x80000002	// Need to get_LastError()
#define ERR_MAXLENGTH 512

class winSocket	// Definition of winSocket
{
public:	// Public Methods
	winSocket::winSocket();		// Constructor
	winSocket::~winSocket();	// Destructor

	int Create( void );												// Creates the socket
	int Close( void );												// Closes the socket
	int Connect( long lIPAddress, unsigned int iPort );				// Connects the socket to a remote site
	int Send( char* strData, int iLen );	
	int Receive( char* strData, int iLen );
	int Listen( int iQueuedConnections );							// Listen for connections
	int Bind( char* strIP, unsigned int iPort );					// Binds to a port
	int Accept( SOCKET s );											// Accepts a connection

	long DNS(char *strRemote);
	void get_LastError( char* strBuffer, int* iErrNum );			// Returns error information
	int set_SendTimeout( int ms );									// Sets send timeout, in milliseconds
	int set_RecvTimeout( int ms );									// Sets recv timeout, in milliseconds
private:	// Private Methods
	void winSocket::set_LastError( char* newError, int errNum );	// Sets last error information
private:	// Private Members
	struct sockaddr_in m_sockaddr;		// Holds all data associated with socket
	struct sockaddr_in m_rsockaddr;		// Holds data associated with remote side
	WORD m_wVersion;					// Version to use when calling WSAStartup
	char m_LastError[ERR_MAXLENGTH+1];	// Buffer that holds last error
	int	 m_ErrorNumber;					// Last error number
public:		// Public Members
	SOCKET m_hSocket;					// Underlying SOCKET object
};

#endif /* _WINSOCKET_H */