using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uNet2.Packet.Events;
using uRAT.CorePlugin.ExtendedSystemInformation.Operations;
using uRAT.CorePlugin.ExtendedSystemInformation.Packets;
using uRAT.Server.Tools.Extensions;

namespace uRAT.CorePlugin.ExtendedSystemInformation.Forms
{
    public partial class ExtendedInformationForm : Form
    {
        private ExtendedInformationOperation _op;

        public ExtendedInformationForm(ExtendedInformationOperation op)
        {
            InitializeComponent();
            _op = op;
            _op.OnPacketReceived += PacketReceived;
            _op.SendPacket(new FetchExtendedInformationPacket());
        }

        private void PacketReceived(object sender, OperationPacketEventArgs e)
        {
            if (e.Packet is FetchExtendedInformationPacket)
            {
                /*
                 *         public string OperatingSystem { get; set; }
        public bool IsAdmin { get; set; }
        public DateTime InstallDate { get; set; }
        public string RunningTime { get; set; }
        public string ComputerName { get; set; }
                 */
                var packet = e.Packet as FetchExtendedInformationPacket;
                var lvItmCountryCode = new ListViewItem("Country Code");
                var lvItmCountryName = new ListViewItem("Country Name");
                var lvItmTimeZone = new ListViewItem("Time Zone");
                var lvItmLatitude = new ListViewItem("Latitude");
                var lvItmLongitude = new ListViewItem("Longitude");
                var lvItmInstalledAntivirus = new ListViewItem("Installed Antivirus");
                var lvItmInstalledFirewall = new ListViewItem("Installed Firewall");
                var lvItmThisPath = new ListViewItem("Current Location");
                var lvItmOperatingSystem = new ListViewItem("Operating System");
                var lvItmIsAdmin = new ListViewItem("Is Admin");
                var lvItmInstallDate = new ListViewItem("Install Date");
                var lvItmRunningTime = new ListViewItem("Running Time");
                var lvItmComputerName = new ListViewItem("Computer Name");

                lvItmCountryCode.SubItems.Add(packet.CountryCode);
                lvItmCountryName.SubItems.Add(packet.CountryName);
                lvItmTimeZone.SubItems.Add(packet.TimeZone);
                lvItmLatitude.SubItems.Add(packet.Latitude);
                lvItmLongitude.SubItems.Add(packet.Longitude);
                lvItmInstalledAntivirus.SubItems.Add(packet.InstalledAntivirus);
                lvItmThisPath.SubItems.Add(packet.ThisPath);
                lvItmOperatingSystem.SubItems.Add(packet.OperatingSystem);
                lvItmIsAdmin.SubItems.Add(packet.IsAdmin ? "True" : "False");
                lvItmInstallDate.SubItems.Add(packet.InstallDate.ToString());
                lvItmRunningTime.SubItems.Add(packet.RunningTime);
                lvItmComputerName.SubItems.Add(packet.ComputerName);

                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmCountryCode));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmCountryName));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmTimeZone));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmLatitude));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmLongitude));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmInstalledAntivirus));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmInstalledFirewall));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmThisPath));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmOperatingSystem));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmIsAdmin));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmInstallDate));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmRunningTime));
                listView1.FlexibleInvoke(lv => lv.Items.Add(lvItmComputerName));
            }
        }

        private void ExtendedInformationForm_Load(object sender, EventArgs e)
        {

        }
    }
}
