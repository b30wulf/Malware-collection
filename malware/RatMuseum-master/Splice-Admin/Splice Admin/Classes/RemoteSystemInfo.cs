using Microsoft.Win32;
using System;
using System.Linq;
using System.Management;
using System.ServiceProcess;
using System.Windows;

namespace Splice_Admin.Classes
{
    class RemoteSystemInfo
    {
        public static string TargetComputer;
        public static string WindowsArchitecture;

        public TaskResult Result { get; set; }
        public string WindowsVersion { get; set; }
        public string WindowsFullVersion { get { return $"{WindowsVersion} ({WindowsArchitecture})"; } }
        public string WindowsVersionNumber { get; set; }
        public string Uptime { get; set; }
        public string ComputerManufacturer { get; set; }
        public string ComputerModel { get; set; }
        public string ComputerSerialNumber { get; set; }
        public string ComputerType { get; set; }
        public string Processor { get; set; }
        public string Memory { get; set; }
        public string IpAddresses { get; set; }
        public string ComputerName { get; set; }
        public string ComputerDescription { get; set; }
        public bool IsRebootRequired { get; set; }


        public static RemoteSystemInfo GetSystemInfo()
        {
            var systemInfo = new RemoteSystemInfo();
            var taskResult = new TaskResult();
            systemInfo.Result = taskResult;

            var op = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                op.Username = GlobalVar.AlternateUsername;
                op.Password = GlobalVar.AlternatePassword;
                op.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var sc = new ManagementScope($@"\\{TargetComputer}\root\CIMV2", op);
            var query = new ObjectQuery("SELECT Caption,Description,LastBootUpTime,Version,ProductType FROM Win32_OperatingSystem");
            var searcher = new ManagementObjectSearcher(sc, query);

            try
            {
                foreach (ManagementObject obj in searcher.Get())
                {
                    systemInfo.ComputerType = (obj["ProductType"] != null) ? obj["ProductType"].ToString() : string.Empty;
                    systemInfo.WindowsVersionNumber = (obj["Version"] != null) ? obj["Version"].ToString().Trim() : string.Empty;
                    systemInfo.WindowsVersion = (obj["Caption"] != null) ? obj["Caption"].ToString().Trim() : string.Empty;
                    systemInfo.ComputerDescription = (obj["Description"] != null) ? obj["Description"].ToString().Trim() : string.Empty;
                    int index = systemInfo.WindowsVersion.IndexOf(@"(R)", StringComparison.OrdinalIgnoreCase);
                    while (index >= 0)
                    {
                        systemInfo.WindowsVersion = systemInfo.WindowsVersion.Remove(index, @"(R)".Length);
                        index = systemInfo.WindowsVersion.IndexOf(@"(R)", StringComparison.OrdinalIgnoreCase);
                    }
                    index = systemInfo.WindowsVersion.IndexOf(@"®", StringComparison.OrdinalIgnoreCase);
                    while (index >= 0)
                    {
                        systemInfo.WindowsVersion = systemInfo.WindowsVersion.Remove(index, @"®".Length);
                        index = systemInfo.WindowsVersion.IndexOf(@"®", StringComparison.OrdinalIgnoreCase);
                    }

                    if (obj["LastBootUpTime"] != null)
                    {
                        DateTime lastBoot = ManagementDateTimeConverter.ToDateTime(obj["LastBootUpTime"].ToString());
                        TimeSpan ts = DateTime.Now - lastBoot;
                        string uptime;
                        if (ts.Days > 0)
                            uptime = string.Format("{0} day{1}, {2} hour{3}, {4} minute{5}",
                                ts.Days, ts.Days == 1 ? "" : "s",
                                ts.Hours, ts.Hours == 1 ? "" : "s",
                                ts.Minutes, ts.Minutes == 1 ? "" : "s");
                        else if (ts.Hours > 0)
                            uptime = string.Format("{0} hour{1}, {2} minute{3}",
                                ts.Hours, ts.Hours == 1 ? "" : "s",
                                ts.Minutes, ts.Minutes == 1 ? "" : "s");
                        else if (ts.Minutes > 0)
                            uptime = string.Format("{0} minute{1}",
                                ts.Minutes, ts.Minutes == 1 ? "" : "s");
                        else
                            uptime = string.Format("{0} second{1}",
                                ts.Seconds, ts.Seconds == 1 ? "" : "s");
                        systemInfo.Uptime = uptime;

                    }

                    //foreach (var prop in obj.Properties)
                    //{
                    //    if (prop.Name == "OSArchitecture" && obj["OSArchitecture"] != null)
                    //        systemInfo.WindowsArchitecture = obj["OSArchitecture"].ToString();
                    //}
                }

                //if (systemInfo.WindowsArchitecture == null)
                //{
                WindowsArchitecture = "32-bit";
                query = new ObjectQuery("SELECT Name,VariableValue FROM Win32_Environment");
                searcher = new ManagementObjectSearcher(sc, query);

                foreach (ManagementObject obj in searcher.Get())
                {
                    if (obj["Name"] != null && obj["Name"].ToString() == "PROCESSOR_ARCHITECTURE" && obj["VariableValue"] != null && obj["VariableValue"].ToString().ToUpper() == "AMD64")
                    {
                        WindowsArchitecture = "64-bit";
                        break;
                    }
                    else if (obj["Name"] != null && obj["Name"].ToString() == "PROCESSOR_ARCHITEW6432 " && obj["VariableValue"] != null && obj["VariableValue"].ToString().ToUpper() == "AMD64")
                    {
                        WindowsArchitecture = "64-bit";
                        break;
                    }
                }
                //}


                if (systemInfo.WindowsVersionNumber.StartsWith("5.0") || systemInfo.WindowsVersionNumber.StartsWith("5.2"))
                    query = new ObjectQuery("SELECT CurrentClockSpeed FROM Win32_Processor");
                else
                    query = new ObjectQuery("SELECT CurrentClockSpeed,NumberOfLogicalProcessors FROM Win32_Processor");
                searcher = new ManagementObjectSearcher(sc, query);
                UInt32 clockSpeed = 0;
                UInt32 numberOfProcessors = 1;
                var isLogicalCpuSupported = false;
                foreach (ManagementObject obj in searcher.Get())
                {
                    if (obj["CurrentClockSpeed"] != null) clockSpeed = (UInt32)obj["CurrentClockSpeed"];
                    if (systemInfo.WindowsVersionNumber.StartsWith("5.0") || systemInfo.WindowsVersionNumber.StartsWith("5.2"))
                        break;
                    else if (obj["NumberOfLogicalProcessors"] != null)
                        isLogicalCpuSupported = true;
                    //foreach (var prop in obj.Properties)
                    //{
                    //    if (prop.Name == "NumberOfLogicalProcessors" && obj["NumberOfLogicalProcessors"] != null)
                    //    {
                    //        isLogicalCpuSupported = true;
                    //        break;
                    //    }
                    //}
                    break;
                }


                if (isLogicalCpuSupported == true)
                    query = new ObjectQuery("SELECT Manufacturer,Model,Name,NumberOfLogicalProcessors,NumberOfProcessors FROM Win32_ComputerSystem");
                else
                    query = new ObjectQuery("SELECT Manufacturer,Model,Name,NumberOfProcessors FROM Win32_ComputerSystem");
                searcher = new ManagementObjectSearcher(sc, query);
                foreach (ManagementObject obj in searcher.Get())
                {
                    if (obj["Manufacturer"] != null) systemInfo.ComputerManufacturer = obj["Manufacturer"].ToString();
                    if (obj["Model"] != null) systemInfo.ComputerModel = obj["Model"].ToString();
                    if (obj["Name"] != null) systemInfo.ComputerName = obj["Name"].ToString();
                    if (isLogicalCpuSupported == true && obj["NumberOfLogicalProcessors"] != null)
                        numberOfProcessors = (UInt32)obj["NumberOfLogicalProcessors"];
                    else if (isLogicalCpuSupported == false && obj["NumberOfProcessors"] != null)
                        numberOfProcessors = (UInt32)obj["NumberOfProcessors"];
                    else
                        numberOfProcessors = 1;
                }
                systemInfo.Processor = string.Format("{0} Core{1} @ {2:0.#} {3}",
                    numberOfProcessors, numberOfProcessors == 1 ? "" : "s",
                    clockSpeed > 1000 ? (double)clockSpeed / 1000.0 : clockSpeed,
                    clockSpeed > 1000 ? "GHz" : "MHz");


                query = new ObjectQuery("SELECT SerialNumber FROM Win32_SystemEnclosure");
                searcher = new ManagementObjectSearcher(sc, query);
                foreach (ManagementObject obj in searcher.Get())
                {
                    systemInfo.ComputerSerialNumber = (obj["SerialNumber"] != null) ? obj["SerialNumber"].ToString() : string.Empty;
                    break;
                }


                query = new ObjectQuery("SELECT Capacity FROM Win32_PhysicalMemory");
                searcher = new ManagementObjectSearcher(sc, query);
                UInt64 totalMemory = 0;
                foreach (ManagementObject m in searcher.Get())
                {
                    if (m["Capacity"] != null)
                    {
                        totalMemory += (UInt64)m["Capacity"];
                    }
                }
                systemInfo.Memory = RemoteAdmin.ConvertBytesToString(totalMemory);


                // Determine computer type:
                if (!string.IsNullOrEmpty(systemInfo.ComputerType) && systemInfo.ComputerType == "3")
                    systemInfo.ComputerType = "Server";
                else
                    systemInfo.ComputerType = "Desktop";

                if (systemInfo.ComputerManufacturer == "VMware, Inc." || (systemInfo.ComputerManufacturer == "Xen" && systemInfo.ComputerModel == "HVM domU"))
                {
                    if (systemInfo.ComputerType == "Server")
                        systemInfo.ComputerType = "Server (Virtual Machine)";
                    else
                        systemInfo.ComputerType = "Virtual Machine";
                }
                query = new ObjectQuery("SELECT BatteryStatus FROM Win32_Battery");
                searcher = new ManagementObjectSearcher(sc, query);
                foreach (ManagementObject m in searcher.Get())
                {
                    systemInfo.ComputerType = "Laptop / Portable";
                    break;
                }

                taskResult.DidTaskSucceed = true;



                query = new ObjectQuery("SELECT * FROM Win32_NetworkAdapterConfiguration WHERE IPEnabled = True");
                searcher = new ManagementObjectSearcher(sc, query);
                foreach (ManagementObject obj in searcher.Get())
                {
                    string[] ipAddresses = (string[])(obj["IPAddress"]);
                    systemInfo.IpAddresses = ipAddresses.FirstOrDefault(s => s.Contains('.'));
                }
            }
            catch
            {
                taskResult.DidTaskSucceed = false;
            }

            systemInfo.IsRebootRequired = GetSysRebootState();

            return systemInfo;
        }


