using System;
using System.Collections.Generic;
using System.Management;
using System.Text;

namespace Splice_Admin.Classes
{
    class RemoteSystemSpecs
    {
        public UInt16 MemorySlotsTotal { get; set; }
        public int MemorySlotsInUse { get; set; }
        public string MemorySpeedString { get; set; }
        public string MemoryBiosReservedString { get; set; }
        public UInt64 MemoryTotalBytes { get; set; }
        public string MemoryTotalString { get; set; }
        public string MemoryInUseString { get; set; }
        public string MemoryAvailableString { get; set; }
        public string MemoryCommittedString { get; set; }
        public string MemoryCommitLimitString { get; set; }
        public string MemoryPagedPoolString { get; set; }
        public string MemoryNonPagedPoolString { get; set; }

        public UInt32 CpuHandleCount { get; set; }
        public UInt32 CpuThreadCount { get; set; }
        public UInt32 CpuNumberOfProcesses { get; set; }
        public string CpuClockSpeed { get; set; }
        public string L1CacheSize { get; set; }
        public string L2CacheSize { get; set; }
        public string L3CacheSize { get; set; }
        public UInt32 CpuNumberOfLogicalProcessors { get; set; }
        public UInt32 CpuNumberOfCores { get; set; }
        public UInt32 CpuNumberOfSockets { get; set; }
        public string CpuName { get; set; }

        public TaskResult Result { get; set; }


        public static RemoteSystemSpecs GetCpuRamDetails()
        {
            var systemSpecs = new RemoteSystemSpecs();
            var memoryModules = new List<RemoteMemoryModule>();
            systemSpecs.Result = new TaskResult();
            systemSpecs.Result.DidTaskSucceed = true;

            var op = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                op.Username = GlobalVar.AlternateUsername;
                op.Password = GlobalVar.AlternatePassword;
                op.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var sc = new ManagementScope($@"\\{RemoteSystemInfo.TargetComputer}\root\CIMV2", op);
            var query = new ObjectQuery("SELECT MemoryDevices FROM Win32_PhysicalMemoryArray");
            var searcher = new ManagementObjectSearcher(sc, query);

            // Determine the number of physical memory slots.
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    systemSpecs.MemorySlotsTotal += (m["MemoryDevices"] != null) ? (UInt16)m["MemoryDevices"] : (UInt16)0;
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine the capacity, speed, and location of each memory module.
            query = new ObjectQuery("SELECT DeviceLocator,Capacity,Speed FROM Win32_PhysicalMemory");
            searcher = new ManagementObjectSearcher(sc, query);
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    string location = (m["DeviceLocator"] != null) ? m["DeviceLocator"].ToString() : string.Empty;
                    UInt64 capacity = (m["Capacity"] != null) ? (UInt64)m["Capacity"] : (UInt64)0;
                    UInt32 speed = (m["Speed"] != null) ? (UInt32)m["Speed"] : (UInt32)0;
                    memoryModules.Add(new RemoteMemoryModule { MemoryLocation = location, MemoryCapacityBytes = capacity, MemorySpeed = speed });
                    systemSpecs.MemorySpeedString = speed.ToString() + " MHz";
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine how many memory slots are in use (This will be the size of the memoryModules List).
            systemSpecs.MemorySlotsInUse = memoryModules.Count;


            // Determine amount of memory reserved by the BIOS.
            query = new ObjectQuery("SELECT TotalPhysicalMemory FROM Win32_ComputerSystem");
            searcher = new ManagementObjectSearcher(sc, query);
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    systemSpecs.MemoryTotalBytes = (m["TotalPhysicalMemory"] != null) ? (UInt64)m["TotalPhysicalMemory"] : 0;
                    UInt64 totalPhysicalMemory = 0;
                    foreach (RemoteMemoryModule mem in memoryModules)
                        totalPhysicalMemory += mem.MemoryCapacityBytes;
                    systemSpecs.MemoryTotalString = RemoteAdmin.ConvertBytesToString(totalPhysicalMemory);
                    UInt64 biosReserved = (systemSpecs.MemoryTotalBytes > 0 && totalPhysicalMemory > 0) ? totalPhysicalMemory - systemSpecs.MemoryTotalBytes : 0;
                    systemSpecs.MemoryBiosReservedString = RemoteAdmin.ConvertBytesToString(biosReserved);
                    break;
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine amount of memory in use / available.
            // Determine Memory Committed and Commit Limit.
            // Determine Paged and Non-Paged pool.
            query = new ObjectQuery("SELECT AvailableBytes,CommittedBytes,CommitLimit,PoolNonpagedBytes,PoolPagedBytes FROM Win32_PerfRawData_PerfOS_Memory");
            searcher = new ManagementObjectSearcher(sc, query);
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    UInt64 availableBytes = (m["AvailableBytes"] != null) ? (UInt64)m["AvailableBytes"] : 0;
                    UInt64 inUseBytes = systemSpecs.MemoryTotalBytes - availableBytes;

                    UInt64 committedBytes = (m["CommittedBytes"] != null) ? (UInt64)m["CommittedBytes"] : 0;
                    UInt64 commitLimitBytes = (m["CommitLimit"] != null) ? (UInt64)m["CommitLimit"] : 0;

                    UInt64 pagedPoolBytes = (m["PoolPagedBytes"] != null) ? (UInt64)m["PoolPagedBytes"] : 0;
                    UInt64 nonPagedPoolBytes = (m["PoolNonpagedBytes"] != null) ? (UInt64)m["PoolNonpagedBytes"] : 0;

                    systemSpecs.MemoryAvailableString = RemoteAdmin.ConvertBytesToString(availableBytes);
                    systemSpecs.MemoryInUseString = RemoteAdmin.ConvertBytesToString(inUseBytes);
                    systemSpecs.MemoryCommittedString = RemoteAdmin.ConvertBytesToString(committedBytes);
                    systemSpecs.MemoryCommitLimitString = RemoteAdmin.ConvertBytesToString(commitLimitBytes);
                    systemSpecs.MemoryPagedPoolString = RemoteAdmin.ConvertBytesToString(pagedPoolBytes);
                    systemSpecs.MemoryNonPagedPoolString = RemoteAdmin.ConvertBytesToString(nonPagedPoolBytes);
                    break;
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine CPU product name, max clock speed, number of cpu sockets, number of cores, number of logical processors.
            query = new ObjectQuery("SELECT * FROM Win32_Processor");
            searcher = new ManagementObjectSearcher(sc, query);
            int i = 0;
            bool isLogicalCpuWmiSupported = false;
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    ++i;

                    UInt32 clockSpeed = (m["MaxClockSpeed"] != null) ? (UInt32)m["MaxClockSpeed"] : (UInt32)0;
                    systemSpecs.CpuClockSpeed = string.Format("{0:0.00} {1}",
                        clockSpeed > 1000 ? (double)clockSpeed / 1000.0 : clockSpeed,
                        clockSpeed > 1000 ? "GHz" : "MHz");

                    // Get CPU model/name then remove extra whitespace and "(R)" from the middle of the string.
                    string cpuName = (m["Name"] != null) ? m["Name"].ToString() : string.Empty;
                    var cpuNameTrimmed = new StringBuilder();
                    for (int j = 0; j < cpuName.Length; ++j)
                    {
                        if (cpuName[j] == ' ')
                        {
                            cpuNameTrimmed.Append(cpuName[j]);
                            while (++j < cpuName.Length && cpuName[j] == ' ')
                                continue;
                            --j;
                        }
                        else if (cpuName[j] == '(' && j <= cpuName.Length - 2 && cpuName[j + 1] == 'R' && cpuName[j + 2] == ')')
                            j += 2;
                        else
                            cpuNameTrimmed.Append(cpuName[j]);
                    }
                    systemSpecs.CpuName = cpuNameTrimmed.ToString();

                    // Determine if target OS is Server 2003.  If so, you cannot determine the number of cores and physical processors.  Only the number of logical processors can be determined.
                    foreach (var prop in m.Properties)
                    {
                        if (prop.Name == "NumberOfLogicalProcessors")
                        {
                            isLogicalCpuWmiSupported = true;
                            break;
                        }
                    }

                    if (isLogicalCpuWmiSupported)
                    {
                        systemSpecs.CpuNumberOfLogicalProcessors += (m["NumberOfLogicalProcessors"] != null) ? (UInt32)m["NumberOfLogicalProcessors"] : (UInt32)0;
                        systemSpecs.CpuNumberOfCores += (m["NumberOfCores"] != null) ? (UInt32)m["NumberOfCores"] : (UInt32)0;
                    }
                }
                // If the number of logical processors is the same as the number of cores.  Set the value to 0 so it does not display on the System Info window.
                if (systemSpecs.CpuNumberOfLogicalProcessors == systemSpecs.CpuNumberOfCores)
                    systemSpecs.CpuNumberOfLogicalProcessors = 0;

