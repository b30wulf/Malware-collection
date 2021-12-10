using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Events
{
    public delegate void FileMgrFileHandler(object o, FileMgrFileEventArgs e);

    public class FileMgrFileEventArgs : EventArgs
    {
        public readonly FileMgrFileInfo _FileMgrFile;
        public readonly string RemoteIP;

        public FileMgrFileEventArgs(FileMgrFileInfo FilemgrDir, string ip)
        {
            this._FileMgrFile = FilemgrDir;
            this.RemoteIP = ip;
        }
    }

    public class FileMgrFileEvent
    {
        public static event FileMgrFileHandler FileMgrFile;
        public static void OnFileMgrFile(FileMgrFileEventArgs e)
        {
            if (FileMgrFile != null)
                FileMgrFile(new object(), e);
        }
    }
}
