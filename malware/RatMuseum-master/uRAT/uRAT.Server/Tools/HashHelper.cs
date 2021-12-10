using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography;
using System.Text;

namespace uRAT.Server.Tools
{
    public static class HashHelper
    {
        public static byte[] CalculateMd5(byte[] input)
        {
            var provider = new MD5CryptoServiceProvider();
            return provider.ComputeHash(input);
        }

        public static byte[] CalculateSha256(byte[] input)
        {
            var provider = new SHA256CryptoServiceProvider();
            return provider.ComputeHash(input);
        }
    }
}
