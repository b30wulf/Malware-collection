@echo off

del C:\ods.log
sc stop medialoader
sc delete medialoader

del %ALLUSERSPROFILE%\Medialoader\ser.dat
del %ALLUSERSPROFILE%\Medialoader\shell.dll
del %ALLUSERSPROFILE%\Medialoader\data.dat
