using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.FileServer.Packets.Send
{
    class S_RemoteIP : SendBasePacket
    {
        public S_RemoteIP(FileClients client)
            : base(client)
        {
        }

        protected internal override void Write()
        {
            WriteByte(0x04);
            WriteString(Client.RemoteEndPoint.ToString());
        }
    }
}