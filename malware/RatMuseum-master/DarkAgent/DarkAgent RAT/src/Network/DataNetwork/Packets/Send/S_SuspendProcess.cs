using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_SuspendProcess : SendBasePacket
    {
        private int PID;
        public S_SuspendProcess(RatClients client, int PID)
            : base(client)
        {
            this.PID = PID;
        }

        protected internal override void Write()
        {
            WriteByte(5);
            WriteInteger(PID);
        }
    }
}
