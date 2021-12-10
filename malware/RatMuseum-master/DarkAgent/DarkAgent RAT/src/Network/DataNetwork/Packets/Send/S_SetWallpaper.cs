using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_SetWallpaper : SendBasePacket
    {
        private string FileLocation;
        public S_SetWallpaper(RatClients client, string FileLocation)
            : base(client)
        {
            this.FileLocation = FileLocation;
        }
        protected internal override void Write()
        {
            WriteByte(0x23);
            WriteString(FileLocation);
        }
    }
}
