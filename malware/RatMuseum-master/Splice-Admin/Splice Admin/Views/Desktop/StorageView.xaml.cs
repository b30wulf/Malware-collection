using Splice_Admin.Classes;
using System.Collections.Generic;
using System.ComponentModel;
using System.Windows;
using System.Windows.Controls;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for StorageUserControl.xaml
    /// </summary>
    public partial class StorageView : UserControl
    {
        public StorageView(string targetComputer)
        {
            InitializeComponent();

            RemoteStorage.ComputerName = targetComputer;
            RefreshStorageList();
        }


        private void RefreshStorageList()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve a list of storage devices and their capacities.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetDrives);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetDrivesCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetDrives(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteStorage.GetStorageDevices();
        }


        private void bgThread_GetDrivesCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Hide loading animations.
            gridLoading.Visibility = Visibility.Collapsed;
            
            if (RemoteStorage.Result != null && RemoteStorage.Result.DidTaskSucceed)
            {
                // Success.  Display the results.
                lvDrives.ItemsSource = e.Result as List<RemoteStorage>;
            }
            else
            {
                // Error.  Display error overlay.
                gridError.Visibility = Visibility.Visible;
            }
        }
    }
}
