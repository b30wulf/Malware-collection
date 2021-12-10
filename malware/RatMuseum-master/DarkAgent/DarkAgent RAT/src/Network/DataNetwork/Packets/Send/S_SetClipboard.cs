using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_SetClipboard : SendBasePacket
    {
        string data = null;

        public S_SetClipboard(RatClients client, string data)
            : base(client)
        {
            this.data = data;
        }
        protected internal override void Write()
        {
            WriteByte(0x24);
            WriteString(data);
        }
    }
}
