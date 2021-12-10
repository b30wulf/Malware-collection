using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Events
{
    public delegate void GetProcessesDLLHandler(object o, GetProcessesDLLEventArgs e);

    public class GetProcessesDLLEventArgs : EventArgs
    {
        public readonly ProcessDllInfo _processDllInfo;
        public readonly string RemoteIP;

        public GetProcessesDLLEventArgs(ProcessDllInfo processDllInfo, string ip)
        {
            this._processDllInfo = processDllInfo;
            this.RemoteIP = ip;
        }
    }

    public class GetProcessesDLLEvent
    {
        public static event GetProcessesDLLHandler ClientProcessesDLL;
        public static void OnGetProcessesDLL(GetProcessesDLLEventArgs e)
        {
            if (ClientProcessesDLL != null)
                ClientProcessesDLL(new object(), e);
        }
    }
}
