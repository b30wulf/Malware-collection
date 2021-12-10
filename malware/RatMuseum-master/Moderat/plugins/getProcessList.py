plugin_name = r"""getProcessList"""
plugin_description = r"""Get Process List"""
plugin_type = r"""remote"""
plugin_source = r"""

EnumProcesses = Psapi.EnumProcesses
EnumProcesses.restype = ctypes.wintypes.BOOL
GetProcessImageFileName = Psapi.GetProcessImageFileNameA
GetProcessImageFileName.restype = ctypes.wintypes.DWORD
OpenProcess = Kernel32.OpenProcess
OpenProcess.restype = ctypes.wintypes.HANDLE
TerminateProcess = Kernel32.TerminateProcess
TerminateProcess.restype = ctypes.wintypes.BOOL
CloseHandle = Kernel32.CloseHandle
MAX_PATH = 260
PROCESS_TERMINATE = 0x0001
PROCESS_QUERY_INFORMATION = 0x0400


def get_processes_list():
    PROCESSES = {}
    max_array = ctypes.c_ulong * 4096
    pProcessIds = max_array()
    pBytesReturned = ctypes.c_ulong()
    Psapi.EnumProcesses(ctypes.byref(pProcessIds), ctypes.sizeof(pProcessIds), ctypes.byref(pBytesReturned))
    nReturned = pBytesReturned.value/ctypes.sizeof(ctypes.c_ulong())
    pidProcessArray = [i for i in pProcessIds][:nReturned]
    for ProcessId in pidProcessArray:
        hProcess = OpenProcess(PROCESS_TERMINATE | PROCESS_QUERY_INFORMATION, False, ProcessId)
        if hProcess:
            ImageFileName = (ctypes.c_char*MAX_PATH)()
            if GetProcessImageFileName(hProcess, ImageFileName, MAX_PATH)>0:
                filename = os.path.basename(ImageFileName.value)
                PROCESSES[ProcessId] = filename
            CloseHandle(hProcess)
    return PROCESSES
"""