using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_Client.src.Objects
{
    public class ProcessDllInfo
    {
        public int PID { get; set; }
        public string FileName { get; set; }
        public string ModuleName { get; set; }
        public string BaseAddress { get; set; }
        public string EntryPointAddress { get; set; }
        public string ModuleMemorySize { get; set; }
    }
}
