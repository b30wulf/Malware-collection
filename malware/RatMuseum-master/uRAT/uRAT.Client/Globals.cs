using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uNet2;
using uRAT.Client.Plugin;

namespace uRAT.Client
{
    public static class Globals
    {
        public static UNetClient Client { get; set; }
        public static UNetClient Client2 { get; set; }
#if DEBUG
        public static PluginAggregator PluginAggregator { get; set; }
#endif
    }
}
