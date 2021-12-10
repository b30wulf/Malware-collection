using System.IO;
using uNet2.Packet;

namespace uRAT.CoreClientPlugin.BasicSystemInformation.Packets
{
    public class SystemInformationInitPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 0; }
        }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
        }

        public void DeserializeFrom(Stream stream)
        {
        }
    }
}
