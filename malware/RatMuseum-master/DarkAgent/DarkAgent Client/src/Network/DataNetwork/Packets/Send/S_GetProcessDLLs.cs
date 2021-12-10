using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Network;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_GetProcessDLLs : SendBasePacket
    {
        ProcessDllInfo inf;
        public S_GetProcessDLLs(ClientConnect client, ProcessDllInfo info)
            : base(client)
        {
            inf = info;
        }

        protected internal override void Write()
        {
            WriteByte(2);
            WriteInteger(inf.PID);
            WriteString(inf.FileName);
            WriteString(inf.ModuleName);
            WriteString(inf.BaseAddress);
            WriteString(inf.EntryPointAddress);
            WriteString(inf.ModuleMemorySize);
        }
    }
}
