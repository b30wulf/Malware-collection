using Splice_Admin.Classes;
using System.Collections.Generic;
using System.ComponentModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for ApplicationsView.xaml
    /// </summary>
    public partial class ApplicationsView : UserControl
    {
        ICollectionView _ApplicationsCollection;

        public ApplicationsView(string targetComputer)
        {
            InitializeComponent();

            RemoteApplication.ComputerName = targetComputer;
            RefreshInstalledApplicationsList();
        }


        private void RefreshInstalledApplicationsList()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve a list of installed applications.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetApplications);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetApplicationsCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetApplications(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteApplication.GetInstalledApplications();
        }


        private void bgThread_GetApplicationsCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridLoading.Visibility = Visibility.Collapsed;

            // If everything succeeded, bind the results to the DataGrid.  Otherwise, display an error.
            if (RemoteApplication.Result != null && RemoteApplication.Result.DidTaskSucceed)
            {
                // Bind the results to the DataGrid and sort the list.
                dgApps.ItemsSource = e.Result as List<RemoteApplication>;
                _ApplicationsCollection = CollectionViewSource.GetDefaultView(e.Result as List<RemoteApplication>);
                _ApplicationsCollection.Filter = ApplicationFilter;
                RemoteAdmin.SortDataGrid(dgApps);
            }
            else
            {
                // If there was an error, display error overlay.
                gridError.Visibility = Visibility.Visible;
            }
        }


        private void txtFilter_KeyUp(object sender, System.Windows.Input.KeyEventArgs e)
        {
            _ApplicationsCollection.Refresh();
        }

        private bool ApplicationFilter(object item)
        {
            var application = item as RemoteApplication;
            var filterText = txtFilter.Text.ToUpper();
            if (!string.IsNullOrEmpty(application.DisplayName) && application.DisplayName.ToUpper().Contains(filterText))
                return true;
            else if (!string.IsNullOrEmpty(application.Publisher) && application.Publisher.ToUpper().Contains(filterText))
                return true;
            else if (!string.IsNullOrEmpty(application.Version) && application.Version.ToUpper().Contains(filterText))
                return true;
            else
                return false;
        }

        private void filterClear_Click(object sender, RoutedEventArgs e)
        {
            txtFilter.Clear();
            _ApplicationsCollection.Refresh();
        }
    }
}
