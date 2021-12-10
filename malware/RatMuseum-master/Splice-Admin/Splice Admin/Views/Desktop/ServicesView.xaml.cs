using Splice_Admin.Classes;
using Splice_Admin.Views.Desktop.Dialog;
using System.Collections.Generic;
using System.ComponentModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for ServicesUserControl.xaml
    /// </summary>
    public partial class ServicesView : UserControl
    {
        ICollectionView _ServicesCollection;

        public ServicesView(string targetComputer)
        {
            InitializeComponent();

            RemoteService.ComputerName = targetComputer;
            RefreshServicesList();
        }


        private void RefreshServicesList()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve a list of installed Windows services.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetServices);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetServicesCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetServices(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteService.GetServices();
        }


        private void bgThread_GetServicesCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridLoading.Visibility = Visibility.Collapsed;

            // If everything succeeded, bind the results to the DataGrid.  Otherwise, display an error.
            if (RemoteService.Result != null && RemoteService.Result.DidTaskSucceed)
            {
                // Success.  Display the results.
                dgServices.ItemsSource = e.Result as List<RemoteService>;
                _ServicesCollection = CollectionViewSource.GetDefaultView(e.Result as List<RemoteService>);
                _ServicesCollection.Filter = ServiceFilter;
                RemoteAdmin.SortDataGrid(dgServices);
            }
            else
            {
                // Error.  Display error overlay.
                gridError.Visibility = Visibility.Visible;
            }
        }


        private void bgThread_StartService(object sender, DoWorkEventArgs e)
        {
            // Background thread to start a Windows service.
            e.Result = RemoteService.StartService(e.Argument as RemoteService);
        }


        private void bgThread_StopService(object sender, DoWorkEventArgs e)
        {
            // Background thread to stop a Windows service.
            e.Result = RemoteService.StopService(e.Argument as RemoteService);
        }


        private void bgThread_StartStopServiceCompleted(object sender, RunWorkerCompletedEventArgs e)
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
            RefreshServicesList();
        }


        private void MenuStartService_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var service = (RemoteService)item.SelectedItem;

            // Setup a background thread to start the selected service.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_StartService);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_StartStopServiceCompleted);

            // Launch background thread to start the selected service
            tbOverlay.Text = "Starting service...";
            gridOverlay.Visibility = Visibility.Visible;
            var mainWindow = Window.GetWindow(this);
            mainWindow.IsEnabled = false;
            bgWorker.RunWorkerAsync(service);
        }


        private void MenuStopService_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var service = (RemoteService)item.SelectedItem;

            // Setup a background thread to stop the selected service.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_StopService);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_StartStopServiceCompleted);

            // Launch background thread to stop the selected service
            tbOverlay.Text = "Stopping service...";
            gridOverlay.Visibility = Visibility.Visible;
            var mainWindow = Window.GetWindow(this);
            mainWindow.IsEnabled = false;
            bgWorker.RunWorkerAsync(service);
        }


        private void txtFilter_KeyUp(object sender, System.Windows.Input.KeyEventArgs e)
        {
            _ServicesCollection.Refresh();
        }

        private bool ServiceFilter(object item)
        {
            var service = item as RemoteService;
            var filterText = txtFilter.Text.ToUpper();
            if (!string.IsNullOrEmpty(service.DisplayName) && service.DisplayName.ToUpper().Contains(filterText))
                return true;
            else if (!string.IsNullOrEmpty(service.StartupType) && service.StartupType.ToUpper().Contains(filterText))
                return true;
            else if (!string.IsNullOrEmpty(service.LogOnAs) && service.LogOnAs.ToUpper().Contains(filterText))
                return true;
            else
                return false;
        }

        private void filterClear_Click(object sender, RoutedEventArgs e)
        {
            txtFilter.Clear();
            _ServicesCollection.Refresh();
        }
    }
}
