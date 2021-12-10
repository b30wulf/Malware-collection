using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_GetCpuInfo : SendBasePacket
    {
       public S_GetCpuInfo(RatClients client)
            : base(client)
        {
        }
        protected internal override void Write()
        {
            WriteByte(0x16);
        }
    }
}
