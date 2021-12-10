using System.Collections.Generic;

namespace uRAT.Client.Plugin.Client
{
    public interface IClientPluginHost
    {
        List<IClientPlugin> Plugins { get; }
    }
}
