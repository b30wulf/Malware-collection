using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using System.Management;
using System.Windows.Forms;
using DarkAgent_Client.src.Engines;
using DarkAgent_Client.src.Utils;

namespace DarkAgent_Client.src.Features
{
    class AntiDebug
    {
        public static bool CheckParentProcess()
        {
            if(!Settings.SystemProcess_CheckParentProcess)
                return true;

            using (ManagementObject mo = new ManagementObject("win32_process.handle='" + Process.GetCurrentProcess().Id.ToString() + "'"))
            {
                mo.Get();
                if (Process.GetProcessById(Convert.ToInt32(mo["ParentProcessId"])).ProcessName.ToLower() != "explorer")
                    return false;
                return true;
            }
        }
    }
}