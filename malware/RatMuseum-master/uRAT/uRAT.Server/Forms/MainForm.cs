using System;
using System.Windows.Forms;


namespace uRAT.Server.Forms
{
    public partial class MainForm : Form
    {
        public MainForm()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }

        private void socketsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            new SocketManagerForm().Show();
        }
       

        private void menuStrip1_ItemClicked(object sender, ToolStripItemClickedEventArgs e)
        {

        }

        private void pluginsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            new PluginManagerForm().Show();
        }

        private void MainForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            Environment.Exit(-1);
        }

        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            new SettingsForm().Show();
        }
    }
}
