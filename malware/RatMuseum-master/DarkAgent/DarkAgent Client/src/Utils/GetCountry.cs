using System;
using System.Collections.Generic;
using System.Text;
using System.Runtime.InteropServices;

namespace DarkAgent_Client.src.Utils
{
    class GetCountry
    {
        [DllImport("kernel32.dll")]
        private static extern int GetLocaleInfo(uint Locale, uint LCType, [Out()]
        System.Text.StringBuilder lpLCData, int cchData);
        private const uint LOCALE_SYSTEM_DEFAULT = 0x400;
        private const uint LOCALE_SENGCOUNTRY = 0x1002;
        public static string Country()
        {
            StringBuilder lpLCData = new StringBuilder(256);
            int ret = GetLocaleInfo(LOCALE_SYSTEM_DEFAULT, LOCALE_SENGCOUNTRY, lpLCData, lpLCData.Capacity);
            if (ret > 0)
                return lpLCData.ToString().Substring(0, ret - 1);
            return string.Empty;
        }

    }
}
