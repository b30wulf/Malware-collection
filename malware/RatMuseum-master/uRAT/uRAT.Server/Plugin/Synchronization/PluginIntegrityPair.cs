using System;

namespace uRAT.Server.Plugin.Synchronization
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
