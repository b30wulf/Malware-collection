using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Events
{
    public delegate void CpuInfoHandler(object o, CpuInfoEventArgs e);

    public class CpuInfoEventArgs : EventArgs
    {
        public readonly string RemoteIP;
        public readonly CpuInfo cpuInfo;
        public CpuInfoEventArgs(string RemoteIP, CpuInfo info)
        {
            this.RemoteIP = RemoteIP;
            this.cpuInfo = info;
        }
    }

    public class CpuInfoEvent
    {
        public static event CpuInfoHandler CpuInfo;
        public static void OnCpuInfo(CpuInfoEventArgs e)
        {
            if (CpuInfo != null)
                CpuInfo(new object(), e);
        }
    }
}
