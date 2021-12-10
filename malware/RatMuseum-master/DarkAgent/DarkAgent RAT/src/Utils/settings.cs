using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Utils
{
    public class settings
    {
        public static Form1 MainForm { get; set; }
        public static ulong ReceivedPackets { get; set; }
        public static ulong SendedPackets { get; set; }
        public static int ClientsConnected { get; set; }
        public static bool AI { get; set; }

        /* Flood Protection */
        public static int Flood_MaxPacketASecond { get; set; }
    }
}
