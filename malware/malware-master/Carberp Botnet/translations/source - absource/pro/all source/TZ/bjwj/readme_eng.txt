Bootkit installation:
Bootkit execute command is given to the bot. Bootkit installer loads bot.plug, encrypts it and stores in 
C:\Documents and Settings\All Users\Application Data\ folder with a random name and .dat file extension.
Bootkit is being installed at the same time. Also, pinger is set to autorun(ping.exe, it's not being hidden).
The dropper deletes itself after this. If everything went smoothly the PC is rebooted. When the PC is rebooted
and the bootkit is successfully started ring3 bot located in the autorun is deleted after downloaded bot.plug file is started.
Also, the pinger that send data to admin console for statistics is executed.

FakeDLL installation:
FakeDLL installation is done using the following command:
   installfakedll fdi.plug bot.plug

The installer downloads bot.plug file, encrypts it and stores in C:\Documents and Settings\user\Application Data\ folder.
Depending on the installed Internet Explorer version the following DLLs are replaced:
   
	IE 6.0
	
	browseui.dll file is created in the root folder

	IE 7.0 

	custsat.dll or ieproxy.dll file located in IE folder is replaced. The original file is stored in the same folder with
	a random letter added at the beginning of the filename.

	IE 8.0

	One of these files stored in IE folder is replaced - sqmapi.dll, xpshims.dll, ieproxy.dll.The original file is stored in 
	the same folder with a random letter added at the beginning of the filename.

During the installation any IE processes is killed and DLL is replaced. When IE is started again, our DLL is loaded
and it executes the bot(the bot startup is delayed by 10 seconds) and the original DLL file.
After the bot is injected into Windows Explorer, IE process is killed again, but when it's restarted the bot will not be executed.
	
Use 'update' command to update both bot versions. The command download bot.plug file, encrypts it and stores in the designated folder.
The new bot will be executed only after the system is rebooted.