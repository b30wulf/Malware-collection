using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;
using System.Runtime.InteropServices;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_ResumeProcess : ReceiveBasePacket
    {
        public R_ResumeProcess(ClientConnect client, byte[] packet)
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
            ResumeProcess(PID);
        }

        public void ResumeProcess(int PID)
        {
            try
            {
                Process processById = Process.GetProcessById(PID);
                if (processById.ProcessName != string.Empty)
                {
                    foreach (ProcessThread thread in processById.Threads)
                    {
                        IntPtr hThread = APIs.OpenThread(APIs.ThreadAccess.SUSPEND_RESUME, false, Convert.ToUInt32(thread.Id));
                        if (hThread == IntPtr.Zero)
                            break;
                        APIs.ResumeThread(hThread);
                    }
                }
            }catch{}
        }
    }
}
