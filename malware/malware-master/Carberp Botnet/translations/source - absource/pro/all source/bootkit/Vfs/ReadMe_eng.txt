VFS administration utility

vfs.exe utility allows
- copy files onto VFS partition;
- read files from VFS partition;
- delete files from VFS partition;
- view root catalogue of VFS partition;
- load NT driver image and execute it;

The utility supports the following commands:
DIR - view the content of a catalogue (example: vfs dir)
COPY - copy a file to of from VFS partition (example: vfs copy c:\windows\calc.exe vfs\calc.exe
													vfs copy vfs\calc.exe c:\test\calc.exe)
DEL - delete a file (example: vfs del vfs\calc.exe)
LOAD - load specified driver (example: vfs load c:\test\sample.sys
										vfs load vfs\sample.sys)

(Use "vfs\" prefix when working with files on VFS partition)