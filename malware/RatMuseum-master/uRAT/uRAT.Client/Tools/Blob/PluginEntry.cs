using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace uRAT.Client.Tools.Blob
{
    internal class PluginEntry
    {
        public Guid PluginGuid { get; set; }
        public byte[] IntegrityHash { get; set; }
        public byte[] Data { get; set; }

        public PluginEntry(Guid pluginGuid, byte[] integrityHash, byte[] data)
        {
            PluginGuid = pluginGuid;
            IntegrityHash = integrityHash;
            Data = data;
        }
    }
}
