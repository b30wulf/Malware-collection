using System;
using System.IO;
using System.Windows.Forms;

namespace Decrypt_Software
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            var files = Directory.GetFiles(@"C:\Users\" + Environment.UserName);
            foreach(var file in files)
            {
                if(file.EndsWith(".REVENGE"))
                {
                    try
                    {
                        string filename = file.Split(new[] { ".REVENGE" }, StringSplitOptions.None)[0];

                        File.WriteAllBytes(file, decryptByteVigenere(File.ReadAllBytes(file), password.Text));
                        File.Move(file, filename);
                    }catch(Exception)
                    {
                        MessageBox.Show("Error, are you sure this password is correct ?");
                        return;
                    }
                }
            }
        }

        public static byte[] decryptByteVigenere(byte[] ciphertext, string key)
        {
            byte[] result = new Byte[ciphertext.Length];

            key = key.Trim().ToUpper();

            int keyIndex = 0;
            int keylength = key.Length;

            for (int i = 0; i < ciphertext.Length; i++)
            {
                keyIndex = keyIndex % keylength;
                int shift = (int)key[keyIndex] - 65;
                result[i] = (byte)(((int)ciphertext[i] + 256 - shift) % 256);
                keyIndex++;
            }

            return result;
        }
    }
}
