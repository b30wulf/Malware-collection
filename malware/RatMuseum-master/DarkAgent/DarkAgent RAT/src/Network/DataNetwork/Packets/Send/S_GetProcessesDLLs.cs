using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_GetProcessesDLLs : SendBasePacket
    {
        private int PID;
        public S_GetProcessesDLLs(RatClients client, int PID)
            : base(client)
        {
            this.PID = PID;
        }

        protected internal override void Write()
        {
            WriteByte(8);
            WriteInteger(PID);
        }
    }
}
