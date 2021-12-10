using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_GetProcesses : SendBasePacket
    {
        public S_GetProcesses(RatClients client)
            : base(client)
        {
        }
        protected internal override void Write()
        {
            WriteByte(3);
        }
    }
}
