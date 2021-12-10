using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_RemoteIP : ReceiveBasePacket
    {
        public R_RemoteIP(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        string IPinfo;
        public override void Read()
        {
            IPinfo = ReadString();
        }

        public override void Run()
        {
            Client.FileServerRemoteIP = IPinfo;
        }
    }
}
