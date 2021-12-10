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
    public partial class SocketManagerForm : Form
    {
        public SocketManagerForm()
        {
            InitializeComponent();
        }

        private void addSocketToolStripMenuItem_Click(object sender, EventArgs e)
        {
            var addSocketFrm = new AddSocketForm(this);
            addSocketFrm.Show();
        }

        private void SocketManager_Load(object sender, EventArgs e)
        {
            foreach (var srv in Globals.ServerPool.ActiveServers)
            {
                var lvItm = new ListViewItem(srv.Key.ToString());
                lvItm.SubItems.Add(srv.Value.GetMainChannel().Port.ToString());
                lvItm.SubItems.Add("Listening");
                listView1.Items.Add(lvItm);
            }
        }
    }
}
