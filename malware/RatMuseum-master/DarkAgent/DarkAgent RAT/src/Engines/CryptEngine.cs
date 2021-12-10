using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Engines
{
    class CryptEngine
    {
        public static byte[] Crypt(byte[] Data, int key)
        {
            for (int i = 0; i < Data.Length; i++)
                Data[i] ^= (byte)key;
            return Data;
        }
    }
}