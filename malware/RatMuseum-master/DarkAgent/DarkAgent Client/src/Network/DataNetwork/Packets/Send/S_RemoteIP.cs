using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_RemoteIP : SendBasePacket
    {
        string remoteIP;
        public S_RemoteIP(ClientConnect client, string remoteIP)
            : base(client)
        {
            this.remoteIP = remoteIP;
        }

        protected internal override void Write()
        {
            WriteByte(0x11);
            WriteString(remoteIP);
        }
    }
}
