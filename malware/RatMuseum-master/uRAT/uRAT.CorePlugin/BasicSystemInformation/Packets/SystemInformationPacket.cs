using System;
using System.Diagnostics;
using System.IO;
using System.Net;
using System.Security.Principal;
using uNet2.Packet;

namespace uRAT.CorePlugin.BasicSystemInformation.Packets
{
    public class SystemInformationPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 1; }
        }

        public string OperatingSystem { get; set; }
        public string ComputerName { get; set; }
        public string CountryCode { get; set; }
        public string IsAdmin { get; set; }


        public void SerializeTo(Stream stream)
        {

        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            br.ReadInt32();
            OperatingSystem = br.ReadString();
            ComputerName = br.ReadString();
            CountryCode = br.ReadString();
            IsAdmin = br.ReadString();
        }
    }
}
