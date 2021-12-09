Debug version compilation:

 #define dbgdbg - this 'define' has to be included in kkqvx.h

 1. z d
 2. z z
 3. on the testing VM, create a folder to store logs files in c:\!dbg
 
 copy "!z_vir\z_kkqvx.vir" onto VM, and rename into exe

 ==========================

To compile a 'live' version the steps are the same, but without including the 'define'

 ==========================

The code you want to use with this service has to be inserted into ServiceMode(kkqvx.c) function after the comment:

  //------ ZDES RAZMESHAETSYA VASH KOD vmesto beckonechnogo cikla
