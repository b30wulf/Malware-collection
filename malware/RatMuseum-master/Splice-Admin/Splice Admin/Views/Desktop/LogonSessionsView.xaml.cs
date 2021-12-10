using CSUACSelfElevation;
using Splice_Admin.Classes;
using Splice_Admin.Views.Desktop.Dialog;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Diagnostics;
using System.Windows;
using System.Windows.Controls;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for LogonSessionsUsersControl.xaml
    /// </summary>
    public partial class LogonSessionsView : UserControl
    {
        public LogonSessionsView(string targetComputer)
        {
            InitializeComponent();
            
            RemoteLogonSession.ComputerName = targetComputer;
            RefreshDataset();

            string[] commandLineArgs = Environment.GetCommandLineArgs();
            if (commandLineArgs.Length == 3 && commandLineArgs[1] == "Elevate" && commandLineArgs[2] == "LogonHistory")
                tabLogonHistory.IsSelected = true;
        }


        private void RefreshDataset()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve a list of logon sessions.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetLogonSessions);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetLogonSessionsCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetLogonSessions(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteLogonSession.GetLogonSessions();
        }


        private void bgThread_GetLogonSessionsCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridLoading.Visibility = Visibility.Collapsed;

            // If everything succeeded, bind the results to the DataGrid.  Otherwise, display an error.
            if (RemoteLogonSession.Result != null && RemoteLogonSession.Result.DidTaskSucceed)
            {
                // Bind the results to the DataGrid and sort the list.
                dgUsers.ItemsSource = e.Result as List<RemoteLogonSession>;
                RemoteAdmin.SortDataGrid(dgUsers);

                // If no logon sessions were found, display the No Sessions overlay.
                if (dgUsers.Items.Count == 0)
                {
                    gridNoSessions.Visibility = Visibility.Visible;
                    tbTargetComputer.Text = RemoteLogonSession.ComputerName;
                }
                else
                {
                    gridNoSessions.Visibility = Visibility.Collapsed;
                }
            }
            else
            {
                // If there was an error, display error overlay.
                gridError.Visibility = Visibility.Visible;
            }
        }


        private void bgThread_LogoffUser(object sender, DoWorkEventArgs e)
        {
            // Background thread to logoff a user.
            e.Result = RemoteLogonSession.LogoffUser(e.Argument as RemoteLogonSession);
        }


        private void bgThread_LogoffUserCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridOverlay.Visibility = Visibility.Collapsed;

            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Display DialogWindow containing the results of the action.
            mainWindow.IsEnabled = true;
            DialogWindow.DisplayDialog(mainWindow, e.Result as DialogResult, controlPosition);

            // Dialog acknowledged.  Refresh dataset.
            RefreshDataset();
        }


        private void bgThread_GetLogonHistory(object sender, DoWorkEventArgs e)
        {
            // Background thread to retrieve a history of logon sessions.
            e.Result = RemoteLogonHistory.GetLogonHistory();
        }


        private void bgThread_GetLogonHistoryCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridHistoryLoading.Visibility = Visibility.Collapsed;

            // If everything succeeded, bind the results to the DataGrid.  Otherwise, display an error.
            if (RemoteLogonHistory.Result != null && RemoteLogonHistory.Result.DidTaskSucceed)
            {
                // Bind the results to the DataGrid and sort the list.
                dgHistory.ItemsSource = e.Result as List<RemoteLogonHistory>;
                RemoteAdmin.SortDataGrid(dgHistory, 1, ListSortDirection.Descending);
                
                // If no logon history was found, display the No History Sessions overlay.
                if (dgHistory.Items.Count == 0)
                {
                    gridNoHistory.Visibility = Visibility.Visible;
                    tbHistoryNoSessions.Text = RemoteLogonSession.ComputerName;
                }
                else
                {
                    gridNoHistory.Visibility = Visibility.Collapsed;
                }
            }
            else
            {
                // If there was an error, display error overlay.
                if (RemoteLogonHistory.Result != null && !string.IsNullOrEmpty(RemoteLogonHistory.Result.MessageBody))
                    tbHistoryError.Text = RemoteLogonHistory.Result.MessageBody;
                else
                    tbHistoryError.Text = "Could not retrieve data.";

                gridHistoryError.Visibility = Visibility.Visible;
            }
        }


        private void MenuLogOff_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var windowsUser = (RemoteLogonSession)item.SelectedItem;


            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Setup a DialogResult which will build the confirmation dialog box.
            var dialog = new DialogResult();
            dialog.DialogTitle = "Logoff User";
            dialog.DialogBody = $"Are you sure you want to logoff {windowsUser.Username}?  Running applications will be forced to close and any unsaved data will be lost.";
            dialog.DialogIconPath = "/Resources/caution-48.png";
            dialog.ButtonIconPath = "/Resources/cancelRed-24.png";
            dialog.ButtonText = "Logoff";
            dialog.IsCancelVisible = true;

            // Display confirmation dialog window.
            if (DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition) == true)
            {
                // User confirmed they want to logoff the selected user.
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_LogoffUser);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_LogoffUserCompleted);

                // Launch background thread to logoff the selected user.
                tbOverlay.Text = "Logging off selected user...";
                gridOverlay.Visibility = Visibility.Visible;
                mainWindow.IsEnabled = false;
                bgWorker.RunWorkerAsync(windowsUser);
            }
        }

        private void MenuSendMessage_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var windowsUser = (RemoteLogonSession)item.SelectedItem;


            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Blur main window
            System.Windows.Media.Effects.BlurEffect objBlur = new System.Windows.Media.Effects.BlurEffect();
            objBlur.Radius = 4;
            mainWindow.Opacity = 0.85;
            mainWindow.Effect = objBlur;

            // Display dialog window.
            var sendMessageWindow = new Dialog.SendMessageWindow(windowsUser);
            sendMessageWindow.WindowStartupLocation = WindowStartupLocation.CenterOwner;
            sendMessageWindow.Owner = mainWindow;
            sendMessageWindow.Left = controlPosition.X;
            sendMessageWindow.Top = controlPosition.Y;
            sendMessageWindow.ShowDialog();

            // Dialog acknowledged.  Remove blur from window.
            mainWindow.Effect = null;
            mainWindow.Opacity = 1;
        }


        private void tabLogonHistory_Selected(object sender, RoutedEventArgs e)
        {
            // Elevate the process if targeting local computer and is not run as administrator.
            if (RemoteLogonSession.ComputerName == Environment.MachineName && !ElevationHelper.IsRunAsAdmin())
            {
                // Launch itself as administrator
                ProcessStartInfo proc = new ProcessStartInfo();
                proc.UseShellExecute = true;
                proc.WorkingDirectory = Environment.CurrentDirectory;
                proc.FileName = System.Reflection.Assembly.GetExecutingAssembly().Location;
                proc.Verb = "runas";
                proc.Arguments = "Elevate LogonHistory";

                try
                {
                    Process.Start(proc);
                }
                catch
                {
                    // The user refused the elevation.
                    // Do nothing and return directly ...
                    gridHistoryLoading.Visibility = Visibility.Collapsed;
                    gridNoHistory.Visibility = Visibility.Collapsed;
                    tbHistoryError.Text = "Access denied.";
                    gridHistoryError.Visibility = Visibility.Visible;
                    return;
                }

                Application.Current.Shutdown();
            }
            else
            {
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetLogonHistory);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetLogonHistoryCompleted);

                // Display overlay window indicating work is being performed.
                gridNoHistory.Visibility = Visibility.Collapsed;
                gridError.Visibility = Visibility.Collapsed;
                gridHistoryLoading.Visibility = Visibility.Visible;

                // Retrieve event log in a new background thread.
                bgWorker.RunWorkerAsync();
            }
        }


        private void MenuGetUserDetails_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var windowsUser = (RemoteLogonSession)item.SelectedItem;

            var wnd = new Dialog.UserDetailsWindow(windowsUser.Username);
            wnd.Show();
        }


        private void MenuGetHistoryUserDetails_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var windowsUser = (RemoteLogonHistory)item.SelectedItem;

            if (windowsUser.LogonName.Length > 0)
            {
                string logonName;
                int startIndex = windowsUser.LogonName.IndexOf(@"\", 0);
                logonName = windowsUser.LogonName.Substring(startIndex + 1);

                var wnd = new Dialog.UserDetailsWindow(logonName);
                wnd.Show();
            }
        }
    }
}
