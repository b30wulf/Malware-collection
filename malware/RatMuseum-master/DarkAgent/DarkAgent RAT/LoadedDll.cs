using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using DarkAgent_RAT.src.Events;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using DarkAgent_RAT.src.Utils;

namespace DarkAgent_RAT
{
    public partial class LoadedDll : DevComponents.DotNetBar.Office2007Form
    {
        public LoadedDll()
        {
            InitializeComponent();
        }

        public string RemoteIP;
        public string ClientName;
        public int ClientID;
        public string ProcessName;
        public int PID;

        #region Specific Events

        public void onProcessDllInfo(object o, GetProcessesDLLEventArgs e)
        {
            try
            {
                if (e.RemoteIP != RemoteIP || PID != e._processDllInfo.PID)
                    return;

                string[] str = new string[5];
                str[0] = e._processDllInfo.FileName;
                str[1] = e._processDllInfo.ModuleName;
                str[2] = e._processDllInfo.BaseAddress;
                str[3] = e._processDllInfo.EntryPointAddress;
                str[4] = e._processDllInfo.ModuleMemorySize;
                ListViewItem itm = new ListViewItem(str, 0);
                listView1.Items.Add(itm);
            }catch{/* Key already exists at XX, networking is too fast ? lol */}
        }

        public void onClientDisconnect(object o, ClientDisconnectEventArgs e)
        {
            if (RemoteIP == e.RemoteIP)
            {
                MessageBox.Show(ClientName + " has been disconnected from the server");
                this.Close();
            }
        }

        #endregion

        #region Form Events

        private void LoadedDll_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - DLL Viewer [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "] [" + ProcessName + "]";
            GetProcessesDLLEvent.ClientProcessesDLL += new GetProcessesDLLHandler(onProcessDllInfo);
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);

            //send packet...
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetProcessesDLLs(RatClientProcessor.Instance._clientList[ClientID], PID));
        }

        private void listView1_ColumnClick(object sender, ColumnClickEventArgs e)
        {
            switch (e.Column)
            {
                case 0:
                    listView1.ListViewItemSorter = new ColumnSorter(0);
                    listView1.Sort();
                    break;
                case 1:
                    listView1.ListViewItemSorter = new ColumnSorter(1);
                    listView1.Sort();
                    break;
                case 2:
                    listView1.ListViewItemSorter = new ColumnSorter(2);
                    listView1.Sort();
                    break;
                case 3:
                    listView1.ListViewItemSorter = new ColumnSorter(3);
                    listView1.Sort();
                    break;
                case 4:
                    listView1.ListViewItemSorter = new ColumnSorter(4);
                    listView1.Sort();
                    break;
            }
        }

        private void copyToClipboardToolStripMenuItem_Click(object sender, EventArgs e)
        {
            ClipboardBuilder cB = new ClipboardBuilder();
            for (int i = 0; i < listView1.SelectedItems.Count; i++)
            {
                cB.WriteLine("File Location: " + listView1.SelectedItems[i].SubItems[0].Text);
                cB.WriteLine("Module Name: " + listView1.SelectedItems[i].SubItems[1].Text);
                cB.WriteLine("Base Address: " + listView1.SelectedItems[i].SubItems[2].Text);
                cB.WriteLine("Entry Point Address: " + listView1.SelectedItems[i].SubItems[3].Text);
                cB.WriteLine("Module Memory Size: " + listView1.SelectedItems[i].SubItems[4].Text);
                cB.WriteLine("");
            }
            if (!cB.Finish())
                MessageBox.Show("Error while copying data!");
        }

        private void viewDLLLocationToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView1.SelectedItems.Count; i++)
            {
                string tmp = listView1.SelectedItems[i].SubItems[0].Text;
                new FileManager(tmp.Substring(0, tmp.Length - tmp.Substring(tmp.LastIndexOf("\\") + 1).Length))
                {
                    RemoteIP = this.RemoteIP,
                    ClientName = this.ClientName,
                    ClientID = this.ClientID
                }.Show();
            }
        }

        #endregion

        private void LoadedDll_FormClosing(object sender, FormClosingEventArgs e)
        {
            GetProcessesDLLEvent.ClientProcessesDLL -= new GetProcessesDLLHandler(onProcessDllInfo);
            ClientDisconnectEvent.ClientDisconnect -= new ClientDisconnectHandler(onClientDisconnect);
        }
    }
}
