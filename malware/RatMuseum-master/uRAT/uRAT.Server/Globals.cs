using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uNet2.Packet;
using uRAT.Server.Forms;
using uRAT.Server.Plugin;
using uRAT.Server.Tools;

namespace uRAT.Server
{
    internal static class Globals
    {
        public static ServerPool ServerPool { get; set; }
        public static MainForm MainForm { get; set; }
        public static PluginAggregator PluginAggregator { get; set; }
        public static IPacketProcessor PacketProcessor { get; set; }
        public static SettingsHelper SettingsHelper { get; set; }
        public static RemotePluginHandler RemotePluginHandler { get; set; }
    }
}
