using Splice_Admin.Classes;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Management;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Input;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for SystemInfoUserControl.xaml
    /// </summary>
    public partial class SystemInfoView : UserControl
    {
        public SystemInfoView(string targetComputer)
        {
            InitializeComponent();

            RemoteSystemInfo.TargetComputer = targetComputer;
            RefreshSystemInformation();
        }


        private void RefreshSystemInformation()
        {
            // Display loading animations.
            gridError.Visibility = Visibility.Collapsed;
            gridLoading.Visibility = Visibility.Visible;

            // Setup a background thread to retrieve general system information.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetSystemInfo);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetSystemInfoCompleted);
            bgWorker.RunWorkerAsync();
        }


        private void bgThread_GetSystemInfo(object sender, DoWorkEventArgs e)
        {
            // Retrieve general system information.
            e.Result = RemoteSystemInfo.GetSystemInfo();
        }


        private void bgThread_GetSystemInfoCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            var systemInfo = e.Result as RemoteSystemInfo;

            if (systemInfo.Result != null && systemInfo.Result.DidTaskSucceed)
            {
                SetSystemInfo(e.Result as RemoteSystemInfo);
                gridLoading.Visibility = Visibility.Collapsed;
            }
            else
            {
                gridLoading.Visibility = Visibility.Collapsed;
                gridError.Visibility = Visibility.Visible;
            }
        }


        private void bgThread_GetCpuRamDetails(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteSystemSpecs.GetCpuRamDetails();
        }


        private void bgThread_GetCpuRamDetailsCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            var systemSpecs = e.Result as RemoteSystemSpecs;

            if (systemSpecs.Result != null && systemSpecs.Result.DidTaskSucceed)
            {
                tbMemTotalCapacity.Text = systemSpecs.MemoryTotalString;
                tbMemSpeed.Text = systemSpecs.MemorySpeedString;
                tbMemSlotsUsed.Text = systemSpecs.MemorySlotsInUse + " of " + systemSpecs.MemorySlotsTotal;
                tbMemHardwareReserved.Text = systemSpecs.MemoryBiosReservedString;
                tbMemInUse.Text = systemSpecs.MemoryInUseString;
                tbMemAvailable.Text = systemSpecs.MemoryAvailableString;
                tbMemCommitted.Text = systemSpecs.MemoryCommittedString;
                tbMemCommitLimit.Text = systemSpecs.MemoryCommitLimitString;
                tbMemPagedPool.Text = systemSpecs.MemoryPagedPoolString;
                tbMemNonPagedPool.Text = systemSpecs.MemoryNonPagedPoolString;

                tbCpuHandles.Text = systemSpecs.CpuHandleCount.ToString();
                tbCpuThreads.Text = systemSpecs.CpuThreadCount.ToString();
                tbCpuProcesses.Text = systemSpecs.CpuNumberOfProcesses.ToString();
                tbCpuL1Cache.Text = systemSpecs.L1CacheSize;
                tbCpuL2Cache.Text = systemSpecs.L2CacheSize;
                tbCpuL3Cache.Text = systemSpecs.L3CacheSize;
                tbCpuLogicalProcessors.Text = systemSpecs.CpuNumberOfLogicalProcessors.ToString();
                tbCpuCores.Text = systemSpecs.CpuNumberOfCores.ToString();
                tbCpuSockets.Text = systemSpecs.CpuNumberOfSockets.ToString();
                tbCpuSpeed.Text = systemSpecs.CpuClockSpeed;
                tbCpuName.Text = systemSpecs.CpuName;

                gridOverlay.Visibility = Visibility.Collapsed;
            }
            else
                gridOverlay.Visibility = Visibility.Collapsed;
        }


        private void bgThread_GetUsbDevices(object sender, DoWorkEventArgs e)
        {
            // Retrieve a list of connected USB devices.
            e.Result = RemoteUsbDevice.GetUsbDevices();
        }


        private void bgThread_GetUsbDevicesCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            var usbDevices = e.Result as List<RemoteUsbDevice>;

            dgUsbDevices.ItemsSource = usbDevices;
            CollectionView view = (CollectionView)CollectionViewSource.GetDefaultView(dgUsbDevices.ItemsSource);
            view.SortDescriptions.Add(new SortDescription("Description", ListSortDirection.Ascending));
            gridOverlay.Visibility = Visibility.Collapsed;
        }

        
        private void bgThread_GetOdbcDsn(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteOdbc.GetOdbcDsn();
        }


        private void bgThread_GetOdbcDsnCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            var odbcEntries = e.Result as List<RemoteOdbc>;

            cboOdbcDsn.ItemsSource = odbcEntries.OrderBy(i => i.DataSourceName);
            if (cboOdbcDsn.Items.Count > 0)
            {
                cboOdbcDsn.SelectedIndex = 0;
                borderNoDsn.Visibility = Visibility.Collapsed;
            }
            else
                borderNoDsn.Visibility = Visibility.Visible;

            gridOverlay.Visibility = Visibility.Collapsed;
        }


        private void SetSystemInfo(RemoteSystemInfo systemInfo)
        {
            ComputerName.Text = systemInfo.ComputerName;
            OperatingSystem.Text = systemInfo.WindowsFullVersion;
            ComputerDescription.Text = systemInfo.ComputerDescription;
            Uptime.Text = systemInfo.Uptime;
            Type.Text = systemInfo.ComputerType.ToString();
            Manufacturer.Text = systemInfo.ComputerManufacturer;
            Model.Text = systemInfo.ComputerModel;
            SerialNumber.Text = systemInfo.ComputerSerialNumber;
            Processor.Text = systemInfo.Processor;
            Memory.Text = systemInfo.Memory;
            if (systemInfo.IsRebootRequired)
            {
                RebootRequired.Text = "* This computer has a pending required reboot.";
                RebootRequired.Visibility = Visibility.Visible;
            }
            else
            {
                RebootRequired.Text = string.Empty;
                RebootRequired.Visibility = Visibility.Collapsed;
            }
            //MessageBox.Show(systemInfo.IpAddresses);
        }
        

        private void tabCpuRam_Selected(object sender, RoutedEventArgs e)
        {
            // Setup a background thread.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetCpuRamDetails);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetCpuRamDetailsCompleted);

            // Display overlay window indicating work is being performed.
            gridOverlay.Visibility = Visibility.Visible;

            // Retrieve event log in a new background thread.
            bgWorker.RunWorkerAsync();
        }


        private void tabUsbDevices_Selected(object sender, RoutedEventArgs e)
        {
            // Setup a background thread.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetUsbDevices);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetUsbDevicesCompleted);

            // Display overlay window indicating work is being performed.
            gridOverlay.Visibility = Visibility.Visible;

            // Retrieve event log in a new background thread.
            bgWorker.RunWorkerAsync();
        }


        private void tabDsn_Selected(object sender, RoutedEventArgs e)
        {
            // Setup a background thread.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetOdbcDsn);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetOdbcDsnCompleted);

            // Display overlay window indicating work is being performed.
            gridOverlay.Visibility = Visibility.Visible;

            // Retrieve event log in a new background thread.
            bgWorker.RunWorkerAsync();
        }
        

        private void UserControl_MouseDown(object sender, MouseButtonEventArgs e)
        {
            SystemInfoGrid.Focus();
        }


        private void cboOdbcDsn_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            var entry = (RemoteOdbc)cboOdbcDsn.SelectedItem;
            if (entry == null)
                return;

            dgOdbcDsnSelected.ItemsSource = entry.Values;
            RemoteAdmin.SortDataGrid(dgOdbcDsnSelected);
        }


        private void MenuItem_Click(object sender, RoutedEventArgs e)
        {
            //Get the clicked MenuItem
            var menuItem = (MenuItem)sender;

            //Get the ContextMenu to which the menuItem belongs
            var contextMenu = (ContextMenu)menuItem.Parent;

            //Find the placementTarget
            var item = (DataGrid)contextMenu.PlacementTarget;

            //Get the underlying item, that you cast to your object that is bound
            //to the DataGrid (and has subject and state as property)
            var odbcDsn = (RemoteOdbc)cboOdbcDsn.SelectedItem;
            var odbcValue = (RemoteOdbcValue)item.SelectedItem;

            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Blur main window
            System.Windows.Media.Effects.BlurEffect objBlur = new System.Windows.Media.Effects.BlurEffect();
            objBlur.Radius = 4;
            mainWindow.Opacity = 0.85;
            mainWindow.Effect = objBlur;

            // Display dialog window.
            var odbcEditWindow = new Dialog.OdbcEditWindow(odbcDsn, odbcValue);
            odbcEditWindow.WindowStartupLocation = WindowStartupLocation.CenterOwner;
            odbcEditWindow.Owner = mainWindow;
            odbcEditWindow.Left = controlPosition.X;
            odbcEditWindow.Top = controlPosition.Y;
            var response = odbcEditWindow.ShowDialog();

            // Dialog acknowledged.  Remove blur from window.
            mainWindow.Effect = null;
            mainWindow.Opacity = 1;

            if (response == true)
            {
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetOdbcDsn);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetOdbcDsnCompleted);

                // Display overlay window indicating work is being performed.
                gridOverlay.Visibility = Visibility.Visible;

                // Retrieve event log in a new background thread.
                bgWorker.RunWorkerAsync();
            }
        }
    }
}
