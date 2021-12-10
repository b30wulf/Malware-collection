using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets.Send
{
    class S_FileTransferSendComplete : SendBasePacket
    {
        private FileTransfer info;
        public S_FileTransferSendComplete(FileTransferConnect client, FileTransfer info)
            : base(client)
        {
            this.info = info;
        }
        protected internal override void Write()
        {
            WriteByte(0x02);
            WriteShort(info.Id);
        }
    }
}
