namespace TutServer
{
    partial class RDesktop
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
            this.pictureBox1 = new System.Windows.Forms.PictureBox();
            this.closeWindowToolStripMenuItem1 = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.closeWindowToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBox1)).BeginInit();
            this.closeWindowToolStripMenuItem1.SuspendLayout();
            this.SuspendLayout();
            // 
            // pictureBox1
            // 
            this.pictureBox1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.pictureBox1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.pictureBox1.Location = new System.Drawing.Point(0, 0);
            this.pictureBox1.Name = "pictureBox1";
            this.pictureBox1.Size = new System.Drawing.Size(284, 262);
            this.pictureBox1.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.pictureBox1.TabIndex = 0;
            this.pictureBox1.TabStop = false;
            this.pictureBox1.MouseDown += new System.Windows.Forms.MouseEventHandler(this.pictureBox1_MouseDown);
            this.pictureBox1.MouseMove += new System.Windows.Forms.MouseEventHandler(this.pictureBox1_MouseMove);
            this.pictureBox1.MouseUp += new System.Windows.Forms.MouseEventHandler(this.pictureBox1_MouseUp);
            // 
            // closeWindowToolStripMenuItem1
            // 
            this.closeWindowToolStripMenuItem1.BackColor = System.Drawing.Color.Red;
            this.closeWindowToolStripMenuItem1.Font = new System.Drawing.Font("Segoe UI", 20.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.closeWindowToolStripMenuItem1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.closeWindowToolStripMenuItem});
            this.closeWindowToolStripMenuItem1.Name = "contextMenuStrip1";
            this.closeWindowToolStripMenuItem1.Size = new System.Drawing.Size(278, 46);
            // 
            // closeWindowToolStripMenuItem
            // 
            this.closeWindowToolStripMenuItem.Name = "closeWindowToolStripMenuItem";
            this.closeWindowToolStripMenuItem.Size = new System.Drawing.Size(277, 42);
            this.closeWindowToolStripMenuItem.Text = "Close Window";
            this.closeWindowToolStripMenuItem.Click += new System.EventHandler(this.closeWindowToolStripMenuItem_Click);
            // 
            // RDesktop
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(284, 262);
            this.Controls.Add(this.pictureBox1);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            this.Name = "RDesktop";
            this.Text = "RDesktop FullScreen";
            this.WindowState = System.Windows.Forms.FormWindowState.Maximized;
            this.Shown += new System.EventHandler(this.RDesktop_Shown);
            this.KeyDown += new System.Windows.Forms.KeyEventHandler(this.RDesktop_KeyDown);
            ((System.ComponentModel.ISupportInitialize)(this.pictureBox1)).EndInit();
            this.closeWindowToolStripMenuItem1.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.PictureBox pictureBox1;
        private System.Windows.Forms.ContextMenuStrip closeWindowToolStripMenuItem1;
        private System.Windows.Forms.ToolStripMenuItem closeWindowToolStripMenuItem;
    }
}