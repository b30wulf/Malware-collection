using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.Client.Plugin.Synchronization.Packets
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
            bw.Write(PluginList.Count);
            foreach (var plugin in PluginList)
            {
                bw.Write(plugin.PluginGuid.ToByteArray());
                bw.Write(plugin.IntegrityHash);
            }
        }

        public void DeserializeFrom(Stream stream)
        {
            
        }
    }
}
