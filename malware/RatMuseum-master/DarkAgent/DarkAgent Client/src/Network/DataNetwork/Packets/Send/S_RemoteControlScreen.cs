using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Network.DataNetwork.Packets.Send
{
    class S_RemoteControlScreen : SendBasePacket
    {
        byte[] ScreenBytes;
        public S_RemoteControlScreen(ClientConnect client, byte[] ScreenBytes)
            : base(client)
        {
            this.ScreenBytes = ScreenBytes;
        }

        protected internal override void Write()
        {
            WriteByte(0x03);
            WriteShort((short)ScreenBytes.Length);
            WriteBytes(ScreenBytes);
        }
    }
}