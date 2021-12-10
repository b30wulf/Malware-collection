using System;
using System.Collections.Generic;
using System.Text;
using System.Net;

namespace Epicenter_Client
{
    class NetTools
    {
        public static string GetCorrespondingIP(string hostname)
        {
            try
            {
                IPHostEntry ihe = Dns.GetHostEntry(hostname);
                IPAddress ipadd = ihe.AddressList[0];

                return ipadd.ToString();
            }
            catch
            {
                return "ERROR";
            }
        }

    }
}
