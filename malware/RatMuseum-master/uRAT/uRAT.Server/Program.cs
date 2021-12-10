using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net;
using System.Windows.Forms;
using System.Xml;
using uRAT.Server.Forms;
using uRAT.Server.Plugin.Synchronization.Packets;
using uRAT.Server.Tools;
using uRAT.Server.Tools.Extensions;

namespace uRAT.Server
{
    static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            var mainFrm = new MainForm();
            InitializeGlobals(mainFrm);
            Application.Run(mainFrm);
        }

        static void InitializeGlobals(MainForm mainFrm)
        {
            Globals.PluginAggregator = new Plugin.PluginAggregator();
            Globals.ServerPool = new ServerPool();
            Globals.MainForm = mainFrm;
            Globals.PacketProcessor = new StandardPacketProcessor()
            {
                PacketTable = new Dictionary<int,Type>
                {
                    {-1, typeof(FetchPluginMetadataPacket)},
                    {-2, typeof(PluginActionPacket)},
                    {-3, typeof(SynchronizationFinalizationPacket)}
                }
            };
            Globals.SettingsHelper = new Tools.SettingsHelper();
            Globals.RemotePluginHandler = new Plugin.RemotePluginHandler();
            if (!File.Exists("settings.xml"))
            {
                Globals.PluginAggregator.LoadPlugins();
                Globals.SettingsHelper.CreateSettingsFile();
                var pluginMngrFrm = new PluginManagerForm();
                pluginMngrFrm.ShowDialog();
            }
            Globals.PluginAggregator.FetchPlugins();
        }
    }
}
