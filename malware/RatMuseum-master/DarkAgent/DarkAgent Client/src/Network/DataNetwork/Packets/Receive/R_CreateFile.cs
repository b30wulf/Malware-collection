using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_CreateFile : ReceiveBasePacket
    {
        public R_CreateFile(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string FileLocation;
        private string FileName;

        public override void Read()
        {
            FileLocation = ReadString();
            FileName = ReadString();
        }

        public override void Run()
        {
            FileInfo info = new FileInfo(FileLocation + FileName);
            if (!info.Exists)
            {
                try
                {
                    File.Create(FileLocation + FileName).Close();
                }
                catch { }
            }
        }
    }
}
