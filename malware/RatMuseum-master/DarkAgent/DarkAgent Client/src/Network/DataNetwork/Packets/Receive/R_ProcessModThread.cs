using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_ProcessModThread : ReceiveBasePacket
    {
        public R_ProcessModThread(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private int PID;
        private int ID;
        private byte type;

        public override void Read()
        {
            PID = ReadInteger();
            ID = ReadInteger();
            type = ReadByte();
        }

        public override void Run()
        {
            try
            {
                Process proc = Process.GetProcessById(PID);
                foreach (ProcessThread thread in proc.Threads)
                {
                    if (ID == thread.Id)
                    {
                        IntPtr hThread = IntPtr.Zero;
                        if(type == 0 || type == 1)
                            hThread = APIs.OpenThread(APIs.ThreadAccess.SUSPEND_RESUME, false, Convert.ToUInt32(thread.Id));
                        else if(type == 2)
                            hThread = APIs.OpenThread(APIs.ThreadAccess.TERMINATE, false, Convert.ToUInt32(thread.Id));

                        if (hThread == IntPtr.Zero)
                            break;

                        switch(type)
                        {
                            case 0:
                                APIs.SuspendThread(hThread);
                                break;
                            case 1:
                                APIs.ResumeThread(hThread);
                                break;
                            case 2:
                                APIs.TerminateThread(hThread, 0);
                                break;
                        }
                    }
                }
            }catch{}
        }
    }
}
