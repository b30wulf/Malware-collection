using System;
using System.Collections.Generic;
using System.Management;

namespace Splice_Admin.Classes
{
    class RemoteProcess
    {
        public static string ComputerName;
        public static TaskResult Result { get; set; }

        public string Name { get; set; }
        public string ExecutablePath { get; set; }
        public string Owner { get; set; }
        public UInt32 ProcessId { get; set; }
        public UInt32 SessionId { get; set; }


        public static List<RemoteProcess> GetProcesses()
        {
            // GetProcesses() uses WMI to retrieve a list of running processes.
            // It returns a List of RemoteProcess which will be bound to a DataGrid on this UserControl.
            var processes = new List<RemoteProcess>();
            var taskResult = new TaskResult();
            Result = taskResult;

            // Setup WMI Query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);
            var query = new ObjectQuery("SELECT * FROM Win32_Process");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                // Retrieve a list of running processes.
                foreach (ManagementObject m in searcher.Get())
                {
                    var process = new RemoteProcess();

                    process.Name = (m["Name"] != null) ? m["Name"].ToString() : string.Empty;
                    process.ExecutablePath = (m["ExecutablePath"] != null) ? m["ExecutablePath"].ToString() : string.Empty;
                    if (m["ProcessId"] != null)
                        process.ProcessId = (UInt32)m["ProcessId"];
                    if (m["SessionId"] != null)
                        process.SessionId = (UInt32)m["SessionId"];

                    string[] argList = new string[] { string.Empty, string.Empty };
                    int returnVal = Convert.ToInt32(m.InvokeMethod("GetOwner", argList));
                    process.Owner = (returnVal == 0) ? argList[0] : string.Empty;

                    if (process.ProcessId == 0 || process.ProcessId == 4)
                        process.Owner = "SYSTEM";

                    switch (process.Owner.ToUpper())
                    {
                        case ("SYSTEM"):
                            process.Owner = "System";
                            break;
                        case ("LOCAL SERVICE"):
                            process.Owner = "Local Service";
                            break;
                        case ("NETWORK SERVICE"):
                            process.Owner = "Network Service";
                            break;
                    }

                    processes.Add(process);
                }
                taskResult.DidTaskSucceed = true;
            }
            catch
            {
                taskResult.DidTaskSucceed = false;
            }

            return processes;
        }


        public static DialogResult TerminateProcess(RemoteProcess process)
        {
            // TerminateProcess() uses WMI to kill the selected RemoteProcess.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = "NTLMDOMAIN:" + GlobalVar.AlternateDomain;
            }
            var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);
            var query = new ObjectQuery($"SELECT * FROM Win32_Process WHERE Name = '{process.Name}' AND ProcessId = '{process.ProcessId}'");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    // Call the WMI method to terminate the selected process.
                    m.InvokeMethod("Terminate", null);
                    didTaskSucceed = true;
                    break;
                }
            }
            catch
            { }

            if (didTaskSucceed)
            {
                // Process terminated.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{process.Name} has been terminated.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Error terminating process.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to terminate {process.Name}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }
    }
}
