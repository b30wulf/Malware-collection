using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_KillProcess : SendBasePacket
    {
        private byte Force;
        private byte DeleteFile;
        private int PID;
        public S_KillProcess(RatClients client, int PID, byte Force, byte DeleteFile)
            : base(client)
        {
            this.PID = PID;
            this.Force = Force;
            this.DeleteFile = DeleteFile;
        }

        protected internal override void Write()
        {
            WriteByte(4);
            WriteInteger(PID);
            WriteByte(Force);
            WriteByte(DeleteFile);
        }
    }
}
