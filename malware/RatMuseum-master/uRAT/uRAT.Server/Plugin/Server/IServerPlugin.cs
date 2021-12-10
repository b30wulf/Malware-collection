using System.Collections.Generic;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;

namespace uRAT.Server.Plugin.Server
{
    public interface IServerPlugin
    {
        string MenuTitle { get; }
        List<IPacket> PluginPackets { get; }
        void Initialize();
        void OnPeerConnected(ChannelEventArgs e);
        void OnPeerDisconnected(ChannelEventArgs e);
        void OnPacketReceived(ServerPacketEventArgs e);
    }
}
