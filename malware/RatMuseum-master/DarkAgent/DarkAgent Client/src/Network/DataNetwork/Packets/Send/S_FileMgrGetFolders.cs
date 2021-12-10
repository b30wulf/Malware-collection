using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Objects;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_FileMgrGetFolders : SendBasePacket
    {
        private FileMgrDirInfo info;
        public S_FileMgrGetFolders(ClientConnect client, FileMgrDirInfo info)
            : base(client)
        {
            this.info = info;
        }

        protected internal override void Write()
        {
            WriteByte(0x5);
            WriteString(info.Name);
            WriteString(info.SubDir);
        }
    }
}
