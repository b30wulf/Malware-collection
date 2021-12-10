using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_ProcessWindowText : SendBasePacket
    {
        private int PID;
        private string WindowText;
        public S_ProcessWindowText(RatClients client, int PID, string text)
            : base(client)
        {
            this.PID = PID;
            this.WindowText = text;
        }

        protected internal override void Write()
        {
            WriteByte(7);
            WriteInteger(PID);
            WriteString(WindowText);
        }
    }
}
