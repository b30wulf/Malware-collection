using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uRAT.ManagersPlugin.ProcessManager.Operations;
using uRAT.ManagersPlugin.ProcessManager.Packets;

namespace uRAT.ManagersPlugin.ProcessManager.Forms
{
    public partial class StartProcessForm : Form
    {
        private ProcessManagerOperation _op;

        public StartProcessForm(ProcessManagerOperation op)
        {
            InitializeComponent();
            _op = op;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Close();
        }

        private void StartProcessForm_Load(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            _op.SendPacket(new StartProcessPacket(textBox1.Text, checkBox1.Checked));
            _op.SendPacket(new RefreshProcessesPacket());
            Close();
        }
    }
}
