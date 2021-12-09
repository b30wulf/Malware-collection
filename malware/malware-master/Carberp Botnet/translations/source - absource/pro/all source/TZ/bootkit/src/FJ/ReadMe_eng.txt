File Join utility FJ

---------------------------------

Joins one or more executable files (EXE, DLL or SYS) to the file installer or the driver.
File installer (driver) has to support the joined files.

The following items can be used as a file installer:
- KLoader.sys driver, can be used to attach the DLL used in the injection
- BkSetup.exe module, can be used to attach loading driver to it

How does it work

The files being joined are compressed and written at the end of file installer.
The file installer is updated to allow the joined files to be loaded into the memory when the file installer is started.
The joined files are later decompressed by the file installer.

Configuration file

The list of files to join including any parameters is assigned through a special configuration file.
Sample of the configuration file is supplied.