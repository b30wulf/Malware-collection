using System; //Fo basic system functions
using System.Drawing; //For form graphics
using System.Threading.Tasks; //For Tasks (they are similar to threads)
using System.Windows.Forms; //For form intercation and controls

#pragma warning disable IDE1006

namespace TutServer //The application namespace
{
    /// <summary>
    /// The main form for handling full screen remote desktop control
    /// </summary>
    public partial class RDesktop : Form
    {
        #region Global Variables

        /// <summary>
        /// Main Form reference
        /// </summary>
        Form parent = Application.OpenForms["Form1"];
        /// <summary>
        /// The frame sent by the client
        /// </summary>
        public Bitmap image;
        /// <summary>
        /// FPS Update rate
        /// </summary>
        private int FPS = 80;
        /// <summary>
        /// Mouse movement flag
        /// </summary>
        private bool mouseMovement = true;

        #endregion

        #region Form and Remote Desktop Functions

        /// <summary>
        /// Create a new full screen remote desktop controller
        /// </summary>
        public RDesktop()
        {
            InitializeComponent(); //Init the controls

            ScreenFPS(); //Set the FPS rate

            MessageBox.Show("Press the (Esc) Key to Exit this Function " , "Information" ,MessageBoxButtons.OK ,MessageBoxIcon.Information); //Notify the user
        }

        /// <summary>
        /// Handles remote mouse button clicks
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            if (Form1.rmouse == 1) //If remote mouse is enabled
            {
                if (e.Button == MouseButtons.Left) //If left button is clicked
                {
                  
                    ((Form1)parent).SendToTarget("rclick-left-down"); //Send command to client
                }
                else //Right button is clicked
                {
                    ((Form1)parent).SendToTarget("rclick-right-down"); //Send command to client
                }
            }
        }

