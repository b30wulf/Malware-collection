using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Management;
using System.ServiceProcess;

namespace Splice_Admin.Classes
{
    class RemoteApplication
    {
        public static string ComputerName;
        public static TaskResult Result { get; set; }

        public string DisplayName { get; set; }
        public string Publisher { get; set; }
        public string Version { get; set; }
        public string InstallDate { get; set; }
        public string UninstallPath { get; set; }


        public static List<RemoteApplication> GetInstalledApplications()
        {
            var apps = new List<RemoteApplication>();
            var taskResult = new TaskResult();
            Result = taskResult;

            const string uninstallKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall";
            const string uninstallKey32on64 = @"SOFTWARE\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall";

            var managementScope = new ManagementScope($@"\\{ComputerName}\root\CIMV2");
            ManagementBaseObject inParams = null;
            ManagementBaseObject outParams = null;

            try
            {
                using (var wmiRegistry = new ManagementClass(managementScope, new ManagementPath("StdRegProv"), null))
                {
                    List<string> subKeys = null;
                    List<string> subKeys32on64 = null;
                    var uninstallKeys = new List<string>();

                    // Get uninstall subkeys.
                    inParams = wmiRegistry.GetMethodParameters("EnumKey");
                    inParams["sSubKeyName"] = uninstallKey;
                    outParams = wmiRegistry.InvokeMethod("EnumKey", inParams, null);
                    if (outParams["sNames"] != null)
                        subKeys = new List<string>((string[])outParams["sNames"]).Select(x => $@"{uninstallKey}\{x}").ToList();

                    // Get 32-bit on 64-bit uninstall subkeys.
                    inParams["sSubKeyName"] = uninstallKey32on64;
                    outParams = wmiRegistry.InvokeMethod("EnumKey", inParams, null);
                    if (outParams["sNames"] != null)
                        subKeys32on64 = new List<string>((string[])outParams["sNames"]).Select(x => $@"{uninstallKey32on64}\{x}").ToList();

                    // Combine lists of keys.
                    if (subKeys != null)
                        uninstallKeys.AddRange(subKeys);
                    if (subKeys32on64 != null)
                        uninstallKeys.AddRange(subKeys32on64);

                    // Enumerate keys.
                    foreach (string subKey in uninstallKeys)
                    {
                        // Get SystemComponent (DWORD) value.  Skip key if this value exists and is set to '1'.
                        inParams = wmiRegistry.GetMethodParameters("GetDWORDValue");
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "SystemComponent";
                        outParams = wmiRegistry.InvokeMethod("GetDWORDValue", inParams, null);
                        if (outParams["uValue"] != null && (UInt32)outParams["uValue"] == 1)
                            continue;

                        // Get ParentKeyName (String) value.  Skip key if this value exists.
                        inParams = wmiRegistry.GetMethodParameters("GetStringValue");
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "ParentKeyName";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null && ((string)outParams["sValue"]).Length > 0)
                            continue;

                        // Get ReleaseType (String) value.  Skip key if this value contains 'Update' or 'Hotfix'.
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "ReleaseType";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null && (((string)outParams["sValue"]).Contains("Update") || ((string)outParams["sValue"]).Equals("Hotfix")))
                            continue;

                        var app = new RemoteApplication();

                        // Get DisplayName (String) value.
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "DisplayName";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null)
                            app.DisplayName = (string)outParams["sValue"];
                        else
                            continue;

                        // Get Publisher (String) value.
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "Publisher";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null)
                            app.Publisher = (string)outParams["sValue"];

                        // Get DisplayVersion (String) value.
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "DisplayVersion";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null)
                            app.Version = (string)outParams["sValue"];

                        // Get UninstallString (String) value.
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "UninstallString";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null)
                            app.UninstallPath = (string)outParams["sValue"];

                        apps.Add(app);
                    }
                }

                taskResult.DidTaskSucceed = true;
            }

            catch (ManagementException ex) when (ex.ErrorCode == ManagementStatus.NotFound)
            {
                // Target OS might not support WMI StdRegProv.  Attempt to gather data using remote registry.
                apps = new List<RemoteApplication>();
                const string serviceName = "RemoteRegistry";
                bool isLocal = ComputerName.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
                bool isServiceRunning = true;

                // If the target computer is remote, then start the Remote Registry service.
                using (
                    GlobalVar.UseAlternateCredentials
                    ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                    : null)
                using (var sc = new ServiceController(serviceName, ComputerName))
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
                        using (RegistryKey key = RegistryKey.OpenRemoteBaseKey(RegistryHive.LocalMachine, ComputerName))
                        {
                            using (RegistryKey mainKey64 = key.OpenSubKey(uninstallKey))
                                apps.AddRange(EnumerateUninstallKeys(mainKey64));
                            using (RegistryKey mainKey32 = key.OpenSubKey(uninstallKey32on64))
                                apps.AddRange(EnumerateUninstallKeys(mainKey32));
                        }

                        taskResult.DidTaskSucceed = true;
                    }
                    catch
                    {
                        taskResult.DidTaskSucceed = false;
                    }


                    // Cleanup.
                    if (!isLocal && !isServiceRunning)
                    {
                        try
                        {
                            if (sc != null)
                            {
                                sc.Stop();
                            }
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

            // Get Internet Explorer version.
            if (taskResult.DidTaskSucceed && apps.Count > 0)
            {
                try
                {
                    var internetExplorerVersion = FileVersionInfo.GetVersionInfo($@"\\{ComputerName}\C$\Program Files\Internet Explorer\iexplore.exe");
                    if (internetExplorerVersion != null && internetExplorerVersion.ProductVersion.Length > 0)
                    {
                        apps.Add(new RemoteApplication
                        {
                            DisplayName = "Internet Explorer",
                            Publisher = "Microsoft Corporation",
                            Version = internetExplorerVersion.ProductVersion
                        });
                    }
                }
                catch { }
            }

            return apps;
        }


        private static List<RemoteApplication> EnumerateUninstallKeys(RegistryKey key)
        {
            var apps = new List<RemoteApplication>();
            if (key == null)
                return apps;

            foreach (string subKeyName in key.GetSubKeyNames())
            {
                using (RegistryKey subKey = key.OpenSubKey(subKeyName))
                {
                    var app = new RemoteApplication();
                    if (subKey.GetValue("SystemComponent") != null && subKey.GetValueKind("SystemComponent") == RegistryValueKind.DWord)
                    {
                        Int64 val = Convert.ToInt64(subKey.GetValue("SystemComponent").ToString());
                        if (val == 1)
                            continue;
                    }
                    if (subKey.GetValue("ParentKeyName") != null && subKey.GetValue("ParentKeyName").ToString().Length > 0)
                        continue;
                    if (subKey.GetValue("ReleaseType") != null && (subKey.GetValue("ReleaseType").ToString().Contains("Update") || subKey.GetValue("ReleaseType").ToString() == "Hotfix"))
                        continue;

                    if (subKey.GetValue("DisplayName") != null)
                    {
                        app.DisplayName = subKey.GetValue("DisplayName").ToString();
                    }
                    else
                        continue;

                    if (subKey.GetValue("Publisher") != null)
                        app.Publisher = subKey.GetValue("Publisher").ToString();
                    if (subKey.GetValue("DisplayVersion") != null)
                        app.Version = subKey.GetValue("DisplayVersion").ToString();
                    if (subKey.GetValue("UninstallString") != null)
                        app.UninstallPath = subKey.GetValue("UninstallString").ToString();

                    apps.Add(app);
                }
            }
            return apps;
        }
    }
}
