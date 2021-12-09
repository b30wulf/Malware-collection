KBOT - NT-kernel bot program
----------------------------

This program can download files over HTTP, save them on VFS partition and register them in KLDR module for further injection into predefined processes.

Supported OS: XP - WIN7.
Supported architectures: x86, AMD64(EM64T).

The project is compiled into a static library(LIB) and linked to the driver.

When the program is first executed it generates 16-bit user ID and stores it on VFS partition in \USER.ID file.
This ID never changes and used every time when communicating with C&C server.

The program performs 2 types of HTTP requests to C&C server:
- configuration file request;
- command file request;

The program support HTTP requests obfuscation.
Every request is encrypted with RC6 algorithm and translated into BASE64 format.

The program supports digital signature checks and encryption configuration and command files.
To support that, public.key file is attached to the driver - it holds open RSA-key.
This key is used when decrypting and checking the digital signature of the received file.
If the file is not validated it's ignored.

Configuration file

The program works based on the parameters specified in the configuration file (config-file).
config-file can be stored on VFS partition or can be attached to the driver directly.
When the program is started, it looks for the file on VFS partition first and if not found it uses attached config-file.
Sample of the config-file with description can be found in \BkBuild\kbot.ini
When a new config-file is received, it's stores on VFS partition with \KBOT.INI filename. The existing KBOT.INI is replaced.

Command file

The text file contains one or many of the following commands:

	LOAD_FILE <HTTP URL> [filename on VFS]	- download the file from specified URL and saves it on VFS partition
	
	DELETE_FILE	<filename on VFS>			- deletes specified filename from VFS partition
	
	SET_INJECT <filename on VFS> <processes list> - setup injects of the specified file(in most cases DLL) into 
	   the processes from the list. All setup injects are applied immediately and stored in \INJECTS.SYS file
	   on VFS partition, so that they will still be active after the system restart.
	   To remove any injects use the same command SET_INJECT <filename on VFS>, but do not specify the 'processes list' parameter.

Command names are case sensitive, parameters are not.