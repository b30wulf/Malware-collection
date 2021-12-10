namespace DarkAgent_RAT
{
    partial class ProcessThreads
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(ProcessThreads));
            this.listView1 = new System.Windows.Forms.ListView();
            this.columnHeader1 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader2 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader3 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader4 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader5 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader6 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader7 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.contextMenuStrip1 = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.suspendThreadToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.resumeThreadToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.terminateThreadToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.copyToClipboardToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.contextMenuStrip1.SuspendLayout();
            this.SuspendLayout();
            // 
            // listView1
            // 
            this.listView1.Columns.AddRange(new System.Windows.Forms.ColumnHeader[] {
            this.columnHeader1,
            this.columnHeader2,
            this.columnHeader3,
            this.columnHeader4,
            this.columnHeader5,
            this.columnHeader6,
            this.columnHeader7});
            this.listView1.ContextMenuStrip = this.contextMenuStrip1;
            this.listView1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.listView1.FullRowSelect = true;
            this.listView1.GridLines = true;
            this.listView1.Location = new System.Drawing.Point(0, 0);
            this.listView1.Name = "listView1";
            this.listView1.Size = new System.Drawing.Size(668, 257);
            this.listView1.Sorting = System.Windows.Forms.SortOrder.Ascending;
            this.listView1.TabIndex = 0;
            this.listView1.UseCompatibleStateImageBehavior = false;
            this.listView1.View = System.Windows.Forms.View.Details;
            this.listView1.ColumnClick += new System.Windows.Forms.ColumnClickEventHandler(this.listView1_ColumnClick);
            // 
            // columnHeader1
            // 
            this.columnHeader1.Text = "ID";
            this.columnHeader1.Width = 41;
            // 
            // columnHeader2
            // 
            this.columnHeader2.Text = "Priority";
            this.columnHeader2.Width = 68;
            // 
            // columnHeader3
            // 
            this.columnHeader3.Text = "Wait Reason";
            this.columnHeader3.Width = 92;
            // 
            // columnHeader4
            // 
            this.columnHeader4.Text = "Priority Boost";
            this.columnHeader4.Width = 98;
            // 
            // columnHeader5
            // 
            this.columnHeader5.Text = "Privileged Processor Time";
            this.columnHeader5.Width = 157;
            // 
            // columnHeader6
            // 
            this.columnHeader6.Text = "Start Time";
            this.columnHeader6.Width = 88;
            // 
            // columnHeader7
            // 
            this.columnHeader7.Text = "Thread State";
            this.columnHeader7.Width = 104;
            // 
            // contextMenuStrip1
            // 
            this.contextMenuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.suspendThreadToolStripMenuItem,
            this.resumeThreadToolStripMenuItem,
            this.terminateThreadToolStripMenuItem,
            this.copyToClipboardToolStripMenuItem});
            this.contextMenuStrip1.Name = "contextMenuStrip1";
            this.contextMenuStrip1.Size = new System.Drawing.Size(172, 114);
            // 
            // suspendThreadToolStripMenuItem
            // 
            this.suspendThreadToolStripMenuItem.Name = "suspendThreadToolStripMenuItem";
            this.suspendThreadToolStripMenuItem.Size = new System.Drawing.Size(171, 22);
            this.suspendThreadToolStripMenuItem.Text = "Suspend Thread";
            this.suspendThreadToolStripMenuItem.Click += new System.EventHandler(this.suspendThreadToolStripMenuItem_Click);
            // 
            // resumeThreadToolStripMenuItem
            // 
            this.resumeThreadToolStripMenuItem.Name = "resumeThreadToolStripMenuItem";
            this.resumeThreadToolStripMenuItem.Size = new System.Drawing.Size(171, 22);
            this.resumeThreadToolStripMenuItem.Text = "Resume Thread";
            this.resumeThreadToolStripMenuItem.Click += new System.EventHandler(this.resumeThreadToolStripMenuItem_Click);
            // 
            // terminateThreadToolStripMenuItem
            // 
            this.terminateThreadToolStripMenuItem.Name = "terminateThreadToolStripMenuItem";
            this.terminateThreadToolStripMenuItem.Size = new System.Drawing.Size(171, 22);
            this.terminateThreadToolStripMenuItem.Text = "Terminate Thread";
            this.terminateThreadToolStripMenuItem.Click += new System.EventHandler(this.terminateThreadToolStripMenuItem_Click);
            // 
            // copyToClipboardToolStripMenuItem
            // 
            this.copyToClipboardToolStripMenuItem.Name = "copyToClipboardToolStripMenuItem";
            this.copyToClipboardToolStripMenuItem.Size = new System.Drawing.Size(171, 22);
            this.copyToClipboardToolStripMenuItem.Text = "Copy to Clipboard";
            this.copyToClipboardToolStripMenuItem.Click += new System.EventHandler(this.copyToClipboardToolStripMenuItem_Click);
            // 
            // ProcessThreads
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(668, 257);
            this.Controls.Add(this.listView1);
            this.DoubleBuffered = true;
            this.EnableGlass = false;
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "ProcessThreads";
            this.Text = "DarkAgent - Thread Manager";
            this.Load += new System.EventHandler(this.ProcessThreads_Load);
            this.contextMenuStrip1.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.ListView listView1;
        private System.Windows.Forms.ColumnHeader columnHeader1;
        private System.Windows.Forms.ColumnHeader columnHeader2;
        private System.Windows.Forms.ColumnHeader columnHeader3;
        private System.Windows.Forms.ColumnHeader columnHeader4;
        private System.Windows.Forms.ColumnHeader columnHeader5;
        private System.Windows.Forms.ColumnHeader columnHeader6;
        private System.Windows.Forms.ColumnHeader columnHeader7;
        private System.Windows.Forms.ContextMenuStrip contextMenuStrip1;
        private System.Windows.Forms.ToolStripMenuItem suspendThreadToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem resumeThreadToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem terminateThreadToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem copyToClipboardToolStripMenuItem;
    }
}