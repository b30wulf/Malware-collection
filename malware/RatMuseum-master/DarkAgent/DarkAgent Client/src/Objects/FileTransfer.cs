using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace DarkAgent_Client.src.Objects
{
    public class FileTransfer
    {
        public short Id { get; set; }
        public string FileName { get; set; }
        public string Destination { get; set; }
        public long FileSize { get; set; }
        public long CurFileSize { get; set; }
        public long CurSize { get; set; }
        public short type { get; set; } //0=FileMgr, -1=MonitorSpy
        public SortedList<int, byte[]> FileBytes { get; set; }
    }
}