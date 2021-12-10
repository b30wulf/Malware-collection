using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace Epicenter_SERVER
{
    class FilelistRecv
    {
        public static bool isExpecting = false;
        public static long expectedLen = 0;
        public static byte[] stream = new byte[1];
        public static long myIndex = 0;
        
    }
}
