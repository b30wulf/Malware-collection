using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Objects
{
    public class ProcessThreadInfo
    {
        public int PID;
        public int ID { get; set; }
        public byte Pritioity { get; set; }
        public string WaitReason { get; set; }
        public byte PriorityBoost { get; set; }
        public string PrivilegedProcessorTime { get; set; }
        public string StartTime { get; set; }
        public string Threadstate { get; set; }
    }
}
