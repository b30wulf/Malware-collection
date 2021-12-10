using System;
using System.Collections.Generic;
using System.Runtime.InteropServices;
using System.Text;
using Microsoft.Win32.SafeHandles;

namespace DarkAPI
{
    public class Kernel32
    {
        [DllImport("Kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool ActivateActCtx(IntPtr hActCtx, out IntPtr lpCookie);

        [DllImport("kernel32.dll")]
        public static extern ushort AddAtom(string lpString);

        [DllImport("kernel32", SetLastError = true)]
        public static extern bool AddConsoleAlias(string Source, string Target, string ExeName);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        public static extern uint AddLocalAlternateComputerName(string lpDnsFQHostname, uint ulFlags);

        [DllImport("kernel32.dll")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool AllocateUserPhysicalPages(IntPtr hProcess, ref uint NumberOfPages, UIntPtr PageArray);

        [DllImport("kernel32")]
        public static extern bool AllocConsole();

        [DllImport("kernel32", SetLastError = true)]
        public static extern bool GetVersionEx(ref OSVERSIONINFOEX osvi);

        [DllImport("kernel32.dll", ExactSpelling = true)]
        public static extern bool AreFileApisANSI();

        [DllImport("kernel32.dll")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool AssignProcessToJobObject(IntPtr hJob, IntPtr hProcess);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool AttachConsole(uint dwProcessId);

        [DllImport("kernel32.dll")]
        public static extern bool BackupRead(IntPtr hFile, IntPtr lpBuffer,
           uint nNumberOfBytesToRead, out uint lpNumberOfBytesRead, bool bAbort,
           bool bProcessSecurity, ref IntPtr lpContext);

        [DllImport("kernel32.dll")]
        public static extern bool BackupSeek(IntPtr hFile, uint dwLowBytesToSeek,
           uint dwHighBytesToSeek, out uint lpdwLowBytesSeeked, out uint
           lpdwHighBytesSeeked, [In] ref IntPtr lpContext);

        [DllImport("kernel32.dll")]
        public static extern bool BackupWrite(IntPtr hFile, IntPtr lpBuffer,
           uint nNumberOfBytesToWrite, out uint lpNumberOfBytesWritten, bool bAbort,
           bool bProcessSecurity, ref IntPtr lpContext);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool Beep(uint dwFreq, uint dwDuration);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern IntPtr BeginUpdateResource(string pFileName,
           [MarshalAs(UnmanagedType.Bool)]bool bDeleteExistingResources);
        
        [DllImport("kernel32.dll")]
        public static extern bool BuildCommDCB(string lpDef, ref DCB lpDCB);

        [DllImport("kernel32.dll")]
        public static extern bool BuildCommDCBAndTimeouts(string lpDef, ref DCB lpDCB, ref COMMTIMEOUTS lpCommTimeouts);

        [DllImport("kernel32.dll")]
        public static extern bool CallNamedPipe(string lpNamedPipeName, byte[] lpInBuffer,
           uint nInBufferSize, [Out] byte[] lpOutBuffer, uint nOutBufferSize,
           out uint lpBytesRead, uint nTimeOut);

        [DllImport("kernel32.dll")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool CancelIo(IntPtr hFile);

        [DllImport("kernel32.dll")]
        public static extern bool CancelWaitableTimer(IntPtr hTimer);

        [DllImport("kernel32.dll")]
        public static extern bool ChangeTimerQueueTimer(IntPtr TimerQueue, IntPtr Timer,
           uint DueTime, uint Period);

        [DllImport("Kernel32.dll", SetLastError = true, ExactSpelling = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool CheckRemoteDebuggerPresent(Int32 hProcess, [MarshalAs(UnmanagedType.Bool)]ref bool isDebuggerPresent);

        [DllImport("kernel32.dll")]
        public static extern bool IsDebuggerPresent();

        [DllImport("kernel32.dll")]
        public static extern bool DebugActiveProcess(uint dwProcessId);

        [DllImport("kernel32.dll")]
        public static extern bool ContinueDebugEvent(uint dwProcessId, uint dwThreadId,
           uint dwContinueStatus);

        [DllImport("kernel32.dll")]
        public static extern void DebugBreak();

        [DllImport("kernel32.dll", EntryPoint = "WaitForDebugEvent")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool WaitForDebugEvent([In] ref DEBUG_EVENT lpDebugEvent, uint dwMilliseconds);

        [DllImport("kernel32.dll")]
        public static extern bool ClearCommBreak(IntPtr hFile);

        [DllImport("kernel32.dll")]
        public static extern bool ClearCommError(
          [In] int hFile,
          [Out, Optional] out uint lpErrors,
          [Out, Optional] out COMSTAT lpStat
        );

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool CloseHandle(IntPtr hObject);

        [DllImport("kernel32.dll")]
        public static extern bool CommConfigDialog(string lpszName, IntPtr hWnd,
           ref COMMCONFIG lpCC);

        [DllImport("kernel32.dll")]
        public static extern int CompareFileTime([In] ref FILETIME lpFileTime1,
           [In] ref FILETIME lpFileTime2);

        [DllImport("kernel32.dll")]
        public static extern int CompareString(uint Locale, uint dwCmpFlags, string lpString1,
           int cchCount1, string lpString2, int cchCount2);

        [DllImport("kernel32.dll")]
        public static extern bool ConnectNamedPipe(IntPtr hNamedPipe,
           [In] ref System.Threading.NativeOverlapped lpOverlapped);

        [DllImport("kernel32.dll")]
        public static extern uint ConvertDefaultLocale(uint Locale);

        [DllImport("kernel32.dll")]
        public static extern IntPtr ConvertThreadToFiber(IntPtr lpParameter);

        [DllImport("kernel32.dll")]
        public static extern bool CopyFile(string lpExistingFileName, string lpNewFileName, bool bFailIfExists);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool CopyFileEx(string lpExistingFileName, string lpNewFileName,
           CopyProgressRoutine lpProgressRoutine, IntPtr lpData, ref Int32 pbCancel,
           CopyFileFlags dwCopyFlags);

        [DllImport("Kernel32.dll", SetLastError = true)]
        public static extern IntPtr CreateActCtxW(ref ACTCTX pActCtx);

        [DllImport("Kernel32.dll", SetLastError = true)]
        public static extern IntPtr CreateActCtxA(ref ACTCTX pActCtx);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateConsoleScreenBuffer(uint dwDesiredAccess,
           uint dwShareMode, IntPtr lpSecurityAttributes, uint dwFlags,
           IntPtr lpScreenBufferData);

        [DllImport("kernel32.dll")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool CreateDirectory(string lpPathName,
           IntPtr lpSecurityAttributes);

        [DllImport("kernel32.dll")]
        public static extern bool CreateDirectoryEx(string lpTemplateDirectory,
           string lpNewDirectory, IntPtr lpSecurityAttributes);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateEvent(IntPtr lpEventAttributes, bool bManualReset, bool bInitialState, string lpName);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto, CallingConvention = CallingConvention.StdCall, SetLastError = true)]
        public static extern SafeFileHandle CreateFile(
              string lpFileName,
              uint dwDesiredAccess,
              uint dwShareMode,
              IntPtr SecurityAttributes,
              uint dwCreationDisposition,
              uint dwFlagsAndAttributes,
              IntPtr hTemplateFile);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern IntPtr CreateFileMapping(
            IntPtr hFile,
            IntPtr lpFileMappingAttributes,
            FileMapProtection flProtect,
            uint dwMaximumSizeHigh,
            uint dwMaximumSizeLow,
            [MarshalAs(UnmanagedType.LPTStr)] string lpName);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern bool CreateHardLink(string lpFileName, string lpExistingFileName, IntPtr lpSecurityAttributes);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateIoCompletionPort(IntPtr FileHandle,
           IntPtr ExistingCompletionPort, UIntPtr CompletionKey,
           uint NumberOfConcurrentThreads);

        [DllImport("kernel32.dll", CharSet = CharSet.Unicode)]
        public static extern IntPtr CreateJobObject([In] ref SECURITY_ATTRIBUTES
           lpJobAttributes, string lpName);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateMailslot(string lpName, uint nMaxMessageSize,
           uint lReadTimeout, IntPtr lpSecurityAttributes);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateMutex(IntPtr lpMutexAttributes, bool bInitialOwner, string lpName);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern IntPtr CreateNamedPipe(string lpName, uint dwOpenMode,
           uint dwPipeMode, uint nMaxInstances, uint nOutBufferSize, uint nInBufferSize,
           uint nDefaultTimeOut, IntPtr lpSecurityAttributes);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern IntPtr CreateNamedPipe(string lpName, uint dwOpenMode,
           uint dwPipeMode, uint nMaxInstances, uint nOutBufferSize, uint nInBufferSize,
           uint nDefaultTimeOut, SECURITY_ATTRIBUTES lpSecurityAttributes);

        [DllImport("kernel32.dll")]
        public static extern bool CreatePipe(out IntPtr hReadPipe, out IntPtr hWritePipe,
           ref SECURITY_ATTRIBUTES lpPipeAttributes, uint nSize);

        [DllImport("kernel32.dll")]
        public static extern bool CreateProcess(string lpApplicationName,
           string lpCommandLine, ref SECURITY_ATTRIBUTES lpProcessAttributes,
           ref SECURITY_ATTRIBUTES lpThreadAttributes, bool bInheritHandles,
           uint dwCreationFlags, IntPtr lpEnvironment, string lpCurrentDirectory,
           [In] ref STARTUPINFO lpStartupInfo,
           out PROCESS_INFORMATION lpProcessInformation);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateRemoteThread(IntPtr hProcess,
           IntPtr lpThreadAttributes, uint dwStackSize, IntPtr lpStartAddress, IntPtr lpParameter, uint dwCreationFlags, IntPtr lpThreadId);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern IntPtr CreateSemaphore(ref SECURITY_ATTRIBUTES securityAttributes, int initialCount, int maximumCount, string name);

        [DllImport("kernel32.dll")]
        [return: System.Runtime.InteropServices.MarshalAs(System.Runtime.InteropServices.UnmanagedType.I1)]
        public static extern bool CreateSymbolicLink(string lpSymlinkFileName, string lpTargetFileName, SYMBOLIC_LINK_FLAG dwFlags);

        [DllImport("kernel32.dll")]
        public static extern uint CreateTapePartition(IntPtr hDevice, uint dwPartitionMethod,
           uint dwCount, uint dwSize);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateThread([In] ref SECURITY_ATTRIBUTES
           SecurityAttributes, uint StackSize, System.Threading.ThreadStart StartFunction,
           IntPtr ThreadParameter, uint CreationFlags, out uint ThreadId);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateTimerQueue();

        [DllImport("kernel32.dll")]
        public static extern bool CreateTimerQueueTimer(out IntPtr phNewTimer,
           IntPtr TimerQueue, WaitOrTimerDelegate Callback, IntPtr Parameter,
           uint DueTime, uint Period, uint Flags);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern IntPtr CreateToolhelp32Snapshot(SnapshotFlags dwFlags, uint th32ProcessID);

        [DllImport("kernel32.dll")]
        public static extern IntPtr CreateWaitableTimer(IntPtr lpTimerAttributes, bool bManualReset, string lpTimerName);

        [DllImport("Kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool DeactivateActCtx(int dwFlags, IntPtr lpCookie);

        [DllImport("Advapi32.dll", SetLastError = true)]
        public static extern bool DecryptFile(string lpFileName, uint dwReserved);

        [DllImport("kernel32.dll")]
        public static extern bool DefineDosDevice(uint dwFlags, string lpDeviceName,
           string lpTargetPath);

        [DllImport("kernel32.dll", SetLastError = true, ExactSpelling = true)]
        public static extern ushort DeleteAtom(ushort nAtom);

        [DllImport("kernel32.dll")]
        public static extern void DeleteFiber(IntPtr lpFiber);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool DeleteFile(string lpFileName);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool DeleteFileA([MarshalAs(UnmanagedType.LPStr)]string lpFileName);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool DeleteFileW([MarshalAs(UnmanagedType.LPWStr)]string lpFileName);

        [DllImport("kernel32.dll")]
        public static extern bool DeleteTimerQueue(IntPtr TimerQueue);

        [DllImport("kernel32.dll")]
        public static extern bool DeleteTimerQueueEx(IntPtr TimerQueue, IntPtr CompletionEvent);

        [DllImport("kernel32.dll")]
        public static extern bool DeleteTimerQueueTimer(IntPtr TimerQueue, IntPtr Timer, IntPtr CompletionEvent);

        [DllImport("kernel32.dll")]
        public static extern bool DeleteVolumeMountPoint(string lpszVolumeMountPoint);

        [DllImport("kernel32.dll", ExactSpelling = true, SetLastError = true, CharSet = CharSet.Auto)]
        public static extern bool DeviceIoControl(IntPtr hDevice, uint dwIoControlCode,
        IntPtr lpInBuffer, uint nInBufferSize,
        IntPtr lpOutBuffer, uint nOutBufferSize,
        out uint lpBytesReturned, IntPtr lpOverlapped);

        [DllImport("kernel32.dll", SetLastError = true)]
        [PreserveSig] static extern bool DisableThreadLibraryCalls([In] IntPtr hModule);

        [DllImport("kernel32.dll")]
        public static extern bool DisconnectNamedPipe(IntPtr hNamedPipe);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern bool DnsHostnameToComputerName(string Hostname,
           [Out] StringBuilder ComputerName, ref uint nSize);

        [DllImport("kernel32.dll")]
        public static extern bool DosDateTimeToFileTime(ushort wFatDate, ushort wFatTime,
           out UFILETIME lpFileTime);

        [DllImport("kernel32.dll")]
        public static extern IntPtr DuplicateConsoleHandle(IntPtr hSourceHandle,
           uint dwDesiredAccess, int bInheritHandle, uint dwOptions);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool DuplicateHandle(IntPtr hSourceProcessHandle,
           IntPtr hSourceHandle, IntPtr hTargetProcessHandle, out IntPtr lpTargetHandle,
           uint dwDesiredAccess, [MarshalAs(UnmanagedType.Bool)] bool bInheritHandle, uint dwOptions);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool EndUpdateResource(IntPtr hUpdate, bool fDiscard);


        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        public static extern uint EnumerateLocalComputerNames(
            COMPUTER_NAME_TYPE NameType,
            uint ulFlags,
            IntPtr lpBuffer,
            ref uint nSize);

        [DllImport("kernel32.dll")]
        public static extern bool EnumResourceNames(IntPtr hModule, string lpszType, EnumResNameProcDelegate lpEnumFunc, IntPtr lParam);

        [DllImport("kernel32.dll")]
        public static extern bool EnumResourceTypes(IntPtr hModule, EnumResTypeProc lpEnumFunc, IntPtr lParam);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool EnumSystemCodePages(EnumCodePagesProcDlgt lpCodePageEnumProc,
           uint dwFlags);
        public const uint CP_INSTALLED = 1;
        public const uint CP_SUPPORTED = 2;
        public delegate bool EnumCodePagesProcDlgt(string lpCodePageString);

        [DllImport("kernel32.dll")]
        public static extern uint EraseTape(IntPtr hDevice, uint dwEraseType, bool bImmediate);

        [DllImport("kernel32.dll")]
        public static extern bool EscapeCommFunction(IntPtr hFile, uint dwFunc);

        [DllImport("kernel32.dll")]
        public static extern bool EscapeCommFunction(IntPtr hFile, ExtendedFunctions dwFunc);

        [DllImport("kernel32.dll")]
        public static extern void ExitProcess(uint uExitCode);

        [DllImport("kernel32.dll")]
        public static extern void ExitThread(uint dwExitCode);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern int ExpandEnvironmentStrings([MarshalAs(UnmanagedType.LPTStr)] String source, [Out] StringBuilder destination, int size);

        [DllImport("kernel32.dll")]
        public static extern void FatalAppExit(uint uAction, string lpMessageText);

        [DllImport("kernel32.dll")]
        public static extern void FatalExit(int ExitCode);

        [DllImport("kernel32.dll")]
        public static extern bool FileTimeToDosDateTime([In] ref FILETIME lpFileTime, out ushort lpFatDate, out ushort lpFatTime);

        [DllImport("kernel32.dll")]
        public static extern bool FileTimeToLocalFileTime([In] ref FILETIME lpFileTime, out FILETIME lpLocalFileTime);

        [DllImport("kernel32.dll",
        CallingConvention = CallingConvention.Winapi, SetLastError = true)]
        public static extern bool FileTimeToSystemTime([In] ref FILETIME lpFileTime, out SYSTEMTIME lpSystemTime);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern ushort FindAtom(string lpString);

        [DllImport("kernel32.dll")]
        public static extern bool FindClose(IntPtr hFindFile);

        [DllImport("kernel32.dll")]
        public static extern bool FindCloseChangeNotification(IntPtr hChangeHandle);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindFirstChangeNotification(string lpPathName, bool bWatchSubtree, uint dwNotifyFilter);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        public static extern IntPtr FindFirstFile(string lpFileName, out WIN32_FIND_DATA lpFindFileData);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindFirstFileEx(string lpFileName, FINDEX_INFO_LEVELS fInfoLevelId, IntPtr lpFindFileData, FINDEX_SEARCH_OPS fSearchOp, IntPtr lpSearchFilter, uint dwAdditionalFlags);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindFirstVolume([Out] StringBuilder lpszVolumeName, uint cchBufferLength);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindFirstVolumeMountPoint(string lpszRootPathName, [Out] StringBuilder lpszVolumeMountPoint, uint cchBufferLength);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        public static extern bool FindNextFile(IntPtr hFindFile, out WIN32_FIND_DATA lpFindFileData);

        [DllImport("kernel32.dll")]
        public static extern bool FindNextVolume(IntPtr hFindVolume, [Out] StringBuilder lpszVolumeName, uint cchBufferLength);

        [DllImport("kernel32.dll")]
        public static extern bool FindNextVolumeMountPoint(IntPtr hFindVolumeMountPoint, [Out] StringBuilder lpszVolumeMountPoint, uint cchBufferLength);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindResource(IntPtr hModule, IntPtr lpName, IntPtr lpType);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindResource(IntPtr hModule, int lpName, int lpType);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindResource(IntPtr hModule, int lpName, string lpType);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindResource(IntPtr hModule, string lpName, int lpType);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern IntPtr FindResource(IntPtr hModule, string lpName, string lpType);

        [DllImport("kernel32.dll")]
        public static extern IntPtr FindResourceEx(IntPtr hModule, IntPtr lpType, IntPtr lpName, ushort wLanguage);

        [DllImport("kernel32.dll")]
        public static extern bool FindVolumeClose(IntPtr hFindVolume);

        [DllImport("kernel32.dll")]
        public static extern bool FindVolumeMountPointClose(IntPtr hFindVolumeMountPoint);

        [DllImport("kernel32.dll")]
        public static extern bool FlushConsoleInputBuffer(IntPtr hConsoleInput);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool FlushFileBuffers(SafeFileHandle hFile);

        [DllImport("kernel32.dll")]
        public static extern bool FlushInstructionCache(IntPtr hProcess, IntPtr lpBaseAddress, UIntPtr dwSize);

        [DllImport("kernel32.dll")]
        public static extern bool FlushViewOfFile(IntPtr lpBaseAddress, UIntPtr dwNumberOfBytesToFlush);

        [DllImport("kernel32.dll")]
        public static extern bool FoldString(uint dwMapFlags, string lpSrcStr, int cchSrc, [Out] StringBuilder lpDestStr, int cchDest);

        [DllImport("kernel32.dll")]
        public static extern uint FormatMessage(uint dwFlags, IntPtr lpSource, uint dwMessageId, uint dwLanguageId, [Out] StringBuilder lpBuffer, uint nSize, IntPtr Arguments);

        [DllImport("Kernel32.dll", SetLastError = true)]
        public static extern uint FormatMessage(uint dwFlags, IntPtr lpSource, uint dwMessageId, uint dwLanguageId, ref IntPtr lpBuffer, uint nSize, IntPtr pArguments);

        [DllImport("Kernel32.dll", SetLastError = true)]
        public static extern uint FormatMessage(uint dwFlags, IntPtr lpSource, uint dwMessageId, uint dwLanguageId, ref IntPtr lpBuffer, uint nSize, string[] Arguments);

        [DllImport("kernel32.dll", SetLastError = true, ExactSpelling = true)]
        public static extern bool FreeConsole();

        [DllImport("kernel32.dll")]
        public static extern bool FreeEnvironmentStrings(string lpszEnvironmentBlock);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool FreeLibrary(IntPtr hModule);

        [DllImport("kernel32.dll")]
        public static extern void FreeLibraryAndExitThread(IntPtr hModule, uint dwExitCode);

        [DllImport("kernel32.dll")]
        public static extern bool FreeUserPhysicalPages(IntPtr hProcess, ref uint NumberOfPages, UIntPtr UserPfnArray);

        [DllImport("kernel32.dll")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool GenerateConsoleCtrlEvent(uint dwCtrlEvent, uint dwProcessGroupId);

        [DllImport("kernel32.dll")]
        public static extern uint GetACP();

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern uint GetAtomName(ushort nAtom, [Out] StringBuilder lpBuffer, int nSize);

        [DllImport("kernel32.dll")]
        public static extern bool GetBinaryType(string lpApplicationName, out uint lpBinaryType);

        [DllImport("kernel32.dll")]
        public static extern int GetCalendarInfo(uint Locale, uint Calendar, uint CalType, [Out] StringBuilder lpCalData, int cchData, IntPtr lpValue);

        [DllImport("user32.dll")]
        public static extern IntPtr GetClipboardData(uint uFormat);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        public static extern System.IntPtr GetCommandLine();

        [DllImport("kernel32.dll")]
        public static extern bool GetCommConfig(IntPtr hCommDev, ref COMMCONFIG lpCC, ref uint lpdwSize);

        [DllImport("kernel32.dll")]
        public static extern bool GetCommMask(IntPtr hFile, out uint lpEvtMask);

        [DllImport("kernel32.dll")]
        public static extern bool GetCommModemStatus(IntPtr hFile, out uint lpModemStat);

        [DllImport("kernel32.dll")]
        public static extern bool GetCommProperties(IntPtr hFile, ref COMMPROP lpCommProp);

        [DllImport("kernel32.dll")]
        public static extern bool GetCommState(IntPtr hFile, ref DCB lpDCB);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetCommTimeouts(IntPtr hFile, ref COMMTIMEOUTS lpCommTimeouts);

        [DllImport("kernel32.dll")]
        public static extern uint GetCompressedFileSize(string lpFileName, out uint lpFileSizeHigh);

        [DllImport("Kernel32", CharSet = CharSet.Auto)]
        public static extern bool GetComputerName(StringBuilder buffer, ref uint size);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern bool GetComputerNameEx(COMPUTER_NAME_FORMAT NameType, [Out] StringBuilder lpBuffer, ref uint lpnSize);

        [DllImport("kernel32", SetLastError = true)]
        public static extern bool GetConsoleAlias(string Source, out StringBuilder TargetBuffer, uint TargetBufferLength, string ExeName);

        [DllImport("kernel32", SetLastError = true)]
        public static extern uint GetConsoleAliasExes(out StringBuilder ExeNameBuffer, uint ExeNameBufferLength);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern uint GetConsoleAliasExesLength();

        [DllImport("kernel32.dll")]
        public static extern uint GetConsoleCP();

        [DllImport("kernel32.dll")]
        public static extern bool GetConsoleCursorInfo(IntPtr hConsoleOutput, out CONSOLE_CURSOR_INFO lpConsoleCursorInfo);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetConsoleDisplayMode(out uint ModeFlags);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetConsoleHistoryInfo(out CONSOLE_HISTORY_INFO ConsoleHistoryInfo);

        [DllImport("kernel32.dll")]
        public static extern bool GetConsoleMode(IntPtr hConsoleHandle, out uint lpMode);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern uint GetConsoleOriginalTitle(out StringBuilder ConsoleTitle, uint Size);

        [DllImport("kernel32.dll")]
        public static extern uint GetConsoleOutputCP();

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern uint GetConsoleProcessList(uint[] ProcessList, uint ProcessCount);

        [DllImport("kernel32.dll")]
        public static extern COORD GetConsoleFontSize(IntPtr hConsoleOutput, Int32 nFont);

        [DllImport("kernel32.dll")]
        public static extern bool GetConsoleScreenBufferInfo(IntPtr hConsoleOutput, out CONSOLE_SCREEN_BUFFER_INFO lpConsoleScreenBufferInfo);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetConsoleSelectionInfo(CONSOLE_SELECTION_INFO ConsoleSelectionInfo);

        [DllImport("kernel32.dll")]
        public static extern uint GetConsoleTitle([Out] StringBuilder lpConsoleTitle, uint nSize);

        [DllImport("kernel32.dll")]
        public static extern IntPtr GetConsoleWindow();

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetCPInfoEx([MarshalAs(UnmanagedType.U4)] int CodePage, [MarshalAs(UnmanagedType.U4)] int dwFlags, out CPINFOEX lpCPInfoEx);

        [DllImport("Kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool GetCurrentActCtx(ref ACTCTX pActCtx);

        [DllImport("kernel32.dll")]
        public static extern bool GetCurrentConsoleFont(IntPtr hConsoleOutput, bool bMaximumWindow, out CONSOLE_FONT_INFO lpConsoleCurrentFont);

        [DllImport("kernel32.dll", CharSet = CharSet.Unicode, SetLastError = true)]
        public static extern bool GetCurrentConsoleFontEx(IntPtr hConsoleOutput, bool bMaximumWindow, [In, Out] CONSOLE_FONT_INFOEX lpConsoleCurrentFont);

        [DllImport("kernel32.dll")]
        public static extern uint GetCurrentDirectory(uint nBufferLength, [Out] StringBuilder lpBuffer);

        [DllImport("kernel32.dll")]
        public static extern IntPtr GetCurrentProcess();

        [DllImport("kernel32.dll")]
        public static extern uint GetCurrentProcessId();

        [DllImport("kernel32.dll")]
        public static extern IntPtr GetCurrentThread();

        [DllImport("kernel32.dll")]
        public static extern uint GetCurrentThreadId();

        [DllImport("kernel32.dll")]
        public static extern int GetDateFormat(uint locale, uint dwFlags, ref SystemTime date, string format, StringBuilder sb, int sbSize);

        [DllImport("kernel32.dll")]
        public static extern bool GetDefaultCommConfig(string lpszName, [In, Out] ref COMMCONFIG lpCC, ref uint lpdwSize);

        [DllImport("kernel32.dll")]
        public static extern bool GetDevicePowerState(IntPtr hDevice, out bool pfOn);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern bool GetDiskFreeSpace(string lpRootPathName,
                                           out uint lpSectorsPerCluster,
                                           out uint lpBytesPerSector,
                                           out uint lpNumberOfFreeClusters,
                                           out uint lpTotalNumberOfClusters);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool GetDiskFreeSpaceEx(string lpDirectoryName,
                                            out ulong lpFreeBytesAvailable,
                                            out ulong lpTotalNumberOfBytes,
                                            out ulong lpTotalNumberOfFreeBytes);


        [DllImport("kernel32.dll")]
        public static extern DriveType GetDriveType([MarshalAs(UnmanagedType.LPStr)] string lpRootPathName);

        [DllImport("kernel32.dll")]
        public static extern IntPtr GetEnvironmentStrings();

        [DllImport("kernel32.dll")]
        public static extern uint GetEnvironmentVariable(string lpName, [Out] StringBuilder lpBuffer, uint nSize);

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool GetExitCodeProcess(IntPtr hProcess, out uint lpExitCode);

        [DllImport("kernel32.dll")]
        public static extern bool GetExitCodeThread(IntPtr hThread, out uint lpExitCode);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern int GetFileAttributes(string lpFileName);

        [DllImport("kernel32.dll")]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool GetFileAttributesEx(string lpFileName,
           GET_FILEEX_INFO_LEVELS fInfoLevelId, IntPtr lpFileInformation);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetFileInformationByHandle(IntPtr hFile,
           out BY_HANDLE_FILE_INFORMATION lpFileInformation);

        [DllImport("kernel32.dll")]
        public static extern uint GetFileSize(IntPtr hFile, IntPtr lpFileSizeHigh);

        [DllImport("kernel32.dll")]
        public static extern bool GetFileSizeEx(IntPtr hFile, out long lpFileSize);


        [DllImport("kernel32.dll")]
        public static extern bool GetFileTime(IntPtr hFile, IntPtr lpCreationTime, IntPtr lpLastAccessTime, IntPtr lpLastWriteTime);

        [DllImport("kernel32.dll")]
        public static extern FileType GetFileType(IntPtr hFile);


        [DllImport("kernel32.dll")]
        public static extern uint GetFullPathName(string lpFileName, uint nBufferLength, [Out] StringBuilder lpBuffer, out StringBuilder lpFilePart);

        [DllImport("kernel32.dll")]
        public static extern bool GetHandleInformation(IntPtr hObject, out uint lpdwFlags);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern UInt32 GetLargePageMinimum();

        [DllImport("kernel32.dll")]
        public static extern COORD GetLargestConsoleWindowSize(IntPtr hConsoleOutput);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern int GetLocaleInfo(uint Locale, uint LCType, [Out] StringBuilder lpLCData, int cchData);

        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Unicode)]
        public static extern int GetLocaleInfoEx(String lpLocaleName, LCTYPE LCType, StringBuilder lpLCData, int cchData);

        [DllImport("kernel32.dll")]
        public static extern void GetLocalTime(out SYSTEMTIME lpSystemTime);

        [DllImport("kernel32.dll")]
        public static extern uint GetLogicalDrives();

        [DllImport("kernel32.dll")]
        public static extern uint GetLogicalDriveStrings(uint nBufferLength, [Out] char[] lpBuffer);


        [DllImport("kernel32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        [return: MarshalAs(UnmanagedType.U4)]
        public static extern int GetLongPathName([MarshalAs(UnmanagedType.LPTStr)] string lpszShortPath, [MarshalAs(UnmanagedType.LPTStr)] StringBuilder lpszLongPath, [MarshalAs(UnmanagedType.U4)] int cchBuffer);

        [DllImport("kernel32.dll")]
        public static extern bool GetMailslotInfo(IntPtr hMailslot, IntPtr lpMaxMessageSize, IntPtr lpNextSize, IntPtr lpMessageCount, IntPtr lpReadTimeout);


        [DllImport("kernel32.dll", SetLastError = true)]
        [PreserveSig]
        public static extern uint GetModuleFileName([In] IntPtr hModule, [Out] StringBuilder lpFilename, [In] [MarshalAs(UnmanagedType.U4)] int nSize);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        public static extern IntPtr GetModuleHandle(string lpModuleName);

        [DllImport("kernel32.dll")]
        public static extern bool GetNamedPipeHandleState(IntPtr hNamedPipe, IntPtr lpState, IntPtr lpCurInstances, IntPtr lpMaxCollectionCount, IntPtr lpCollectDataTimeout, [Out] StringBuilder lpUserName, uint nMaxUserNameSize);

        [DllImport("kernel32.dll")]
        public static extern bool GetNamedPipeInfo(IntPtr hNamedPipe, IntPtr lpFlags, IntPtr lpOutBufferSize, IntPtr lpInBufferSize, IntPtr lpMaxInstances);

        [DllImport("kernel32.dll")]
        public static extern void GetNativeSystemInfo(ref SYSTEM_INFO lpSystemInfo);

        [DllImport("kernel32.dll")]
        public static extern int GetNumberFormat(uint Locale, uint dwFlags, string lpValue, IntPtr lpFormat, [Out] StringBuilder lpNumberStr, int cchNumber);

        [DllImport("kernel32.dll")]
        public static extern bool GetNumberOfConsoleInputEvents(IntPtr hConsoleInput, out uint lpcNumberOfEvents);

        [DllImport("kernel32.dll")]
        public static extern bool GetNumberOfConsoleMouseButtons(ref uint lpNumberOfMouseButtons);

        [DllImport("kernel32.dll")]
        public static extern uint GetOEMCP();

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetOverlappedResult(IntPtr hFile, [In] ref System.Threading.NativeOverlapped lpOverlapped, out uint lpNumberOfBytesTransferred, bool bWait);

        [DllImport("kernel32.dll")]
        public static extern uint GetPriorityClass(IntPtr hProcess);

        [DllImport("kernel32.dll")]
        public static extern uint GetPrivateProfileInt(string lpAppName, string lpKeyName, int nDefault, string lpFileName);

        [DllImport("kernel32.dll")]
        public static extern uint GetPrivateProfileSectionNames(IntPtr lpszReturnBuffer, uint nSize, string lpFileName);

        [DllImport("kernel32.dll", CharSet = CharSet.Unicode)]
        public static extern uint GetPrivateProfileString(string lpAppName, string lpKeyName, string lpDefault, StringBuilder lpReturnedString, uint nSize, string lpFileName);

        [DllImport("kernel32.dll")]
        public static extern bool GetPrivateProfileStruct(string lpszSection, string lpszKey, IntPtr lpStruct, uint uSizeStruct, string szFile);

        [DllImport("kernel32", CharSet = CharSet.Ansi, ExactSpelling = true, SetLastError = true)]
        public static extern UIntPtr GetProcAddress(IntPtr hModule, string procName);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetProcessAffinityMask(IntPtr hProcess, out UIntPtr lpProcessAffinityMask, out UIntPtr lpSystemAffinityMask);

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool GetProcessHandleCount(IntPtr hProcess, ref UInt32 dwHandleCount);







        [StructLayout(LayoutKind.Sequential)]
        public  struct SYSTEM_INFO
        {
            public ushort wProcessorArchitecture;
            public ushort wReserved;
            public uint dwPageSize;
            public IntPtr lpMinimumApplicationAddress;
            public IntPtr lpMaximumApplicationAddress;
            public UIntPtr dwActiveProcessorMask;
            public uint dwNumberOfProcessors;
            public uint dwProcessorType;
            public uint dwAllocationGranularity;
            public ushort wProcessorLevel;
            public ushort wProcessorRevision;
        };

        public enum LCTYPE : uint
        {
            LOCALE_NOUSEROVERRIDE = 0x80000000,   // do not use user overrides
            LOCALE_RETURN_NUMBER = 0x20000000,   // return number instead of string

            // Modifier for genitive names
            LOCALE_RETURN_GENITIVE_NAMES = 0x10000000,   //Flag to return the Genitive forms of month names
            LOCALE_SLOCALIZEDDISPLAYNAME = 0x00000002,   // localized name of locale, eg "German (Germany)" in UI language
            LOCALE_SENGLISHDISPLAYNAME = 0x00000072,   // Display name (language + country usually) in English, eg "German (Germany)"
            LOCALE_SNATIVEDISPLAYNAME = 0x00000073,   // Display name in native locale language, eg "Deutsch (Deutschland)

            LOCALE_SLOCALIZEDLANGUAGENAME = 0x0000006f,   // Language Display Name for a language, eg "German" in UI language
            LOCALE_SENGLISHLANGUAGENAME = 0x00001001,   // English name of language, eg "German"
            LOCALE_SNATIVELANGUAGENAME = 0x00000004,   // native name of language, eg "Deutsch"

            LOCALE_SLOCALIZEDCOUNTRYNAME = 0x00000006,   // localized name of country, eg "Germany" in UI language
            LOCALE_SENGLISHCOUNTRYNAME = 0x00001002,   // English name of country, eg "Germany"
            LOCALE_SNATIVECOUNTRYNAME = 0x00000008,   // native name of country, eg "Deutschland"

            // Additional LCTYPEs
            LOCALE_SABBREVLANGNAME = 0x00000003,   // abbreviated language name

            LOCALE_ICOUNTRY = 0x00000005,   // country code
            LOCALE_SABBREVCTRYNAME = 0x00000007,   // abbreviated country name
            LOCALE_IGEOID = 0x0000005B,   // geographical location id

            LOCALE_IDEFAULTLANGUAGE = 0x00000009,   // default language id
            LOCALE_IDEFAULTCOUNTRY = 0x0000000A,   // default country code
            LOCALE_IDEFAULTCODEPAGE = 0x0000000B,   // default oem code page
            LOCALE_IDEFAULTANSICODEPAGE = 0x00001004,   // default ansi code page
            LOCALE_IDEFAULTMACCODEPAGE = 0x00001011,   // default mac code page

            LOCALE_SLIST = 0x0000000C,   // list item separator
            LOCALE_IMEASURE = 0x0000000D,   // 0 = metric, 1 = US

            LOCALE_SDECIMAL = 0x0000000E,   // decimal separator
            LOCALE_STHOUSAND = 0x0000000F,   // thousand separator
            LOCALE_SGROUPING = 0x00000010,   // digit grouping
            LOCALE_IDIGITS = 0x00000011,   // number of fractional digits
            LOCALE_ILZERO = 0x00000012,   // leading zeros for decimal
            LOCALE_INEGNUMBER = 0x00001010,   // negative number mode
            LOCALE_SNATIVEDIGITS = 0x00000013,   // native digits for 0-9

            LOCALE_SCURRENCY = 0x00000014,   // local monetary symbol
            LOCALE_SINTLSYMBOL = 0x00000015,   // uintl monetary symbol
            LOCALE_SMONDECIMALSEP = 0x00000016,   // monetary decimal separator
            LOCALE_SMONTHOUSANDSEP = 0x00000017,   // monetary thousand separator
            LOCALE_SMONGROUPING = 0x00000018,   // monetary grouping
            LOCALE_ICURRDIGITS = 0x00000019,   // # local monetary digits
            LOCALE_IINTLCURRDIGITS = 0x0000001A,   // # uintl monetary digits
            LOCALE_ICURRENCY = 0x0000001B,   // positive currency mode
            LOCALE_INEGCURR = 0x0000001C,   // negative currency mode

            LOCALE_SDATE = 0x0000001D,   // date separator (derived from LOCALE_SSHORTDATE, use that instead)
            LOCALE_STIME = 0x0000001E,   // time separator (derived from LOCALE_STIMEFORMAT, use that instead)
            LOCALE_SSHORTDATE = 0x0000001F,   // short date format string
            LOCALE_SLONGDATE = 0x00000020,   // long date format string
            LOCALE_STIMEFORMAT = 0x00001003,   // time format string
            LOCALE_IDATE = 0x00000021,   // short date format ordering (derived from LOCALE_SSHORTDATE, use that instead)
            LOCALE_ILDATE = 0x00000022,   // long date format ordering (derived from LOCALE_SLONGDATE, use that instead)
            LOCALE_ITIME = 0x00000023,   // time format specifier (derived from LOCALE_STIMEFORMAT, use that instead)
            LOCALE_ITIMEMARKPOSN = 0x00001005,   // time marker position (derived from LOCALE_STIMEFORMAT, use that instead)
            LOCALE_ICENTURY = 0x00000024,   // century format specifier (short date, LOCALE_SSHORTDATE is preferred)
            LOCALE_ITLZERO = 0x00000025,   // leading zeros in time field (derived from LOCALE_STIMEFORMAT, use that instead)
            LOCALE_IDAYLZERO = 0x00000026,   // leading zeros in day field (short date, LOCALE_SSHORTDATE is preferred)
            LOCALE_IMONLZERO = 0x00000027,   // leading zeros in month field (short date, LOCALE_SSHORTDATE is preferred)
            LOCALE_S1159 = 0x00000028,   // AM designator
            LOCALE_S2359 = 0x00000029,   // PM designator

            LOCALE_ICALENDARTYPE = 0x00001009,   // type of calendar specifier
            LOCALE_IOPTIONALCALENDAR = 0x0000100B,   // additional calendar types specifier
            LOCALE_IFIRSTDAYOFWEEK = 0x0000100C,   // first day of week specifier
            LOCALE_IFIRSTWEEKOFYEAR = 0x0000100D,   // first week of year specifier

            LOCALE_SDAYNAME1 = 0x0000002A,   // long name for Monday
            LOCALE_SDAYNAME2 = 0x0000002B,   // long name for Tuesday
            LOCALE_SDAYNAME3 = 0x0000002C,   // long name for Wednesday
            LOCALE_SDAYNAME4 = 0x0000002D,   // long name for Thursday
            LOCALE_SDAYNAME5 = 0x0000002E,   // long name for Friday
            LOCALE_SDAYNAME6 = 0x0000002F,   // long name for Saturday
            LOCALE_SDAYNAME7 = 0x00000030,   // long name for Sunday
            LOCALE_SABBREVDAYNAME1 = 0x00000031,   // abbreviated name for Monday
            LOCALE_SABBREVDAYNAME2 = 0x00000032,   // abbreviated name for Tuesday
            LOCALE_SABBREVDAYNAME3 = 0x00000033,   // abbreviated name for Wednesday
            LOCALE_SABBREVDAYNAME4 = 0x00000034,   // abbreviated name for Thursday
            LOCALE_SABBREVDAYNAME5 = 0x00000035,   // abbreviated name for Friday
            LOCALE_SABBREVDAYNAME6 = 0x00000036,   // abbreviated name for Saturday
            LOCALE_SABBREVDAYNAME7 = 0x00000037,   // abbreviated name for Sunday
            LOCALE_SMONTHNAME1 = 0x00000038,   // long name for January
            LOCALE_SMONTHNAME2 = 0x00000039,   // long name for February
            LOCALE_SMONTHNAME3 = 0x0000003A,   // long name for March
            LOCALE_SMONTHNAME4 = 0x0000003B,   // long name for April
            LOCALE_SMONTHNAME5 = 0x0000003C,   // long name for May
            LOCALE_SMONTHNAME6 = 0x0000003D,   // long name for June
            LOCALE_SMONTHNAME7 = 0x0000003E,   // long name for July
            LOCALE_SMONTHNAME8 = 0x0000003F,   // long name for August
            LOCALE_SMONTHNAME9 = 0x00000040,   // long name for September
            LOCALE_SMONTHNAME10 = 0x00000041,   // long name for October
            LOCALE_SMONTHNAME11 = 0x00000042,   // long name for November
            LOCALE_SMONTHNAME12 = 0x00000043,   // long name for December
            LOCALE_SMONTHNAME13 = 0x0000100E,   // long name for 13th month (if exists)
            LOCALE_SABBREVMONTHNAME1 = 0x00000044,   // abbreviated name for January
            LOCALE_SABBREVMONTHNAME2 = 0x00000045,   // abbreviated name for February
            LOCALE_SABBREVMONTHNAME3 = 0x00000046,   // abbreviated name for March
            LOCALE_SABBREVMONTHNAME4 = 0x00000047,   // abbreviated name for April
            LOCALE_SABBREVMONTHNAME5 = 0x00000048,   // abbreviated name for May
            LOCALE_SABBREVMONTHNAME6 = 0x00000049,   // abbreviated name for June
            LOCALE_SABBREVMONTHNAME7 = 0x0000004A,   // abbreviated name for July
            LOCALE_SABBREVMONTHNAME8 = 0x0000004B,   // abbreviated name for August
            LOCALE_SABBREVMONTHNAME9 = 0x0000004C,   // abbreviated name for September
            LOCALE_SABBREVMONTHNAME10 = 0x0000004D,   // abbreviated name for October
            LOCALE_SABBREVMONTHNAME11 = 0x0000004E,   // abbreviated name for November
            LOCALE_SABBREVMONTHNAME12 = 0x0000004F,   // abbreviated name for December
            LOCALE_SABBREVMONTHNAME13 = 0x0000100F,   // abbreviated name for 13th month (if exists)

            LOCALE_SPOSITIVESIGN = 0x00000050,   // positive sign
            LOCALE_SNEGATIVESIGN = 0x00000051,   // negative sign
            LOCALE_IPOSSIGNPOSN = 0x00000052,   // positive sign position (derived from INEGCURR)
            LOCALE_INEGSIGNPOSN = 0x00000053,   // negative sign position (derived from INEGCURR)
            LOCALE_IPOSSYMPRECEDES = 0x00000054,   // mon sym precedes pos amt (derived from ICURRENCY)
            LOCALE_IPOSSEPBYSPACE = 0x00000055,   // mon sym sep by space from pos amt (derived from ICURRENCY)
            LOCALE_INEGSYMPRECEDES = 0x00000056,   // mon sym precedes neg amt (derived from INEGCURR)
            LOCALE_INEGSEPBYSPACE = 0x00000057,   // mon sym sep by space from neg amt (derived from INEGCURR)

            LOCALE_FONTSIGNATURE = 0x00000058,   // font signature
            LOCALE_SISO639LANGNAME = 0x00000059,   // ISO abbreviated language name
            LOCALE_SISO3166CTRYNAME = 0x0000005A,   // ISO abbreviated country name

            LOCALE_IDEFAULTEBCDICCODEPAGE = 0x00001012,   // default ebcdic code page
            LOCALE_IPAPERSIZE = 0x0000100A,   // 1 = letter, 5 = legal, 8 = a3, 9 = a4
            LOCALE_SENGCURRNAME = 0x00001007,   // english name of currency
            LOCALE_SNATIVECURRNAME = 0x00001008,   // native name of currency
            LOCALE_SYEARMONTH = 0x00001006,   // year month format string
            LOCALE_SSORTNAME = 0x00001013,   // sort name
            LOCALE_IDIGITSUBSTITUTION = 0x00001014,   // 0 = context, 1 = none, 2 = national

            LOCALE_SNAME = 0x0000005c,   // locale name (with sort info) (ie: de-DE_phoneb)
            LOCALE_SDURATION = 0x0000005d,   // time duration format
            LOCALE_SKEYBOARDSTOINSTALL = 0x0000005e,   // (windows only) keyboards to install
            LOCALE_SSHORTESTDAYNAME1 = 0x00000060,   // Shortest day name for Monday
            LOCALE_SSHORTESTDAYNAME2 = 0x00000061,   // Shortest day name for Tuesday
            LOCALE_SSHORTESTDAYNAME3 = 0x00000062,   // Shortest day name for Wednesday
            LOCALE_SSHORTESTDAYNAME4 = 0x00000063,   // Shortest day name for Thursday
            LOCALE_SSHORTESTDAYNAME5 = 0x00000064,   // Shortest day name for Friday
            LOCALE_SSHORTESTDAYNAME6 = 0x00000065,   // Shortest day name for Saturday
            LOCALE_SSHORTESTDAYNAME7 = 0x00000066,   // Shortest day name for Sunday
            LOCALE_SISO639LANGNAME2 = 0x00000067,   // 3 character ISO abbreviated language name
            LOCALE_SISO3166CTRYNAME2 = 0x00000068,   // 3 character ISO country name
            LOCALE_SNAN = 0x00000069,   // Not a Number
            LOCALE_SPOSINFINITY = 0x0000006a,   // + Infinity
            LOCALE_SNEGINFINITY = 0x0000006b,   // - Infinity
            LOCALE_SSCRIPTS = 0x0000006c,   // Typical scripts in the locale
            LOCALE_SPARENT = 0x0000006d,   // Fallback name for resources
            LOCALE_SCONSOLEFALLBACKNAME = 0x0000006e,   // Fallback name for within the console

        }

        public enum FileType : uint
        {
            FileTypeChar = 0x0002,
            FileTypeDisk = 0x0001,
            FileTypePipe = 0x0003,
            FileTypeRemote = 0x8000,
            FileTypeUnknown = 0x0000,
        }

        public struct BY_HANDLE_FILE_INFORMATION
        {
            public uint FileAttributes;
            public FILETIME CreationTime;
            public FILETIME LastAccessTime;
            public FILETIME LastWriteTime;
            public uint VolumeSerialNumber;
            public uint FileSizeHigh;
            public uint FileSizeLow;
            public uint NumberOfLinks;
            public uint FileIndexHigh;
            public uint FileIndexLow;
        }

        public enum GET_FILEEX_INFO_LEVELS
        {
            GetFileExInfoStandard,
            GetFileExMaxInfoLevel
        }

        public enum DriveType : uint
        {
            Unknown = 0,
            Error = 1,
            Removable = 2,
            Fixed = 3,
            Remote = 4,
            CDROM = 5,
            RAMDisk = 6
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct SystemTime
        {
            [MarshalAs(UnmanagedType.U2)]
            public ushort Year;
            [MarshalAs(UnmanagedType.U2)]
            public ushort Month;
            [MarshalAs(UnmanagedType.U2)]
            public ushort DayOfWeek;
            [MarshalAs(UnmanagedType.U2)]
            public ushort Day;
            [MarshalAs(UnmanagedType.U2)]
            public ushort Hour;
            [MarshalAs(UnmanagedType.U2)]
            public ushort Minute;
            [MarshalAs(UnmanagedType.U2)]
            public ushort Second;
            [MarshalAs(UnmanagedType.U2)]
            public ushort Milliseconds;
        }

        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Unicode)]
        public class CONSOLE_FONT_INFOEX
        {
            private int cbSize;
            public CONSOLE_FONT_INFOEX()
            {
                cbSize = Marshal.SizeOf(typeof(CONSOLE_FONT_INFOEX));
            }
            public int FontIndex;
            public short FontWidth;
            public short FontHeight;
            public int FontFamily;
            public int FontWeight;
            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = 32)]
            public string FaceName;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct CONSOLE_FONT_INFO
        {
            public int nFont;
            public COORD dwFontSize;
        }

        public const int MAX_DEFAULTCHAR = 2;
        public const int MAX_LEADBYTES = 12;
        public const int MAX_PATH = 260;

        [StructLayout(LayoutKind.Sequential)]
        public struct CPINFOEX
        {
            [MarshalAs(UnmanagedType.U4)]
            public int MaxCharSize;

            [MarshalAs(UnmanagedType.ByValArray, SizeConst = MAX_DEFAULTCHAR)]
            public byte[] DefaultChar;

            [MarshalAs(UnmanagedType.ByValArray, SizeConst = MAX_LEADBYTES)]
            public byte[] LeadBytes;

            public char UnicodeDefaultChar;

            [MarshalAs(UnmanagedType.U4)]
            public int CodePage;

            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = MAX_PATH)]
            public string CodePageName;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct CONSOLE_SELECTION_INFO
        {
            uint Flags;
            COORD SelectionAnchor;
            SMALL_RECT Selection;

            // Flags values:
            const uint CONSOLE_MOUSE_DOWN = 0x0008; // Mouse is down
            const uint CONSOLE_MOUSE_SELECTION = 0x0004; //Selecting with the mouse
            const uint CONSOLE_NO_SELECTION = 0x0000; //No selection
            const uint CONSOLE_SELECTION_IN_PROGRESS = 0x0001; //Selection has begun
            const uint CONSOLE_SELECTION_NOT_EMPTY = 0x0002; //Selection rectangle is not empty
        }

        public struct SMALL_RECT
        {
            public short Left;
            public short Top;
            public short Right;
            public short Bottom;
        }

        public struct CONSOLE_SCREEN_BUFFER_INFO
        {
            public COORD dwSize;
            public COORD dwCursorPosition;
            public short wAttributes;
            public SMALL_RECT srWindow;
            public COORD dwMaximumWindowSize;
        }

        public struct COORD
        {
            public short X;
            public short Y;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct CONSOLE_HISTORY_INFO
        {
            ushort cbSize;
            ushort HistoryBufferSize;
            ushort NumberOfHistoryBuffers;
            uint dwFlags;
        }


        public struct CONSOLE_CURSOR_INFO
        {
            int dwSize;
            bool bVisible;
        }

        public enum COMPUTER_NAME_FORMAT
        {
            ComputerNameNetBIOS,
            ComputerNameDnsHostname,
            ComputerNameDnsDomain,
            ComputerNameDnsFullyQualified,
            ComputerNamePhysicalNetBIOS,
            ComputerNamePhysicalDnsHostname,
            ComputerNamePhysicalDnsDomain,
            ComputerNamePhysicalDnsFullyQualified
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct COMMPROP
        {
            short wPacketLength;
            short wPacketVersion;
            int dwServiceMask;
            int dwReserved1;
            int dwMaxTxQueue;
            int dwMaxRxQueue;
            int dwMaxBaud;
            int dwProvSubType;
            int dwProvCapabilities;
            int dwSettableParams;
            int dwSettableBaud;
            short wSettableData;
            short wSettableStopParity;
            int dwCurrentTxQueue;
            int dwCurrentRxQueue;
            int dwProvSpec1;
            int dwProvSpec2;
            string wcProvChar;
        }


        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Auto)]
        public struct WIN32_FIND_DATA
        {
            public uint dwFileAttributes;
            public System.Runtime.InteropServices.ComTypes.FILETIME ftCreationTime;
            public System.Runtime.InteropServices.ComTypes.FILETIME ftLastAccessTime;
            public System.Runtime.InteropServices.ComTypes.FILETIME ftLastWriteTime;
            public uint nFileSizeHigh;
            public uint nFileSizeLow;
            public uint dwReserved0;
            public uint dwReserved1;
            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = 260)]
            public string cFileName;
            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = 14)]
            public string cAlternateFileName;
        }

        public enum FINDEX_SEARCH_OPS : int
        {
            FindExSearchNameMatch = 0,
            FindExSearchLimitToDirectories = 1,
            FindExSearchLimitToDevices = 2
        };

        public enum FINDEX_INFO_LEVELS : int
        {
            FindExInfoStandard = 0,
            FindExInfoBasic = 1
        };


        [StructLayout(LayoutKind.Sequential)]
        public struct SYSTEMTIME
        {
            [MarshalAs(UnmanagedType.U2)]
            public short Year;
            [MarshalAs(UnmanagedType.U2)]
            public short Month;
            [MarshalAs(UnmanagedType.U2)]
            public short DayOfWeek;
            [MarshalAs(UnmanagedType.U2)]
            public short Day;
            [MarshalAs(UnmanagedType.U2)]
            public short Hour;
            [MarshalAs(UnmanagedType.U2)]
            public short Minute;
            [MarshalAs(UnmanagedType.U2)]
            public short Second;
            [MarshalAs(UnmanagedType.U2)]
            public short Milliseconds;

            public SYSTEMTIME(DateTime dt)
            {
                dt = dt.ToUniversalTime();  // SetSystemTime expects the SYSTEMTIME in UTC
                Year = (short)dt.Year;
                Month = (short)dt.Month;
                DayOfWeek = (short)dt.DayOfWeek;
                Day = (short)dt.Day;
                Hour = (short)dt.Hour;
                Minute = (short)dt.Minute;
                Second = (short)dt.Second;
                Milliseconds = (short)dt.Millisecond;
            }
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct FILETIME
        {
            public uint DateTimeLow;
            public uint DateTimeHigh;
        }

        [Flags]
        public enum ExtendedFunctions : uint
        {
            CLRBREAK = 9,
            CLRDTR = 6,
            CLRRTS = 4,
            SETBREAK = 8,
            SETDTR = 5,
            SETRTS = 3,
            SETXOFF = 1,
            SETXON = 2
        }

        public delegate bool EnumResTypeProc(IntPtr hModule, string lpszType, IntPtr lParam);

        public struct UFILETIME
        {
            public uint dwLowDateTime;
            public uint dwHighDateTime;
        }

        public delegate bool EnumResNameProcDelegate(IntPtr hModule, IntPtr lpszType, IntPtr lpszName, IntPtr lParam);
        public enum ResType
        {
            CURSOR = 1,
            BITMAP = 2,
            ICON = 3,
            MENU = 4,
            DIALOG = 5,
            STRING = 6,
            FONTDIR = 7,
            FONT = 8,
            ACCELERATOR = 9,
            RCDATA = 10,
            MESSAGETABLE = 11,
            GROUP_CURSOR = 12,
            GROUP_ICON = 14,
            VERSION = 16,
            DLGINCLUDE = 17,
            PLUGPLAY = 19,
            VXD = 20,
            ANICURSOR = 21,
            ANIICON = 22,
            HTML = 23,
            MANIFEST = 24
        }

        public enum COMPUTER_NAME_TYPE
        {
            PrimaryComputerName,
            AlternateComputerNames,
            AllComputerNames
        }

        [Flags]
        public enum SnapshotFlags : uint
        {
            HeapList = 0x00000001,
            Process = 0x00000002,
            Thread = 0x00000004,
            Module = 0x00000008,
            Module32 = 0x00000010,
            Inherit = 0x80000000,
            All = 0x0000001F
        }


        public delegate void WaitOrTimerDelegate (IntPtr lpParameter, bool TimerOrWaitFired);
        public enum ExecuteFlags
        {
            WT_EXECUTEDEFAULT = 0x00000000,
            WT_EXECUTEINTIMERTHREAD = 0x00000020,
            WT_EXECUTEINIOTHREAD = 0x00000001,
            WT_EXECUTEINPERSISTENTTHREAD = 0x00000080,
            WT_EXECUTELONGFUNCTION = 0x00000010,
            WT_EXECUTEONLYONCE = 0x00000008,
            WT_TRANSFER_IMPERSONATION = 0x00000100
        }

        public enum SYMBOLIC_LINK_FLAG
        {
            File = 0,
            Directory = 1
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct PROCESS_INFORMATION
        {
            public IntPtr hProcess;
            public IntPtr hThread;
            public int dwProcessId;
            public int dwThreadId;
        }

        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Unicode)]
        public struct STARTUPINFO
        {
            public Int32 cb;
            public string lpReserved;
            public string lpDesktop;
            public string lpTitle;
            public Int32 dwX;
            public Int32 dwY;
            public Int32 dwXSize;
            public Int32 dwYSize;
            public Int32 dwXCountChars;
            public Int32 dwYCountChars;
            public Int32 dwFillAttribute;
            public Int32 dwFlags;
            public Int16 wShowWindow;
            public Int16 cbReserved2;
            public IntPtr lpReserved2;
            public IntPtr hStdInput;
            public IntPtr hStdOutput;
            public IntPtr hStdError;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct SECURITY_ATTRIBUTES
        {
            public int nLength;
            public IntPtr lpSecurityDescriptor;
            public int bInheritHandle;
        }

        [Flags]
        public enum FileMapProtection : uint
        {
            PageReadonly = 0x02,
            PageReadWrite = 0x04,
            PageWriteCopy = 0x08,
            PageExecuteRead = 0x20,
            PageExecuteReadWrite = 0x40,
            SectionCommit = 0x8000000,
            SectionImage = 0x1000000,
            SectionNoCache = 0x10000000,
            SectionReserve = 0x4000000,
        }

        public struct ACTCTX
        {
            long cbSize;
            int dwFlags;
            string lpSource;
            short wProcessorArchitecture;
            short wLangId;
            string lpAssemblyDirectory;
            string lpResourceName;
            string lpApplicationName;
            int hModule;
        }

        public delegate CopyProgressResult CopyProgressRoutine(
                                    long TotalFileSize,
                                    long TotalBytesTransferred,
                                    long StreamSize,
                                    long StreamBytesTransferred,
                                    uint dwStreamNumber,
                                    CopyProgressCallbackReason dwCallbackReason,
                                    IntPtr hSourceFile,
                                    IntPtr hDestinationFile,
                                    IntPtr lpData);

        public enum CopyProgressResult : uint
        {
            PROGRESS_CONTINUE = 0,
            PROGRESS_CANCEL = 1,
            PROGRESS_STOP = 2,
            PROGRESS_QUIET = 3
        }

        public enum CopyProgressCallbackReason : uint
        {
            CALLBACK_CHUNK_FINISHED = 0x00000000,
            CALLBACK_STREAM_SWITCH = 0x00000001
        }

        [Flags]
        public enum CopyFileFlags : uint
        {
            COPY_FILE_FAIL_IF_EXISTS = 0x00000001,
            COPY_FILE_RESTARTABLE = 0x00000002,
            COPY_FILE_OPEN_SOURCE_FOR_WRITE = 0x00000004,
            COPY_FILE_ALLOW_DECRYPTED_DESTINATION = 0x00000008,
            COPY_FILE_COPY_SYMLINK = 0x00000800 //NT 6.0+
        }

        public struct COMMCONFIG
        {
          int dwSize;
          short  wVersion;
          short  wReserved;
          DCB   dcb;
          int dwProviderSubType;
          int dwProviderOffset;
          int dwProviderSize;
          char wcProviderData;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct COMSTAT
        {
            public const uint fCtsHold = 0x1;
            public const uint fDsrHold = 0x2;
            public const uint fRlsdHold = 0x4;
            public const uint fXoffHold = 0x8;
            public const uint fXoffSent = 0x10;
            public const uint fEof = 0x20;
            public const uint fTxim = 0x40;
            public UInt32 Flags;
            public UInt32 cbInQue;
            public UInt32 cbOutQue;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct DEBUG_EVENT
        {
            public uint dwDebugEventCode;
            public uint dwProcessId;
            public uint dwThreadId;
            public Union u;
        }

        [StructLayout(LayoutKind.Explicit)]
        public struct Union
        {
            [FieldOffset(0)]
            public EXCEPTION_DEBUG_INFO Exception;
            [FieldOffset(0)]
            public CREATE_THREAD_DEBUG_INFO CreateThread;
            [FieldOffset(0)]
            public CREATE_PROCESS_DEBUG_INFO CreateProcessInfo;
            [FieldOffset(0)]
            public EXIT_THREAD_DEBUG_INFO ExitThread;
            [FieldOffset(0)]
            public EXIT_PROCESS_DEBUG_INFO ExitProcess;
            [FieldOffset(0)]
            public LOAD_DLL_DEBUG_INFO LoadDll;
            [FieldOffset(0)]
            public UNLOAD_DLL_DEBUG_INFO UnloadDll;
            [FieldOffset(0)]
            public OUTPUT_DEBUG_STRING_INFO DebugString;
            [FieldOffset(0)]
            public RIP_INFO RipInfo;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct EXCEPTION_DEBUG_INFO
        {
            public EXCEPTION_RECORD ExceptionRecord;
            public uint dwFirstChance;
        }

        public delegate uint PTHREAD_START_ROUTINE(IntPtr lpThreadParameter);

        [StructLayout(LayoutKind.Sequential)]
        public struct CREATE_THREAD_DEBUG_INFO
        {
            public IntPtr hThread;
            public IntPtr lpThreadLocalBase;
            public PTHREAD_START_ROUTINE lpStartAddress;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct CREATE_PROCESS_DEBUG_INFO
        {
            public IntPtr hFile;
            public IntPtr hProcess;
            public IntPtr hThread;
            public IntPtr lpBaseOfImage;
            public uint dwDebugInfoFileOffset;
            public uint nDebugInfoSize;
            public IntPtr lpThreadLocalBase;
            public PTHREAD_START_ROUTINE lpStartAddress;
            public IntPtr lpImageName;
            public ushort fUnicode;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct EXCEPTION_RECORD
        {
            public uint ExceptionCode;
            public uint ExceptionFlags;
            public IntPtr ExceptionRecord;
            public IntPtr ExceptionAddress;
            public uint NumberParameters;
            [MarshalAs(UnmanagedType.ByValArray, SizeConst = 15, ArraySubType = UnmanagedType.U4)]
            public uint[] ExceptionInformation;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct EXIT_THREAD_DEBUG_INFO
        {
            public uint dwExitCode;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct OUTPUT_DEBUG_STRING_INFO
        {
            [MarshalAs(UnmanagedType.LPStr)]
            public string lpDebugStringData;
            public ushort fUnicode;
            public ushort nDebugStringLength;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct RIP_INFO
        {
            public uint dwError;
            public uint dwType;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct UNLOAD_DLL_DEBUG_INFO
        {
            public IntPtr lpBaseOfDll;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct LOAD_DLL_DEBUG_INFO
        {
            public IntPtr hFile;
            public IntPtr lpBaseOfDll;
            public uint dwDebugInfoFileOffset;
            public uint nDebugInfoSize;
            public IntPtr lpImageName;
            public ushort fUnicode;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct EXIT_PROCESS_DEBUG_INFO
        {
            public uint dwExitCode;
        }



        public struct DCB
        {
          int DCBlength;
          int BaudRate;
          int fBinary;
          int fParity;
          int fOutxCtsFlow;
          int fOutxDsrFlow;
          int fDtrControl;
          int fDsrSensitivity;
          int fTXContinueOnXoff;
          int fOutX;
          int fInX;
          int fErrorChar;
          int fNull;
          int fRtsControl;
          int fAbortOnError;
          int fDummy2;
          short  wReserved;
          short  XonLim;
          short  XoffLim;
          byte  ByteSize;
          byte  Parity;
          byte  StopBits;
          char  XonChar;
          char  XoffChar;
          char  ErrorChar;
          char  EofChar;
          char  EvtChar;
          short  wReserved1;
        }

        public struct COMMTIMEOUTS
        {
            int ReadIntervalTimeout;
            int ReadTotalTimeoutMultiplier;
            int ReadTotalTimeoutConstant;
            int WriteTotalTimeoutMultiplier;
            int WriteTotalTimeoutConstant;
        }


        [StructLayout(LayoutKind.Sequential)]
        public struct OSVERSIONINFOEX
        {
            public uint dwOSVersionInfoSize;
            public uint dwMajorVersion;
            public uint dwMinorVersion;
            public uint dwBuildNumber;
            public uint dwPlatformId;
            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = 128)]
            public string szCSDVersion;
            public UInt16 wServicePackMajor;
            public UInt16 wServicePackMinor;
            public UInt16 wSuiteMask;
            public byte wProductType;
            public byte wReserved;
        }
    }
}
