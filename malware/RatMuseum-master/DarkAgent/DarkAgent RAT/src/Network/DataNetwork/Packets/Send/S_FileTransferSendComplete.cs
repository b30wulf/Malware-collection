using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_FileTransferSendComplete : SendBasePacket
    {
        private FileTransfer info;
        public S_FileTransferSendComplete(RatClients client, FileTransfer info)
            : base(client)
        {
            this.info = info;
        }
        protected internal override void Write()
        {
            WriteByte(0x28);
            WriteShort(info.Id);
        }
    }
}