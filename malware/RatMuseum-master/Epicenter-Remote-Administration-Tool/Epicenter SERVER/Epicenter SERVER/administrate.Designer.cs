namespace Epicenter_SERVER
{
    partial class administrate
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.Windows.Forms.TreeNode treeNode1 = new System.Windows.Forms.TreeNode("System");
            System.Windows.Forms.TreeNode treeNode2 = new System.Windows.Forms.TreeNode("Network");
            System.Windows.Forms.TreeNode treeNode3 = new System.Windows.Forms.TreeNode("General Information", new System.Windows.Forms.TreeNode[] {
            treeNode1,
            treeNode2});
            System.Windows.Forms.TreeNode treeNode4 = new System.Windows.Forms.TreeNode("Display a message");
            System.Windows.Forms.TreeNode treeNode5 = new System.Windows.Forms.TreeNode("Control the power state");
            System.Windows.Forms.TreeNode treeNode6 = new System.Windows.Forms.TreeNode("Lock keyboard and mouse");
            System.Windows.Forms.TreeNode treeNode7 = new System.Windows.Forms.TreeNode("Start or kill processes");
            System.Windows.Forms.TreeNode treeNode8 = new System.Windows.Forms.TreeNode("Take screenshots");
            System.Windows.Forms.TreeNode treeNode9 = new System.Windows.Forms.TreeNode("Other");
            System.Windows.Forms.TreeNode treeNode10 = new System.Windows.Forms.TreeNode("Administrative Features", new System.Windows.Forms.TreeNode[] {
            treeNode4,
            treeNode5,
            treeNode6,
            treeNode7,
            treeNode8,
            treeNode9});
            System.Windows.Forms.TreeNode treeNode11 = new System.Windows.Forms.TreeNode("Manage remote files");
            System.Windows.Forms.TreeNode treeNode12 = new System.Windows.Forms.TreeNode("Access the command line");
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(administrate));
            this.tvFunctions = new System.Windows.Forms.TreeView();
            this.tabControl1 = new System.Windows.Forms.TabControl();
            this.tabPage1 = new System.Windows.Forms.TabPage();
            this.label2 = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.txtCommand = new System.Windows.Forms.TextBox();
            this.btnSend = new System.Windows.Forms.Button();
            this.tabPage2 = new System.Windows.Forms.TabPage();
            this.lblSystemProperties = new System.Windows.Forms.Label();
            this.tabPage3 = new System.Windows.Forms.TabPage();
            this.lblNetworkProperties = new System.Windows.Forms.Label();
            this.tabPage4 = new System.Windows.Forms.TabPage();
            this.label3 = new System.Windows.Forms.Label();
            this.tabPage5 = new System.Windows.Forms.TabPage();
            this.btnDisplayMessage = new System.Windows.Forms.Button();
            this.txtMB_Message = new System.Windows.Forms.TextBox();
            this.txtMB_Title = new System.Windows.Forms.TextBox();
            this.label5 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.tabPage6 = new System.Windows.Forms.TabPage();
            this.rbLogoff = new System.Windows.Forms.RadioButton();
            this.rbRestart = new System.Windows.Forms.RadioButton();
            this.rbShutdown = new System.Windows.Forms.RadioButton();
            this.btnControlPowerState = new System.Windows.Forms.Button();
            this.tabPage7 = new System.Windows.Forms.TabPage();
            this.label13 = new System.Windows.Forms.Label();
            this.label6 = new System.Windows.Forms.Label();
            this.btnLock = new System.Windows.Forms.Button();
            this.btnUnlock = new System.Windows.Forms.Button();
            this.tabPage8 = new System.Windows.Forms.TabPage();
            this.btnRefreshProcessList = new System.Windows.Forms.Label();
            this.label8 = new System.Windows.Forms.Label();
            this.txtProcessAddr = new System.Windows.Forms.TextBox();
            this.btnKillProc = new System.Windows.Forms.Button();
            this.lbProcesses = new System.Windows.Forms.ListBox();
            this.label7 = new System.Windows.Forms.Label();
            this.btnLaunchProc = new System.Windows.Forms.Button();
            this.tabPage9 = new System.Windows.Forms.TabPage();
            this.lblSSstatus = new System.Windows.Forms.Label();
            this.label21 = new System.Windows.Forms.Label();
            this.label19 = new System.Windows.Forms.Label();
            this.txtSSPort = new System.Windows.Forms.TextBox();
            this.label9 = new System.Windows.Forms.Label();
            this.txtQuality = new System.Windows.Forms.TextBox();
            this.btnCaptureScreenshot = new System.Windows.Forms.Button();
            this.label10 = new System.Windows.Forms.Label();
            this.label11 = new System.Windows.Forms.Label();
            this.tabPage10 = new System.Windows.Forms.TabPage();
            this.lblStatus = new System.Windows.Forms.Label();
            this.txtTransferPort = new System.Windows.Forms.TextBox();
            this.label18 = new System.Windows.Forms.Label();
            this.label17 = new System.Windows.Forms.Label();
            this.btnList = new System.Windows.Forms.Button();
            this.txtLocation = new System.Windows.Forms.TextBox();
            this.listFiles = new System.Windows.Forms.ListView();
            this.columnHeader1 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader2 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader3 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.label14 = new System.Windows.Forms.Label();
            this.btnFileUpload = new System.Windows.Forms.Button();
            this.btnFileDownload = new System.Windows.Forms.Button();
            this.btnFileDel = new System.Windows.Forms.Button();
            this.tabPage11 = new System.Windows.Forms.TabPage();
            this.txtCMDcommand = new System.Windows.Forms.TextBox();
            this.txtDisplayCMD = new System.Windows.Forms.TextBox();
            this.label12 = new System.Windows.Forms.Label();
            this.tabPage12 = new System.Windows.Forms.TabPage();
            this.lblRegStatus = new System.Windows.Forms.Label();
            this.label16 = new System.Windows.Forms.Label();
            this.btnRegistery = new System.Windows.Forms.Button();
            this.label15 = new System.Windows.Forms.Label();
            this.btnRemoteExit = new System.Windows.Forms.Button();
            this.panel2 = new System.Windows.Forms.Panel();
            this.panel3 = new System.Windows.Forms.Panel();
            this.panel4 = new System.Windows.Forms.Panel();
            this.tabControl1.SuspendLayout();
            this.tabPage1.SuspendLayout();
            this.tabPage2.SuspendLayout();
            this.tabPage3.SuspendLayout();
            this.tabPage4.SuspendLayout();
            this.tabPage5.SuspendLayout();
            this.tabPage6.SuspendLayout();
            this.tabPage7.SuspendLayout();
            this.tabPage8.SuspendLayout();
            this.tabPage9.SuspendLayout();
            this.tabPage10.SuspendLayout();
            this.tabPage11.SuspendLayout();
            this.tabPage12.SuspendLayout();
            this.SuspendLayout();
            // 
            // tvFunctions
            // 
            this.tvFunctions.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)));
            this.tvFunctions.Location = new System.Drawing.Point(12, 12);
            this.tvFunctions.Name = "tvFunctions";
            treeNode1.Name = "nodeSystem";
            treeNode1.Text = "System";
            treeNode2.Name = "nodeNetwork";
            treeNode2.Text = "Network";
            treeNode3.Name = "nodeInfo";
            treeNode3.Text = "General Information";
            treeNode4.Name = "nodeMessage";
            treeNode4.Text = "Display a message";
            treeNode5.Name = "nodePower";
            treeNode5.Text = "Control the power state";
            treeNode6.Name = "nodeLockdown";
            treeNode6.Text = "Lock keyboard and mouse";
            treeNode7.Name = "nodeProcess";
            treeNode7.Text = "Start or kill processes";
            treeNode8.Name = "nodeScreenshot";
            treeNode8.Text = "Take screenshots";
            treeNode9.Name = "nodeOther";
            treeNode9.Text = "Other";
            treeNode10.Name = "nodeAdmin";
            treeNode10.Text = "Administrative Features";
            treeNode11.Name = "nodeFile";
            treeNode11.Text = "Manage remote files";
            treeNode12.Name = "nodeCMD";
            treeNode12.Text = "Access the command line";
            this.tvFunctions.Nodes.AddRange(new System.Windows.Forms.TreeNode[] {
            treeNode3,
            treeNode10,
            treeNode11,
            treeNode12});
            this.tvFunctions.Size = new System.Drawing.Size(185, 288);
            this.tvFunctions.TabIndex = 2;
            this.tvFunctions.AfterSelect += new System.Windows.Forms.TreeViewEventHandler(this.tvFunctions_AfterSelect);
            // 
            // tabControl1
            // 
            this.tabControl1.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.tabControl1.Controls.Add(this.tabPage1);
            this.tabControl1.Controls.Add(this.tabPage2);
            this.tabControl1.Controls.Add(this.tabPage3);
            this.tabControl1.Controls.Add(this.tabPage4);
            this.tabControl1.Controls.Add(this.tabPage5);
            this.tabControl1.Controls.Add(this.tabPage6);
            this.tabControl1.Controls.Add(this.tabPage7);
            this.tabControl1.Controls.Add(this.tabPage8);
            this.tabControl1.Controls.Add(this.tabPage9);
            this.tabControl1.Controls.Add(this.tabPage10);
            this.tabControl1.Controls.Add(this.tabPage11);
            this.tabControl1.Controls.Add(this.tabPage12);
            this.tabControl1.Location = new System.Drawing.Point(202, -8);
            this.tabControl1.Name = "tabControl1";
            this.tabControl1.SelectedIndex = 0;
            this.tabControl1.Size = new System.Drawing.Size(386, 308);
            this.tabControl1.TabIndex = 5;
            this.tabControl1.SelectedIndexChanged += new System.EventHandler(this.tabControl1_SelectedIndexChanged);
            // 
            // tabPage1
            // 
            this.tabPage1.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage1.Controls.Add(this.label2);
            this.tabPage1.Controls.Add(this.label1);
            this.tabPage1.Controls.Add(this.txtCommand);
            this.tabPage1.Controls.Add(this.btnSend);
            this.tabPage1.Location = new System.Drawing.Point(4, 22);
            this.tabPage1.Name = "tabPage1";
            this.tabPage1.Padding = new System.Windows.Forms.Padding(3);
            this.tabPage1.Size = new System.Drawing.Size(378, 282);
            this.tabPage1.TabIndex = 0;
            this.tabPage1.Text = "1";
            // 
            // label2
            // 
            this.label2.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(15, 183);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(124, 13);
            this.label2.TabIndex = 7;
            this.label2.Text = "For debugging purposes:";
            // 
            // label1
            // 
            this.label1.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(15, 26);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(356, 117);
            this.label1.TabIndex = 6;
            this.label1.Text = resources.GetString("label1.Text");
            // 
            // txtCommand
            // 
            this.txtCommand.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.txtCommand.Location = new System.Drawing.Point(18, 199);
            this.txtCommand.Name = "txtCommand";
            this.txtCommand.Size = new System.Drawing.Size(354, 20);
            this.txtCommand.TabIndex = 5;
            // 
            // btnSend
            // 
            this.btnSend.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnSend.Location = new System.Drawing.Point(97, 225);
            this.btnSend.Name = "btnSend";
            this.btnSend.Size = new System.Drawing.Size(207, 23);
            this.btnSend.TabIndex = 4;
            this.btnSend.Text = "Send";
            this.btnSend.UseVisualStyleBackColor = true;
            this.btnSend.Click += new System.EventHandler(this.btnSend_Click);
            // 
            // tabPage2
            // 
            this.tabPage2.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage2.Controls.Add(this.lblSystemProperties);
            this.tabPage2.Location = new System.Drawing.Point(4, 22);
            this.tabPage2.Name = "tabPage2";
            this.tabPage2.Padding = new System.Windows.Forms.Padding(3);
            this.tabPage2.Size = new System.Drawing.Size(378, 282);
            this.tabPage2.TabIndex = 1;
            this.tabPage2.Text = "2";
            // 
            // lblSystemProperties
            // 
            this.lblSystemProperties.AutoSize = true;
            this.lblSystemProperties.Font = new System.Drawing.Font("Microsoft Sans Serif", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.lblSystemProperties.Location = new System.Drawing.Point(7, 17);
            this.lblSystemProperties.Name = "lblSystemProperties";
            this.lblSystemProperties.Size = new System.Drawing.Size(150, 16);
            this.lblSystemProperties.TabIndex = 0;
            this.lblSystemProperties.Text = "Retrieving... Please wait";
            // 
            // tabPage3
            // 
            this.tabPage3.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage3.Controls.Add(this.lblNetworkProperties);
            this.tabPage3.Location = new System.Drawing.Point(4, 22);
            this.tabPage3.Name = "tabPage3";
            this.tabPage3.Size = new System.Drawing.Size(378, 282);
            this.tabPage3.TabIndex = 2;
            this.tabPage3.Text = "3";
            // 
            // lblNetworkProperties
            // 
            this.lblNetworkProperties.AutoSize = true;
            this.lblNetworkProperties.Font = new System.Drawing.Font("Microsoft Sans Serif", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.lblNetworkProperties.Location = new System.Drawing.Point(7, 17);
            this.lblNetworkProperties.Name = "lblNetworkProperties";
            this.lblNetworkProperties.Size = new System.Drawing.Size(150, 16);
            this.lblNetworkProperties.TabIndex = 1;
            this.lblNetworkProperties.Text = "Retrieving... Please wait";
            // 
            // tabPage4
            // 
            this.tabPage4.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage4.Controls.Add(this.label3);
            this.tabPage4.Location = new System.Drawing.Point(4, 22);
            this.tabPage4.Name = "tabPage4";
            this.tabPage4.Size = new System.Drawing.Size(378, 282);
            this.tabPage4.TabIndex = 3;
            this.tabPage4.Text = "4";
            // 
            // label3
            // 
            this.label3.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label3.Location = new System.Drawing.Point(38, 80);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(316, 104);
            this.label3.TabIndex = 5;
            this.label3.Text = resources.GetString("label3.Text");
            this.label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // tabPage5
            // 
            this.tabPage5.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage5.Controls.Add(this.btnDisplayMessage);
            this.tabPage5.Controls.Add(this.txtMB_Message);
            this.tabPage5.Controls.Add(this.txtMB_Title);
            this.tabPage5.Controls.Add(this.label5);
            this.tabPage5.Controls.Add(this.label4);
            this.tabPage5.Location = new System.Drawing.Point(4, 22);
            this.tabPage5.Name = "tabPage5";
            this.tabPage5.Size = new System.Drawing.Size(378, 282);
            this.tabPage5.TabIndex = 4;
            this.tabPage5.Text = "5";
            // 
            // btnDisplayMessage
            // 
            this.btnDisplayMessage.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.btnDisplayMessage.Location = new System.Drawing.Point(225, 192);
            this.btnDisplayMessage.Name = "btnDisplayMessage";
            this.btnDisplayMessage.Size = new System.Drawing.Size(91, 23);
            this.btnDisplayMessage.TabIndex = 19;
            this.btnDisplayMessage.Text = "Send";
            this.btnDisplayMessage.UseVisualStyleBackColor = true;
            this.btnDisplayMessage.Click += new System.EventHandler(this.btnDisplayMessage_Click);
            // 
            // txtMB_Message
            // 
            this.txtMB_Message.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtMB_Message.Location = new System.Drawing.Point(103, 102);
            this.txtMB_Message.Multiline = true;
            this.txtMB_Message.Name = "txtMB_Message";
            this.txtMB_Message.Size = new System.Drawing.Size(213, 84);
            this.txtMB_Message.TabIndex = 18;
            // 
            // txtMB_Title
            // 
            this.txtMB_Title.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtMB_Title.Location = new System.Drawing.Point(103, 75);
            this.txtMB_Title.Name = "txtMB_Title";
            this.txtMB_Title.Size = new System.Drawing.Size(213, 20);
            this.txtMB_Title.TabIndex = 17;
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Location = new System.Drawing.Point(38, 105);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(53, 13);
            this.label5.TabIndex = 16;
            this.label5.Text = "Message:";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(38, 78);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(30, 13);
            this.label4.TabIndex = 15;
            this.label4.Text = "Title:";
            // 
            // tabPage6
            // 
            this.tabPage6.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage6.Controls.Add(this.rbLogoff);
            this.tabPage6.Controls.Add(this.rbRestart);
            this.tabPage6.Controls.Add(this.rbShutdown);
            this.tabPage6.Controls.Add(this.btnControlPowerState);
            this.tabPage6.Location = new System.Drawing.Point(4, 22);
            this.tabPage6.Name = "tabPage6";
            this.tabPage6.Size = new System.Drawing.Size(378, 282);
            this.tabPage6.TabIndex = 5;
            this.tabPage6.Text = "6";
            // 
            // rbLogoff
            // 
            this.rbLogoff.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.rbLogoff.AutoSize = true;
            this.rbLogoff.Location = new System.Drawing.Point(158, 126);
            this.rbLogoff.Name = "rbLogoff";
            this.rbLogoff.Size = new System.Drawing.Size(58, 17);
            this.rbLogoff.TabIndex = 12;
            this.rbLogoff.Text = "Log off";
            this.rbLogoff.UseVisualStyleBackColor = true;
            // 
            // rbRestart
            // 
            this.rbRestart.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.rbRestart.AutoSize = true;
            this.rbRestart.Location = new System.Drawing.Point(158, 103);
            this.rbRestart.Name = "rbRestart";
            this.rbRestart.Size = new System.Drawing.Size(59, 17);
            this.rbRestart.TabIndex = 11;
            this.rbRestart.Text = "Restart";
            this.rbRestart.UseVisualStyleBackColor = true;
            // 
            // rbShutdown
            // 
            this.rbShutdown.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.rbShutdown.AutoSize = true;
            this.rbShutdown.Checked = true;
            this.rbShutdown.Location = new System.Drawing.Point(158, 80);
            this.rbShutdown.Name = "rbShutdown";
            this.rbShutdown.Size = new System.Drawing.Size(76, 17);
            this.rbShutdown.TabIndex = 10;
            this.rbShutdown.TabStop = true;
            this.rbShutdown.Text = "Shut down";
            this.rbShutdown.UseVisualStyleBackColor = true;
            // 
            // btnControlPowerState
            // 
            this.btnControlPowerState.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnControlPowerState.Location = new System.Drawing.Point(135, 158);
            this.btnControlPowerState.Name = "btnControlPowerState";
            this.btnControlPowerState.Size = new System.Drawing.Size(117, 33);
            this.btnControlPowerState.TabIndex = 9;
            this.btnControlPowerState.Text = "Send";
            this.btnControlPowerState.UseVisualStyleBackColor = true;
            this.btnControlPowerState.Click += new System.EventHandler(this.btnControlPowerState_Click);
            // 
            // tabPage7
            // 
            this.tabPage7.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage7.Controls.Add(this.label13);
            this.tabPage7.Controls.Add(this.label6);
            this.tabPage7.Controls.Add(this.btnLock);
            this.tabPage7.Controls.Add(this.btnUnlock);
            this.tabPage7.Location = new System.Drawing.Point(4, 22);
            this.tabPage7.Name = "tabPage7";
            this.tabPage7.Size = new System.Drawing.Size(378, 282);
            this.tabPage7.TabIndex = 6;
            this.tabPage7.Text = "7";
            // 
            // label13
            // 
            this.label13.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label13.AutoSize = true;
            this.label13.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label13.Location = new System.Drawing.Point(41, 187);
            this.label13.Name = "label13";
            this.label13.Size = new System.Drawing.Size(286, 12);
            this.label13.TabIndex = 11;
            this.label13.Text = "Also note that this feature requires elevation on Windows Vista and 7";
            // 
            // label6
            // 
            this.label6.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label6.AutoSize = true;
            this.label6.Location = new System.Drawing.Point(12, 87);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(348, 52);
            this.label6.TabIndex = 9;
            this.label6.Text = "When this command is executed, the user becomes incapable of doing \r\nany input to" +
                " the computer.\r\n\r\nBe aware that the user can\'t use CTRL+ALT+DEL to solve this si" +
                "tuation.";
            // 
            // btnLock
            // 
            this.btnLock.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnLock.Location = new System.Drawing.Point(15, 149);
            this.btnLock.Name = "btnLock";
            this.btnLock.Size = new System.Drawing.Size(193, 33);
            this.btnLock.TabIndex = 8;
            this.btnLock.Text = "Lock";
            this.btnLock.UseVisualStyleBackColor = true;
            this.btnLock.Click += new System.EventHandler(this.btnLock_Click);
            // 
            // btnUnlock
            // 
            this.btnUnlock.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnUnlock.Location = new System.Drawing.Point(214, 149);
            this.btnUnlock.Name = "btnUnlock";
            this.btnUnlock.Size = new System.Drawing.Size(146, 33);
            this.btnUnlock.TabIndex = 10;
            this.btnUnlock.Text = "Unlock";
            this.btnUnlock.UseVisualStyleBackColor = true;
            this.btnUnlock.Click += new System.EventHandler(this.btnUnlock_Click);
            // 
            // tabPage8
            // 
            this.tabPage8.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage8.Controls.Add(this.btnRefreshProcessList);
            this.tabPage8.Controls.Add(this.label8);
            this.tabPage8.Controls.Add(this.txtProcessAddr);
            this.tabPage8.Controls.Add(this.btnKillProc);
            this.tabPage8.Controls.Add(this.lbProcesses);
            this.tabPage8.Controls.Add(this.label7);
            this.tabPage8.Controls.Add(this.btnLaunchProc);
            this.tabPage8.Location = new System.Drawing.Point(4, 22);
            this.tabPage8.Name = "tabPage8";
            this.tabPage8.Size = new System.Drawing.Size(378, 282);
            this.tabPage8.TabIndex = 7;
            this.tabPage8.Text = "8";
            // 
            // btnRefreshProcessList
            // 
            this.btnRefreshProcessList.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnRefreshProcessList.AutoSize = true;
            this.btnRefreshProcessList.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btnRefreshProcessList.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.btnRefreshProcessList.Location = new System.Drawing.Point(320, 77);
            this.btnRefreshProcessList.Name = "btnRefreshProcessList";
            this.btnRefreshProcessList.Size = new System.Drawing.Size(44, 12);
            this.btnRefreshProcessList.TabIndex = 17;
            this.btnRefreshProcessList.Text = "[Refresh]";
            this.btnRefreshProcessList.Click += new System.EventHandler(this.btnRefreshProcessList_Click);
            // 
            // label8
            // 
            this.label8.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.label8.AutoSize = true;
            this.label8.Location = new System.Drawing.Point(17, 238);
            this.label8.Name = "label8";
            this.label8.Size = new System.Drawing.Size(51, 13);
            this.label8.TabIndex = 15;
            this.label8.Text = "Location:";
            // 
            // txtProcessAddr
            // 
            this.txtProcessAddr.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtProcessAddr.Location = new System.Drawing.Point(74, 235);
            this.txtProcessAddr.Name = "txtProcessAddr";
            this.txtProcessAddr.Size = new System.Drawing.Size(211, 20);
            this.txtProcessAddr.TabIndex = 14;
            this.txtProcessAddr.KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.txtProcessAddr_KeyPress);
            // 
            // btnKillProc
            // 
            this.btnKillProc.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.btnKillProc.Location = new System.Drawing.Point(20, 204);
            this.btnKillProc.Name = "btnKillProc";
            this.btnKillProc.Size = new System.Drawing.Size(344, 23);
            this.btnKillProc.TabIndex = 13;
            this.btnKillProc.Text = "Kill";
            this.btnKillProc.UseVisualStyleBackColor = true;
            this.btnKillProc.Click += new System.EventHandler(this.btnKillProc_Click);
            // 
            // lbProcesses
            // 
            this.lbProcesses.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.lbProcesses.FormattingEnabled = true;
            this.lbProcesses.Location = new System.Drawing.Point(20, 93);
            this.lbProcesses.Name = "lbProcesses";
            this.lbProcesses.ScrollAlwaysVisible = true;
            this.lbProcesses.Size = new System.Drawing.Size(344, 108);
            this.lbProcesses.TabIndex = 12;
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.Location = new System.Drawing.Point(17, 21);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(323, 65);
            this.label7.TabIndex = 11;
            this.label7.Text = resources.GetString("label7.Text");
            // 
            // btnLaunchProc
            // 
            this.btnLaunchProc.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.btnLaunchProc.Location = new System.Drawing.Point(291, 233);
            this.btnLaunchProc.Name = "btnLaunchProc";
            this.btnLaunchProc.Size = new System.Drawing.Size(73, 23);
            this.btnLaunchProc.TabIndex = 16;
            this.btnLaunchProc.Text = "Start";
            this.btnLaunchProc.UseVisualStyleBackColor = true;
            this.btnLaunchProc.Click += new System.EventHandler(this.btnLaunchProc_Click);
            // 
            // tabPage9
            // 
            this.tabPage9.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage9.Controls.Add(this.lblSSstatus);
            this.tabPage9.Controls.Add(this.label21);
            this.tabPage9.Controls.Add(this.label19);
            this.tabPage9.Controls.Add(this.txtSSPort);
            this.tabPage9.Controls.Add(this.label9);
            this.tabPage9.Controls.Add(this.txtQuality);
            this.tabPage9.Controls.Add(this.btnCaptureScreenshot);
            this.tabPage9.Controls.Add(this.label10);
            this.tabPage9.Controls.Add(this.label11);
            this.tabPage9.Location = new System.Drawing.Point(4, 22);
            this.tabPage9.Name = "tabPage9";
            this.tabPage9.Size = new System.Drawing.Size(378, 282);
            this.tabPage9.TabIndex = 8;
            this.tabPage9.Text = "9";
            // 
            // lblSSstatus
            // 
            this.lblSSstatus.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.lblSSstatus.AutoSize = true;
            this.lblSSstatus.Location = new System.Drawing.Point(167, 222);
            this.lblSSstatus.Name = "lblSSstatus";
            this.lblSSstatus.Size = new System.Drawing.Size(24, 13);
            this.lblSSstatus.TabIndex = 25;
            this.lblSSstatus.Text = "Idle";
            // 
            // label21
            // 
            this.label21.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label21.AutoSize = true;
            this.label21.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label21.Location = new System.Drawing.Point(12, 222);
            this.label21.Name = "label21";
            this.label21.Size = new System.Drawing.Size(153, 13);
            this.label21.TabIndex = 24;
            this.label21.Text = "Download/Upload Status:";
            // 
            // label19
            // 
            this.label19.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label19.AutoSize = true;
            this.label19.Location = new System.Drawing.Point(138, 162);
            this.label19.Name = "label19";
            this.label19.Size = new System.Drawing.Size(29, 13);
            this.label19.TabIndex = 18;
            this.label19.Text = "Port:";
            // 
            // txtSSPort
            // 
            this.txtSSPort.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.txtSSPort.Location = new System.Drawing.Point(186, 159);
            this.txtSSPort.Name = "txtSSPort";
            this.txtSSPort.Size = new System.Drawing.Size(53, 20);
            this.txtSSPort.TabIndex = 17;
            this.txtSSPort.Text = "81";
            this.txtSSPort.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            // 
            // label9
            // 
            this.label9.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label9.AutoSize = true;
            this.label9.Location = new System.Drawing.Point(138, 134);
            this.label9.Name = "label9";
            this.label9.Size = new System.Drawing.Size(42, 13);
            this.label9.TabIndex = 15;
            this.label9.Text = "Quality:";
            // 
            // txtQuality
            // 
            this.txtQuality.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.txtQuality.Location = new System.Drawing.Point(186, 131);
            this.txtQuality.Name = "txtQuality";
            this.txtQuality.Size = new System.Drawing.Size(29, 20);
            this.txtQuality.TabIndex = 14;
            this.txtQuality.Text = "50";
            this.txtQuality.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            // 
            // btnCaptureScreenshot
            // 
            this.btnCaptureScreenshot.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnCaptureScreenshot.Location = new System.Drawing.Point(141, 185);
            this.btnCaptureScreenshot.Name = "btnCaptureScreenshot";
            this.btnCaptureScreenshot.Size = new System.Drawing.Size(98, 23);
            this.btnCaptureScreenshot.TabIndex = 13;
            this.btnCaptureScreenshot.Text = "Capture";
            this.btnCaptureScreenshot.UseVisualStyleBackColor = true;
            this.btnCaptureScreenshot.Click += new System.EventHandler(this.btnCaptureScreenshot_Click);
            // 
            // label10
            // 
            this.label10.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label10.AutoSize = true;
            this.label10.Location = new System.Drawing.Point(12, 55);
            this.label10.Name = "label10";
            this.label10.Size = new System.Drawing.Size(355, 52);
            this.label10.TabIndex = 12;
            this.label10.Text = "You can take a screenshot from the remote station.\r\n\r\nPlease set the quality of t" +
                "he screenshot and click \"Capture\". Keep in mind\r\nthat a screenshot with greater " +
                "quality is bigger in size.";
            // 
            // label11
            // 
            this.label11.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label11.AutoSize = true;
            this.label11.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label11.Location = new System.Drawing.Point(216, 130);
            this.label11.Name = "label11";
            this.label11.Size = new System.Drawing.Size(23, 20);
            this.label11.TabIndex = 16;
            this.label11.Text = "%";
            // 
            // tabPage10
            // 
            this.tabPage10.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage10.Controls.Add(this.lblStatus);
            this.tabPage10.Controls.Add(this.txtTransferPort);
            this.tabPage10.Controls.Add(this.label18);
            this.tabPage10.Controls.Add(this.label17);
            this.tabPage10.Controls.Add(this.btnList);
            this.tabPage10.Controls.Add(this.txtLocation);
            this.tabPage10.Controls.Add(this.listFiles);
            this.tabPage10.Controls.Add(this.label14);
            this.tabPage10.Controls.Add(this.btnFileUpload);
            this.tabPage10.Controls.Add(this.btnFileDownload);
            this.tabPage10.Controls.Add(this.btnFileDel);
            this.tabPage10.Location = new System.Drawing.Point(4, 22);
            this.tabPage10.Name = "tabPage10";
            this.tabPage10.Size = new System.Drawing.Size(378, 282);
            this.tabPage10.TabIndex = 9;
            this.tabPage10.Text = "10";
            // 
            // lblStatus
            // 
            this.lblStatus.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.lblStatus.AutoSize = true;
            this.lblStatus.Location = new System.Drawing.Point(174, 262);
            this.lblStatus.Name = "lblStatus";
            this.lblStatus.Size = new System.Drawing.Size(24, 13);
            this.lblStatus.TabIndex = 23;
            this.lblStatus.Text = "Idle";
            // 
            // txtTransferPort
            // 
            this.txtTransferPort.Location = new System.Drawing.Point(126, 40);
            this.txtTransferPort.Name = "txtTransferPort";
            this.txtTransferPort.Size = new System.Drawing.Size(72, 20);
            this.txtTransferPort.TabIndex = 25;
            this.txtTransferPort.Text = "81";
            this.txtTransferPort.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            // 
            // label18
            // 
            this.label18.AutoSize = true;
            this.label18.Location = new System.Drawing.Point(19, 43);
            this.label18.Name = "label18";
            this.label18.Size = new System.Drawing.Size(104, 13);
            this.label18.TabIndex = 24;
            this.label18.Text = "Transfer comm. port:";
            // 
            // label17
            // 
            this.label17.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.label17.AutoSize = true;
            this.label17.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label17.Location = new System.Drawing.Point(19, 262);
            this.label17.Name = "label17";
            this.label17.Size = new System.Drawing.Size(153, 13);
            this.label17.TabIndex = 22;
            this.label17.Text = "Download/Upload Status:";
            // 
            // btnList
            // 
            this.btnList.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnList.Location = new System.Drawing.Point(304, 63);
            this.btnList.Name = "btnList";
            this.btnList.Size = new System.Drawing.Size(65, 23);
            this.btnList.TabIndex = 21;
            this.btnList.Text = "List";
            this.btnList.UseVisualStyleBackColor = true;
            this.btnList.Click += new System.EventHandler(this.btnList_Click);
            // 
            // txtLocation
            // 
            this.txtLocation.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtLocation.Location = new System.Drawing.Point(22, 65);
            this.txtLocation.Name = "txtLocation";
            this.txtLocation.Size = new System.Drawing.Size(276, 20);
            this.txtLocation.TabIndex = 20;
            this.txtLocation.KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.txtLocation_KeyPress);
            // 
            // listFiles
            // 
            this.listFiles.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.listFiles.Columns.AddRange(new System.Windows.Forms.ColumnHeader[] {
            this.columnHeader1,
            this.columnHeader2,
            this.columnHeader3});
            this.listFiles.GridLines = true;
            this.listFiles.Location = new System.Drawing.Point(22, 91);
            this.listFiles.MultiSelect = false;
            this.listFiles.Name = "listFiles";
            this.listFiles.Size = new System.Drawing.Size(347, 139);
            this.listFiles.TabIndex = 17;
            this.listFiles.TileSize = new System.Drawing.Size(10, 10);
            this.listFiles.UseCompatibleStateImageBehavior = false;
            this.listFiles.View = System.Windows.Forms.View.Details;
            this.listFiles.DoubleClick += new System.EventHandler(this.listFiles_DoubleClick);
            // 
            // columnHeader1
            // 
            this.columnHeader1.Text = "Filename";
            this.columnHeader1.Width = 196;
            // 
            // columnHeader2
            // 
            this.columnHeader2.Text = "Type";
            this.columnHeader2.Width = 83;
            // 
            // columnHeader3
            // 
            this.columnHeader3.Text = "Size";
            // 
            // label14
            // 
            this.label14.AutoSize = true;
            this.label14.Location = new System.Drawing.Point(19, 17);
            this.label14.Name = "label14";
            this.label14.Size = new System.Drawing.Size(280, 13);
            this.label14.TabIndex = 15;
            this.label14.Text = "Download, upload and delete files from the remote station.";
            // 
            // btnFileUpload
            // 
            this.btnFileUpload.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.btnFileUpload.Location = new System.Drawing.Point(126, 236);
            this.btnFileUpload.Name = "btnFileUpload";
            this.btnFileUpload.Size = new System.Drawing.Size(98, 23);
            this.btnFileUpload.TabIndex = 18;
            this.btnFileUpload.Text = "Upload";
            this.btnFileUpload.UseVisualStyleBackColor = true;
            this.btnFileUpload.Click += new System.EventHandler(this.btnFileUpload_Click);
            // 
            // btnFileDownload
            // 
            this.btnFileDownload.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.btnFileDownload.Location = new System.Drawing.Point(22, 236);
            this.btnFileDownload.Name = "btnFileDownload";
            this.btnFileDownload.Size = new System.Drawing.Size(98, 23);
            this.btnFileDownload.TabIndex = 16;
            this.btnFileDownload.Text = "Download";
            this.btnFileDownload.UseVisualStyleBackColor = true;
            this.btnFileDownload.Click += new System.EventHandler(this.btnFileDownload_Click);
            // 
            // btnFileDel
            // 
            this.btnFileDel.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.btnFileDel.Location = new System.Drawing.Point(271, 236);
            this.btnFileDel.Name = "btnFileDel";
            this.btnFileDel.Size = new System.Drawing.Size(98, 23);
            this.btnFileDel.TabIndex = 19;
            this.btnFileDel.Text = "Delete";
            this.btnFileDel.UseVisualStyleBackColor = true;
            this.btnFileDel.Click += new System.EventHandler(this.btnFileDel_Click);
            // 
            // tabPage11
            // 
            this.tabPage11.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage11.Controls.Add(this.txtCMDcommand);
            this.tabPage11.Controls.Add(this.txtDisplayCMD);
            this.tabPage11.Controls.Add(this.label12);
            this.tabPage11.Location = new System.Drawing.Point(4, 22);
            this.tabPage11.Name = "tabPage11";
            this.tabPage11.Size = new System.Drawing.Size(378, 282);
            this.tabPage11.TabIndex = 10;
            this.tabPage11.Text = "11";
            // 
            // txtCMDcommand
            // 
            this.txtCMDcommand.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtCMDcommand.BackColor = System.Drawing.SystemColors.WindowFrame;
            this.txtCMDcommand.ForeColor = System.Drawing.Color.White;
            this.txtCMDcommand.Location = new System.Drawing.Point(9, 259);
            this.txtCMDcommand.Name = "txtCMDcommand";
            this.txtCMDcommand.Size = new System.Drawing.Size(365, 20);
            this.txtCMDcommand.TabIndex = 19;
            // 
            // txtDisplayCMD
            // 
            this.txtDisplayCMD.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtDisplayCMD.BackColor = System.Drawing.SystemColors.InfoText;
            this.txtDisplayCMD.ForeColor = System.Drawing.SystemColors.ScrollBar;
            this.txtDisplayCMD.Location = new System.Drawing.Point(9, 46);
            this.txtDisplayCMD.Multiline = true;
            this.txtDisplayCMD.Name = "txtDisplayCMD";
            this.txtDisplayCMD.ReadOnly = true;
            this.txtDisplayCMD.Size = new System.Drawing.Size(365, 214);
            this.txtDisplayCMD.TabIndex = 18;
            this.txtDisplayCMD.Click += new System.EventHandler(this.txtDisplayCMD_Click);
            // 
            // label12
            // 
            this.label12.Anchor = System.Windows.Forms.AnchorStyles.Top;
            this.label12.AutoSize = true;
            this.label12.Location = new System.Drawing.Point(7, 14);
            this.label12.Name = "label12";
            this.label12.Size = new System.Drawing.Size(370, 26);
            this.label12.TabIndex = 17;
            this.label12.Text = "Access the command line of the remote station by typing into the grey textbox\r\nan" +
                "d pressing ENTER.";
            // 
            // tabPage12
            // 
            this.tabPage12.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage12.Controls.Add(this.lblRegStatus);
            this.tabPage12.Controls.Add(this.label16);
            this.tabPage12.Controls.Add(this.btnRegistery);
            this.tabPage12.Controls.Add(this.label15);
            this.tabPage12.Controls.Add(this.btnRemoteExit);
            this.tabPage12.Location = new System.Drawing.Point(4, 22);
            this.tabPage12.Name = "tabPage12";
            this.tabPage12.Size = new System.Drawing.Size(378, 282);
            this.tabPage12.TabIndex = 11;
            this.tabPage12.Text = "12";
            // 
            // lblRegStatus
            // 
            this.lblRegStatus.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.lblRegStatus.Font = new System.Drawing.Font("Microsoft Sans Serif", 15F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.lblRegStatus.Location = new System.Drawing.Point(245, 175);
            this.lblRegStatus.Name = "lblRegStatus";
            this.lblRegStatus.Size = new System.Drawing.Size(113, 60);
            this.lblRegStatus.TabIndex = 4;
            this.lblRegStatus.Text = "NONE";
            this.lblRegStatus.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            // 
            // label16
            // 
            this.label16.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label16.AutoSize = true;
            this.label16.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label16.Location = new System.Drawing.Point(18, 174);
            this.label16.Name = "label16";
            this.label16.Size = new System.Drawing.Size(221, 60);
            this.label16.TabIndex = 3;
            this.label16.Text = resources.GetString("label16.Text");
            this.label16.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // btnRegistery
            // 
            this.btnRegistery.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnRegistery.Location = new System.Drawing.Point(20, 148);
            this.btnRegistery.Name = "btnRegistery";
            this.btnRegistery.Size = new System.Drawing.Size(338, 23);
            this.btnRegistery.TabIndex = 2;
            this.btnRegistery.Text = "Add yourself to the registry";
            this.btnRegistery.UseVisualStyleBackColor = true;
            this.btnRegistery.Click += new System.EventHandler(this.btnRegistery_Click);
            // 
            // label15
            // 
            this.label15.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.label15.AutoSize = true;
            this.label15.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label15.Location = new System.Drawing.Point(33, 105);
            this.label15.Name = "label15";
            this.label15.Size = new System.Drawing.Size(314, 12);
            this.label15.TabIndex = 1;
            this.label15.Text = "Note that you won\'t be able to access the client until the process is restarted";
            this.label15.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            // 
            // btnRemoteExit
            // 
            this.btnRemoteExit.Anchor = System.Windows.Forms.AnchorStyles.None;
            this.btnRemoteExit.Location = new System.Drawing.Point(20, 79);
            this.btnRemoteExit.Name = "btnRemoteExit";
            this.btnRemoteExit.Size = new System.Drawing.Size(338, 23);
            this.btnRemoteExit.TabIndex = 0;
            this.btnRemoteExit.Text = "Kill the remote process";
            this.btnRemoteExit.UseVisualStyleBackColor = true;
            this.btnRemoteExit.Click += new System.EventHandler(this.btnRemoteExit_Click);
            // 
            // panel2
            // 
            this.panel2.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)));
            this.panel2.Location = new System.Drawing.Point(197, 2);
            this.panel2.Name = "panel2";
            this.panel2.Size = new System.Drawing.Size(10, 307);
            this.panel2.TabIndex = 7;
            // 
            // panel3
            // 
            this.panel3.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.panel3.Location = new System.Drawing.Point(199, 293);
            this.panel3.Name = "panel3";
            this.panel3.Size = new System.Drawing.Size(398, 15);
            this.panel3.TabIndex = 7;
            // 
            // panel4
            // 
            this.panel4.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.panel4.Location = new System.Drawing.Point(583, 8);
            this.panel4.Name = "panel4";
            this.panel4.Size = new System.Drawing.Size(10, 307);
            this.panel4.TabIndex = 8;
            // 
            // administrate
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(599, 312);
            this.Controls.Add(this.panel3);
            this.Controls.Add(this.panel4);
            this.Controls.Add(this.panel2);
            this.Controls.Add(this.tabControl1);
            this.Controls.Add(this.tvFunctions);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.MinimumSize = new System.Drawing.Size(615, 350);
            this.Name = "administrate";
            this.Text = "Administration Panel - %CLIENTNAME%";
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.administrate_FormClosing);
            this.tabControl1.ResumeLayout(false);
            this.tabPage1.ResumeLayout(false);
            this.tabPage1.PerformLayout();
            this.tabPage2.ResumeLayout(false);
            this.tabPage2.PerformLayout();
            this.tabPage3.ResumeLayout(false);
            this.tabPage3.PerformLayout();
            this.tabPage4.ResumeLayout(false);
            this.tabPage5.ResumeLayout(false);
            this.tabPage5.PerformLayout();
            this.tabPage6.ResumeLayout(false);
            this.tabPage6.PerformLayout();
            this.tabPage7.ResumeLayout(false);
            this.tabPage7.PerformLayout();
            this.tabPage8.ResumeLayout(false);
            this.tabPage8.PerformLayout();
            this.tabPage9.ResumeLayout(false);
            this.tabPage9.PerformLayout();
            this.tabPage10.ResumeLayout(false);
            this.tabPage10.PerformLayout();
            this.tabPage11.ResumeLayout(false);
            this.tabPage11.PerformLayout();
            this.tabPage12.ResumeLayout(false);
            this.tabPage12.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.TreeView tvFunctions;
        private System.Windows.Forms.TabControl tabControl1;
        private System.Windows.Forms.TabPage tabPage1;
        private System.Windows.Forms.TabPage tabPage2;
        private System.Windows.Forms.TabPage tabPage3;
        private System.Windows.Forms.TabPage tabPage4;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.TabPage tabPage5;
        private System.Windows.Forms.TabPage tabPage6;
        private System.Windows.Forms.RadioButton rbLogoff;
        private System.Windows.Forms.RadioButton rbRestart;
        private System.Windows.Forms.RadioButton rbShutdown;
        private System.Windows.Forms.Button btnControlPowerState;
        private System.Windows.Forms.TabPage tabPage7;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.Button btnLock;
        private System.Windows.Forms.Button btnUnlock;
        private System.Windows.Forms.TabPage tabPage8;
        private System.Windows.Forms.Label label8;
        private System.Windows.Forms.TextBox txtProcessAddr;
        private System.Windows.Forms.Button btnKillProc;
        private System.Windows.Forms.ListBox lbProcesses;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.Button btnLaunchProc;
        private System.Windows.Forms.TabPage tabPage9;
        private System.Windows.Forms.Label label9;
        private System.Windows.Forms.TextBox txtQuality;
        private System.Windows.Forms.Button btnCaptureScreenshot;
        private System.Windows.Forms.Label label10;
        private System.Windows.Forms.Label label11;
        private System.Windows.Forms.TabPage tabPage10;
        private System.Windows.Forms.ListView listFiles;
        private System.Windows.Forms.Label label14;
        private System.Windows.Forms.Button btnFileUpload;
        private System.Windows.Forms.Button btnFileDownload;
        private System.Windows.Forms.Button btnFileDel;
        private System.Windows.Forms.TabPage tabPage11;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.TextBox txtCommand;
        private System.Windows.Forms.Button btnSend;
        private System.Windows.Forms.TextBox txtCMDcommand;
        private System.Windows.Forms.TextBox txtDisplayCMD;
        private System.Windows.Forms.Label label12;
        private System.Windows.Forms.Panel panel2;
        private System.Windows.Forms.Panel panel3;
        private System.Windows.Forms.Panel panel4;
        private System.Windows.Forms.Button btnDisplayMessage;
        private System.Windows.Forms.TextBox txtMB_Message;
        private System.Windows.Forms.TextBox txtMB_Title;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.Label lblSystemProperties;
        private System.Windows.Forms.Label lblNetworkProperties;
        private System.Windows.Forms.Label label13;
        private System.Windows.Forms.TabPage tabPage12;
        private System.Windows.Forms.Button btnRegistery;
        private System.Windows.Forms.Label label15;
        private System.Windows.Forms.Button btnRemoteExit;
        private System.Windows.Forms.Label btnRefreshProcessList;
        private System.Windows.Forms.ColumnHeader columnHeader1;
        private System.Windows.Forms.ColumnHeader columnHeader2;
        private System.Windows.Forms.ColumnHeader columnHeader3;
        private System.Windows.Forms.Button btnList;
        private System.Windows.Forms.TextBox txtLocation;
        private System.Windows.Forms.Label lblStatus;
        private System.Windows.Forms.Label label17;
        private System.Windows.Forms.TextBox txtTransferPort;
        private System.Windows.Forms.Label label18;
        private System.Windows.Forms.Label label16;
        private System.Windows.Forms.Label lblRegStatus;
        private System.Windows.Forms.Label label19;
        private System.Windows.Forms.TextBox txtSSPort;
        private System.Windows.Forms.Label lblSSstatus;
        private System.Windows.Forms.Label label21;
    }
}