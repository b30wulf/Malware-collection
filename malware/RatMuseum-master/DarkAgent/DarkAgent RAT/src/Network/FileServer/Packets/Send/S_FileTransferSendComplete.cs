using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Network.FileServer.Packets.Send
{
    class S_FileTransferSendComplete : SendBasePacket
    {
        private FileTransfer info;
        public S_FileTransferSendComplete(FileClients client, FileTransfer info)
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
