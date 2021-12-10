using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_CursorPosition : SendBasePacket
    {
        private int x;
        private int y;
        public S_CursorPosition(RatClients client, int x, int y)
            : base(client)
        {
            this.x = x;
            this.y = y;
        }
        protected internal override void Write()
        {
            WriteByte(1);
            WriteInteger(x);
            WriteInteger(y);
        }
    }
}
