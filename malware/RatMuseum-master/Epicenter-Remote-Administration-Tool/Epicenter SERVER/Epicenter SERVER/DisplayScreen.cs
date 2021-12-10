using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Threading;

namespace Epicenter_SERVER
{
    public partial class DisplayScreen : Form
    {
        string location;

        public DisplayScreen(string _location)
        {
            InitializeComponent();
            location = _location;
            pbPicture.Image = Image.FromFile(location);
        }

        private void btnSave_Click(object sender, EventArgs e)
        {
            Thread thSave = new Thread(new ThreadStart(Saver));
            thSave.SetApartmentState(ApartmentState.STA);
            thSave.Start();
        }

        private void Saver()
        {
            SaveFileDialog dia = new SaveFileDialog();

            if (dia.ShowDialog() == DialogResult.OK)
            {
                File.Copy(location, dia.FileName);
                MessageBox.Show("Screenshot saved successfully!", "Saved", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }
    }
}
