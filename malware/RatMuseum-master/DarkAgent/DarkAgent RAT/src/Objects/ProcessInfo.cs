using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Objects
{
    public class ProcessInfo
    {
        public string ProcessName { get; set; }
        public int PID { get; set; }
        public string WindowsTitle { get; set; }
        public bool Responding { get; set; }
        public string FileLocation { get; set; }
        public int Handle { get; set; }
        public int ProcessorAffinity { get; set; }
        public int Threads { get; set; }
        public string Priority { get; set; }
    }
}
