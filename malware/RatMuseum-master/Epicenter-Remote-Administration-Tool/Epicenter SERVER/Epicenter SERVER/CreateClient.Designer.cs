namespace Epicenter_SERVER
{
    partial class CreateClient
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(CreateClient));
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.txtName = new System.Windows.Forms.TextBox();
            this.txtIP = new System.Windows.Forms.TextBox();
            this.txtComm = new System.Windows.Forms.TextBox();
            this.label5 = new System.Windows.Forms.Label();
            this.btnCreate = new System.Windows.Forms.Button();
            this.txtImageName = new System.Windows.Forms.TextBox();
            this.label6 = new System.Windows.Forms.Label();
            this.label7 = new System.Windows.Forms.Label();
            this.radioButton1 = new System.Windows.Forms.RadioButton();
            this.radioButton2 = new System.Windows.Forms.RadioButton();
            this.radioButton3 = new System.Windows.Forms.RadioButton();
            this.label8 = new System.Windows.Forms.Label();
            this.label9 = new System.Windows.Forms.Label();
            this.txtAuth = new System.Windows.Forms.TextBox();
            this.txtConn = new System.Windows.Forms.TextBox();
            this.label10 = new System.Windows.Forms.Label();
            this.label11 = new System.Windows.Forms.Label();
            this.label12 = new System.Windows.Forms.Label();
            this.SuspendLayout();
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(12, 12);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(413, 13);
            this.label1.TabIndex = 0;
            this.label1.Text = "You can create an Epicenter Client by filling out the fields below and clicking \"" +
                "Create\".";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(12, 45);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(67, 13);
            this.label2.TabIndex = 1;
            this.label2.Text = "Client Name:";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(12, 71);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(155, 13);
            this.label3.TabIndex = 4;
            this.label3.Text = "Server IP address or hostname:";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(12, 97);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(103, 13);
            this.label4.TabIndex = 6;
            this.label4.Text = "Communication port:";
            // 
            // txtName
            // 
            this.txtName.Location = new System.Drawing.Point(175, 42);
            this.txtName.Name = "txtName";
            this.txtName.Size = new System.Drawing.Size(139, 20);
            this.txtName.TabIndex = 2;
            // 
            // txtIP
            // 
            this.txtIP.Location = new System.Drawing.Point(175, 68);
            this.txtIP.Name = "txtIP";
            this.txtIP.Size = new System.Drawing.Size(139, 20);
            this.txtIP.TabIndex = 5;
            // 
            // txtComm
            // 
            this.txtComm.Location = new System.Drawing.Point(175, 94);
            this.txtComm.Name = "txtComm";
            this.txtComm.Size = new System.Drawing.Size(139, 20);
            this.txtComm.TabIndex = 7;
            this.txtComm.Text = "80";
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label5.Location = new System.Drawing.Point(320, 45);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(67, 12);
            this.label5.TabIndex = 3;
            this.label5.Text = "(max. 20chars)";
            // 
            // btnCreate
            // 
            this.btnCreate.Location = new System.Drawing.Point(15, 319);
            this.btnCreate.Name = "btnCreate";
            this.btnCreate.Size = new System.Drawing.Size(299, 23);
            this.btnCreate.TabIndex = 21;
            this.btnCreate.Text = "Create";
            this.btnCreate.UseVisualStyleBackColor = true;
            this.btnCreate.Click += new System.EventHandler(this.btnCreate_Click);
            // 
            // txtImageName
            // 
            this.txtImageName.Location = new System.Drawing.Point(175, 215);
            this.txtImageName.Name = "txtImageName";
            this.txtImageName.Size = new System.Drawing.Size(139, 20);
            this.txtImageName.TabIndex = 16;
            this.txtImageName.Text = "scvhost.exe";
            // 
            // label6
            // 
            this.label6.AutoSize = true;
            this.label6.Location = new System.Drawing.Point(12, 218);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(110, 13);
            this.label6.TabIndex = 15;
            this.label6.Text = "Dumped image name:";
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.Location = new System.Drawing.Point(12, 248);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(78, 13);
            this.label7.TabIndex = 17;
            this.label7.Text = "Dump location:";
            // 
            // radioButton1
            // 
            this.radioButton1.AutoSize = true;
            this.radioButton1.Checked = true;
            this.radioButton1.Enabled = false;
            this.radioButton1.Location = new System.Drawing.Point(175, 246);
            this.radioButton1.Name = "radioButton1";
            this.radioButton1.Size = new System.Drawing.Size(67, 17);
            this.radioButton1.TabIndex = 18;
            this.radioButton1.TabStop = true;
            this.radioButton1.Text = "AppData";
            this.radioButton1.UseVisualStyleBackColor = true;
            // 
            // radioButton2
            // 
            this.radioButton2.AutoSize = true;
            this.radioButton2.Enabled = false;
            this.radioButton2.Location = new System.Drawing.Point(175, 269);
            this.radioButton2.Name = "radioButton2";
            this.radioButton2.Size = new System.Drawing.Size(52, 17);
            this.radioButton2.TabIndex = 19;
            this.radioButton2.Text = "Temp";
            this.radioButton2.UseVisualStyleBackColor = true;
            // 
            // radioButton3
            // 
            this.radioButton3.AutoSize = true;
            this.radioButton3.Enabled = false;
            this.radioButton3.Location = new System.Drawing.Point(175, 292);
            this.radioButton3.Name = "radioButton3";
            this.radioButton3.Size = new System.Drawing.Size(71, 17);
            this.radioButton3.TabIndex = 20;
            this.radioButton3.Text = "System32";
            this.radioButton3.UseVisualStyleBackColor = true;
            // 
            // label8
            // 
            this.label8.AutoSize = true;
            this.label8.Location = new System.Drawing.Point(12, 141);
            this.label8.Name = "label8";
            this.label8.Size = new System.Drawing.Size(153, 13);
            this.label8.TabIndex = 8;
            this.label8.Text = "Authentication attempt interval:";
            // 
            // label9
            // 
            this.label9.AutoSize = true;
            this.label9.Location = new System.Drawing.Point(12, 167);
            this.label9.Name = "label9";
            this.label9.Size = new System.Drawing.Size(139, 13);
            this.label9.TabIndex = 12;
            this.label9.Text = "Connection attempt interval:";
            // 
            // txtAuth
            // 
            this.txtAuth.Location = new System.Drawing.Point(175, 138);
            this.txtAuth.Name = "txtAuth";
            this.txtAuth.Size = new System.Drawing.Size(93, 20);
            this.txtAuth.TabIndex = 9;
            this.txtAuth.Text = "60";
            // 
            // txtConn
            // 
            this.txtConn.Location = new System.Drawing.Point(175, 164);
            this.txtConn.Name = "txtConn";
            this.txtConn.Size = new System.Drawing.Size(93, 20);
            this.txtConn.TabIndex = 13;
            this.txtConn.Text = "60";
            // 
            // label10
            // 
            this.label10.AutoSize = true;
            this.label10.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label10.Location = new System.Drawing.Point(320, 141);
            this.label10.Name = "label10";
            this.label10.Size = new System.Drawing.Size(146, 36);
            this.label10.TabIndex = 11;
            this.label10.Text = "These are advanced features.\r\nDon\'t touch them unless you know\r\nwhat they mean.";
            // 
            // label11
            // 
            this.label11.AutoSize = true;
            this.label11.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label11.Location = new System.Drawing.Point(270, 141);
            this.label11.Name = "label11";
            this.label11.Size = new System.Drawing.Size(40, 12);
            this.label11.TabIndex = 10;
            this.label11.Text = "seconds";
            // 
            // label12
            // 
            this.label12.AutoSize = true;
            this.label12.Font = new System.Drawing.Font("Microsoft Sans Serif", 6.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(162)));
            this.label12.Location = new System.Drawing.Point(270, 168);
            this.label12.Name = "label12";
            this.label12.Size = new System.Drawing.Size(40, 12);
            this.label12.TabIndex = 14;
            this.label12.Text = "seconds";
            // 
            // CreateClient
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(471, 354);
            this.Controls.Add(this.label12);
            this.Controls.Add(this.label11);
            this.Controls.Add(this.label10);
            this.Controls.Add(this.txtConn);
            this.Controls.Add(this.txtAuth);
            this.Controls.Add(this.label9);
            this.Controls.Add(this.label8);
            this.Controls.Add(this.radioButton3);
            this.Controls.Add(this.radioButton2);
            this.Controls.Add(this.radioButton1);
            this.Controls.Add(this.label7);
            this.Controls.Add(this.txtImageName);
            this.Controls.Add(this.label6);
            this.Controls.Add(this.btnCreate);
            this.Controls.Add(this.label5);
            this.Controls.Add(this.txtComm);
            this.Controls.Add(this.txtIP);
            this.Controls.Add(this.txtName);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.MaximizeBox = false;
            this.Name = "CreateClient";
            this.Text = "Create an Epicenter Client";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.TextBox txtName;
        private System.Windows.Forms.TextBox txtIP;
        private System.Windows.Forms.TextBox txtComm;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Button btnCreate;
        private System.Windows.Forms.TextBox txtImageName;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.RadioButton radioButton1;
        private System.Windows.Forms.RadioButton radioButton2;
        private System.Windows.Forms.RadioButton radioButton3;
        private System.Windows.Forms.Label label8;
        private System.Windows.Forms.Label label9;
        private System.Windows.Forms.TextBox txtAuth;
        private System.Windows.Forms.TextBox txtConn;
        private System.Windows.Forms.Label label10;
        private System.Windows.Forms.Label label11;
        private System.Windows.Forms.Label label12;
    }
}