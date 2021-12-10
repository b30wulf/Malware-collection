using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Events
{
    public delegate void FileMgrDirHandler(object o, FileMgrDirEventArgs e);

    public class FileMgrDirEventArgs : EventArgs
    {
        public readonly FileMgrDirInfo _FileMgrDir;
        public readonly string RemoteIP;

        public FileMgrDirEventArgs(FileMgrDirInfo FilemgrDir, string ip)
        {
            this._FileMgrDir = FilemgrDir;
            this.RemoteIP = ip;
        }
    }

    public class FileMgrDirEvent
    {
        public static event FileMgrDirHandler FileMgrDir;
        public static void OnFileMgrDir(FileMgrDirEventArgs e)
        {
            if (FileMgrDir != null)
                FileMgrDir(new object(), e);
        }
    }
}
