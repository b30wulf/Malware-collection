using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_FileMgrGetDrives : SendBasePacket
    {
        FileMgrDrives inf;
        public S_FileMgrGetDrives(ClientConnect client, FileMgrDrives info)
            : base(client)
        {
            inf = info;
        }

        protected internal override void Write()
        {
            WriteByte(4);
            WriteString(inf.Drive);
            WriteString(inf.Caption);
            WriteString(inf.type);
        }
    }
}