        /// <summary>
        /// Handles remote mouse button clicks
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            if (Form1.rmouse == 1) //If remote mouse control is enabled
            {
                if (e.Button == MouseButtons.Left) //The left button is pressed
                {
                    ((Form1)parent).SendToTarget("rclick-left-up"); //Send command to client
                }
                else //Right button is pressed
                {
                    ((Form1)parent).SendToTarget("rclick-right-up"); //Send command to client
                }
            }
        }

        /// <summary>
        /// Handles remote mouse movement
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private async void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            if (mouseMovement == true) //if mouse movement is enabled
            {
                Rectangle scr = Screen.PrimaryScreen.WorkingArea; //Get the screen size

                if (Form1.IsRdFull) //If we are in full screen mode
                {
                    scr = pictureBox1.DisplayRectangle; //Get the size of the pictureBox
                }

                try //Try
                {
                    int mx = (e.X * Form1.resx) / scr.Width; //Calculate the remote mouse position X
                    int my = (e.Y * Form1.resy) / scr.Height; //Calcualte the remote mouse position Y

                    if (Form1.rmouse == 1) //If remoute mouse if enabled
                    {
                        if (Form1.plx != e.X || Form1.ply != e.Y) //The mouse moved after the last move
                        {
                            ((Form1)parent).SendToTarget("rmove-" + mx + ":" + my); //Send command to client
                            Form1.plx = e.X; //Store last X position
                            Form1.ply = e.Y; //Store last Y position

                            mouseMovement = false; //Disable mouse movement
                        }
                        //Wait for 200 ms
                        await Task.Delay(200); //this should stop the spammings of send commands -move the coursor very slowly and it will lockup so i added this
                        //Re enable mouse movements
                        mouseMovement = true; //and this switch ,cant send again for 200 ms - it works perfectly i am happy with it :-)
                    }
                }
                catch (Exception) //Something went wrong
                {
                    //Do nothing
                }
            }
        }

        /// <summary>
        /// Handles remote kexboard
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void RDesktop_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Escape) //If escape is pressed
            {
                closeWindowToolStripMenuItem1.Show(); //Open this CMS
            }

            if (Form1.rkeyboard == 1) //If remote keyboard is enabled
            {
                string keysToSend = ""; //Declare the keys to send

                //Append the modifier keys
                if (e.Shift)
                    keysToSend += "+";
                if (e.Alt)
                    keysToSend += "%";
                if (e.Control)
                    keysToSend += "^";

                if (Console.CapsLock == true) //Caps Lock enabled
                {
                    if (e.KeyValue >= 65 && e.KeyValue <= 90) //If key falls in this range
                    {
                        keysToSend += e.KeyCode.ToString().ToLower(); //Send the key in lowercase
                    }
                }

                if (Console.CapsLock == false) //If Caps Lock is disabled
                {
                    if (e.KeyValue >= 65 && e.KeyValue <= 90) //If key falls in range
                    {
                        keysToSend += e.KeyCode.ToString().ToUpper(); //Send the key in upper case
                    }
                    
                }
                
                //Handle special keys

                if (e.KeyCode.ToString().Equals("Back"))
                    keysToSend += "{BS}";
                else if (e.KeyCode.ToString().Equals("Pause"))
                    keysToSend += "{BREAK}";
                else if (e.KeyCode.ToString().Equals("Capital"))
                    keysToSend += "{CAPSLOCK}";
                else if (e.KeyCode.ToString().Equals("Space"))
                    keysToSend += " ";
                else if (e.KeyCode.ToString().Equals("Home"))
                    keysToSend += "{HOME}";
                else if (e.KeyCode.ToString().Equals("Return"))
                    keysToSend += "{ENTER}";
                else if (e.KeyCode.ToString().Equals("End"))
                    keysToSend += "{END}";
                else if (e.KeyCode.ToString().Equals("Tab"))
                    keysToSend += "{TAB}";
                else if (e.KeyCode.ToString().Equals("Escape"))
                    keysToSend += "{ESC}";
                else if (e.KeyCode.ToString().Equals("Insert"))
                    keysToSend += "{INS}";
                else if (e.KeyCode.ToString().Equals("Up"))
                    keysToSend += "{UP}";
                else if (e.KeyCode.ToString().Equals("Down"))
                    keysToSend += "{DOWN}";
                else if (e.KeyCode.ToString().Equals("Left"))
                    keysToSend += "{LEFT}";
                else if (e.KeyCode.ToString().Equals("Right"))
                    keysToSend += "{RIGHT}";
                else if (e.KeyCode.ToString().Equals("PageUp"))
                    keysToSend += "{PGUP}";
                else if (e.KeyCode.ToString().Equals("Next"))
                    keysToSend += "{PGDN}";
                else if (e.KeyCode.ToString().Equals("Tab"))
                    keysToSend += "{TAB}";
                else if (e.KeyCode.ToString().Equals("D1"))
                    keysToSend += "1";
                else if (e.KeyCode.ToString().Equals("D2"))
                    keysToSend += "2";
                else if (e.KeyCode.ToString().Equals("D3"))
                    keysToSend += "3";
                else if (e.KeyCode.ToString().Equals("D4"))
                    keysToSend += "4";
                else if (e.KeyCode.ToString().Equals("D5"))
                    keysToSend += "5";
                else if (e.KeyCode.ToString().Equals("D6"))
                    keysToSend += "6";
                else if (e.KeyCode.ToString().Equals("D7"))
                    keysToSend += "7";
                else if (e.KeyCode.ToString().Equals("D8"))
                    keysToSend += "8";
                else if (e.KeyCode.ToString().Equals("D9"))
                    keysToSend += "9";
                else if (e.KeyCode.ToString().Equals("D0"))
                    keysToSend += "0";
                else if (e.KeyCode.ToString().Equals("F1"))
                    keysToSend += "{F1}";
                else if (e.KeyCode.ToString().Equals("F2"))
                    keysToSend += "{F2}";
                else if (e.KeyCode.ToString().Equals("F3"))
                    keysToSend += "{F3}";
                else if (e.KeyCode.ToString().Equals("F4"))
                    keysToSend += "{F4}";
                else if (e.KeyCode.ToString().Equals("F5"))
                    keysToSend += "{F5}";
                else if (e.KeyCode.ToString().Equals("F6"))
                    keysToSend += "{F6}";
                else if (e.KeyCode.ToString().Equals("F7"))
                    keysToSend += "{F7}";
                else if (e.KeyCode.ToString().Equals("F8"))
                    keysToSend += "{F8}";
                else if (e.KeyCode.ToString().Equals("F9"))
                    keysToSend += "{F9}";
                else if (e.KeyCode.ToString().Equals("F10"))
                    keysToSend += "{F10}";
                else if (e.KeyCode.ToString().Equals("F11"))
                    keysToSend += "{F11}";
                else if (e.KeyCode.ToString().Equals("F12"))
                    keysToSend += "{F12}";
                else if (e.KeyValue == 186)
                    keysToSend += "{;}";
                else if (e.KeyValue == 222)
                    keysToSend += "'";
                else if (e.KeyValue == 191)
                    keysToSend += "/";
                else if (e.KeyValue == 190)
                    keysToSend += ".";
                else if (e.KeyValue == 188)
                    keysToSend += ",";
                else if (e.KeyValue == 219)
                    keysToSend += "{[}";
                else if (e.KeyValue == 221)
                    keysToSend += "{]}";
                else if (e.KeyValue == 220)
                    keysToSend += "\\";
                else if (e.KeyValue == 187)
                    keysToSend += "{=}";
                else if (e.KeyValue == 189)
                    keysToSend += "{-}";
                else if (e.KeyValue == 233)
                    keysToSend += "é";
                else if (e.KeyValue == 225)
                    keysToSend += "á";
                else if (e.KeyValue == 369)
                    keysToSend += "ű";
                else if (e.KeyValue == 337)
                    keysToSend += "ő";
                else if (e.KeyValue == 250)
                    keysToSend += "ú";
                else if (e.KeyValue == 246)
                    keysToSend += "ö";
                else if (e.KeyValue == 252)
                    keysToSend += "ü";
                else if (e.KeyValue == 243)
                    keysToSend += "ó";
             
                ((Form1)parent).SendToTarget($"rtype-{keysToSend}"); //Send command to the client
            }
        }

        /// <summary>
        /// Handles the start of image updater
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void RDesktop_Shown(object sender, EventArgs e)
        {
            Timer t = new Timer
            {
                // t.Interval = 100;
                Interval = FPS //Set the frequency to the screen update rate
            }; //Create a new timer
            t.Tick += new EventHandler(UpdateImage); //Set the tick event handler
            t.Start(); //Start the timer
        }

        /// <summary>
        /// Update the image frame
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void UpdateImage(object sender, EventArgs e)
        {
            if (image != null) //If the image is not null
            {
                pictureBox1.Image = image; //Set the image
            }

            //Call garbage collector
            GC.Collect();
            GC.WaitForPendingFinalizers();
            System.Threading.Thread.SpinWait(5000);
        }

        /// <summary>
        /// Close the full screen view
        /// </summary>
        /// <param name="sender">The sender of the event</param>
        /// <param name="e">The event args</param>
        private void closeWindowToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Form1 f1 = (Form1)parent; //Create a new Form1

            //Reset the checkboxes

            if (f1.checkBoxrKeyboard.Checked)
            {
                f1.checkBoxrKeyboard.Checked = false;
            }
            if (f1.checkBoxrMouse.Checked)
            {
                f1.checkBoxrMouse.Checked = false;
            }

            Form1.IsRdFull = false; //reset the picture back to form1 picturebox1

           
            Close(); //Close the form
        }

        /// <summary>
        /// Set the FPS update rate
        /// </summary>
        public void ScreenFPS()
        {
            int value = ((Form1)parent).trackBar1.Value; //Get the FPS value

            //Set the fps rate
            if (value < 25)
                FPS = 150;  //low
            else if (value >= 75 && value <= 85)
                FPS = 80; //best
            else if (value >= 85)
                FPS = 50; //high
            else if (value >= 25)
                FPS = 100; //mid           
        }

        #endregion
    }
}
