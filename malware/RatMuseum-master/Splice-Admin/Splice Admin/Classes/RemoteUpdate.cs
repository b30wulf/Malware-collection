using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.Management;
using System.ServiceProcess;
using System.Text.RegularExpressions;

namespace Splice_Admin.Classes
{
    class RemoteUpdate
    {
        public static string ComputerName;
        public static TaskResult Result { get; set; }

        public string UpdateId { get; set; }
        public string ExternalLink { get; set; }
        public DateTime InstallDate { get; set; }



        public static List<RemoteUpdate> GetInstalledUpdates()
        {
            // Use WMI to retrieve a list of installed Microsoft updates.
            var updates = new List<RemoteUpdate>();
            var taskResult = new TaskResult();
            Result = taskResult;
            const string microsoftKbUrl = "http://support.microsoft.com/?kbid=";

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);
            var query = new ObjectQuery("SELECT HotFixID,InstalledOn FROM Win32_QuickFixEngineering WHERE HotFixID <> 'File 1'");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    var update = new RemoteUpdate();

                    update.UpdateId = (m["HotFixID"] != null) ? m["HotFixID"].ToString() : string.Empty;
                    if (m["InstalledOn"] != null)
                    {
                        DateTime installDate;
                        if (DateTime.TryParse(m["InstalledOn"].ToString(), out installDate))
                            update.InstallDate = installDate;
                    }
                    update.ExternalLink = (ExtractKbNumber(update.UpdateId).Length > 0) ? microsoftKbUrl + ExtractKbNumber(update.UpdateId) : string.Empty;

                    if (update.UpdateId.Length > 0)
                        updates.Add(update);
                }
                taskResult.DidTaskSucceed = true;
            }
            catch
            {
                taskResult.DidTaskSucceed = false;
            }

            return updates;
        }


        public static string ExtractKbNumber(string kbNumber)
        {
            var regex = new Regex(@"^KB(?<kbNumber>\d{5,8})");
            var match = regex.Match(kbNumber);

            return match.Groups["kbNumber"].Value;
        }


        public static bool GetRebootState()
        {
            bool isRebootPending = false;

            const string wuRegKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\RebootRequired";
            const string cbsRegKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\Component Based Servicing\RebootPending";
            const string pfroRegKey = @"SYSTEM\CurrentControlSet\Control\Session Manager\FileRenameOperations";

            var managementScope = new ManagementScope($@"\\{ComputerName}\root\CIMV2");
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


        public static DialogResult UninstallUpdate(RemoteUpdate update)
        {
            // UninstallUpdate() uses WMI to execute the Microsoft update uninstaller.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;

            string commandLine = $"wusa /uninstall /kb:{ExtractKbNumber(update.UpdateId)} /quiet /norestart";

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);

            // Connect to WMI and invoke the process creation method.
            try
            {
                scope.Connect();
                var objectGetOptions = new ObjectGetOptions();
                var managementPath = new ManagementPath("Win32_Process");
                var managementClass = new ManagementClass(scope, managementPath, objectGetOptions);

                ManagementBaseObject inParams = managementClass.GetMethodParameters("Create");
                inParams["CommandLine"] = commandLine;
                ManagementBaseObject outParams = managementClass.InvokeMethod("Create", inParams, null);

                int returnValue;
                bool isReturnValid = int.TryParse(outParams["ReturnValue"].ToString(), out returnValue);
                if (!isReturnValid || returnValue != 0)
                    throw new Exception();

                didTaskSucceed = true;
            }

            catch
            { }

            if (didTaskSucceed)
            {
                // Process terminated.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"The target computer is processing the request to uninstall Microsoft update {update.UpdateId}." +
                    $"{Environment.NewLine}{Environment.NewLine}" +
                    "It might take several minutes to complete the uninstallation and the computer might need to be rebooted when finished.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Error terminating process.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to uninstall Microsoft update {update.UpdateId}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }
    }
}
