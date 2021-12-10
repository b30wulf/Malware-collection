using System;
using System.Collections.Generic;
using System.Text;
using System.Drawing;
using DarkAgent_Client.src.Objects;
using System.IO;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Receive
{
    class R_SetWallpaper : ReceiveBasePacket
    {
        public R_SetWallpaper(ClientConnect client, byte[] packet)
            : base(client, packet)
        {
        }

        private string FileLocation;
        public override void Read()
        {
            FileLocation = ReadString();
        }

        public override void Run()
        {
            FileInfo info = new FileInfo(FileLocation);
            if (info.Exists)
                SetWallpaper(FileLocation);
        }

        public void SetWallpaper(string Wallpaper)
        {
            if (Wallpaper.ToLower().EndsWith(".jpg") || Wallpaper.ToLower().EndsWith(".bmp") ||
                Wallpaper.ToLower().EndsWith(".png") || Wallpaper.ToLower().EndsWith(".jpeg") ||
                Wallpaper.ToLower().EndsWith(".jpe") || Wallpaper.ToLower().EndsWith(".gif"))
            {
                try
                {
                    Image Background;
                    Background = Image.FromFile(Wallpaper);
                    string Location = Environment.SystemDirectory + "\\CurrentWallpaper.Bmp";
                    Background.Save(Location, System.Drawing.Imaging.ImageFormat.Bmp);
                    APIs.SystemParametersInfo(0x14, 0, Location, 0x1 | 0x2);
                    GC.Collect();
                }
                catch
                {
                }
            }
        }
    }
}