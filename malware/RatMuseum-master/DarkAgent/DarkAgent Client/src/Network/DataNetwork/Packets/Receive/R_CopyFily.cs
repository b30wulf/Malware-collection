using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_CopyFily : ReceiveBasePacket
    {
        public R_CopyFily(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string FileLocation;
        private string NewFileLocation;

        public override void Read()
        {
            FileLocation = ReadString();
            NewFileLocation = ReadString();
        }

        public override void Run()
        {
            FileInfo info = new FileInfo(FileLocation);
            if (info.Exists)
            {
                try
                {

                    File.Copy(FileLocation, NewFileLocation + info.Name, true);
                }
                catch { }
            }
        }
    }
}
