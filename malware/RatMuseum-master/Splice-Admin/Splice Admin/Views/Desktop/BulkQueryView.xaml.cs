using Splice_Admin.Classes;
using System;
using System.Collections.Generic;
using System.DirectoryServices;
using System.Linq;
using System.Windows;
using System.Windows.Controls;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for BulkQueryView.xaml
    /// </summary>
    public partial class BulkQueryView : UserControl
    {
        public BulkQueryView()
        {
            InitializeComponent();

            cboQueryType.SelectedIndex = 0;
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txtSearchPhrase.Text))
            {
                MessageBox.Show("You must enter a valid search phrase.", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                txtSearchPhrase.Focus();
                return;
            }
            else if (rdoManual.IsChecked == true && string.IsNullOrEmpty(txtTargetComputer.Text))
            {
                MessageBox.Show("You must enter a comma-separated list of target computers.", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                txtTargetComputer.Focus();
                return;
            }
            else if (cboQueryType.Text.Equals("Registry value"))
            {
                //txtSearchPhrase.Text = txtSearchPhrase.Text.TrimEnd('\\');
                if (txtSearchPhrase.Text.Count(x => x == '\\') < 2)
                {
                    MessageBox.Show("Invalid registry path.", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                    txtSearchPhrase.Focus();
                    return;
                }
                switch (txtSearchPhrase.Text.Substring(0, txtSearchPhrase.Text.IndexOf('\\')).ToUpper())
                {
                    case "HKEY_CLASSES_ROOT":
                    case "HKEY_LOCAL_MACHINE":
                    case "HKEY_USERS":
                    case "HKEY_CURRENT_CONFIG":
                    case "HKCR":
                    case "HKLM":
                    case "HKU":
                    case "HKCC":
                        break;
                    case "HKEY_CURRENT_USER":
                    case "HKCU":
                        MessageBox.Show("You cannot remotely search the HKEY_CURRENT_USER hive.", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                        txtSearchPhrase.Focus();
                        return;
                    default:
                        MessageBox.Show("Invalid registry path.", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                        txtSearchPhrase.Focus();
                        return;
                }
            }

            var bulkQuery = new RemoteBulkQuery();
            bulkQuery.TargetComputerList = new List<string>();
            if (rdoManual.IsChecked == true)
            {
                bulkQuery.TargetComputerList = txtTargetComputer.Text.Split(',').Select(t => t.Trim()).ToList();
            }
            else if (rdoAllDomainComputers.IsChecked == true)
            {
                bulkQuery.TargetComputerList = GetDomainComputers(DomainComputer.All);
            }
            else if (rdoAllServers.IsChecked == true)
            {
                bulkQuery.TargetComputerList = GetDomainComputers(DomainComputer.Server);
            }
            else if (rdoAllWorkstations.IsChecked == true)
            {
                bulkQuery.TargetComputerList = GetDomainComputers(DomainComputer.Workstation);
            }
            
            bulkQuery.SearchPhrase = txtSearchPhrase.Text;

            switch (cboQueryType.SelectedIndex)
            {
                case 0:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.File;
                    break;
                case 1:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.InstalledApplication;
                    break;
                case 2:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.LoggedOnUser;
                    break;
                case 3:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.Registry;
                    break;
                case 4:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.Process;
                    break;
                case 5:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.Service;
                    break;
                default:
                    bulkQuery.SearchType = RemoteBulkQuery.QueryType.File;
                    break;
            }

            var wnd = new BulkQueryWindow(bulkQuery);
            wnd.Show();
        }

        private List<string> GetDomainComputers(DomainComputer searchType)
        {
            List<string> computerNames = new List<string>();

            using (DirectoryEntry directoryEntry = new DirectoryEntry($"LDAP://{Environment.UserDomainName}"))
            using (DirectorySearcher directorySearcher = new DirectorySearcher(directoryEntry))
            {
                var ldapFilter = "(&(objectClass=computer)(!userAccountControl:1.2.840.113556.1.4.803:=2)";
                if (chkOnlyActiveComputers.IsChecked == true)
                {
                    var thirtyDaysAgo = DateTime.UtcNow.AddDays(-30).ToString("yyyyMMddHHmmss.f'Z'");
                    ldapFilter += $"(whenChanged>={thirtyDaysAgo})";
                }

                switch (searchType)
                {
                    case DomainComputer.All:
                        directorySearcher.Filter = ldapFilter + ")";
                        break;
                    case DomainComputer.Server:
                        ldapFilter += "(|(operatingSystem=Windows Server*)(operatingSystem=Windows 2000 Server)))";
                        directorySearcher.Filter = ldapFilter;
                        break;
                    case DomainComputer.Workstation:
                        ldapFilter += "(!operatingSystem=Windows Server*)(!operatingSystem=Windows 2000 Server))";
                        directorySearcher.Filter = ldapFilter;
                        break;
                }
                directorySearcher.SizeLimit = int.MaxValue;
                directorySearcher.PageSize = int.MaxValue;

                foreach (SearchResult searchResult in directorySearcher.FindAll())
                {
                    string computerName = searchResult.GetDirectoryEntry().Name;
                    if (computerName.StartsWith("CN="))
                        computerName = computerName.Substring(3);
                    computerNames.Add(computerName);
                }
            }

            computerNames.Sort();
            return computerNames;
        }

        private enum DomainComputer
        {
            All,
            Server,
            Workstation
        }

        private void btnHelp_Click(object sender, RoutedEventArgs e)
        {
            popup.IsOpen = true;
        }

        private void btnClosePopup_Click(object sender, RoutedEventArgs e)
        {
            popup.IsOpen = false;
        }
    }
}
