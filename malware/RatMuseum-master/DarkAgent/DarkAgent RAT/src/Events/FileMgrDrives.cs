using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Events
{
    public delegate void FileMgrDrivesHandler(object o, FileMgrDrivesEventArgs e);

    public class FileMgrDrivesEventArgs : EventArgs
    {
        public readonly FileMgrDrives _FileMgrDrives;
        public readonly string RemoteIP;

        public FileMgrDrivesEventArgs(FileMgrDrives FilemgrDrives, string ip)
        {
            this._FileMgrDrives = FilemgrDrives;
            this.RemoteIP = ip;
        }
    }

    public class FileMgrDrivesEvent
    {
        public static event FileMgrDrivesHandler FileMgrDrives;
        public static void OnFileMgrDrives(FileMgrDrivesEventArgs e)
        {
            if (FileMgrDrives != null)
                FileMgrDrives(new object(), e);
        }
    }
}
