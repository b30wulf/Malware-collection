using System.Collections.Generic;
using System.IO;
using uNet2.Packet;

namespace uRAT.Server.Plugin.Synchronization.Packets
{
    internal class FetchPluginMetadataPacket : IDataPacket
    {
        public int PacketId
        {
            get { return -1; }
        }

        public List<PluginIntegrityPair> PluginList { get; set; }

        public FetchPluginMetadataPacket()
        {
            PluginList = new List<PluginIntegrityPair>();
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
            var count = br.ReadInt32();
            for (var i = 0; i < count; i++)
                PluginList.Add(new PluginIntegrityPair(new System.Guid(br.ReadBytes(16)), br.ReadBytes(32)));
            
        }
    }
}
