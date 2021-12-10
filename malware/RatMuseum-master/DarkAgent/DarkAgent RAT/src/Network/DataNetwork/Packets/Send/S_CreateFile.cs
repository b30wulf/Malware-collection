using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_CreateFile : SendBasePacket
    {
        private string FileLocation;
        private string FileName;

        public S_CreateFile(RatClients client, string FileLocation, string FileName)
            : base(client)
        {
            this.FileLocation = FileLocation;
            this.FileName = FileName;
        }
        protected internal override void Write()
        {
            WriteByte(0x14);
            WriteString(FileLocation);
            WriteString(FileName);
        }
    }
}
