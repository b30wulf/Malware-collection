using System;
using System.Collections.Generic;
using System.IO;
using uNet2.Packet;

namespace uRAT.Server.Plugin.Synchronization.Packets
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
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(Actions.Count);
            foreach (var action in Actions)
            {
                bw.Write((byte) action.Action);
                bw.Write(action.PluginGuid.ToByteArray());
                bw.Write(action.IntegrityHash);
                bw.Write(action.PluginData.Length);
                bw.Write(action.PluginData);
            }
        }

        public void DeserializeFrom(Stream stream)
        {
           
        }
    }
}
