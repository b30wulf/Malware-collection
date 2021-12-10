using RemoteDesktopServicesAPI;
using System;
using System.Collections.Generic;
using System.Management;

namespace Splice_Admin.Classes
{
    public class RemoteLogonSession
    {
        public static bool IsServerEdition;
        public static string ComputerName;
        public static TaskResult Result { get; set; }

        public string Username { get; set; }
        public string UsernameAndStatus { get { return this.IsDisconnected == true ? $"{this.Username} (Disconnected)" : this.Username; } }
        public UInt32 SessionId { get; set; }
        public DateTime LogonTime { get; set; }
        public bool IsDisconnected { get; set; }
        public string IpAddress { get; set; }


        public static List<RemoteLogonSession> GetLogonSessions()
        {
            // GetProcesses() first uses WMI to determine if the target computer is running a desktop or server OS.
            // If running a server OS, it uses the Remote Desktop Service API to retrieve logon sessions.
            // If running a desktop OS, it uses WMI to retrieve logon sessions.
            // It returns a List of RemoteLogonSession which will be bound to a DataGrid on this UserControl.

            var logonSessions = new List<RemoteLogonSession>();
            var taskResult = new TaskResult();
            Result = taskResult;
            UInt32 productType = 1;

            // Determine whether operating system is server or desktop edition.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);
            var query = new ObjectQuery("SELECT ProductType FROM Win32_OperatingSystem");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                foreach (ManagementObject m in searcher.Get())
                {
                    productType = (m["ProductType"] != null) ? (UInt32)m["ProductType"] : 1;
                    break;
                }
            }
            catch
            {
                taskResult.DidTaskSucceed = false;
                return logonSessions;
            }
            IsServerEdition = productType > 1 ? true : false;

            // If operating system is server edition, use Remote Desktop Services API to retrieve logon sessions.
            if (IsServerEdition)
            {
                try
                {
                    using (
                        GlobalVar.UseAlternateCredentials
                        ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                        : null)
                    {
                        IntPtr server = WtsApi.WTSOpenServer(ComputerName);
                        logonSessions.AddRange(WtsApi.GetWindowsUsers(server));

                        foreach (RemoteLogonSession logonSession in logonSessions)
                        {
                            query = new ObjectQuery($"SELECT CreationDate FROM Win32_Process WHERE SessionId = {logonSession.SessionId}");
                            searcher = new ManagementObjectSearcher(scope, query);
                            DateTime logonTime = DateTime.Now;
                            foreach (ManagementObject m in searcher.Get())
                            {
                                DateTime procCreationDate = ManagementDateTimeConverter.ToDateTime(m["CreationDate"].ToString());
                                if (procCreationDate < logonTime)
                                    logonSession.LogonTime = procCreationDate;
                            }
                        }
                    }
                    taskResult.DidTaskSucceed = true;
                }
                catch
                {
                    taskResult.DidTaskSucceed = false;
                }
            }
            // If operating system is desktop edition, query Win32_Process for explorer.exe to determine logged on users.
            else
            {
                query = new ObjectQuery("SELECT * FROM Win32_Process WHERE Name = 'explorer.exe'");
                searcher = new ManagementObjectSearcher(scope, query);

                try
                {
                    foreach (ManagementObject m in searcher.Get())
                    {
                        var logonSession = new RemoteLogonSession();
                        logonSession.SessionId = (UInt32)m["SessionId"];
                        var dmtfDateTime = m["CreationDate"].ToString();
                        logonSession.LogonTime = ManagementDateTimeConverter.ToDateTime(dmtfDateTime);

                        string[] argList = new string[] { string.Empty, string.Empty };
                        int returnVal = Convert.ToInt32(m.InvokeMethod("GetOwner", argList));
                        if (returnVal == 0)
                            logonSession.Username = argList[0];
                        else
                            logonSession.Username = string.Empty;

                        int index = logonSessions.FindIndex(item => item.SessionId == logonSession.SessionId);
                        if (index >= 0)
                            continue;
                        else
                            logonSessions.Add(logonSession);
                    }
                    taskResult.DidTaskSucceed = true;
                }
                catch
                {
                    taskResult.DidTaskSucceed = false;
                }
            }

            return logonSessions;
        }


        public static DialogResult LogoffUser(RemoteLogonSession windowsUser)
        {
            // LogoffUser() forces the selected user to logoff.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;

            if (IsServerEdition)
            {
                try
                {
                    IntPtr server = WtsApi.WTSOpenServer(ComputerName);
                    if (WtsApi.LogOffUser(server, Convert.ToInt32(windowsUser.SessionId), windowsUser.Username) == true)
                        didTaskSucceed = true;
                }
                catch
                { }
            }
            else
            {
                // Setup WMI query.
                var options = new ConnectionOptions();
                if (GlobalVar.UseAlternateCredentials)
                {
                    options.Username = GlobalVar.AlternateUsername;
                    options.Password = GlobalVar.AlternatePassword;
                    options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
                }
                var scope = new ManagementScope($@"\\{ComputerName}\root\CIMV2", options);
                var query = new ObjectQuery("SELECT * FROM Win32_OperatingSystem");
                var searcher = new ManagementObjectSearcher(scope, query);

                try
                {
                    foreach (ManagementObject m in searcher.Get())
                    {
                        ManagementBaseObject inParams = m.GetMethodParameters("Win32Shutdown");
                        inParams["Flags"] = 4;
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
            }

            if (didTaskSucceed)
            {
                // User has been logged off.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"{windowsUser.Username} has been logged off.  It might take a few minutes for the target user to be fully logged out.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Error logging off user.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to logoff {windowsUser.Username}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }
    }
}
