using System;
using System.Collections.Generic;
using System.Text;
using System.Net.Sockets;

namespace Epicenter_SERVER
{
    class commonData
    {
        public static List <NetworkStream> ns_collection = new List<NetworkStream>();
        public static Dictionary<string, administrate> ip_panel_pair = new Dictionary<string, administrate>();

        // Random set of bytes in order to check the validity of the incoming package
        // This set can be of any size
        public static byte[] scheme = { 21, 36, 47, 71, 99, 61, 71, 71, 99, 255, 254, 253,
                            36, 45, 66, 253, 21, 46, 47, 66, 39, 41, 66, 99, 
                            253, 252, 251, 216, 222, 221, 216, 99, 41, 56, 57, 58, 3};

        // The set of bytes sent when valid data is received from the client
        // This byte array is consisting of arbitrary bytes and can be altered if needed - keep in mind that you should 
        //   also change it on the client side
        public static byte[] recv_signal = { 20, 26, 91, 36, 71, 64, 63, 99, 251, 253, 69, 31, 31,
                                 72, 69, 109, 101, 108, 97, 110, 105, 101, 109, 101, 108};
    }
}
