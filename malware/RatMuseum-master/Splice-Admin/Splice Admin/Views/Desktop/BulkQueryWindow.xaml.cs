using Microsoft.Win32;
using RemoteDesktopServicesAPI;
using Splice_Admin.Classes;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Management;
using System.ServiceProcess;
using System.Windows;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for BulkQueryWindow.xaml
    /// </summary>
    public partial class BulkQueryWindow : Window
    {
        private ObservableCollection<QueryResult> HasMatchCollection = new ObservableCollection<QueryResult>();
        private ObservableCollection<QueryResult> NoMatchCollection = new ObservableCollection<QueryResult>();
        private BackgroundWorker bw;
        private int SearchItemsRemaining;

        public BulkQueryWindow(RemoteBulkQuery bulkQuery)
        {
            InitializeComponent();

            dgHasMatch.ItemsSource = HasMatchCollection;
            dgNoMatch.ItemsSource = NoMatchCollection;

            switch (bulkQuery.SearchType)
            {
                case RemoteBulkQuery.QueryType.File:
                    tbSearchType.Text = "File or Directory";
                    break;
                case RemoteBulkQuery.QueryType.InstalledApplication:
                    tbSearchType.Text = "Installed Application";
                    break;
                case RemoteBulkQuery.QueryType.LoggedOnUser:
                    tbSearchType.Text = "Logged On User";
                    break;
                case RemoteBulkQuery.QueryType.Registry:
                    tbSearchType.Text = "Registry Value";
                    break;
                case RemoteBulkQuery.QueryType.Process:
                    tbSearchType.Text = "Running Process";
                    break;
                case RemoteBulkQuery.QueryType.Service:
                    tbSearchType.Text = "Windows Service";
                    break;
                default:
                    tbSearchType.Text = "Unknown";
                    break;
            }
            tbSearchPhrase.Text = bulkQuery.SearchPhrase;
            SearchItemsRemaining = bulkQuery.TargetComputerList.Count;
            txtRemainingCount.Text = $"Remaining: {SearchItemsRemaining}";

            // Setup a background thread.
            bw = new BackgroundWorker();
            bw.WorkerReportsProgress = true;
            bw.DoWork += new DoWorkEventHandler(bgThread_RunQuery);
            bw.ProgressChanged += new ProgressChangedEventHandler(bgThread_RunQueryProgressChanged);
            bw.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_RunQueryCompleted);

            // Launch background thread to retrieve a list of running services.
            bw.RunWorkerAsync(bulkQuery);
        }

        private void bgThread_RunQuery(object sender, DoWorkEventArgs e)
        {
            var bulkQuery = e.Argument as RemoteBulkQuery;

            switch (bulkQuery.SearchType)
            {
                case RemoteBulkQuery.QueryType.File:
                    foreach (var targetComputer in bulkQuery.TargetComputerList)
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.ProgressReport,
                            new QueryResult { ComputerName = targetComputer });
                        SearchForFile(targetComputer, bulkQuery.SearchPhrase);
                    }
                    break;
                case RemoteBulkQuery.QueryType.InstalledApplication:
                    foreach (var targetComputer in bulkQuery.TargetComputerList)
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.ProgressReport,
                            new QueryResult { ComputerName = targetComputer });
                        SearchForInstalledApplication(targetComputer, bulkQuery.SearchPhrase);
                    }
                    break;
                case RemoteBulkQuery.QueryType.LoggedOnUser:
                    foreach (var targetComputer in bulkQuery.TargetComputerList)
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.ProgressReport,
                            new QueryResult { ComputerName = targetComputer });
                        SearchForLoggedOnUser(targetComputer, bulkQuery.SearchPhrase);
                    }
                    break;
                case RemoteBulkQuery.QueryType.Registry:
                    UInt32 selectedRegistryHive = 0;
                    switch (bulkQuery.SearchPhrase.Substring(0, bulkQuery.SearchPhrase.IndexOf('\\')).ToUpper())
                    {
                        case "HKEY_CLASSES_ROOT":
                        case "HKCR":
                            selectedRegistryHive = (uint)RemoteRegistry.Hive.HKEY_CLASSES_ROOT;
                            break;
                        case "HKEY_LOCAL_MACHINE":
                        case "HKLM":
                            selectedRegistryHive = (uint)RemoteRegistry.Hive.HKEY_LOCAL_MACHINE;
                            break;
                        case "HKEY_USERS":
                        case "HKU":
                            selectedRegistryHive = (uint)RemoteRegistry.Hive.HKEY_USERS;
                            break;
                        case "HKEY_CURRENT_CONFIG":
                        case "HKCC":
                            selectedRegistryHive = (uint)RemoteRegistry.Hive.HKEY_CURRENT_CONFIG;
                            break;
                    }

                    var regKeyName = bulkQuery.SearchPhrase.Substring(bulkQuery.SearchPhrase.IndexOf('\\') + 1);
                    regKeyName = regKeyName.Substring(0, regKeyName.LastIndexOf('\\'));
                    var regValueName = bulkQuery.SearchPhrase.Substring(bulkQuery.SearchPhrase.LastIndexOf('\\') + 1);


                    foreach (var targetComputer in bulkQuery.TargetComputerList)
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.ProgressReport,
                            new QueryResult { ComputerName = targetComputer });
                        SearchForRegistryValue(targetComputer, selectedRegistryHive, regKeyName, regValueName);
                    }
                    break;
                case RemoteBulkQuery.QueryType.Process:
                    foreach (var targetComputer in bulkQuery.TargetComputerList)
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.ProgressReport,
                            new QueryResult { ComputerName = targetComputer });
                        SearchForProcess(targetComputer, bulkQuery.SearchPhrase);
                    }
                    break;
                case RemoteBulkQuery.QueryType.Service:
                    foreach (var targetComputer in bulkQuery.TargetComputerList)
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.ProgressReport,
                            new QueryResult { ComputerName = targetComputer });
                        SearchForWindowsService(targetComputer, bulkQuery.SearchPhrase);
                    }
                    break;
            }
        }

        private void bgThread_RunQueryCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            txtStatus.Text = "Search complete";
            txtRemainingCount.Text = "";
        }

        private void bgThread_RunQueryProgressChanged(object sender, ProgressChangedEventArgs e)
        {
            var queryResult = e.UserState as QueryResult;
            switch (e.ProgressPercentage)
            {
                case ((int)QueryResult.Type.Match):
                    HasMatchCollection.Add(queryResult);
                    break;
                case ((int)QueryResult.Type.NoMatch):
                    NoMatchCollection.Add(queryResult);
                    break;
                case ((int)QueryResult.Type.ProgressReport):
                    txtStatus.Text = $"Querying: {queryResult.ComputerName}";
                    txtRemainingCount.Text = $"Remaining: {--SearchItemsRemaining}";
                    break;
            }
        }

        private void SearchForFile(string targetComputer, string searchPhrase)
        {
            // Setup WMI query.
            var options = new ConnectionOptions();
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);

            // Determine whether or not this is a wildcard search.
            if (!string.IsNullOrEmpty(Path.GetFileName(searchPhrase)) && searchPhrase.Contains('*'))
            {
                string queryString;

                try
                {
                    var pathRoot = Path.GetPathRoot(searchPhrase).TrimEnd('\\');
                    var path = Path.GetDirectoryName(searchPhrase);
                    path = path.Substring(pathRoot.Length);

                    if (Path.HasExtension(searchPhrase))
                    {
                        var fileName = Path.GetFileNameWithoutExtension(searchPhrase).Replace('*', '%');
                        var extension = Path.GetExtension(searchPhrase).Replace('*', '%').TrimStart('.');
                        queryString = $"SELECT * FROM CIM_DataFile WHERE Drive = '{pathRoot}' AND Path = '{path.Replace(@"\", @"\\")}\\\\' AND FileName LIKE '{fileName}' AND Extension LIKE '{extension}'";
                    }
                    else
                    {
                        var fileName = Path.GetFileName(searchPhrase).Replace('*', '%');
                        queryString = $"SELECT * FROM CIM_DataFile WHERE Drive = '{pathRoot}' AND Path = '{path.Replace(@"\", @"\\")}\\\\' AND FileName LIKE '{fileName}'";
                    }

                    var query = new ObjectQuery(queryString);
                    var searcher = new ManagementObjectSearcher(scope, query);

                    if (searcher.Get().Count > 0)
                    {
                        foreach (ManagementObject obj in searcher.Get())
                        {

                            bw.ReportProgress(
                               (int)QueryResult.Type.Match,
                                new QueryResult { ComputerName = targetComputer, ResultText = (string)obj["Name"] });
                        }
                    }
                    else
                    {
                        bw.ReportProgress(
                            (int)QueryResult.Type.NoMatch,
                            new QueryResult { ComputerName = targetComputer, ResultText = "File not found." });
                    }
                }
                catch (Exception ex)
                {
                    string errorMessage = ex.Message;
                    if (ex.Message.Contains("("))
                        errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                    bw.ReportProgress(
                        (int)QueryResult.Type.NoMatch,
                        new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
                }

            }
            else
            {
                try
                {
                    var query = new ObjectQuery($@"SELECT * FROM CIM_DataFile WHERE Name = '{searchPhrase.Replace(@"\", @"\\")}'");
                    var searcher = new ManagementObjectSearcher(scope, query);

                    if (searcher.Get().Count > 0)
                        bw.ReportProgress(
                            (int)QueryResult.Type.Match,
                            new QueryResult { ComputerName = targetComputer, ResultText = "File found." });
                    else
                    {
                        query = new ObjectQuery($@"SELECT * FROM Win32_Directory WHERE Name = '{searchPhrase.Replace(@"\", @"\\")}'");
                        searcher = new ManagementObjectSearcher(scope, query);

                        if (searcher.Get().Count > 0)
                            bw.ReportProgress(
                                (int)QueryResult.Type.Match,
                                new QueryResult { ComputerName = targetComputer, ResultText = "Directory found." });
                        else
                            bw.ReportProgress(
                                (int)QueryResult.Type.NoMatch,
                                new QueryResult { ComputerName = targetComputer, ResultText = "File not found." });
                    }
                }
                catch (Exception ex)
                {
                    string errorMessage = ex.Message;
                    if (ex.Message.Contains("("))
                        errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                    bw.ReportProgress(
                        (int)QueryResult.Type.NoMatch,
                        new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
                }
            }



        }

        private void SearchForWindowsService(string targetComputer, string searchPhrase)
        {
            // Setup WMI query.
            var options = new ConnectionOptions();
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);
            var query = new ObjectQuery($"SELECT * FROM Win32_Service WHERE DisplayName LIKE '%{searchPhrase}%' OR Name LIKE '%{searchPhrase}%'");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                if (searcher.Get().Count > 0)
                {
                    foreach (ManagementObject obj in searcher.Get())
                    {
                        var displayName = (obj["DisplayName"] != null) ? obj["DisplayName"].ToString() : string.Empty;
                        var startMode = (obj["StartMode"] != null) ? obj["StartMode"].ToString() : string.Empty;
                        var state = (obj["State"] != null) ? obj["State"].ToString() : string.Empty;

                        bw.ReportProgress(
                            (int)QueryResult.Type.Match,
                            new QueryResult { ComputerName = targetComputer, ResultText = $"{displayName} ({startMode}) - {state}" });
                    }
                }
                else
                    bw.ReportProgress(
                        (int)QueryResult.Type.NoMatch,
                        new QueryResult { ComputerName = targetComputer, ResultText = "Service not found." });
            }
            catch (Exception ex)
            {
                string errorMessage = ex.Message;
                if (ex.Message.Contains("("))
                    errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                bw.ReportProgress(
                    (int)QueryResult.Type.NoMatch,
                    new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
            }
        }

        private void SearchForInstalledApplication(string targetComputer, string searchPhrase)
        {
            const string uninstallKey = @"SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall";
            const string uninstallKey32on64 = @"SOFTWARE\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall";

            var managementScope = new ManagementScope($@"\\{targetComputer}\root\CIMV2");
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
                    int numberOfMatchingApplications = 0;
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

                        // Get DisplayName (String) value.
                        inParams["sSubKeyName"] = subKey;
                        inParams["sValueName"] = "DisplayName";
                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                        if (outParams["sValue"] != null)
                        {
                            // Check if DisplayName contains the search phrase.
                            var appName = (string)outParams["sValue"];
                            if (appName.ToUpper().Contains(searchPhrase.ToUpper()))
                            {
                                // Match.  Get the version and then report progress.
                                // Get DisplayVersion (String) value.
                                ++numberOfMatchingApplications;
                                inParams["sSubKeyName"] = subKey;
                                inParams["sValueName"] = "DisplayVersion";
                                outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);

                                string resultText;
                                if (outParams["sValue"] != null)
                                    resultText = $"{appName} [{(string)outParams["sValue"]}]";
                                else
                                    resultText = appName;

                                bw.ReportProgress(
                                    (int)QueryResult.Type.Match,
                                    new QueryResult { ComputerName = targetComputer, ResultText = resultText });
                            }
                        }
                    }

                    if (numberOfMatchingApplications == 0)
                        bw.ReportProgress(
                            (int)QueryResult.Type.NoMatch,
                            new QueryResult { ComputerName = targetComputer, ResultText = "Application not found." });
                }
            }

            catch (ManagementException ex) when (ex.ErrorCode == ManagementStatus.NotFound)
            {
                const string serviceName = "RemoteRegistry";
                bool isLocal = targetComputer.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
                bool isServiceRunning = true;

                // If the target computer is remote, then start the Remote Registry service.
                using (var sc = new ServiceController(serviceName, targetComputer))
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
                        int numberOfMatchingApplications = 0;

                        using (RegistryKey key = RegistryKey.OpenRemoteBaseKey(RegistryHive.LocalMachine, targetComputer))
                        {
                            using (RegistryKey mainKey64 = key.OpenSubKey(uninstallKey))
                                numberOfMatchingApplications += EnumerateUninstallKeys(mainKey64, searchPhrase, targetComputer);
                            using (RegistryKey mainKey32 = key.OpenSubKey(uninstallKey32on64))
                                numberOfMatchingApplications += EnumerateUninstallKeys(mainKey32, searchPhrase, targetComputer);
                        }

                        if (numberOfMatchingApplications == 0)
                            bw.ReportProgress(
                                (int)QueryResult.Type.NoMatch,
                                new QueryResult { ComputerName = targetComputer, ResultText = "Application not found." });
                    }
                    catch (Exception excpt)
                    {
                        string errorMessage = excpt.Message;
                        if (excpt.Message.Contains("("))
                            errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                        bw.ReportProgress(
                            (int)QueryResult.Type.NoMatch,
                            new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
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

            catch (Exception ex)
            {
                string errorMessage = ex.Message;
                if (ex.Message.Contains("("))
                    errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                bw.ReportProgress(
                    (int)QueryResult.Type.NoMatch,
                    new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
            }

            finally
            {
                if (inParams != null)
                    inParams.Dispose();
                if (outParams != null)
                    outParams.Dispose();
            }
        }

        private void SearchForLoggedOnUser(string targetComputer, string searchPhrase)
        {
            UInt32 productType = 1;

            // Determine whether operating system is server or desktop edition.
            var options = new ConnectionOptions();
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);
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
            }

            // If operating system is server edition, use Remote Desktop Services API to retrieve logon sessions.
            if (productType > 1)
            {
                try
                {
                    IntPtr server = WtsApi.WTSOpenServer(targetComputer);
                    if (WtsApi.IsUserLoggedOn(server, searchPhrase))
                        bw.ReportProgress(
                            (int)QueryResult.Type.Match,
                            new QueryResult { ComputerName = targetComputer, ResultText = "User logged in." });
                    else
                        bw.ReportProgress(
                            (int)QueryResult.Type.NoMatch,
                            new QueryResult { ComputerName = targetComputer, ResultText = "User not logged in." });
                }
                catch (Exception ex)
                {
                    string errorMessage = ex.Message;
                    if (ex.Message.Contains("("))
                        errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                    bw.ReportProgress(
                        (int)QueryResult.Type.NoMatch,
                        new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
                }
            }
            // If operating system is desktop edition, query Win32_Process for explorer.exe to determine logged on users.
            else
            {
                query = new ObjectQuery("SELECT * FROM Win32_Process WHERE Name = 'explorer.exe'");
                searcher = new ManagementObjectSearcher(scope, query);

                try
                {
                    bool hasMatch = false;
                    foreach (ManagementObject m in searcher.Get())
                    {
                        string loggedOnUser;

                        string[] argList = new string[] { string.Empty, string.Empty };
                        int returnVal = Convert.ToInt32(m.InvokeMethod("GetOwner", argList));
                        if (returnVal == 0)
                            loggedOnUser = argList[0];
                        else
                            loggedOnUser = string.Empty;

                        if (loggedOnUser.Equals(searchPhrase, StringComparison.OrdinalIgnoreCase))
                        {
                            hasMatch = true;
                            break;
                        }
                    }

                    if (hasMatch)
                        bw.ReportProgress(
                            (int)QueryResult.Type.Match,
                            new QueryResult { ComputerName = targetComputer, ResultText = "User logged in." });
                    else
                        bw.ReportProgress(
                            (int)QueryResult.Type.NoMatch,
                            new QueryResult { ComputerName = targetComputer, ResultText = "User not logged in." });
                }
                catch (Exception ex)
                {
                    string errorMessage = ex.Message;
                    if (ex.Message.Contains("("))
                        errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                    bw.ReportProgress(
                        (int)QueryResult.Type.NoMatch,
                        new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
                }
            }
        }


        private void SearchForRegistryValue(string targetComputer, UInt32 registryHive, string registryKeyName, string registryValueName)
        {
            var managementScope = new ManagementScope($@"\\{targetComputer}\root\CIMV2");
            ManagementBaseObject inParams = null;
            ManagementBaseObject outParams = null;

            try
            {
                using (var wmiRegistry = new ManagementClass(managementScope, new ManagementPath("StdRegProv"), null))
                {
                    // A registry search can either retrieve the data for a specific value, or it can enumerate the values for a given key.
                    // At this point, we are unsure if the user supplied a path to a value or a key.
                    // We are first assuming a key was provided and will attempt to enumerate values.
                    inParams = wmiRegistry.GetMethodParameters("EnumValues");
                    inParams["hDefKey"] = registryHive;
                    inParams["sSubKeyName"] = $@"{registryKeyName}\{registryValueName}";
                    outParams = wmiRegistry.InvokeMethod("EnumValues", inParams, null);
                    if (outParams["sNames"] != null && outParams["Types"] != null)
                    {
                        // Enumerate values.
                        var names = outParams["sNames"] as string[];
                        var types = outParams["Types"] as int[];
                        for (int i = 0; i < names.Length; ++i)
                        {
                            switch (types[i])
                            {
                                case (int)RemoteRegistry.ValueType.REG_DWORD:
                                    inParams = wmiRegistry.GetMethodParameters("GetDWORDValue");
                                    inParams["hDefKey"] = registryHive;
                                    inParams["sSubKeyName"] = $@"{registryKeyName}\{registryValueName}";
                                    inParams["sValueName"] = names[i];
                                    outParams = wmiRegistry.InvokeMethod("GetDWORDValue", inParams, null);
                                    if (outParams["uValue"] != null)
                                        bw.ReportProgress(
                                            (int)QueryResult.Type.Match,
                                            new QueryResult { ComputerName = targetComputer, ResultText = $"{names[i]} ({(UInt32)outParams["uValue"]})" });
                                    break;
                                case (int)RemoteRegistry.ValueType.REG_EXPAND_SZ:
                                case (int)RemoteRegistry.ValueType.REG_SZ:
                                    inParams = wmiRegistry.GetMethodParameters("GetStringValue");
                                    inParams["hDefKey"] = registryHive;
                                    inParams["sSubKeyName"] = $@"{registryKeyName}\{registryValueName}";
                                    inParams["sValueName"] = names[i];
                                    outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                                    if (outParams["sValue"] != null)
                                        bw.ReportProgress(
                                            (int)QueryResult.Type.Match,
                                            new QueryResult { ComputerName = targetComputer, ResultText = $"{names[i]} ({(string)outParams["sValue"]})" });
                                    break;
                                default:
                                    bw.ReportProgress(
                                            (int)QueryResult.Type.Match,
                                            new QueryResult { ComputerName = targetComputer, ResultText = $"{names[i]} [UNSUPPORTED TYPE: {((RemoteRegistry.ValueType)types[i]).ToString()}]" });
                                    break;

                            }
                        }
                    }
                    else
                    {
                        // Key enumeration failed or returned no results.
                        // We will now attempt to see if the provided search phrase was for a specific registry value.
                        int valueType = 0;
                        inParams = wmiRegistry.GetMethodParameters("EnumValues");
                        inParams["hDefKey"] = registryHive;
                        inParams["sSubKeyName"] = registryKeyName;
                        outParams = wmiRegistry.InvokeMethod("EnumValues", inParams, null);
                        if (outParams["sNames"] != null && outParams["Types"] != null)
                        {
                            var valueNames = outParams["sNames"] as string[];
                            for (int i = 0; i < valueNames.Length; ++i)
                            {
                                if (valueNames[i].ToUpper().Equals(registryValueName.ToUpper()))
                                {
                                    var valueTypes = outParams["Types"] as int[];
                                    valueType = valueTypes[i];
                                    break;
                                }
                            }

                            if (valueType != 0)
                            {
                                switch (valueType)
                                {
                                    case (int)RemoteRegistry.ValueType.REG_DWORD:
                                        inParams = wmiRegistry.GetMethodParameters("GetDWORDValue");
                                        inParams["hDefKey"] = registryHive;
                                        inParams["sSubKeyName"] = registryKeyName;
                                        inParams["sValueName"] = registryValueName;
                                        outParams = wmiRegistry.InvokeMethod("GetDWORDValue", inParams, null);
                                        if (outParams["uValue"] != null)
                                            bw.ReportProgress(
                                                (int)QueryResult.Type.Match,
                                                new QueryResult { ComputerName = targetComputer, ResultText = ((UInt32)outParams["uValue"]).ToString() });
                                        else
                                            bw.ReportProgress(
                                                (int)QueryResult.Type.NoMatch,
                                                new QueryResult { ComputerName = targetComputer, ResultText = "Error reading REG_DWORD value." });
                                        break;
                                    case (int)RemoteRegistry.ValueType.REG_EXPAND_SZ:
                                    case (int)RemoteRegistry.ValueType.REG_SZ:
                                        inParams = wmiRegistry.GetMethodParameters("GetStringValue");
                                        inParams["hDefKey"] = registryHive;
                                        inParams["sSubKeyName"] = registryKeyName;
                                        inParams["sValueName"] = registryValueName;
                                        outParams = wmiRegistry.InvokeMethod("GetStringValue", inParams, null);
                                        if (outParams["sValue"] != null)
                                            bw.ReportProgress(
                                                (int)QueryResult.Type.Match,
                                                new QueryResult { ComputerName = targetComputer, ResultText = (string)outParams["sValue"] });
                                        else
                                            bw.ReportProgress(
                                                (int)QueryResult.Type.NoMatch,
                                                new QueryResult { ComputerName = targetComputer, ResultText = "Error reading REG_SZ value." });
                                        break;
                                }
                            }
                            else
                                bw.ReportProgress(
                                    (int)QueryResult.Type.NoMatch,
                                    new QueryResult { ComputerName = targetComputer, ResultText = "Registry value not found." });
                        }
                        else
                            bw.ReportProgress(
                                (int)QueryResult.Type.NoMatch,
                                new QueryResult { ComputerName = targetComputer, ResultText = "Registry key not found." });
                    }
                }
            }

            catch (ManagementException ex) when (ex.ErrorCode == ManagementStatus.NotFound)
            {
                bw.ReportProgress(
                    (int)QueryResult.Type.NoMatch,
                    new QueryResult { ComputerName = targetComputer, ResultText = "Target operating system not supported." });
            }

            catch (Exception ex)
            {
                string errorMessage = ex.Message;
                if (ex.Message.Contains("("))
                    errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                bw.ReportProgress(
                    (int)QueryResult.Type.NoMatch,
                    new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
            }
        }



        private void SearchForProcess(string targetComputer, string searchPhrase)
        {
            // Setup WMI Query.
            var options = new ConnectionOptions();
            var scope = new ManagementScope($@"\\{targetComputer}\root\CIMV2", options);
            var query = new ObjectQuery($"SELECT * FROM Win32_Process WHERE Name LIKE '%{searchPhrase}%'");
            var searcher = new ManagementObjectSearcher(scope, query);

            try
            {
                if (searcher.Get().Count > 0)
                {
                    // Retrieve a list of running processes.
                    foreach (ManagementObject m in searcher.Get())
                    {
                        if (m["Name"] != null)
                        {
                            var resultText = m["Name"].ToString();
                            if (m["ProcessId"] != null) resultText += $" ({m["ProcessId"]})";

                            var argList = new string[] { string.Empty, string.Empty };
                            int returnVal = Convert.ToInt32(m.InvokeMethod("GetOwner", argList));

                            string processOwner = (returnVal == 0) ? argList[0] : string.Empty;

                            switch (processOwner.ToUpper())
                            {
                                case ("SYSTEM"):
                                    processOwner = "System";
                                    break;
                                case ("LOCAL SERVICE"):
                                    processOwner = "Local Service";
                                    break;
                                case ("NETWORK SERVICE"):
                                    processOwner = "Network Service";
                                    break;
                            }

                            if (processOwner.Length > 0) resultText += $" ({processOwner})";

                            bw.ReportProgress(
                                (int)QueryResult.Type.Match,
                                new QueryResult { ComputerName = targetComputer, ResultText = resultText });
                        }
                    }
                }
                else
                    bw.ReportProgress(
                        (int)QueryResult.Type.NoMatch,
                        new QueryResult { ComputerName = targetComputer, ResultText = "Process not found." });
            }
            catch (Exception ex)
            {
                string errorMessage = ex.Message;
                if (ex.Message.Contains("("))
                    errorMessage = errorMessage.Substring(0, errorMessage.IndexOf('('));

                bw.ReportProgress(
                    (int)QueryResult.Type.NoMatch,
                    new QueryResult { ComputerName = targetComputer, ResultText = errorMessage.Trim() });
            }
        }


        private int EnumerateUninstallKeys(RegistryKey key, string searchPhrase, string targetComputer)
        {
            int numberOfMatchingApplications = 0;

            if (key == null)
                return numberOfMatchingApplications;

            foreach (string subKeyName in key.GetSubKeyNames())
            {
                using (RegistryKey subKey = key.OpenSubKey(subKeyName))
                {
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
                        if (subKey.GetValue("DisplayName").ToString().ToUpper().Contains(searchPhrase.ToUpper()))
                        {
                            ++numberOfMatchingApplications;

                            string resultText;
                            if (subKey.GetValue("DisplayVersion") != null)
                                resultText = $"{subKey.GetValue("DisplayName")} [{subKey.GetValue("DisplayVersion")}]";
                            else
                                resultText = subKey.GetValue("DisplayName").ToString();

                            bw.ReportProgress(
                                (int)QueryResult.Type.Match,
                                new QueryResult { ComputerName = targetComputer, ResultText = resultText });
                        }
                    }
                    else
                        continue;
                }
            }
            return numberOfMatchingApplications;
        }

    }
}
