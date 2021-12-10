using System.Collections.Generic;
using System.Management;
using System.Text;
using System.Text.RegularExpressions;

namespace Splice_Admin.Classes
{
    class RemoteUsbDevice
    {
        public string Description { get; set; }
        public string DeviceId { get; set; }
        public string VendorAndProductId { get; set; }


        public static List<RemoteUsbDevice> GetUsbDevices()
        {
            var usbDevices = new List<RemoteUsbDevice>();

            var op = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                op.Username = GlobalVar.AlternateUsername;
                op.Password = GlobalVar.AlternatePassword;
                op.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var sc = new ManagementScope($@"\\{RemoteSystemInfo.TargetComputer}\root\CIMV2", op);
            var query = new ObjectQuery("SELECT Dependent FROM Win32_USBControllerDevice");
            var searcher = new ManagementObjectSearcher(sc, query);

            var deviceIds = new List<string>();
            var captions = new List<string>();

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    if (m["Dependent"] != null)
                    {
                        var regexString = "Win32_PnPEntity.DeviceID=\"(?<deviceId>.*?)\"";
                        var regex = new Regex(regexString);
                        var match = regex.Match(m["Dependent"].ToString());
                        deviceIds.Add(match.Groups["deviceId"].Value);
                    }
                }

                var sb = new StringBuilder();
                foreach (string deviceId in deviceIds)
                {
                    if (sb.Length == 0)
                        sb.Append($"SELECT Caption,DeviceID FROM Win32_PnPEntity WHERE DeviceID = '{deviceId}'");
                    else
                        sb.Append($" OR DeviceID = '{deviceId}'");
                }

                if (sb.Length > 0)
                {
                    query = new ObjectQuery(sb.ToString());
                    searcher = new ManagementObjectSearcher(sc, query);

                    foreach (ManagementObject m in searcher.Get())
                    {
                        string caption = (m["Caption"] != null) ? m["Caption"].ToString() : string.Empty;

                        if (caption.StartsWith("USB Root Hub"))
                            continue;
                        else if (caption.Equals("Generic USB Hub"))
                            continue;

                        string deviceId = (m["DeviceID"] != null) ? m["DeviceID"].ToString() : string.Empty;

                        var regexString = "\\\\(?<vendorId>VID_.*?)\\\\";
                        var regex = new Regex(regexString);
                        var match = regex.Match(deviceId);

                        usbDevices.Add(new RemoteUsbDevice { Description = caption, DeviceId = deviceId, VendorAndProductId = match.Groups["vendorId"].Value });
                    }
                }
                else
                    usbDevices.Add(new RemoteUsbDevice { Description = "No USB devices found." });
            }
            catch
            { }

            return usbDevices;
        }
    }
}
