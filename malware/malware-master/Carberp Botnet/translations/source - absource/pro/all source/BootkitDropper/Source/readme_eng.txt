2.09.2011							-	added Java patch and Win 7 exploit.

09.08.2011							-	added new functions and a module
  1.Utils.h/Utils.cpp				-	FixDWORD,CheckWow64,CheckUAC,CheckAdmin
  
19.07.2011							-	added bootkit installer, EXE and DLL downloads
	1. BootEvents.cpp
	
12.07.2011
	1. Inject.cpp / Inject.h		-	 added InjectRemouteDll
	2. Inject.cpp					-	 RemouteAllocateImageDll	

09.07.2011
	1. utils.cpp  / utils.h			-	 added IsUserAdmin
	2. GetApi.cpp / GetApi.h		-	 added shlwapi( PathCombineA ) library
	3. BootEvents.cpp				-	 added privileges elevation code when executing the bootkit
 