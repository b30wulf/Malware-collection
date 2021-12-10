using System.Windows.Forms; //For form intercation and controls

#pragma warning disable IDE1006

namespace TutServer //The application namespace
{
    /// <summary>
    /// Class for handling remote IPC connections
    /// </summary>
    public partial class RemotePipe : Form
    {
        #region Global Variables

        /// <summary>
        /// Reference to the main form
        /// </summary>
        private Form1 ctx;
        /// <summary>
        /// The remote IPC server name
        /// </summary>
        public string pname;
        /// <summary>
        /// Indicates if the pipe is connected to the remote IPC server
        /// </summary>
        public bool RemoteRemove = true;
        /// <summary>
        /// The output sent by the remote IPC server
        /// </summary>
        public RichTextBox outputBox;

        #endregion

        #region Remote Pipe and Form Functions

        /// <summary>
        /// Constructor
        /// </summary>
        /// <param name="pipeName">The name of the IPC server</param>
        /// <param name="context">Reference to the main form</param>
        public RemotePipe(string pipeName, Form1 context)
        {
            InitializeComponent(); //Init the controls
            Text = $"Remote Pipe Connection ({pipeName})"; //Set the title text
            pname = pipeName; //Set the IPC server name
            ctx = context; //Set the main form reference
            outputBox = richTextBox1; //Set the output of the IPC server
        }

        /// <summary>
        /// Handles the sending of IPC input
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void textBox1_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Enter) //if enter is pressed
            {
                string data = textBox1.Text; //Get the data in the input
                string cmd = $"writeipc§{pname}§{data}"; //Construct the command
                ctx.SendToTarget(cmd); //Send the command to the client
                textBox1.Text = ""; //Clear the input box
            }
        }

        /// <summary>
        /// Delegate used for writing IPC output
        /// </summary>
        /// <param name="text">The text to write to the IPC output</param>
        private delegate void SetTextCallback(string text);

        /// <summary>
        /// Write to the IPC output
        /// </summary>
        /// <param name="text">The text to write to the output</param>
        private void SetText(string text)
        {
            if (InvokeRequired) //If we need to invoke
            {
                SetTextCallback stc = new SetTextCallback(SetText); //Create a new callback
                Invoke(stc, new object[] { text }); //Invoke the callback
                return; //Return
            }

            richTextBox1.Text = text; //Set the output text
        }

        /// <summary>
        /// Write the output of an IPC command
        /// </summary>
        /// <param name="output">The output of the IPC server</param>
        public void WriteOutput(string output)
        {
            SetText(output); //Write the output to the richTextBox
        }

        /// <summary>
        /// Close the remote pipe
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void remotePipe_FormClosing(object sender, FormClosingEventArgs e)
        {
            ctx.RemovePipe(this, RemoteRemove); //Remove the pipe from the list
        }

        #endregion
    }
}
