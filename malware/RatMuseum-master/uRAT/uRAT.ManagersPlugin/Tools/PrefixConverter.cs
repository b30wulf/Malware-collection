using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace uRAT.ManagersPlugin.Tools
{
    public static class PrefixConverter
    {
        public static double ToMegabytes(long input)
        {
            return input/Math.Pow(1024, 2);
        }
    }
}
