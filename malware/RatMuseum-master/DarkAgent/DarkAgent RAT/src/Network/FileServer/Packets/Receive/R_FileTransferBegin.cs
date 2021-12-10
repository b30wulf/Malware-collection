using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Network.FileServer.Packets.Receive
{
    class R_FileTransferBegin : ReceiveBasePacket
    {
        public R_FileTransferBegin(FileClients client, byte[] packet)
            : base(client, packet)
        {
        }

        private FileTransfer info = new FileTransfer();

        public override void Read()
        {
            info.Id = ReadShort();
            info.FileName = ReadString();
            info.Destination = ReadString();
            info.FileSize = ReadLong();
            info.type = ReadShort();
        }

        public override void Run()
        {
            if (!Client._FileTransfer.ContainsKey(info.Id))
                Client._FileTransfer.Add(info.Id, info);
        }
    }
}
