using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_GetProcessDLLs : ReceiveBasePacket
    {
        public R_GetProcessDLLs(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        ProcessDllInfo DLL = new ProcessDllInfo();
        public override void Read()
        {
            DLL.PID = ReadInteger();
            DLL.FileName = ReadString();
            DLL.ModuleName = ReadString();
            DLL.BaseAddress = ReadString();
            DLL.EntryPointAddress = ReadString();
            DLL.ModuleMemorySize = ReadString();
        }

        public override void Run()
        {
            GetProcessesDLLEventArgs e1 = new GetProcessesDLLEventArgs(DLL, Client.RemoteEndPoint.ToString());
            GetProcessesDLLEvent.OnGetProcessesDLL(e1);
            DLL = null; //clean memory
        }
    }
}
