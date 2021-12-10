using System;
using System.Collections.Generic;

namespace uRAT.Server.Plugin.Server
{
    public interface IServerPluginHost
    {
        string Name { get; }
        string Author { get; }
        string Description { get; }
        Version Version { get; }
        Guid AssociatedClientPlugin { get; }

        List<IServerPlugin> Plugins { get; }
    }
}
