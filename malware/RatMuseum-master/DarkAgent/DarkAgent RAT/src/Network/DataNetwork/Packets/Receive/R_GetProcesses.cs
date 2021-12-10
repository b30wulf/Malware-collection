using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;
using DarkAgent_RAT.src.Utils;
using DarkAgent_RAT.src.Events;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_GetProcesses : ReceiveBasePacket
    {
        public R_GetProcesses(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        ProcessInfo process = new ProcessInfo();

        public override void Read()
        {
            process.ProcessName = ReadString();
            process.PID = ReadInteger();
            process.WindowsTitle = ReadString();
            process.Responding = Convert.ToBoolean(ReadByte());
            process.FileLocation = ReadString();
            process.Handle = ReadInteger();
            process.ProcessorAffinity = ReadInteger();
            process.Threads = ReadInteger();

            byte Priority = ReadByte();
            switch (Priority)
            {
                case 4:
                    process.Priority = "Idle";
                    break;
                case 8:
                    process.Priority = "Normal";
                    break;
                case 13:
                    process.Priority = "High";
                    break;
                case 24:
                    process.Priority = "RealTime";
                    break;
                default:
                    process.Priority = "Unknown";
                    break;
            }
        }

        public override void Run()
        {
            ProcessEventArgs e1 = new ProcessEventArgs(process, Client.RemoteEndPoint.ToString());
            ProcessEvent.OnGetProcesses(e1);
            process = null; //clean memory
        }
    }
}