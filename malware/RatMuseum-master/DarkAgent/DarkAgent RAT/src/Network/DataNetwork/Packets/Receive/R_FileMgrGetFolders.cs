using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_FileMgrGetFolders : ReceiveBasePacket
    {
        public R_FileMgrGetFolders(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        FileMgrDirInfo info = new FileMgrDirInfo();
        public override void Read()
        {
            info.Name = ReadString();
            info.SubDir = ReadString();
        }

        public override void Run()
        {
            FileMgrDirEventArgs e1 = new FileMgrDirEventArgs(info, Client.RemoteEndPoint.ToString());
            FileMgrDirEvent.OnFileMgrDir(e1);
            info = null; //clean memory
        }
    }
}
