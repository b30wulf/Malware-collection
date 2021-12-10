using System;
using System.Collections.Generic;
using uNet2;
using uNet2.Channel;

namespace uRAT.Server.Tools
{
    public class ServerPool
    {
        public Dictionary<Guid, UNetServer> ActiveServers { get; set; }

        public ServerPool()
        {
            ActiveServers = new Dictionary<Guid, UNetServer>();
        }

        public UNetServer CreateServer()
        {
            var guid = Guid.NewGuid();
            var srv = new UNetServer();
            ActiveServers.Add(guid, srv);
            return ActiveServers[guid];
        }

        public UNetServer CreateServer(int port, out Guid guid)
        {
            guid = Guid.NewGuid();
            var srv = new UNetServer();
            var mainChannel = srv.CreateChannel<TcpServerChannel>();
            mainChannel.PacketProcessor = Globals.PacketProcessor;
            mainChannel.EnsurePacketIntegrity = false;
            mainChannel.Port = (uint)port;
            srv.Initialize(mainChannel);
            ActiveServers.Add(guid, srv);
            return ActiveServers[guid];
        }

    }
}
