using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace Epicenter_SERVER
{
    public partial class FileUploadDisplay : Form
    {
        administrate owner;
        public FileUploadDisplay(administrate _owner, string remotePosition)
        {
            InitializeComponent();
            txtDestination.Text = remotePosition;
            owner = _owner;
        }

        private void btnCancel_Click(object sender, EventArgs e)
        {
            DialogResult = DialogResult.Cancel;
            this.Close();
        }

        private void btnBrowse_Click(object sender, EventArgs e)
        {
            OpenFileDialog dia = new OpenFileDialog();

            if (dia.ShowDialog() == DialogResult.OK)
                txtLocal.Text = dia.FileName;
        }

        private void btnProceed_Click(object sender, EventArgs e)
        {
            if (String.IsNullOrEmpty(txtLocal.Text) || String.IsNullOrEmpty(txtDestination.Text))
            {
                MessageBox.Show("You haven't filled in all the necessary fields.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            owner.toUpload_local = txtLocal.Text;
            owner.toUpload_remote = txtDestination.Text;

            DialogResult = DialogResult.OK;
            this.Close();
        }


    }
}
