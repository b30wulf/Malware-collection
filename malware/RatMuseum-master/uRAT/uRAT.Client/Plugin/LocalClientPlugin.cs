using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace uRAT.Client.Plugin
{
    public class BlobClientPlugin
    {
        public Guid PluginGuid { get; set; }
        public int Offset { get; set; }
        public int Size { get; set; }
        public byte[] Data { get; set; }
        public byte[] Hash { get; set; }

        public BlobClientPlugin()
        {
        }
    }
}
