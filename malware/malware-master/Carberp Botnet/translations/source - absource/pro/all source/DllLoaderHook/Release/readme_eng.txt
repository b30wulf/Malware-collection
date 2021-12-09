creating exe:
builder.exe my.dll output.exe
where - my.dll - your DLL, output.exe - result file

schtasks.dll - sets autorun through Task Scheduler

your DLL has to have the following function

int start(char* exe, int admin)

exe - my 'exe' to be setup to autorun
admin - 0 - no admin rights, 1 - 'exe' was started with admin rights, 
        2 - exe successfully forced to obtain the admin rights
the function has to return 1 - autorun completed successfully, 0 - autorun failed
my program will save the results into debug log