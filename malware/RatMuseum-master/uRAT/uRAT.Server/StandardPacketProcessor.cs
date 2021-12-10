using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using uNet2.Packet;

namespace uRAT.Server
{
    public class StandardPacketProcessor : IPacketProcessor
    {
        public Dictionary<int, Type> PacketTable {get;set;}

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
