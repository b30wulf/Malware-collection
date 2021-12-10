using System;
using System.Collections.Generic;
using System.Text;
using System.Drawing;

namespace DarkAgent_RAT.src.Events
{
    public delegate void RemoteControlHandler(object o, RemoteControlEventArgs e);

    public class RemoteControlEventArgs : EventArgs
    {
        public readonly string RemoteIP;
        public Image ScreenImage;
        public RemoteControlEventArgs(string RemoteIP, Image ScreenImage)
        {
            this.RemoteIP = RemoteIP;
            this.ScreenImage = ScreenImage;
        }
    }

    public class RemoteControlEvent
    {
        public static event RemoteControlHandler RemoteControl;
        public static void OnRemoteControl(RemoteControlEventArgs e)
        {
            if (RemoteControl != null)
                RemoteControl(new object(), e);
        }
    }
}
