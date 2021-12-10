using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.ServiceProcess;

namespace Splice_Admin.Classes
{
    public class RemoteOdbc
    {
        public RemoteOdbc()
        {
            Values = new List<RemoteOdbcValue>();
        }

        public string DataSourceName { get; set; }
        public string DataSourceDriver { get; set; }
        public List<RemoteOdbcValue> Values { get; set; }
        public bool Is32bitOn64bit { get; set; }
        public string ArchitectureString { get; set; }


        public static List<RemoteOdbc> GetOdbcDsn()
        {
            var odbcEntries = new List<RemoteOdbc>();

            const string odbcDataSources = @"SOFTWARE\ODBC\ODBC.INI\ODBC Data Sources";
            const string odbcDataSources32bitOn64bit = @"SOFTWARE\Wow6432Node\ODBC\ODBC.INI\ODBC Data Sources";
            const string odbcRoot = @"SOFTWARE\ODBC\ODBC.INI";
            const string odbcRoot32bitOn64bit = @"SOFTWARE\Wow6432Node\ODBC\ODBC.INI";
            const string serviceName = "RemoteRegistry";
            bool isLocal = RemoteSystemInfo.TargetComputer.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
            bool isServiceRunning = true;

            // If the target computer is remote, then start the Remote Registry service.
            using (
                GlobalVar.UseAlternateCredentials
                ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                : null)
            using (var sc = new ServiceController(serviceName, RemoteSystemInfo.TargetComputer))
            {
                try
                {
                    if (!isLocal && sc.Status != ServiceControllerStatus.Running)
                    {
                        isServiceRunning = false;
                        sc.Start();
                        sc.WaitForStatus(ServiceControllerStatus.Running);
                    }
                }
                catch (Exception)
                {
                }

                try
                {
                    using (RegistryKey key = RegistryKey.OpenRemoteBaseKey(RegistryHive.LocalMachine, RemoteSystemInfo.TargetComputer))
                    {
                        if (RemoteSystemInfo.WindowsArchitecture == "64-bit")
                        {
                            using (RegistryKey subKey = key.OpenSubKey(odbcDataSources32bitOn64bit))
                            {
                                if (subKey != null)
                                    foreach (var value in subKey.GetValueNames())
                                        odbcEntries.Add(new RemoteOdbc
                                        {
                                            DataSourceName = value,
                                            DataSourceDriver = subKey.GetValue(value).ToString(),
                                            ArchitectureString = "32-bit",
                                            Is32bitOn64bit = true
                                        });
                            }
                            using (RegistryKey subKey = key.OpenSubKey(odbcDataSources))
                            {
                                if (subKey != null)
                                    foreach (var value in subKey.GetValueNames())
                                        odbcEntries.Add(new RemoteOdbc
                                        {
                                            DataSourceName = value,
                                            DataSourceDriver = subKey.GetValue(value).ToString(),
                                            ArchitectureString = "64-bit",
                                            Is32bitOn64bit = false
                                        });
                            }

                            using (RegistryKey subKey = key.OpenSubKey(odbcRoot))
                            {
                                if (subKey != null)
                                {
                                    foreach (var dataSource in odbcEntries)
                                    {
                                        if (dataSource.Is32bitOn64bit)
                                            continue;

                                        using (RegistryKey subSubKey = subKey.OpenSubKey(dataSource.DataSourceName))
                                        {
                                            if (subSubKey != null)
                                            {
                                                foreach (var value in subSubKey.GetValueNames())
                                                {
                                                    dataSource.Values.Add(new RemoteOdbcValue
                                                    {
                                                        OdbcValueName = value,
                                                        OdbcValueData = subSubKey.GetValue(value).ToString()
                                                    });
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            using (RegistryKey subKey = key.OpenSubKey(odbcRoot32bitOn64bit))
                            {
                                if (subKey != null)
                                {
                                    foreach (var dataSource in odbcEntries)
                                    {
                                        if (!dataSource.Is32bitOn64bit)
                                            continue;

                                        using (RegistryKey subSubKey = subKey.OpenSubKey(dataSource.DataSourceName))
                                        {
                                            if (subSubKey != null)
                                                foreach (var value in subSubKey.GetValueNames())
                                                    dataSource.Values.Add(new RemoteOdbcValue
                                                    {
                                                        OdbcValueName = value,
                                                        OdbcValueData = subSubKey.GetValue(value).ToString()
                                                    });
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            using (RegistryKey subKey = key.OpenSubKey(odbcDataSources))
                            {
                                if (subKey != null)
                                    foreach (var value in subKey.GetValueNames())
                                        odbcEntries.Add(new RemoteOdbc
                                        {
                                            DataSourceName = value,
                                            DataSourceDriver = subKey.GetValue(value).ToString(),
                                            ArchitectureString = "32-bit",
                                            Is32bitOn64bit = false
                                        });
                            }

                            using (RegistryKey subKey = key.OpenSubKey(odbcRoot))
                            {
                                if (subKey != null)
                                {
                                    foreach (var dataSource in odbcEntries)
                                    {
                                        using (RegistryKey subSubKey = subKey.OpenSubKey(dataSource.DataSourceName))
                                        {
                                            if (subSubKey != null)
                                                foreach (var value in subSubKey.GetValueNames())
                                                    dataSource.Values.Add(new RemoteOdbcValue
                                                    {
                                                        OdbcValueName = value,
                                                        OdbcValueData = subSubKey.GetValue(value).ToString()
                                                    });
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                catch { }

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

            return odbcEntries;
        }
    }
}
