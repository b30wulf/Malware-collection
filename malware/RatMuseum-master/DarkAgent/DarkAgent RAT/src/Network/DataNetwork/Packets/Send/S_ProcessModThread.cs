using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_ProcessModThread : SendBasePacket
    {
        private int PID;
        private int ID;
        private byte type;
        public S_ProcessModThread(RatClients client, int PID, int ID, byte type)
            : base(client)
        {
            this.PID = PID;
            this.ID = ID;
            this.type = type;
        }
        protected internal override void Write()
        {
            WriteByte(0x10);
            WriteInteger(PID);
            WriteInteger(ID);
            WriteByte(type); //0=suspend, 1=resume, 2=terminate
        }
    }
}
