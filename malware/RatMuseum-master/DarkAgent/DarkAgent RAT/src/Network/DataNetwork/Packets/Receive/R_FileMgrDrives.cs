using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_FileMgrDrives : ReceiveBasePacket
    {
        public R_FileMgrDrives(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        FileMgrDrives info = new FileMgrDrives();
        public override void Read()
        {
            info.Drive = ReadString();
            info.Caption = ReadString();
            info.type = ReadString();
        }

        public override void Run()
        {
            FileMgrDrivesEventArgs e1 = new FileMgrDrivesEventArgs(info, Client.RemoteEndPoint.ToString());
            FileMgrDrivesEvent.OnFileMgrDrives(e1);
            info = null; //clean memory
        }
    }
}