using System; //For basic system functions
using System.Collections.Generic; //For list
using System.Drawing; //For form graphics
using System.Windows.Forms; //For form interaction and controls
using sCore.UI; //The plugin core UI

#pragma warning disable IDE1006

namespace TutServer //The application namespace
{
    /// <summary>
    /// Class that handles editing of text based files
    /// </summary>
    public partial class Edit : Form
    {
        #region Global Variables

        /// <summary>
        /// The conten to edit
        /// </summary>
        private string content;
        /// <summary>
        /// Reference to the main form
        /// </summary>
        private Form1 prt;

        #endregion

        #region Form and Editor

        /// <summary>
        /// Create a new file editor
        /// </summary>
        /// <param name="textFile">The file content to edit</param>
        /// <param name="parent">Reference to the main form</param>
        public Edit(String textFile, Form1 parent)
        {
            content = textFile; //Set the edit content
            prt = parent; //Set the main form reference
            InitializeComponent(); //Init the controls
        }

        /// <summary>
        /// Editor loaded the controls and the form
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void Edit_Shown(object sender, EventArgs e)
        {
            richTextBox1.ReadOnly = true; //Set mode to read only
            richTextBox1.BackColor = SystemColors.Window; //Set the color to window (not the grayish color you get for read only)
            richTextBox1.Text = content; //Set the editor content
            CommonControls.editorTextBox = richTextBox1; //Notify the plugins of the file editor
        }

        /// <summary>
        /// Enable/Disable the read only property of the editor
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {
            bool isReadOnly = !checkBox1.Checked; //Get the state of read only
            richTextBox1.ReadOnly = isReadOnly; //Set the read only state
        }

        /// <summary>
        /// Clear the whole editor text
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button7_Click(object sender, EventArgs e)
        {
            richTextBox1.Clear(); //Clear the editor box
        }

        /// <summary>
        /// Decrypt the contents of the file
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button6_Click(object sender, EventArgs e)
        {
            try //Try
            {
                string[] lines = richTextBox1.Lines; //Get the lines of the text
                List<string> decrypted = new List<string>(); //Declare a new list for decrypted lines
                for (int i = 0; i < lines.Length; i++)//Go through each encrypted line
                {
                    string line = lines[i];
                    decrypted.Add(prt.Decrypt(line)); //decrypt the line and add it to the list
                }
                richTextBox1.Lines = decrypted.ToArray(); //Set the decrypted lines as the text
                richTextBox1.Refresh(); //Refresh the richTextBox's display
            }
            catch (Exception ex) //Can't decrypt
            {
                MessageBox.Show(this, "Error", "Error trying to decrypt content!\n" + ex.Message, MessageBoxButtons.OK, MessageBoxIcon.Error); //Notify the user
            }
        }

        /// <summary>
        /// Encrypt the editor text
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button5_Click(object sender, EventArgs e)
        {
            try //Try
            {
                string[] lines = richTextBox1.Lines; //Get the lines of the text
                List<string> encrypted = new List<string>(); //Declare a new list for encrypted lines
                for (int i = 0; i < lines.Length; i++)//Go through each plain text line
                {
                    string line = lines[i];
                    encrypted.Add(prt.Encrypt(line)); //Encrypt the lines and add it to the list
                }
                richTextBox1.Lines = encrypted.ToArray(); //Set the editor content to the encrypted lines
                richTextBox1.Refresh(); //Refresh the editor display
            }
            catch (Exception ex) //Can't encrypt
            {
                MessageBox.Show(this, "Error", "Error trying to encrypt content!\n" + ex.Message, MessageBoxButtons.OK, MessageBoxIcon.Error); //Notify the user
            }
        }

        /// <summary>
        /// Redo the last change made to the content
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button4_Click(object sender, EventArgs e)
        {
            richTextBox1.Redo(); //Redo a change
        }

        /// <summary>
        /// Undo the last change made to the content
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button3_Click(object sender, EventArgs e)
        {
            richTextBox1.Undo(); //Undo the last change
        }

        /// <summary>
        /// Close without saving
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button2_Click(object sender, EventArgs e)
        {
            Close(); //Close the form
        }

        /// <summary>
        /// Save the file on remote client
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void button1_Click(object sender, EventArgs e)
        {
            prt.SaveFile(richTextBox1.Text); //Save the file
        }

        /// <summary>
        /// Form Closing event (not saving the file)
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void Edit_FormClosing(object sender, FormClosingEventArgs e)
        {
            CommonControls.editorTextBox = null; //Remove the editro reference from the plugins
        }

        #endregion
    }
}
