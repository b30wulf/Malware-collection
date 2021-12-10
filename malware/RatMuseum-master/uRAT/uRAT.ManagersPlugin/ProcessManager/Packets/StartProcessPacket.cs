using System.IO;
using uNet2.Packet;

namespace uRAT.ManagersPlugin.ProcessManager.Packets
{
    class StartProcessPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 7; }
        }

        public string Filename { get; set; }
        public bool NoWindow { get; set; }

        public StartProcessPacket()
        {
        }

        public StartProcessPacket(string filename, bool noWindow)
        {
            Filename = filename;
            NoWindow = noWindow;
        }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(Filename);
            bw.Write(NoWindow);
        }

        public void DeserializeFrom(Stream stream)
        {

        }
    }
}