                if (isLogicalCpuWmiSupported)
                    systemSpecs.CpuNumberOfSockets = (UInt32)i;
                else
                {
                    // If the target OS is Server 2003, the number of cores and physical processors are incorrectly reported through WMI.  Only the total number of logical CPUs can be determined.
                    systemSpecs.CpuNumberOfSockets = (UInt32)0;
                    systemSpecs.CpuNumberOfCores = (UInt32)0;
                    systemSpecs.CpuNumberOfLogicalProcessors = (UInt32)i;
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine L1 / L2 / L3 cache size.
            query = new ObjectQuery("SELECT MaxCacheSize,Purpose FROM Win32_CacheMemory");
            searcher = new ManagementObjectSearcher(sc, query);
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    string purpose = (m["Purpose"] != null) ? m["Purpose"].ToString().ToUpper() : string.Empty;
                    UInt32 cacheSize = (m["MaxCacheSize"] != null) ? (UInt32)m["MaxCacheSize"] : (UInt32)0;
                    if (purpose.Contains("L1"))
                        systemSpecs.L1CacheSize = RemoteAdmin.ConvertBytesToString(cacheSize * 1024);
                    else if (purpose.Contains("L2"))
                        systemSpecs.L2CacheSize = RemoteAdmin.ConvertBytesToString(cacheSize * 1024);
                    else if (purpose.Contains("L3"))
                        systemSpecs.L3CacheSize = RemoteAdmin.ConvertBytesToString(cacheSize * 1024);
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine number of running processes.
            query = new ObjectQuery("SELECT NumberOfProcesses FROM Win32_OperatingSystem");
            searcher = new ManagementObjectSearcher(sc, query);
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    systemSpecs.CpuNumberOfProcesses = (m["NumberOfProcesses"] != null) ? (UInt32)m["NumberOfProcesses"] : 0;
                    break;
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            // Determine number of threads and handles.
            query = new ObjectQuery("SELECT HandleCount,ThreadCount FROM Win32_PerfRawData_PerfProc_Process WHERE Name = '_Total'");
            searcher = new ManagementObjectSearcher(sc, query);
            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    systemSpecs.CpuHandleCount = (m["HandleCount"] != null) ? (UInt32)m["HandleCount"] : 0;
                    systemSpecs.CpuThreadCount = (m["ThreadCount"] != null) ? (UInt32)m["ThreadCount"] : 0;
                    break;
                }
            }
            catch
            {
                systemSpecs.Result.DidTaskSucceed = false;
            }


            return systemSpecs;
        }
    }
}
