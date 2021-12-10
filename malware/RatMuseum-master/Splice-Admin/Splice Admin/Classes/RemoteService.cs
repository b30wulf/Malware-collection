using System;
using System.Collections.Generic;
using System.Management;
using System.ServiceProcess;

namespace Splice_Admin.Classes
{
    class RemoteService
    {
        public static string ComputerName;
        public static TaskResult Result { get; set; }

        public bool AcceptPause { get; set; }
        public bool AcceptStop { get; set; }
        public string DisplayName { get; set; }
        public string Description { get; set; }
        public string Name { get; set; }
        public string PathName { get; set; }
        public string StartupType { get; set; }
        public string LogOnAs { get; set; }
        public string State { get; set; }

        public static List<RemoteService> GetServices()
        {
            // GetServices() uses WMI to retrieve a list of running services.
            // It returns a List of RemoteService which will be bound to a DataGrid on this UserControl.
            var services = new List<RemoteService>();
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
            var query = new ObjectQuery("SELECT * FROM Win32_Service");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                // Retrieve a list of running services.
                foreach (ManagementObject m in searcher.Get())
                {
                    var service = new RemoteService();

                    service.DisplayName = (m["DisplayName"] != null) ? m["DisplayName"].ToString() : string.Empty;
                    service.AcceptPause = (m["AcceptPause"] != null) ? (bool)m["AcceptPause"] : false;
                    service.AcceptStop = (m["AcceptStop"] != null) ? (bool)m["AcceptStop"] : false;
                    service.Description = (m["Description"] != null) ? m["Description"].ToString() : string.Empty;
                    service.Name = (m["Name"] != null) ? m["Name"].ToString() : string.Empty;
                    service.PathName = (m["PathName"] != null) ? m["PathName"].ToString() : string.Empty;
                    service.StartupType = (m["StartMode"] != null) ? m["StartMode"].ToString() : string.Empty;
                    service.LogOnAs = (m["StartName"] != null) ? m["StartName"].ToString() : string.Empty;
                    service.State = (m["State"] != null) ? m["State"].ToString() : string.Empty;

                    int index = service.LogOnAs.IndexOf(@"NT AUTHORITY\", StringComparison.OrdinalIgnoreCase);
                    if (index >= 0)
                        service.LogOnAs = service.LogOnAs.Remove(index, @"NT AUTHORITY\".Length);

                    switch (service.LogOnAs.ToUpper())
                    {
                        case ("LOCALSERVICE"):
                            service.LogOnAs = "Local Service";
                            break;
                        case ("LOCALSYSTEM"):
                            service.LogOnAs = "Local System";
                            break;
                        case ("NETWORKSERVICE"):
                            service.LogOnAs = "Network Service";
                            break;
                    }

                    services.Add(service);
                }
                taskResult.DidTaskSucceed = true;
            }
            catch
            {
                taskResult.DidTaskSucceed = false;
            }

            return services;
        }


        public static DialogResult StartService(RemoteService service)
        {
            // StartService() attempts to start the specified service.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;
            bool didTimeoutOccur = false;

            using (
                GlobalVar.UseAlternateCredentials
                ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                : null)
            using (var sc = new ServiceController(service.Name, ComputerName))
            {
                try
                {
                    if (sc.Status == ServiceControllerStatus.Stopped)
                    {
                        sc.Start();
                        sc.WaitForStatus(ServiceControllerStatus.Running, TimeSpan.FromSeconds(30));
                        if (sc.Status == ServiceControllerStatus.StartPending)
                            didTimeoutOccur = true;
                        else
                            didTaskSucceed = true;

                    }
                }

                catch
                {
                    if (service.StartupType == "Disabled")
                        dialog.DialogBody = "You cannot start a service that is disabled.";
                    else
                        dialog.DialogBody = $"Failed to start {service.DisplayName}.";
                }
            }

            if (didTaskSucceed)
            {
                // Service started.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{service.DisplayName} is now running.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Service failed to start.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
                if (didTimeoutOccur)
                    dialog.DialogBody = $"Timed out waiting for {service.DisplayName} to start.";
            }

            return dialog;
        }


        public static DialogResult StopService(RemoteService service)
        {
            var dialog = new DialogResult();
            bool didTaskSucceed = false;
            bool didTimeoutOccur = false;

            using (
                GlobalVar.UseAlternateCredentials
                ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                : null)
            using (var sc = new ServiceController(service.Name, ComputerName))
            {
                try
                {
                    if (sc.Status != ServiceControllerStatus.Stopped)
                    {
                        sc.Stop();
                        sc.WaitForStatus(ServiceControllerStatus.Stopped, TimeSpan.FromSeconds(30));
                        if (sc.Status == ServiceControllerStatus.StopPending)
                            didTimeoutOccur = true;
                        else
                            didTaskSucceed = true;
                    }
                    else
                        dialog.DialogBody = $"{service.DisplayName} is already stopped.";
                }

                catch
                {
                }
            }

            if (didTaskSucceed)
            {
                // Service started.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{service.DisplayName} is now stopped.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Service failed to start.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                if (string.IsNullOrEmpty(dialog.DialogBody))
                    dialog.DialogBody = $"Failed to stop {service.DisplayName}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
                if (didTimeoutOccur)
                    dialog.DialogBody = $"Timed out waiting for {service.DisplayName} to stop.";
            }

            return dialog;
        }
    }
}
