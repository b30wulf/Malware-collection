using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;

using System.Reflection;
using uRAT.Client.Plugin.Client;

namespace uRAT.Client.Plugin
{
#if DEBUG
    public class PluginAggregator
    {
        public List<IClientPluginHost> LoadedPlugins { get; set; }

        public void FetchPlugins()
        {
            LoadedPlugins = FetchPluginsImpl().ToList();
            LoadedPlugins.ForEach(lp =>
            {
                lp.Plugins.ForEach(lpp =>
                {
                    if(lpp.PluginPackets != null)
                        lpp.PluginPackets.ForEach(lppp =>
                        {
                            Globals.Client.PacketProcessor.PacketTable.Add(lppp.PacketId, lppp.GetType());
                        
                        });
                    if(lpp.SocketOperations != null)
                        lpp.SocketOperations.ForEach(lppso =>
                        {
                            Globals.Client.RegisterOperation(lppso.GetType());
                        });
                });
            });
        }

        //TODO: make better
        private IEnumerable<IClientPluginHost> FetchPluginsImpl()
        {
            foreach (var file in Directory.GetFiles(Directory.GetCurrentDirectory()))
            {
                if (!file.ToLower().Contains("plugin"))
                    continue;
                if (!file.EndsWith(".dll"))
                    continue;
                var asm = Assembly.LoadFile(file);
                var types = asm.GetTypes();
                foreach (var t in types)
                {
                    if(t.GetInterfaces().Contains(typeof(IClientPluginHost)) && !t.IsAbstract)
                        yield return Activator.CreateInstance(t) as IClientPluginHost;
                }
            }
        }
    }
#endif
}
