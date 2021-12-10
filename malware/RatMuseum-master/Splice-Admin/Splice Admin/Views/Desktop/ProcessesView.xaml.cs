using Splice_Admin.Classes;
using Splice_Admin.Views.Desktop.Dialog;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for ProcessesUserControl.xaml
    /// </summary>
    public partial class ProcessesView : UserControl
    {
        ICollectionView _ProccessesCollection;

        public ProcessesView(string targetComputer)
        {
            InitializeComponent();

            RemoteProcess.ComputerName = targetComputer;
            RefreshProcessesList();
        }


        private void RefreshProcessesList()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve a list of running processes.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetProcesses);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetProcessesCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetProcesses(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteProcess.GetProcesses();
        }


        private void bgThread_GetProcessesCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridLoading.Visibility = Visibility.Collapsed;

            // If everything succeeded, bind the results to the DataGrid.  Otherwise, display an error.
            if (RemoteProcess.Result != null && RemoteProcess.Result.DidTaskSucceed)
            {
                // Bind the results to the DataGrid and sort the list.
                dgProcesses.ItemsSource = e.Result as List<RemoteProcess>;
                _ProccessesCollection = CollectionViewSource.GetDefaultView(e.Result as List<RemoteProcess>);
                _ProccessesCollection.Filter = ProcessFilter;
                RemoteAdmin.SortDataGrid(dgProcesses);
            }
            else
            {
                // If there was an error, display error overlay.
                gridError.Visibility = Visibility.Visible;
            }
        }


        private void bgThread_TerminateProcess(object sender, DoWorkEventArgs e)
        {
            // Background thread to terminate a specific process.
            e.Result = RemoteProcess.TerminateProcess(e.Argument as RemoteProcess);
        }


        private void bgThread_TerminateProcessCompleted(object sender, RunWorkerCompletedEventArgs e)
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
            RefreshProcessesList();
        }


        private void MenuTerminateProcess_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var process = (RemoteProcess)item.SelectedItem;


            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Setup a DialogResult which will build the confirmation dialog box.
            var dialog = new DialogResult();
            dialog.DialogTitle = "Terminate Process";
            dialog.DialogBody = $"Process termination could result in a loss of data.  Are you sure you want to terminate this process?{Environment.NewLine}{process.Name} [{process.ProcessId}]";
            dialog.DialogIconPath = "/Resources/caution-48.png";
            dialog.ButtonIconPath = "/Resources/cancelRed-24.png";
            dialog.ButtonText = "Terminate";
            dialog.IsCancelVisible = true;

            // Display confirmation dialog window.
            if (DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition) == true)
            {
                // User confirmed they want to terminate the selected process.
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_TerminateProcess);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_TerminateProcessCompleted);

                // Launch background thread to terminate the selected process.
                tbOverlay.Text = "Terminating process...";
                gridOverlay.Visibility = Visibility.Visible;
                mainWindow.IsEnabled = false;
                bgWorker.RunWorkerAsync(process);
            }
        }

        private void txtFilter_KeyUp(object sender, System.Windows.Input.KeyEventArgs e)
        {
            _ProccessesCollection.Refresh();
        }

        private bool ProcessFilter(object item)
        {
            var process = item as RemoteProcess;
            var filterText = txtFilter.Text.ToUpper();
            if (!string.IsNullOrEmpty(process.Name) && process.Name.ToUpper().Contains(filterText))
                return true;
            else if (!string.IsNullOrEmpty(process.Owner) && process.Owner.ToUpper().Contains(filterText))
                return true;
            else if (!string.IsNullOrEmpty(process.ExecutablePath) && process.ExecutablePath.ToUpper().Contains(filterText))
                return true;
            else if (process.ProcessId.ToString().Contains(filterText))
                return true;
            else
                return false;
        }

        private void filterClear_Click(object sender, RoutedEventArgs e)
        {
            txtFilter.Clear();
            _ProccessesCollection.Refresh();
        }
    }
}
