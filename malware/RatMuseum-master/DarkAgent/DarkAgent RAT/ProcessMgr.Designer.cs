namespace DarkAgent_RAT
{
    partial class ProcessMgr
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
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(ProcessMgr));
            this.listView4 = new System.Windows.Forms.ListView();
            this.columnHeader30 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader31 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader32 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader33 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader35 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader40 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader41 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader42 = new System.Windows.Forms.ColumnHeader();
            this.columnHeader43 = new System.Windows.Forms.ColumnHeader();
            this.contextMenuStrip1 = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.startNewProcessToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.toolStripTextBox2 = new System.Windows.Forms.ToolStripTextBox();
            this.startToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.startHiddenToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.startSelectedProcessToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.killProcessToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.forceKillProcessToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.forceKillDeleteFileToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.changeWindowTextToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.toolStripTextBox1 = new System.Windows.Forms.ToolStripTextBox();
            this.setWindowTextToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.copyToClipboardToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.viewLoadeddllsToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.viewRunningThreadsToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.suspendProcessToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.resumeProcessToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.refreshToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.viewProcessLocationToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.contextMenuStrip1.SuspendLayout();
            this.SuspendLayout();
            // 
            // listView4
            // 
            this.listView4.Columns.AddRange(new System.Windows.Forms.ColumnHeader[] {
            this.columnHeader30,
            this.columnHeader31,
            this.columnHeader32,
            this.columnHeader33,
            this.columnHeader35,
            this.columnHeader40,
            this.columnHeader41,
            this.columnHeader42,
            this.columnHeader43});
            this.listView4.ContextMenuStrip = this.contextMenuStrip1;
            this.listView4.Dock = System.Windows.Forms.DockStyle.Fill;
            this.listView4.FullRowSelect = true;
            this.listView4.GridLines = true;
            this.listView4.Location = new System.Drawing.Point(0, 0);
            this.listView4.Name = "listView4";
            this.listView4.Size = new System.Drawing.Size(870, 416);
            this.listView4.TabIndex = 5;
            this.listView4.UseCompatibleStateImageBehavior = false;
            this.listView4.View = System.Windows.Forms.View.Details;
            this.listView4.ColumnClick += new System.Windows.Forms.ColumnClickEventHandler(this.listView4_ColumnClick);
            // 
            // columnHeader30
            // 
            this.columnHeader30.Text = "Process";
            this.columnHeader30.Width = 104;
            // 
            // columnHeader31
            // 
            this.columnHeader31.Text = "PID";
            this.columnHeader31.Width = 52;
            // 
            // columnHeader32
            // 
            this.columnHeader32.Text = "Window Title";
            this.columnHeader32.Width = 139;
            // 
            // columnHeader33
            // 
            this.columnHeader33.Text = "Responding";
            this.columnHeader33.Width = 78;
            // 
            // columnHeader35
            // 
            this.columnHeader35.Text = "File location";
            this.columnHeader35.Width = 171;
            // 
            // columnHeader40
            // 
            this.columnHeader40.Text = "Handle";
            // 
            // columnHeader41
            // 
            this.columnHeader41.Text = "Processor Affinity";
            this.columnHeader41.Width = 101;
            // 
            // columnHeader42
            // 
            this.columnHeader42.Text = "Threads";
            // 
            // columnHeader43
            // 
            this.columnHeader43.Text = "Priority";
            // 
            // contextMenuStrip1
            // 
            this.contextMenuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.startNewProcessToolStripMenuItem,
            this.startSelectedProcessToolStripMenuItem,
            this.killProcessToolStripMenuItem,
            this.forceKillProcessToolStripMenuItem,
            this.forceKillDeleteFileToolStripMenuItem,
            this.changeWindowTextToolStripMenuItem,
            this.copyToClipboardToolStripMenuItem,
            this.viewLoadeddllsToolStripMenuItem,
            this.viewRunningThreadsToolStripMenuItem,
            this.suspendProcessToolStripMenuItem,
            this.resumeProcessToolStripMenuItem,
            this.refreshToolStripMenuItem,
            this.viewProcessLocationToolStripMenuItem});
            this.contextMenuStrip1.Name = "contextMenuStrip1";
            this.contextMenuStrip1.Size = new System.Drawing.Size(199, 290);
            // 
            // startNewProcessToolStripMenuItem
            // 
            this.startNewProcessToolStripMenuItem.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.toolStripTextBox2,
            this.startToolStripMenuItem,
            this.startHiddenToolStripMenuItem});
            this.startNewProcessToolStripMenuItem.Name = "startNewProcessToolStripMenuItem";
            this.startNewProcessToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.startNewProcessToolStripMenuItem.Text = "Start new process";
            // 
            // toolStripTextBox2
            // 
            this.toolStripTextBox2.Name = "toolStripTextBox2";
            this.toolStripTextBox2.Size = new System.Drawing.Size(200, 23);
            // 
            // startToolStripMenuItem
            // 
            this.startToolStripMenuItem.Name = "startToolStripMenuItem";
            this.startToolStripMenuItem.Size = new System.Drawing.Size(260, 22);
            this.startToolStripMenuItem.Text = "Start";
            this.startToolStripMenuItem.Click += new System.EventHandler(this.startToolStripMenuItem_Click);
            // 
            // startHiddenToolStripMenuItem
            // 
            this.startHiddenToolStripMenuItem.Name = "startHiddenToolStripMenuItem";
            this.startHiddenToolStripMenuItem.Size = new System.Drawing.Size(260, 22);
            this.startHiddenToolStripMenuItem.Text = "Start Hidden";
            this.startHiddenToolStripMenuItem.Click += new System.EventHandler(this.startHiddenToolStripMenuItem_Click);
            // 
            // startSelectedProcessToolStripMenuItem
            // 
            this.startSelectedProcessToolStripMenuItem.Name = "startSelectedProcessToolStripMenuItem";
            this.startSelectedProcessToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.startSelectedProcessToolStripMenuItem.Text = "Start selected process";
            this.startSelectedProcessToolStripMenuItem.Click += new System.EventHandler(this.startSelectedProcessToolStripMenuItem_Click);
            // 
            // killProcessToolStripMenuItem
            // 
            this.killProcessToolStripMenuItem.Name = "killProcessToolStripMenuItem";
            this.killProcessToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.killProcessToolStripMenuItem.Text = "Kill Process";
            this.killProcessToolStripMenuItem.Click += new System.EventHandler(this.killProcessToolStripMenuItem_Click);
            // 
            // forceKillProcessToolStripMenuItem
            // 
            this.forceKillProcessToolStripMenuItem.Name = "forceKillProcessToolStripMenuItem";
            this.forceKillProcessToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.forceKillProcessToolStripMenuItem.Text = "Force Kill Process";
            this.forceKillProcessToolStripMenuItem.Click += new System.EventHandler(this.forceKillProcessToolStripMenuItem_Click);
            // 
            // forceKillDeleteFileToolStripMenuItem
            // 
            this.forceKillDeleteFileToolStripMenuItem.Name = "forceKillDeleteFileToolStripMenuItem";
            this.forceKillDeleteFileToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.forceKillDeleteFileToolStripMenuItem.Text = "Force Kill && Delete File";
            this.forceKillDeleteFileToolStripMenuItem.Click += new System.EventHandler(this.forceKillDeleteFileToolStripMenuItem_Click);
            // 
            // changeWindowTextToolStripMenuItem
            // 
            this.changeWindowTextToolStripMenuItem.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.toolStripTextBox1,
            this.setWindowTextToolStripMenuItem});
            this.changeWindowTextToolStripMenuItem.Name = "changeWindowTextToolStripMenuItem";
            this.changeWindowTextToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.changeWindowTextToolStripMenuItem.Text = "Change Window Text";
            // 
            // toolStripTextBox1
            // 
            this.toolStripTextBox1.Name = "toolStripTextBox1";
            this.toolStripTextBox1.Size = new System.Drawing.Size(100, 23);
            // 
            // setWindowTextToolStripMenuItem
            // 
            this.setWindowTextToolStripMenuItem.Name = "setWindowTextToolStripMenuItem";
            this.setWindowTextToolStripMenuItem.Size = new System.Drawing.Size(163, 22);
            this.setWindowTextToolStripMenuItem.Text = "Set Window Text";
            this.setWindowTextToolStripMenuItem.Click += new System.EventHandler(this.setWindowTextToolStripMenuItem_Click);
            // 
            // copyToClipboardToolStripMenuItem
            // 
            this.copyToClipboardToolStripMenuItem.Name = "copyToClipboardToolStripMenuItem";
            this.copyToClipboardToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.copyToClipboardToolStripMenuItem.Text = "Copy to Clipboard";
            this.copyToClipboardToolStripMenuItem.Click += new System.EventHandler(this.copyToClipboardToolStripMenuItem_Click);
            // 
            // viewLoadeddllsToolStripMenuItem
            // 
            this.viewLoadeddllsToolStripMenuItem.Name = "viewLoadeddllsToolStripMenuItem";
            this.viewLoadeddllsToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.viewLoadeddllsToolStripMenuItem.Text = "View loaded .dll\'s";
            this.viewLoadeddllsToolStripMenuItem.Click += new System.EventHandler(this.viewLoadeddllsToolStripMenuItem_Click);
            // 
            // viewRunningThreadsToolStripMenuItem
            // 
            this.viewRunningThreadsToolStripMenuItem.Name = "viewRunningThreadsToolStripMenuItem";
            this.viewRunningThreadsToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.viewRunningThreadsToolStripMenuItem.Text = "View running threads";
            this.viewRunningThreadsToolStripMenuItem.Click += new System.EventHandler(this.viewRunningThreadsToolStripMenuItem_Click);
            // 
            // suspendProcessToolStripMenuItem
            // 
            this.suspendProcessToolStripMenuItem.Name = "suspendProcessToolStripMenuItem";
            this.suspendProcessToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.suspendProcessToolStripMenuItem.Text = "Suspend Process";
            this.suspendProcessToolStripMenuItem.Click += new System.EventHandler(this.suspendProcessToolStripMenuItem_Click);
            // 
            // resumeProcessToolStripMenuItem
            // 
            this.resumeProcessToolStripMenuItem.Name = "resumeProcessToolStripMenuItem";
            this.resumeProcessToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.resumeProcessToolStripMenuItem.Text = "Resume Process";
            this.resumeProcessToolStripMenuItem.Click += new System.EventHandler(this.resumeProcessToolStripMenuItem_Click);
            // 
            // refreshToolStripMenuItem
            // 
            this.refreshToolStripMenuItem.Name = "refreshToolStripMenuItem";
            this.refreshToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.refreshToolStripMenuItem.Text = "Refresh";
            this.refreshToolStripMenuItem.Click += new System.EventHandler(this.refreshToolStripMenuItem_Click);
            // 
            // viewProcessLocationToolStripMenuItem
            // 
            this.viewProcessLocationToolStripMenuItem.Name = "viewProcessLocationToolStripMenuItem";
            this.viewProcessLocationToolStripMenuItem.Size = new System.Drawing.Size(198, 22);
            this.viewProcessLocationToolStripMenuItem.Text = "View Process Location";
            this.viewProcessLocationToolStripMenuItem.Click += new System.EventHandler(this.viewProcessLocationToolStripMenuItem_Click);
            // 
            // ProcessMgr
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(870, 416);
            this.Controls.Add(this.listView4);
            this.DoubleBuffered = true;
            this.EnableGlass = false;
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "ProcessMgr";
            this.Text = "DarkAgent - Process Manager";
            this.Load += new System.EventHandler(this.ProcessMgr_Load);
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.ProcessMgr_FormClosing);
            this.contextMenuStrip1.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        public System.Windows.Forms.ListView listView4;
        private System.Windows.Forms.ColumnHeader columnHeader30;
        private System.Windows.Forms.ColumnHeader columnHeader31;
        private System.Windows.Forms.ColumnHeader columnHeader32;
        private System.Windows.Forms.ColumnHeader columnHeader33;
        private System.Windows.Forms.ColumnHeader columnHeader35;
        private System.Windows.Forms.ColumnHeader columnHeader40;
        private System.Windows.Forms.ColumnHeader columnHeader41;
        private System.Windows.Forms.ColumnHeader columnHeader42;
        private System.Windows.Forms.ColumnHeader columnHeader43;
        private System.Windows.Forms.ContextMenuStrip contextMenuStrip1;
        private System.Windows.Forms.ToolStripMenuItem killProcessToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem forceKillProcessToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem forceKillDeleteFileToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem changeWindowTextToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem copyToClipboardToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem viewLoadeddllsToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem viewRunningThreadsToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem suspendProcessToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem resumeProcessToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem refreshToolStripMenuItem;
        private System.Windows.Forms.ToolStripTextBox toolStripTextBox1;
        private System.Windows.Forms.ToolStripMenuItem setWindowTextToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem startNewProcessToolStripMenuItem;
        private System.Windows.Forms.ToolStripTextBox toolStripTextBox2;
        private System.Windows.Forms.ToolStripMenuItem startSelectedProcessToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem startToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem startHiddenToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem viewProcessLocationToolStripMenuItem;
    }
}