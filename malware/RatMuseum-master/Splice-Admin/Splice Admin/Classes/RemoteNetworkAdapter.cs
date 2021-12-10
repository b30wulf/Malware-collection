using System;
using System.Collections.Generic;
using System.Management;

namespace Splice_Admin.Classes
{
    class RemoteNetworkAdapter
    {
        public static string ComputerName;
        public string[] IpAddresses { get; set; }
        public string[] SubnetMasks { get; set; }
        public string[] DefaultGateways { get; set; }
        public bool IsDhcpEnabled { get; set; }
        public string DhcpServer { get; set; }
        public DateTime DhcpLeaseObtained { get; set; }
        public DateTime DhcpLeaseExpires { get; set; }
        public string[] DnsServers { get; set; }
        public string DnsDomain { get; set; }
        public string[] DnsSuffixSearchOrder { get; set; }
        public string MacAddress { get; set; }
        public UInt32 Index { get; set; }
        public string ConnectionId { get; set; }
        public string Description { get; set; }
        public bool IsEnabled { get; set; }
        public UInt16 ConnectionStatus { get; set; }
        public string ConnectionStatusAsString
        {
            get
            {
                if (!IsEnabled) return "Disabled";
                switch (ConnectionStatus)
                {
                    case 0:
                        return "Disconnected";
                    case 1:
                        return "Connecting";
                    case 2:
                        return "Connected";
                    case 3:
                        return "Disconnecting";
                    case 4:
                        return "Hardware not present";
                    case 5:
                        return "Hardware disabled";
                    case 6:
                        return "Hardware malfunction";
                    case 7:
                        return "Media disconnected";
                    case 8:
                        return "Authenticating";
                    case 9:
                        return "Authentication succeeded";
                    case 10:
                        return "Authentication failed";
                    case 11:
                        return "Invalid address";
                    case 12:
                        return "Credentials_Required";
                    default:
                        return "Unknown";
                }
            }
        }
        

        public static List<RemoteNetworkAdapter> GetNetworkAdapters()
        {
            var networkAdapters = new List<RemoteNetworkAdapter>();

            var op = new ConnectionOptions();
            var sc = new ManagementScope($@"\\{ComputerName}\root\CIMV2", op);
            var query = new ObjectQuery("SELECT * FROM Win32_NetworkAdapter WHERE Manufacturer != NULL AND Manufacturer != 'Microsoft'");
            var searcher = new ManagementObjectSearcher(sc, query);

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    var networkAdapter = new RemoteNetworkAdapter();

                    networkAdapter.ConnectionId = (m["NetConnectionID"] != null) ? m["NetConnectionID"].ToString() : string.Empty;
                    networkAdapter.Description = (m["Description"] != null) ? m["Description"].ToString() : string.Empty;
                    networkAdapter.MacAddress = (m["MACAddress"] != null) ? m["MACAddress"].ToString() : string.Empty;
                    if (m["Index"] != null) networkAdapter.Index = (UInt32)m["Index"];
                    if (m["NetConnectionStatus"] != null) networkAdapter.ConnectionStatus = (UInt16)m["NetConnectionStatus"];

                    if (string.IsNullOrEmpty(networkAdapter.MacAddress) && networkAdapter.ConnectionStatus == 0)
                        networkAdapter.IsEnabled = false;
                    else
                        networkAdapter.IsEnabled = true;

                    networkAdapters.Add(networkAdapter);
                }

                foreach (var networkAdapter in networkAdapters)
                {
                    if (!networkAdapter.IsEnabled) continue;

                    query = new ObjectQuery($"SELECT * FROM Win32_NetworkAdapterConfiguration WHERE Index={networkAdapter.Index}");
                    searcher = new ManagementObjectSearcher(sc, query);

                    foreach (ManagementObject m in searcher.Get())
                    {
                        if (m["IPAddress"] != null)
                            networkAdapter.IpAddresses = (string[])m["IPAddress"];
                        if (m["IPSubnet"] != null)
                            networkAdapter.SubnetMasks = (string[])m["IPSubnet"];
                        if (m["DefaultIPGateway"] != null)
                            networkAdapter.DefaultGateways = (string[])m["DefaultIPGateway"];
                        if (m["DHCPEnabled"] != null)
                            networkAdapter.IsDhcpEnabled = (bool)m["DHCPEnabled"];
                        if (m["DHCPServer"] != null)
                            networkAdapter.DhcpServer = m["DHCPServer"].ToString();
                        if (m["DNSDomain"] != null)
                            networkAdapter.DnsDomain = m["DNSDomain"].ToString();
                        if (m["DNSDomainSuffixSearchOrder"] != null)
                            networkAdapter.DnsSuffixSearchOrder = (string[])m["DNSDomainSuffixSearchOrder"];
                        if (m["DNSServerSearchOrder"] != null)
                            networkAdapter.DnsServers = (string[])m["DNSServerSearchOrder"];
                        if (m["DHCPLeaseObtained"] != null)
                            networkAdapter.DhcpLeaseObtained = ManagementDateTimeConverter.ToDateTime(m["DHCPLeaseObtained"].ToString());
                        if (m["DHCPLeaseExpires"] != null)
                            networkAdapter.DhcpLeaseExpires = ManagementDateTimeConverter.ToDateTime(m["DHCPLeaseExpires"].ToString());
                    }

                    // If DNS search order is the same as the DNS domain, clear the DNS search order.
                    if (networkAdapter.DnsSuffixSearchOrder.Length == 1 &&
                        (networkAdapter.DnsSuffixSearchOrder[0].Equals(networkAdapter.DnsDomain) ||
                        string.IsNullOrEmpty(networkAdapter.DnsDomain)))
                    {
                        networkAdapter.DnsSuffixSearchOrder[0] = string.Empty;
                    }
                }
            }
            catch
            { }

            return networkAdapters;
        }
    }
}
