using Splice_Admin.Classes;
using Splice_Admin.Views.Desktop.Dialog;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Diagnostics;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for UpdatesUserControl.xaml
    /// </summary>
    public partial class UpdatesView : UserControl
    {
        public UpdatesView(string targetComputer)
        {
            InitializeComponent();

            RemoteUpdate.ComputerName = targetComputer;
            RefreshUpdatesList();
        }


        private void RefreshUpdatesList()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve a list of installed Microsoft updates.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetUpdates);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetUpdatesCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetUpdates(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteUpdate.GetInstalledUpdates();
        }


        private void bgThread_GetUpdatesCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            if (RemoteUpdate.Result != null && RemoteUpdate.Result.DidTaskSucceed)
            {
                // Retrieve and display the current Microsoft update configuration from the registry.
                DisplayUpdateConfiguration(RemoteUpdateConfiguration.GetUpdateConfiguration());
                if (RemoteUpdate.GetRebootState())
                {
                    RebootRequired.Text = "* This computer has a pending required reboot.";
                    RebootRequired.Visibility = Visibility.Visible;
                }
                else
                {
                    RebootRequired.Text = string.Empty;
                    RebootRequired.Visibility = Visibility.Collapsed;
                }

                // Bind the returned list of installed updates to the DataGrid.
                dgUpdates.ItemsSource = e.Result as List<RemoteUpdate>;
                RemoteAdmin.SortDataGrid(dgUpdates);
                gridLoading.Visibility = Visibility.Collapsed;
            }
            else
            {
                // Something went wrong while retrieving updates.  Display an error overlay.
                gridLoading.Visibility = Visibility.Collapsed;
                gridError.Visibility = Visibility.Visible;
            }
        }


        private void bgThread_UninstallUpdate(object sender, DoWorkEventArgs e)
        {
            // Uninstall a Microsoft update.
            e.Result = RemoteUpdate.UninstallUpdate(e.Argument as RemoteUpdate);
        }


        private void bgThread_UninstallUpdateCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridOverlay.Visibility = Visibility.Collapsed;

            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Display DialogWindow using the MainWindow reference, the UninstallUpdate() results, and the control coordinates.
            mainWindow.IsEnabled = true;
            DialogWindow.DisplayDialog(mainWindow, e.Result as DialogResult, controlPosition);
        }


        private void DisplayUpdateConfiguration(RemoteUpdateConfiguration updateConfiguration)
        {
            tbAutoUpdates.Text = updateConfiguration.IsAutomaticUpdatesEnabled ? "Automatic Updates" : "Manual Updates";
            if (updateConfiguration.AuOptionCode <= 0)
                tbAutoUpdates.Text = string.Empty;

            if (updateConfiguration.LastUpdateCheck.Date == DateTime.Today)
                tbLastUpdateCheck.Text = "Today at ";
            else if (DateTime.Today - updateConfiguration.LastUpdateCheck == TimeSpan.FromDays(1))
                tbLastUpdateCheck.Text = "Yesterday at ";
            else
                tbLastUpdateCheck.Text = $"{updateConfiguration.LastUpdateCheck.ToShortDateString()}  at ";
            tbLastUpdateCheck.Text += updateConfiguration.LastUpdateCheck.ToShortTimeString();
            if (updateConfiguration.LastUpdateCheck == DateTime.MinValue)
                tbLastUpdateCheck.Text = string.Empty;

            if (updateConfiguration.LastUpdateInstall.Date == DateTime.Today)
                tbLastUpdateInstall.Text = "Today at ";
            else if (DateTime.Today - updateConfiguration.LastUpdateInstall == TimeSpan.FromDays(1))
                tbLastUpdateInstall.Text = "Yesterday at ";
            else
                tbLastUpdateInstall.Text = $"{updateConfiguration.LastUpdateInstall.ToShortDateString()} at ";
            tbLastUpdateInstall.Text += updateConfiguration.LastUpdateInstall.ToShortTimeString();
            if (updateConfiguration.LastUpdateInstall == DateTime.MinValue)
                tbLastUpdateInstall.Text = string.Empty;
        }


        private void DG_Hyperlink_Click(object sender, RoutedEventArgs e)
        {
            Hyperlink link = (Hyperlink)e.OriginalSource;
            Process.Start(link.NavigateUri.AbsoluteUri);
        }


        private void MenuUninstallUpdate_Click(object sender, RoutedEventArgs e)
        {
            // Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            // Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            // Find the PlacementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            // Get the underlying item, cast as my object type.
            var update = (RemoteUpdate)item.SelectedItem;

            // Determine if the selected update can be uninstalled and if automatic updates are enabled.
            bool isUpdateValid = RemoteUpdate.ExtractKbNumber(update.UpdateId).Length > 0;
            bool isAutoUpdateEnabled = (tbAutoUpdates.Text == "Automatic Updates") ? true : false;

            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Setup a DialogResult which will build the confirmation dialog box.
            var dialog = new DialogResult();
            dialog.DialogTitle = "Uninstall Update";
            dialog.DialogBody = $"Are you sure you want to uninstall this update?{Environment.NewLine}{update.UpdateId}";
            dialog.DialogIconPath = "/Resources/caution-48.png";
            dialog.ButtonIconPath = "/Resources/cancelRed-24.png";
            dialog.ButtonText = "Uninstall";
            dialog.IsCancelVisible = true;

            if (isAutoUpdateEnabled)
            {
                dialog.DialogBody = "Automatic updates are enabled.  The target computer might automatically re-install the update at a later time." +
                    $"{Environment.NewLine}{Environment.NewLine}Do you still want to uninstall this update?{Environment.NewLine}{update.UpdateId}";
            }

            if (!isUpdateValid)
            {
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "The selected update cannot be uninstalled.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }


            // Display confirmation dialog window.
            if (DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition) == true && isUpdateValid)
            {
                // User confirmed they want to terminate the selected process.
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_UninstallUpdate);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_UninstallUpdateCompleted);

                // Launch background thread to terminate the selected process.
                tbOverlay.Text = "Uninstalling selected update...";
                gridOverlay.Visibility = Visibility.Visible;
                mainWindow.IsEnabled = false;
                bgWorker.RunWorkerAsync(update);
            }
        }
    }
}
