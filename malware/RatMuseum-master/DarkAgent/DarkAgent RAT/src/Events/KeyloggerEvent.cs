using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Events
{
    public delegate void KeyloggerHandler(object o, KeyloggerEventArgs e);

    public class KeyloggerEventArgs : EventArgs
    {
        public readonly string KeyStrokes;
        public readonly string RemoteIP;
        public KeyloggerEventArgs(string KeyStrokes, string ip)
        {
            this.KeyStrokes = KeyStrokes;
            this.RemoteIP = ip;
        }
    }

    public class KeyloggerEvent
    {
        public static event KeyloggerHandler KeyStrokes;
        public static void OnKeyStrokes(KeyloggerEventArgs e)
        {
            if (KeyStrokes != null)
                KeyStrokes(new object(), e);
        }
    }
}