        private static bool GetSysRebootState()
        {
            var isRebootPending = false;
            
            const string wuRegKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\RebootRequired";
            const string cbsRegKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\Component Based Servicing\RebootPending";
            const string pfroRegKey = @"SYSTEM\CurrentControlSet\Control\Session Manager\FileRenameOperations";

            var managementScope = new ManagementScope($@"\\{TargetComputer}\root\CIMV2");
            ManagementBaseObject inParams = null;
            ManagementBaseObject outParams = null;

            try
            {
                using (var wmiRegistry = new ManagementClass(managementScope, new ManagementPath("StdRegProv"), null))
                {
                    inParams = wmiRegistry.GetMethodParameters("EnumValues");
                    inParams["sSubKeyName"] = wuRegKey;
                    outParams = wmiRegistry.InvokeMethod("EnumValues", inParams, null);
                    if ((UInt32)outParams["ReturnValue"] == 0)
                        isRebootPending = true;

                    inParams["sSubKeyName"] = cbsRegKey;
                    outParams = wmiRegistry.InvokeMethod("EnumValues", inParams, null);
                    if ((UInt32)outParams["ReturnValue"] == 0)
                        isRebootPending = true;

                    inParams["sSubKeyName"] = pfroRegKey;
                    outParams = wmiRegistry.InvokeMethod("EnumValues", inParams, null);
                    if ((UInt32)outParams["ReturnValue"] == 0 && (string[])outParams["sNames"] != null)
                        isRebootPending = true;
                }
            }

            catch (ManagementException ex) when (ex.ErrorCode == ManagementStatus.NotFound)
            {
                // Target OS might not support WMI StdRegProv.  Attempt to gather data using remote registry.
                isRebootPending = false;
                const string serviceName = "RemoteRegistry";
                bool isLocal = TargetComputer.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
                bool isServiceRunning = true;

                // If the target computer is remote, then start the Remote Registry service.
                using (
                    GlobalVar.UseAlternateCredentials
                    ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                    : null)
                using (var sc = new ServiceController(serviceName, TargetComputer))
                {
                    try
                    {
                        if (!isLocal && sc.Status != ServiceControllerStatus.Running)
                        {
                            isServiceRunning = false;
                            sc.Start();
                        }
                    }
                    catch (Exception)
                    {
                    }

                    try
                    {
                        using (RegistryKey key = RegistryKey.OpenRemoteBaseKey(RegistryHive.LocalMachine, TargetComputer))
                        {
                            using (RegistryKey subKey = key.OpenSubKey(wuRegKey))
                            {
                                if (subKey != null)
                                    isRebootPending = true;
                            }
                            using (RegistryKey subKey = key.OpenSubKey(cbsRegKey))
                            {
                                if (subKey != null)
                                    isRebootPending = true;
                            }
                            using (RegistryKey subKey = key.OpenSubKey(pfroRegKey))
                            {
                                if (subKey != null && subKey.GetValueNames().Length > 0)
                                    isRebootPending = true;
                            }
                        }
                    }
                    catch
                    {
                    }

                    // Cleanup.
                    if (!isLocal && !isServiceRunning)
                    {
                        try
                        {
                            if (sc != null)
                                sc.Stop();
                        }

                        catch (Exception)
                        {
                        }
                    }
                }
            }

            catch
            {
                // Do nothing.
            }
            finally
            {
                if (inParams != null)
                    inParams.Dispose();
                if (outParams != null)
                    outParams.Dispose();
            }

            return isRebootPending;
        }
    }
}
