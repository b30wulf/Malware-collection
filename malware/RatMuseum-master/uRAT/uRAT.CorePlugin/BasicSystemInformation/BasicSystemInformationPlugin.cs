using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows.Forms;
using uNet2.Channel;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;
using uRAT.CorePlugin.BasicSystemInformation.Operations;
using uRAT.CorePlugin.BasicSystemInformation.Packets;
using uRAT.CorePlugin.ConnectionPing.Packets;
using uRAT.Server.Controls;
using uRAT.Server.Plugin.Server;
using uRAT.Server.Plugin.UIService;
using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.CorePlugin.BasicSystemInformation
{
    public class BasicSystemInformationPlugin : IServerPlugin
    {
        private ConnectionListService _connectionList;

        public string MenuTitle
        {
            get { return null; }
        }

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

        public void Initialize()
        {
          
        }

        public void OnPeerConnected(ChannelEventArgs e)
        {
            var op = e.Channel.CreateOperation<BasicSystemInformationOperation>(e.Peer.Identity.Guid);
            op.OnPacketReceived += OperationPacketReceived;
            op.SendPacket(new SystemInformationInitPacket());
        }

        void OperationPacketReceived(object sender, uNet2.Packet.Events.OperationPacketEventArgs e)
        {
            if (e.Packet is SystemInformationPacket)
            {
                var packet = e.Packet as SystemInformationPacket;
                var peer =
                    (e.Channel as TcpServerChannel).ConnectedPeers.FirstOrDefault(
                        p => p.Identity.Guid == e.Operation.ConnectionGuid);

                _connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
                var lvItm = new ConnectionListItem(peer, e.Channel as TcpServerChannel);
                lvItm.Text = peer.Endpoint.ToString();
                lvItm.SubItems.Add(packet.ComputerName);
                lvItm.SubItems.Add(packet.OperatingSystem);
                lvItm.SubItems.Add(packet.CountryCode);
                lvItm.SubItems.Add(packet.IsAdmin);
                lvItm.SubItems.Add(Math.Truncate(peer.PingDelay) + " ms");
                _connectionList.AddItem(lvItm);
            }
            e.Operation.CloseOperation();
        }

        public void OnPeerDisconnected(ChannelEventArgs e)
        {
            _connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            if (_connectionList != null)
            {
                var item = _connectionList.FindItem(itm => itm != null && Equals(itm.AssociatedPeer.Identity, e.Peer.Identity));
                _connectionList.RemoveItem(item);
            }
        }

        public void OnPacketReceived(ServerPacketEventArgs e)
        {
            
        }
    }
}