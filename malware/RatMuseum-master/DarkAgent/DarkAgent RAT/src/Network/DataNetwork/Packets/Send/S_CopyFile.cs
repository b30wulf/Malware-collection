using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_CopyFile : SendBasePacket
    {
        private string FileLocation;
        private string NewFileLocation;

        public S_CopyFile(RatClients client, string FileLocation, string NewFileLocation)
            : base(client)
        {
            this.FileLocation = FileLocation;
            this.NewFileLocation = NewFileLocation;
        }
        protected internal override void Write()
        {
            WriteByte(0x15);
            WriteString(FileLocation);
            WriteString(NewFileLocation);
        }
    }
}
