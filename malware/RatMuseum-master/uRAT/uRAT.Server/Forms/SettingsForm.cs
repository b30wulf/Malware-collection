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
    public partial class SettingsForm : Form
    {
        public SettingsForm()
        {
            InitializeComponent();
        }

        private void treeView1_AfterSelect(object sender, TreeViewEventArgs e)
        {
            switch ((string)e.Node.Tag)
            {
                case "OnLoad":
                    OnLoadPanel.BringToFront();
                    break;
            }
        }
    }
}
