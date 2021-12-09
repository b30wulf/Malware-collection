Copy the DLL you wish to execute into a folder 'In' and rename it to 'in.dll'
The DLL has to have a function called 'start'(all lower-case). The function has to be defined in the following format:
start(char* exe, int admin)
where 'exe' is the full path to the file the DLL was executed with
admin - 0 - no admin rights and it wasn't possible to obtain them
        1 - already has admin rights
		2 - no admin rights, but were successfully forced to obtain

The entire project has to be re-build in order to create the 'exe' file. The end result will be in 'release' folder.
The current 'exe' file in the 'release' folder has embedded autorun DLL that uses Task Scheduler - this autorun will work only with admin rights
