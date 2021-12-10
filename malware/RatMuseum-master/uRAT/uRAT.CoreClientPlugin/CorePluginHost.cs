using System.Collections.Generic;
using uRAT.Client.Plugin.Client;
using uRAT.CoreClientPlugin.BasicSystemInformation;
using uRAT.CoreClientPlugin.ConnectionManagement;
using uRAT.CoreClientPlugin.ConnectionPing;
using uRAT.CoreClientPlugin.ExtendedSystemInformation;

namespace uRAT.CoreClientPlugin
{
    public class CoreClientPluginHost : IClientPluginHost
    {
        public List<IClientPlugin> Plugins
        {
            get
            {
                return new List<IClientPlugin>
                {
                    new BasicSystemInformationClientPlugin(),
                    new ConnectionManagementPlugin(),
                    new ConnectionPingPlugin(),
                    new ExtendedSystemInformationPlugin()
                };
            }
        }
    }
}
