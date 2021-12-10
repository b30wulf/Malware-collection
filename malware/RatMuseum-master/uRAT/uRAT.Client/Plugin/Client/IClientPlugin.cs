using System.Collections.Generic;
using uNet2.Packet;
using uNet2.Packet.Events;
using uNet2.SocketOperation;

namespace uRAT.Client.Plugin.Client
{
    public interface IClientPlugin
    {
        List<IPacket> PluginPackets { get; }
        List<ISocketOperation> SocketOperations { get; }
        void Initialize();
        void OnClientConnected();
        void OnPacketReceived(object sender, ClientPacketEventArgs e);
    }
}
