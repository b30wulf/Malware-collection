using Microsoft.Win32;
using System;
using System.Management;
using System.ServiceProcess;
using System.Windows;

namespace Splice_Admin.Classes
{
    class RemoteUpdateConfiguration
    {
        public bool IsAutomaticUpdatesEnabled { get; set; }
        public int AuOptionCode { get; set; }
        public DateTime LastUpdateCheck { get; set; }
        public DateTime LastUpdateInstall { get; set; }


        public static RemoteUpdateConfiguration GetUpdateConfiguration()
        {
            var updateConfiguration = new RemoteUpdateConfiguration();
            
            const string updatesLastCheckedKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\Results\Detect";
            const string updatesLastCheckedValue = "LastSuccessTime";
            const string updatesLastInstalledKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\Results\Install";
            const string updatesLastInstalledValue = "LastSuccessTime";
            const string updatesConfigurationKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update";
            const string updatesConfigurationAltKey = @"SOFTWARE\Policies\Microsoft\Windows\WindowsUpdate\AU";
            const string updatesConfigurationValue = "AUOptions";
            
            var managementScope = new ManagementScope($@"\\{RemoteUpdate.ComputerName}\root\CIMV2");
            ManagementBaseObject inParams = null;
            ManagementBaseObject outParams = null;

            try
            {
                using (var wmiRegistry = new ManagementClass(managementScope, new ManagementPath("StdRegProv"), null))
                {
                    // Get date and time of last update check.
                    inParams = wmiRegistry.GetMethodParameters("GetStringValue");
                    inParams["sSubKeyName"] = updatesLastCheckedKey;
                    inParams["sValueName"] = updatesLastCheckedValue;
                    outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                    if (outParams["sValue"] != null)
                        updateConfiguration.LastUpdateCheck = DateTime.SpecifyKind(DateTime.Parse((string)outParams["sValue"]), DateTimeKind.Utc).ToLocalTime();

                    // Get date and time of last installed update.
                    inParams["sSubKeyName"] = updatesLastInstalledKey;
                    inParams["sValueName"] = updatesLastInstalledValue;
                    outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                    if (outParams["sValue"] != null)
                        updateConfiguration.LastUpdateInstall = DateTime.SpecifyKind(DateTime.Parse((string)outParams["sValue"]), DateTimeKind.Utc).ToLocalTime();

                    // Get update configuration (automatic or manual).
                    inParams = wmiRegistry.GetMethodParameters("GetDWORDValue");
                    inParams["sSubKeyName"] = updatesConfigurationAltKey;
                    inParams["sValueName"] = updatesConfigurationValue;
                    outParams = wmiRegistry.InvokeMethod("GetDWORDValue", inParams, null);
                    if (outParams["uValue"] != null)
                        updateConfiguration.AuOptionCode = (int)(UInt32)outParams["uValue"];
                    if (updateConfiguration.AuOptionCode <= 0)
                    {
                        inParams["sSubKeyName"] = updatesConfigurationKey;
                        inParams["sValueName"] = updatesConfigurationValue;
                        outParams = wmiRegistry.InvokeMethod("GetDWORDValue", inParams, null);
                        if (outParams["uValue"] != null)
                            updateConfiguration.AuOptionCode = (int)(UInt32)outParams["uValue"];
                    }
                }
            }

            catch (ManagementException ex) when (ex.ErrorCode == ManagementStatus.NotFound)
            {
                // Target OS might not support WMI StdRegProv.  Attempt to gather data using remote registry.
                updateConfiguration = new RemoteUpdateConfiguration();
                const string serviceName = "RemoteRegistry";
                bool isLocal = RemoteUpdate.ComputerName.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
                bool isServiceRunning = true;

                // If the target computer is remote, then start the Remote Registry service.
                using (
                    GlobalVar.UseAlternateCredentials
                    ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                    : null)
                using (var sc = new ServiceController(serviceName, RemoteUpdate.ComputerName))
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
                        using (RegistryKey key = RegistryKey.OpenRemoteBaseKey(RegistryHive.LocalMachine, RemoteUpdate.ComputerName))
                        {
                            using (RegistryKey subKey = key.OpenSubKey(updatesLastCheckedKey))
                            {
                                if (subKey != null && subKey.GetValue("LastSuccessTime") != null)
                                    updateConfiguration.LastUpdateCheck = DateTime.SpecifyKind(DateTime.Parse(subKey.GetValue("LastSuccessTime").ToString()), DateTimeKind.Utc).ToLocalTime();
                            }
                            using (RegistryKey subKey = key.OpenSubKey(updatesLastInstalledKey))
                            {
                                if (subKey != null && subKey.GetValue("LastSuccessTime") != null)
                                    updateConfiguration.LastUpdateInstall = DateTime.SpecifyKind(DateTime.Parse(subKey.GetValue("LastSuccessTime").ToString()), DateTimeKind.Utc).ToLocalTime();
                            }
                            using (RegistryKey subKey = key.OpenSubKey(updatesConfigurationAltKey))
                            {
                                if (subKey != null)
                                    updateConfiguration.AuOptionCode = (subKey.GetValue("AUOptions") != null) ? int.Parse(subKey.GetValue("AUOptions").ToString()) : 0;
                            }
                            if (updateConfiguration.AuOptionCode <= 0)
                            {
                                using (RegistryKey subKey = key.OpenSubKey(updatesConfigurationKey))
                                {
                                    if (subKey != null)
                                        updateConfiguration.AuOptionCode = (subKey.GetValue("AUOptions") != null) ? int.Parse(subKey.GetValue("AUOptions").ToString()) : 0;
                                }
                            }
                        }

                        if (updateConfiguration.AuOptionCode < 4)
                            updateConfiguration.IsAutomaticUpdatesEnabled = false;
                        else
                            updateConfiguration.IsAutomaticUpdatesEnabled = true;
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

                if (updateConfiguration.AuOptionCode < 4)
                    updateConfiguration.IsAutomaticUpdatesEnabled = false;
                else
                    updateConfiguration.IsAutomaticUpdatesEnabled = true;
            }
            
            return updateConfiguration;
        }
    }
}
