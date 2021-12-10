using CSUACSelfElevation;
using Splice_Admin.Classes;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Diagnostics;
using System.DirectoryServices;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Controls.Primitives;
using System.Windows.Input;
using System.Windows.Media;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        List<string> _DomainComputerList;
        BulkQueryView _BulkQueryView = new BulkQueryView();

        public MainWindow()
        {
            InitializeComponent();

            string[] commandLineArgs = Environment.GetCommandLineArgs();

            if (commandLineArgs.Length > 1)
                txtTargetComputer.Text = commandLineArgs[1];
            else
                txtTargetComputer.Text = Environment.MachineName;
            GlobalVar.TargetComputerName = txtTargetComputer.Text;

            

            var topMargin = txtTargetComputer.Margin.Top + txtTargetComputer.Height;
            var labelMargin = new Thickness(0.0, topMargin + 1.0, 0.0, 0.0);
            lbSuggestion.Margin = labelMargin;
            _DomainComputerList = new List<string>();
            
            txtTargetComputer.TextChanged += new TextChangedEventHandler(txtTargetComputer_TextChanged);

            // Background thread to retrieve a list of all domain computers.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetDomainComputers);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetDomainComputersCompleted);
            bgWorker.RunWorkerAsync();

            if (commandLineArgs.Length == 3 && commandLineArgs[1] == "Elevate")
            {
                txtTargetComputer.Text = Environment.MachineName;
                GlobalVar.TargetComputerName = txtTargetComputer.Text;

                switch (commandLineArgs[2])
                {
                    case "LogonHistory":
                        btnUsers.RaiseEvent(new RoutedEventArgs(ButtonBase.ClickEvent));
                        break;
                    case "Processes":
                        btnProcesses.RaiseEvent(new RoutedEventArgs(ButtonBase.ClickEvent));
                        break;
                    default:
                        contentControl.Content = new HomeView();
                        break;
                }
            }
            else
                contentControl.Content = new HomeView();
        }


        private void bgThread_GetDomainComputers(object sender, DoWorkEventArgs e)
        {
            // Retrieve a List<string> of all domain computers.
            e.Result = GetDomainComputers();
        }


        private void bgThread_GetDomainComputersCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Finished retreiving the list of computers.  Update the global list.
            _DomainComputerList = e.Result as List<string>;
        }


        private void EnterCommand_CanExecute(object sender, CanExecuteRoutedEventArgs e)
        {
            e.CanExecute = true;
        }

        public void EnterCommand_Executed(object sender, ExecutedRoutedEventArgs e)
        {
            lbSuggestion.Visibility = Visibility.Collapsed;
            lbSuggestion.ItemsSource = null;

            RoutedEventArgs newEventArgs = new RoutedEventArgs(Button.ClickEvent);
            switch (SelectedItem.Text)
            {
                case "System Info":
                    btnSystemInfo.RaiseEvent(newEventArgs);
                    break;
                case "Processes":
                    btnProcesses.RaiseEvent(newEventArgs);
                    break;
                case "Services":
                    btnServices.RaiseEvent(newEventArgs);
                    break;
                case "Networking":
                    btnNetworking.RaiseEvent(newEventArgs);
                    break;
                case "Storage":
                    btnStorage.RaiseEvent(newEventArgs);
                    break;
                case "Applications":
                    btnApplications.RaiseEvent(newEventArgs);
                    break;
                case "Updates":
                    btnUpdates.RaiseEvent(newEventArgs);
                    break;
                case "Users":
                    btnUsers.RaiseEvent(newEventArgs);
                    break;
                case "Tasks":
                    btnTasks.RaiseEvent(newEventArgs);
                    break;
                case "Bulk Query":
                    return;
                default:
                    btnSystemInfo.RaiseEvent(newEventArgs);
                    break;
            }
            contentControl.Focus();
        }


        private static List<string> GetDomainComputers()
        {
            List<string> computerNames = new List<string>();

            using (DirectoryEntry directoryEntry = new DirectoryEntry("LDAP://" + Environment.UserDomainName))
            using (DirectorySearcher directorySearcher = new DirectorySearcher(directoryEntry))
            {
                directorySearcher.Filter = ("(objectClass=computer)");
                directorySearcher.SizeLimit = 1000;
                directorySearcher.PageSize = int.MaxValue;

                try
                {
                    using (SearchResultCollection searchResultCollection = directorySearcher.FindAll())
                    {
                        foreach (SearchResult searchResult in searchResultCollection)
                        {
                            string computerName = searchResult.GetDirectoryEntry().Name;
                            if (computerName.StartsWith("CN="))
                                computerName = computerName.Substring(3);
                            computerNames.Add(computerName.ToUpper());
                        }
                    }
                }
                catch
                {
                    // Silently fail.
                }
            }

            computerNames.Sort();
            return computerNames;
        }


        private void MenuButton_Click(object sender, RoutedEventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txtTargetComputer.Text))
                return;

            var selectedButton = sender as Button;
            SetMenuColors(selectedButton);
            SelectedItem.Text = selectedButton.Content.ToString();
            SelectedTarget.Text = txtTargetComputer.Text;
            SelectedTarget.Visibility = Visibility.Visible;
            txtTargetComputer.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#000000"));
            switch (SelectedItem.Text)
            {
                case "System Info":
                    contentControl.Content = new SystemInfoView(txtTargetComputer.Text.Trim());
                    break;
                case "Processes":
                    // Elevate the process if targeting local computer and is not run as administrator.
                    if (txtTargetComputer.Text == Environment.MachineName && !ElevationHelper.IsRunAsAdmin())
                    {
                        // Launch itself as administrator
                        ProcessStartInfo proc = new ProcessStartInfo();
                        proc.UseShellExecute = true;
                        proc.WorkingDirectory = Environment.CurrentDirectory;
                        proc.FileName = System.Reflection.Assembly.GetExecutingAssembly().Location;
                        proc.Verb = "runas";
                        proc.Arguments = "Elevate Processes";

                        try
                        {
                            Process.Start(proc);
                        }
                        catch
                        {
                            // The user refused the elevation.
                            // Do nothing and return directly ...
                            contentControl.Content = new ProcessesView(txtTargetComputer.Text.Trim());
                            break;
                        }

                        Application.Current.Shutdown();
                    }
                    else
                        contentControl.Content = new ProcessesView(txtTargetComputer.Text.Trim());
                    break;
                case "Services":
                    contentControl.Content = new ServicesView(txtTargetComputer.Text.Trim());
                    break;
                case "Networking":
                    contentControl.Content = new NetworkingView(txtTargetComputer.Text.Trim());
                    break;
                case "Storage":
                    contentControl.Content = new StorageView(txtTargetComputer.Text.Trim());
                    break;
                case "Applications":
                    contentControl.Content = new ApplicationsView(txtTargetComputer.Text.Trim());
                    break;
                case "Updates":
                    contentControl.Content = new UpdatesView(txtTargetComputer.Text.Trim());
                    break;
                case "Users":
                    contentControl.Content = new LogonSessionsView(txtTargetComputer.Text.Trim());
                    break;
                case "Tasks":
                    SelectedTarget.Visibility = Visibility.Collapsed;
                    contentControl.Content = new TasksView();
                    break;
                case "Bulk Query":
                    SelectedTarget.Visibility = Visibility.Collapsed;
                    txtTargetComputer.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#999"));
                    contentControl.Content = _BulkQueryView;
                    break;
                default:
                    MessageBox.Show("Button not defined.");
                    break;
            }
        }

        private void SetMenuColors(Button selectedButton)
        {
            btnSystemInfo.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnProcesses.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnServices.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnNetworking.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnStorage.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnApplications.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnUpdates.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnUsers.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnTasks.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            btnBulkQuery.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#a5abb3"));
            selectedButton.Foreground = (SolidColorBrush)(new BrushConverter().ConvertFrom("#eee"));
        }
        

        

        private void txtTargetComputer_TextChanged(object sender, TextChangedEventArgs e)
        {
            GlobalVar.TargetComputerName = txtTargetComputer.Text;
            var typedString = txtTargetComputer.Text;
            var autoList = new List<string>();
            autoList.Clear();

            foreach (string item in _DomainComputerList)
            {
                if (!string.IsNullOrEmpty(txtTargetComputer.Text))
                {
                    if (item.StartsWith(typedString))
                        autoList.Add(item);
                }
            }

            if (autoList.Count == 1 && autoList[0].Equals(typedString))
            {
                lbSuggestion.Visibility = Visibility.Collapsed;
                lbSuggestion.ItemsSource = null;
            }
            else if (autoList.Count > 0)
            {
                lbSuggestion.ItemsSource = autoList;
                lbSuggestion.Visibility = Visibility.Visible;
            }
            else
            {
                lbSuggestion.Visibility = Visibility.Collapsed;
                lbSuggestion.ItemsSource = null;
            }
        }

        private void txtTargetComputer_PreviewKeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Down)
                lbSuggestion.Focus();
            else if (e.Key == Key.Escape)
            {
                lbSuggestion.Visibility = Visibility.Collapsed;
                lbSuggestion.ItemsSource = null;
                txtTargetComputer.Focus();
            }
        }

        private void lbSuggestion_MouseLeftButtonUp(object sender, MouseButtonEventArgs e)
        {
            if (lbSuggestion.ItemsSource != null)
            {
                lbSuggestion.Visibility = Visibility.Collapsed;
                txtTargetComputer.TextChanged -= new TextChangedEventHandler(txtTargetComputer_TextChanged);
                if (lbSuggestion.SelectedIndex != -1)
                {
                    txtTargetComputer.Text = lbSuggestion.SelectedItem.ToString();
                    GlobalVar.TargetComputerName = txtTargetComputer.Text;
                }
                txtTargetComputer.TextChanged += new TextChangedEventHandler(txtTargetComputer_TextChanged);
            }
        }

        private void lbSuggestion_PreviewKeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                if (lbSuggestion.ItemsSource != null)
                {
                    lbSuggestion.Visibility = Visibility.Collapsed;
                    txtTargetComputer.TextChanged -= new TextChangedEventHandler(txtTargetComputer_TextChanged);
                    if (lbSuggestion.SelectedIndex != -1)
                    {
                        txtTargetComputer.Text = lbSuggestion.SelectedItem.ToString();
                        GlobalVar.TargetComputerName = txtTargetComputer.Text;
                    }
                    txtTargetComputer.TextChanged += new TextChangedEventHandler(txtTargetComputer_TextChanged);
                }
            }
            else if (e.Key == Key.Escape)
            {
                lbSuggestion.Visibility = Visibility.Collapsed;
                lbSuggestion.ItemsSource = null;
                txtTargetComputer.Focus();
            }
        }


        private void Button_Click(object sender, RoutedEventArgs e)
        {
            var howdy = contentControl.Content;
            contentControl.Content = new AlternateCredentialsView(contentControl, howdy);
        }
    }
}
