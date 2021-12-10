using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_Client.src.Utils;

namespace DarkAgent_Client.src.Engines
{
    class CryptEngine
    {
        private static int _key = Settings.NetworkKey;
        public static byte[] Crypt(byte[] Data)
        {
            for (int i = 0; i < Data.Length; i++)
                Data[i] ^= (byte)_key;
            return Data;
        }
    }
}