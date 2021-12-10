using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Events
{
    public delegate void ClipboardHandler(object o, ClipboardEventArgs e);

    public class ClipboardEventArgs : EventArgs
    {
        public readonly string RemoteIP;
        public readonly string data;
        public ClipboardEventArgs(string RemoteIP, string data)
        {
            this.RemoteIP = RemoteIP;
            this.data = data;
        }
    }

    public class ClipboardEvent
    {
        public static event ClipboardHandler Clipboard;
        public static void OnClipboard(ClipboardEventArgs e)
        {
            if (Clipboard != null)
                Clipboard(new object(), e);
        }
    }
}