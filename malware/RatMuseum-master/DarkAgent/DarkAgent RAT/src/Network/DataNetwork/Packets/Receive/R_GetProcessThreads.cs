using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_GetProcessThreads : ReceiveBasePacket
    {
        public R_GetProcessThreads(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        ProcessThreadInfo threadinfo = new ProcessThreadInfo();
        public override void Read()
        {
            threadinfo.PID = ReadInteger();
            threadinfo.ID = ReadInteger();
            threadinfo.Pritioity = ReadByte();
            threadinfo.WaitReason = ReadString();
            threadinfo.PriorityBoost = ReadByte();
            threadinfo.PrivilegedProcessorTime = ReadString();
            threadinfo.StartTime = ReadString();
            threadinfo.Threadstate = ReadString();
        }

        public override void Run()
        {
            GetProcessesThreadEventArgs e1 = new GetProcessesThreadEventArgs(threadinfo, Client.RemoteEndPoint.ToString());
            GetProcessesThreadEvent.OnGetProcessesThread(e1);
            threadinfo = null; //clean memory
        }
    }
}
