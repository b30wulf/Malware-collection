#ifndef _SDROPPER_H_
#define _SDROPPER_H_

namespace Drop
{
	#define DROP_EXP_MUTEX_ID	-3
	#define DROP_RUN_MUTEX_ID	-1
	#define DROP_MACHINEGUID	"abcxvcxvx"
	#define DROP_MACHINESIGN	"sacfsfdsf"

	extern CHAR MachineGuid[MAX_PATH];
	extern CHAR CurrentModulePath[MAX_PATH];
	extern CHAR CurrentConfigPath[MAX_PATH];
	extern PVOID CurrentImageBase;
	extern DWORD CurrentImageSize;
	extern BOOLEAN bFirstImageLoad;
	extern BOOLEAN bWorkThread;

	PCHAR GetMachineGuid();
	VOID CreateInjectStartThread();
	DWORD InjectStartThread(PVOID Context);
};

#endif
