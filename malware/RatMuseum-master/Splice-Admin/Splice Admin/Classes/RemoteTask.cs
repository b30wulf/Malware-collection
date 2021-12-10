using System;
using System.Diagnostics;
using System.IO;
using System.Management;
using System.Security.AccessControl;
using System.Security.Principal;
using System.Threading;

namespace Splice_Admin.Classes
{
    class RemoteTask
    {
        public static DialogResult TaskRebootComputer(string targetComputer)
        {
            // TaskRebootComputer() uses WMI to reboot the selected computer.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);
            var query = new ObjectQuery("SELECT * FROM Win32_OperatingSystem");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    ManagementBaseObject inParams = m.GetMethodParameters("Win32Shutdown");
                    inParams["Flags"] = 6;
                    ManagementBaseObject outParams = m.InvokeMethod("Win32Shutdown", inParams, null);
                    int returnValue;
                    bool isReturnValid = int.TryParse(outParams["ReturnValue"].ToString(), out returnValue);
                    if (isReturnValid && returnValue == 0)
                        didTaskSucceed = true;
                    break;
                }
            }
            catch
            { }

            if (didTaskSucceed)
            {
                // Reboot command was successful.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{targetComputer} is now in the process of rebooting.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Failed to reboot computer.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to reboot {targetComputer}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }


        public static DialogResult TaskShutdownComputer(string targetComputer)
        {
            // TaskShutdownComputer() uses WMI to shutdown the selected computer.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);
            var query = new ObjectQuery("SELECT * FROM Win32_OperatingSystem");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    ManagementBaseObject inParams = m.GetMethodParameters("Win32Shutdown");
                    inParams["Flags"] = 5;
                    ManagementBaseObject outParams = m.InvokeMethod("Win32Shutdown", inParams, null);
                    int returnValue;
                    bool isReturnValid = int.TryParse(outParams["ReturnValue"].ToString(), out returnValue);
                    if (isReturnValid && returnValue == 0)
                        didTaskSucceed = true;
                    break;
                }
            }
            catch
            { }

            if (didTaskSucceed)
            {
                // Shutdown command was successful.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{targetComputer} is now in the process of shutting down.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Failed to shutdown computer.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to shutdown {targetComputer}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }


        public static DialogResult TaskGpupdate(string targetComputer)
        {
            // TaskGpupdate() uses WMI to execute the GPUpdate command.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;
            const string gpupdateComputer = "cmd /c echo n | gpupdate /force";

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);

            try
            {
                scope.Connect();
                var objectGetOptions = new ObjectGetOptions();
                var managementPath = new ManagementPath("Win32_Process");
                var managementClass = new ManagementClass(scope, managementPath, objectGetOptions);

                ManagementBaseObject inParams = managementClass.GetMethodParameters("Create");
                inParams["CommandLine"] = gpupdateComputer;
                ManagementBaseObject outParams = managementClass.InvokeMethod("Create", inParams, null);

                int returnValue;
                bool isReturnValid = int.TryParse(outParams["ReturnValue"].ToString(), out returnValue);
                if (!isReturnValid || returnValue != 0)
                    throw new Exception();
            }
            catch
            { }

            try
            {
                // To apply user policies, gpupdate.exe MUST be run in the context of each logged in user.
                // In order to do so, a scheduled task is created on the target machine that is configured to run GPUpdate
                // as each currently logged in user.  Prior to running the task, a simple .VBS script that calls gpupdate.exe is
                // written to C:\Windows\TEMP on the target computer.  The scheduled task points to the .VBS script.  This is done
                // to hide any console windows that would appear if the task were to directly run gpupdate.exe.  The temporary
                // script file and task are then deleted once the execution has kicked off.
                const string gpupdateUser = "CreateObject(\"WScript.Shell\").Run \"cmd /c echo n | gpupdate /target:user /force\", 0";
                string remotePathToGpupdateScript = $@"\\{targetComputer}\C$\Windows\Temp";
                string localPathToGpupdateScript = @"C:\Windows\Temp";

                RemoteLogonSession.ComputerName = targetComputer;
                var loggedOnUsers = RemoteLogonSession.GetLogonSessions();

                if (loggedOnUsers.Count > 0 && Directory.Exists(remotePathToGpupdateScript))
                {
                    var rnd = new Random();
                    var rndNumber = rnd.Next(0, 1000);
                    remotePathToGpupdateScript += $@"\splc-{rndNumber}.vbs";
                    localPathToGpupdateScript += $@"\splc-{rndNumber}.vbs";
                    var scheduledTaskName = "SpliceAdmin-Gpupdate-" + rndNumber;

                    // Write temporary .vbs script file.
                    File.WriteAllText(remotePathToGpupdateScript, gpupdateUser);

                    if (File.Exists(remotePathToGpupdateScript))
                    {
                        var fileInfo = new FileInfo(remotePathToGpupdateScript);
                        var fileSecurity = fileInfo.GetAccessControl();
                        fileSecurity.AddAccessRule(new FileSystemAccessRule(new SecurityIdentifier(WellKnownSidType.WorldSid, null), FileSystemRights.ReadAndExecute, InheritanceFlags.None, PropagationFlags.None, AccessControlType.Allow));
                        fileInfo.SetAccessControl(fileSecurity);

                        var startInfo = new ProcessStartInfo();
                        startInfo.RedirectStandardOutput = true;
                        startInfo.RedirectStandardError = true;
                        startInfo.UseShellExecute = false;
                        startInfo.CreateNoWindow = true;
                        startInfo.FileName = "schtasks.exe";

                        foreach (var user in loggedOnUsers)
                        {
                            startInfo.Arguments = $"/create /f /s {targetComputer} /ru {user.Username} /sc once /st 00:00 /tn {scheduledTaskName} /tr \"wscript.exe //e:vbs //b \\\"{localPathToGpupdateScript}\\\"\"  /it";
                            Process.Start(startInfo);
                            Thread.Sleep(500);

                            startInfo.Arguments = $"/run /tn {scheduledTaskName} /s {targetComputer}";
                            Process.Start(startInfo);
                            Thread.Sleep(2000);
                        }

                        startInfo.Arguments = $"/delete /f /tn {scheduledTaskName} /s {targetComputer}";
                        Process.Start(startInfo);

                        File.Delete(remotePathToGpupdateScript);
                    }

                    Thread.Sleep(7000);
                    didTaskSucceed = true;
                }
            }
            catch
            { }

            if (didTaskSucceed)
            {
                // GPUpdate was successful.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{targetComputer} has successfuly refreshed all Group Policy Objects.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Failed to run GPUpdate.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to refresh Group Policy Objects on {targetComputer}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }
    }
}
