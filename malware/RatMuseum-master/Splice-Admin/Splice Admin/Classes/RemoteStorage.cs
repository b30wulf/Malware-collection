using System;
using System.Collections.Generic;
using System.Management;

namespace Splice_Admin.Classes
{
    class RemoteStorage
    {
        public static string ComputerName;
        public static TaskResult Result { get; set; }

        public string DriveLetter { get; set; }
        public string DriveLetterAndName
        {
            get
            {
                if (VolumeName != null && VolumeName.Length > 0)
                    return $"{DriveLetter} ({VolumeName})";
                else
                    return DriveLetter;
            }
        }
        public ulong Capacity { get; set; }
        public string CapacityString { get; set; }
        public ulong FreeSpace { get; set; }
        public string FreeSpaceString { get; set; }
        public string FreeSpaceAndCapacityString
        {
            get
            {
                if (DriveType == 3)
                    return $"{FreeSpaceString} free of {CapacityString}";
                else
                    return CapacityString;
            }
        }
        public ulong UsedSpace { get; set; }
        public string UsedSpaceString { get; set; }
        public double UsedSpacePercentage { get { return (double)UsedSpace / Capacity; } }
        public uint DriveType { get; set; }
        public string VolumeName { get; set; }


        public static List<RemoteStorage> GetStorageDevices()
        {
            // Use WMI to retrieve a list of storage devices.
            var drives = new List<RemoteStorage>();
            var taskResult = new TaskResult();
            Result = taskResult;

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);
            var query = new ObjectQuery("SELECT * FROM Win32_LogicalDisk WHERE DriveType = 2 OR DriveType = 3 OR DriveType = 5");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                // Retrieve a list of storage devices.
                foreach (ManagementObject m in searcher.Get())
                {
                    var drive = new RemoteStorage();

                    drive.DriveLetter = (m["Name"] != null) ? m["Name"].ToString() : string.Empty;
                    drive.VolumeName = (m["VolumeName"] != null) ? m["VolumeName"].ToString() : string.Empty;
                    drive.Capacity = (m["Size"] != null) ? (UInt64)m["Size"] : 0;
                    drive.FreeSpace = (m["FreeSpace"] != null) ? (UInt64)m["FreeSpace"] : 0;
                    drive.UsedSpace = drive.Capacity - drive.FreeSpace;
                    drive.DriveType = (UInt32)m["DriveType"];

                    double bytes = (double)drive.Capacity;
                    switch (drive.DriveType)
                    {
                        case (2):
                            drive.CapacityString = "Removable";
                            break;
                        case (5):
                            drive.CapacityString = "CD-ROM";
                            break;
                        default:
                            drive.CapacityString = ConvertBytesToString(bytes);
                            break;
                    }

                    bytes = (double)drive.FreeSpace;
                    drive.FreeSpaceString = (drive.DriveType == 2 || drive.DriveType == 5) ? string.Empty : ConvertBytesToString(bytes);

                    bytes = (double)drive.UsedSpace;
                    drive.UsedSpaceString = (drive.DriveType == 2 || drive.DriveType == 5) ? string.Empty : ConvertBytesToString(bytes);

                    drives.Add(drive);
                }
                taskResult.DidTaskSucceed = true;
            }
            catch
            {
                taskResult.DidTaskSucceed = false;
            }

            return drives;
        }


        private static string ConvertBytesToString(double bytes)
        {
            string suffix = "KB";
            bytes /= 1024.0;
            if (bytes >= 1000.0)
            {
                bytes /= 1024.0;
                suffix = "MB";
            }
            if (bytes >= 1000.0)
            {
                bytes /= 1024.0;
                suffix = "GB";
            }
            if (bytes >= 1000.0)
            {
                bytes /= 1024.0;
                suffix = "TB";
            }

            return $"{bytes.ToString("N1")} {suffix}";
        }
    }
}
