using System;
using System.Collections;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using uRAT.Client.Plugin;
using uRAT.Client.Plugin.Client;

namespace uRAT.Client.Tools.Blob
{
    internal class PluginBlobReader : IDisposable
    {
        private BlobReader _reader;

        public PluginBlobReader(string blobFile)
        {
            var fs = new FileStream(blobFile, FileMode.OpenOrCreate);
            _reader = new BlobReader(fs);
        }

        public PluginBlobMetadata ReadMetadata()
        {
            if (_reader.BaseStream.Length == 0)
            {
                return new PluginBlobMetadata(0, new System.Collections.Generic.Dictionary<Guid, BlobClientPlugin>());
            }

            _reader.BaseStream.Position = 0;
            var md = new PluginBlobMetadata
            {
                AvailablePluginCount = _reader.ReadInt32()
            };
            for (var i = 0; i < md.AvailablePluginCount; i++)
            {
                var dataOffset = _reader.ReadInt32();
                var dataSize = _reader.ReadInt32();
                var curPos = _reader.BaseStream.Position;
                md.PluginTable.Add(_reader.ReadGuid(), ReadPlugin(dataOffset, dataSize));
                _reader.BaseStream.Position = curPos + 16;
            }

            return md;
        }

        public List<IClientPluginHost> GetPluginHost(Guid guid)
        {
            return GetPluginHostImpl(guid).ToList();
        }

        IEnumerable<IClientPluginHost> GetPluginHostImpl(Guid guid)
        {
            var md = ReadMetadata();
            var plugin = ReadPlugin(md.PluginTable[guid].Offset, md.PluginTable[guid].Size);
            var asm = Assembly.Load(plugin.Data);
            var types = asm.GetTypes();
            foreach (var t in types)
            {
                if (((IList) t.GetInterfaces()).Contains(typeof(IClientPluginHost)) && !t.IsAbstract)
                    yield return Activator.CreateInstance(t) as IClientPluginHost;
            }
        }

        private BlobClientPlugin ReadPlugin(int offset, int size)
        {
            _reader.BaseStream.Position = offset;

            var plugin = new BlobClientPlugin()
            {
                PluginGuid = _reader.ReadGuid(),
                Hash = _reader.ReadBytes(32),
                Data = _reader.ReadBytes(size),
                Size = size,
                Offset = offset
            };

            return plugin;

        }

        public void SetPosition(long offset)
        {
            _reader.BaseStream.Position = offset;
        }

        public void Dispose()
        {
            _reader.Close();
        }
    }
}
