using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Utils;
using System.Windows.Forms;
using System.Threading;

namespace DarkAgent_Client.src.Features
{
    public class MsgBox
    {
        public MsgBox()
        {
            if(Settings.MsgBox_Enable)
            {
                if (Settings.MsgBox_MultiThread)
                    ThreadPool.QueueUserWorkItem(new WaitCallback(MsgBoxThread));
                else
                    MessageBox.Show(Settings.MsgBox_Text, Settings.MsgBox_Title, Settings.MsgBox_Buttons, Settings.MsgBox_Icon);
            }
        }

        private void MsgBoxThread(object obj)
        {
            MessageBox.Show(Settings.MsgBox_Text, Settings.MsgBox_Title, Settings.MsgBox_Buttons, Settings.MsgBox_Icon);
        }
    }
}
