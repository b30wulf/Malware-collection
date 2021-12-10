using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.CorePlugin.ConnectionPing.Packets
{
    class ConnectionPingPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 3; }
        }

        public long Timestamp { get; set; }

        public void SerializeTo(Stream stream)
        {
            Timestamp = DateTime.Now.ToBinary();

            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(Timestamp);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            Timestamp = br.ReadInt64();
        }
    }
}
