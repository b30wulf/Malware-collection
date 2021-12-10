using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;
using uRAT.CorePlugin.ConnectionPing.Packets;
using uRAT.Server.Plugin.Server;
using uRAT.Server.Plugin.UIService;
using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.CorePlugin.ConnectionPing
{
    public class ConnectionPingPlugin : IServerPlugin
    {
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
                    new ConnectionPingPacket()
                };
            }
        }

        public void Initialize()
        {
            var tsItm = new ToolStripMenuItem("Ping client(s)");
            tsItm.Click += tsItm_Click;
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            connectionList.AddContextMenuItem(tsItm);
        }

        void tsItm_Click(object sender, EventArgs e)
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            connectionList.GetSelectedItems().ForEach(itm =>
            {
                itm.AssociatedChannel.Send(new ConnectionPingPacket(), itm.AssociatedPeer.Identity.Guid);
            });
        }

        public void OnPeerConnected(ChannelEventArgs e)
        {

        }

        public void OnPeerDisconnected(ChannelEventArgs e2)
        {

        }

        public void OnPacketReceived(ServerPacketEventArgs e)
        {
            if (e.Packet is ConnectionPingPacket)
            {
                var diff =
                    (DateTime.Now - DateTime.FromBinary((e.Packet as ConnectionPingPacket).Timestamp)).Milliseconds;
                var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
                connectionList.FindItem(itm => itm.AssociatedPeer.Identity.Equals(e.Peer.Identity)).SubItems[5].Text =
                    diff + "ms";
            }
        }
    }
}
