using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uRAT.ManagersPlugin.ProcessManager;
using uRAT.Server.Plugin.Server;

namespace uRAT.ManagersPlugin
{
    public class ManagersPluginHost : IServerPluginHost
    {
        public string Name
        {
            get { return "Managers plugin"; }
        }

        public string Author
        {
            get { return "ubbelol"; }
        }

        public string Description
        {
            get { return "Contains all managers"; }
        }

        public Version Version
        {
            get { return new Version(1, 0); }
        }

        public Guid AssociatedClientPlugin
        {
            get { return new Guid("0d7c901e-300c-419d-b648-14768b4e9aa8"); }
        }

        public List<IServerPlugin> Plugins
        {
            get
            {
                return new List<IServerPlugin>
                {
                    new ProcessManagerPlugin()
                };
            }
        }
    }
}
