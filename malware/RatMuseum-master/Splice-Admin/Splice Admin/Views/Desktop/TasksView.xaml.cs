using Microsoft.Win32;
using Splice_Admin.Classes;
using Splice_Admin.Views.Desktop.Dialog;
using System;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.InteropServices;
using System.ServiceProcess;
using System.Threading;
using System.Windows;
using System.Windows.Controls;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for TasksView.xaml
    /// </summary>
    public partial class TasksView : UserControl
    {
        public TasksView()
        {
            InitializeComponent();
        }


        private void bgThread_RebootComputer(object sender, DoWorkEventArgs e)
        {
            // Background thread to reboot the target computer.
            e.Result = RemoteTask.TaskRebootComputer(e.Argument as string);
        }


        private void bgThread_ShutdownComputer(object sender, DoWorkEventArgs e)
        {
            // Background thread to shutdown the target computer.
            e.Result = RemoteTask.TaskShutdownComputer(e.Argument as string);
        }


        private void bgThread_GpUpdate(object sender, DoWorkEventArgs e)
        {
            // Background thread to force a group policy update on the target computer.
            e.Result = RemoteTask.TaskGpupdate(e.Argument as string);
        }


        private void bgThread_TaskCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Background thread completed.
            // Get a reference to MainWindow along with the coordinates of the current user control.
            var mainWindow = Window.GetWindow(this);
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            this.IsEnabled = true;
            // Display DialogWindow using the MainWindow reference, the background thread results, and the control coordinates.
            gridOverlay.Visibility = Visibility.Collapsed;
            DialogWindow.DisplayDialog(mainWindow, e.Result as DialogResult, controlPosition);
        }


        private void btnReboot_Click(object sender, RoutedEventArgs e)
        {
            // Get a reference to MainWindow along with the coordinates of the current user control.
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));
            var mainWindow = Window.GetWindow(this);

            // Setup a DialogResult which will build the confirmation dialog box.
            var dialog = new DialogResult();
            dialog.DialogTitle = "Reboot Computer";
            dialog.DialogBody = $"Are you sure you want to reboot {GlobalVar.TargetComputerName}?  Applications will be forced to close and any unsaved data will be lost.";
            dialog.DialogIconPath = "/Resources/caution-48.png";
            dialog.ButtonIconPath = "/Resources/cancelRed-24.png";
            dialog.ButtonText = "Reboot";
            dialog.IsCancelVisible = true;

            // Display confirmation dialog window.
            if (DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition) == true)
            {
                // User confirmed they want to terminate the selected process.
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_RebootComputer);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_TaskCompleted);

                // Launch background thread to terminate the selected process.
                tbOverlay.Text = $"Sending reboot command to {GlobalVar.TargetComputerName}...";
                gridOverlay.Visibility = Visibility.Visible;
                this.IsEnabled = false;
                bgWorker.RunWorkerAsync(GlobalVar.TargetComputerName);
            }
        }


        private void btnShutdown_Click(object sender, RoutedEventArgs e)
        {
            // Get a reference to MainWindow along with the coordinates of the current user control.
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));
            var mainWindow = Window.GetWindow(this);

            // Setup a DialogResult which will build the confirmation dialog box.
            var dialog = new DialogResult();
            dialog.DialogTitle = "Shutdown Computer";
            dialog.DialogBody = $"Are you sure you want to shutdown {GlobalVar.TargetComputerName}?  Applications will be forced to close and any unsaved data will be lost.";
            dialog.DialogIconPath = "/Resources/caution-48.png";
            dialog.ButtonIconPath = "/Resources/cancelRed-24.png";
            dialog.ButtonText = "Shutdown";
            dialog.IsCancelVisible = true;

            // Display confirmation dialog window.
            if (DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition) == true)
            {
                // User confirmed they want to terminate the selected process.
                // Setup a background thread.
                var bgWorker = new BackgroundWorker();
                bgWorker.DoWork += new DoWorkEventHandler(bgThread_ShutdownComputer);
                bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_TaskCompleted);

                // Launch background thread to terminate the selected process.
                tbOverlay.Text = $"Sending shutdown command to {GlobalVar.TargetComputerName}...";
                gridOverlay.Visibility = Visibility.Visible;
                this.IsEnabled = false;
                bgWorker.RunWorkerAsync(GlobalVar.TargetComputerName);
            }
        }


        private void btnGpupdate_Click(object sender, RoutedEventArgs e)
        {
            // User confirmed they want to terminate the selected process.
            // Setup a background thread.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GpUpdate);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_TaskCompleted);

            // Launch background thread to terminate the selected process.
            tbOverlay.Text = $"Refreshing Group Policies on {GlobalVar.TargetComputerName}...";
            gridOverlay.Visibility = Visibility.Visible;
            this.IsEnabled = false;
            bgWorker.RunWorkerAsync(GlobalVar.TargetComputerName);
        }


        private void btnRemoteDesktop_Click(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = false;
            string processPath =
                Environment.SystemDirectory + @"\mstsc.exe";
            string processArguments =
                "/v:" + GlobalVar.TargetComputerName;

            try
            {
                Process.Start(processPath, processArguments);
                didTaskSucceed = true;
            }
            catch
            { }

            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Microsoft Remote Desktop client.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }


        private void btnRemoteAssistance_Click(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = false;
            string processPath =
                Environment.SystemDirectory + @"\msra.exe";
            string processArguments =
                $"/offerRA {GlobalVar.TargetComputerName}";

            try
            {
                Process.Start(processPath, processArguments);
                didTaskSucceed = true;
            }
            catch
            { }

            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Microsoft Remote Desktop client.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }


        [DllImport("User32.dll")]
        static extern int SetForegroundWindow(IntPtr point);

        private void btnWindowsRegistry_Click(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = true;
            bool isServiceRunning = true;
            bool isLocal = GlobalVar.TargetComputerName.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
            const string serviceName = "RemoteRegistry";
            const string keyName = @"Software\Microsoft\Windows\CurrentVersion\Applets\Regedit";
            string processPath = $@"{Environment.GetFolderPath(Environment.SpecialFolder.Windows)}\regedit.exe";

            try
            {
                using (RegistryKey key = Registry.CurrentUser.OpenSubKey(keyName, true))
                {
                    if (key != null)
                        key.DeleteValue("LastKey");
                }
            }
            catch
            { }

            // If the target computer is remote, then start the Remote Registry service.
            using (var sc = new ServiceController(serviceName, GlobalVar.TargetComputerName))
            {
                try
                {
                    if (!isLocal && sc.Status != ServiceControllerStatus.Running)
                    {
                        // Get a reference to MainWindow along with the coordinates of the current user control.
                        Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                        var mainWindow = Window.GetWindow(this);

                        // Setup a DialogResult which will build the confirmation dialog box.
                        var dialog = new DialogResult();
                        dialog.DialogTitle = "Remote Registry Service";
                        dialog.DialogBody = $"The remote registry service is not running on: {GlobalVar.TargetComputerName}.  Would you like to start the service?  Once started, it will continue to run until you manually stop it.";
                        dialog.DialogIconPath = "/Resources/caution-48.png";
                        dialog.ButtonIconPath = "/Resources/checkMark-24.png";
                        dialog.ButtonText = "Start Registry";
                        dialog.IsCancelVisible = true;

                        // Display confirmation dialog window.
                        if (DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition) == true)
                        {
                            isServiceRunning = false;
                            sc.Start();
                        }
                        else
                            return;
                    }
                }
                catch
                { }

                try
                {
                    Process p = Process.Start(processPath);
                    if (p != null && !isLocal)
                    {
                        SetForegroundWindow(p.MainWindowHandle);
                        Thread.Sleep(1250);
                        SetForegroundWindow(p.MainWindowHandle);
                        System.Windows.Forms.SendKeys.SendWait("{HOME}");
                        Thread.Sleep(100);
                        SetForegroundWindow(p.MainWindowHandle);
                        System.Windows.Forms.SendKeys.SendWait("{LEFT}");
                        Thread.Sleep(100);
                        SetForegroundWindow(p.MainWindowHandle);
                        System.Windows.Forms.SendKeys.SendWait("%F");
                        Thread.Sleep(250);
                        SetForegroundWindow(p.MainWindowHandle);
                        System.Windows.Forms.SendKeys.SendWait("C");
                        Thread.Sleep(750);
                        System.Windows.Forms.SendKeys.SendWait(GlobalVar.TargetComputerName);
                        Thread.Sleep(250);
                        System.Windows.Forms.SendKeys.SendWait("{ENTER}");
                    }
                }

                catch
                { }

                // Cleanup.
                if (!isLocal && !isServiceRunning)
                {
                    try
                    {
                        //if (sc != null)
                        //    sc.Stop();
                    }

                    catch (Exception)
                    {
                    }
                }
            }


            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Microsoft Remote Desktop client.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }


        private void btnEventLogViewer(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = false;
            string processPath = $@"{Environment.SystemDirectory}\eventvwr.msc";
            string processArguments = $"/computer:{GlobalVar.TargetComputerName}";

            try
            {
                Process.Start(processPath, processArguments);
                didTaskSucceed = true;
            }
            catch
            { }

            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Windows Event Viewer.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }


        private void btnComputerManagement_Click(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = false;
            string processPath = $@"{Environment.SystemDirectory}\compmgmt.msc";
            string processArguments = $"/computer:{GlobalVar.TargetComputerName}";

            try
            {
                Process.Start(processPath, processArguments);
                didTaskSucceed = true;
            }
            catch
            { }

            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Computer Management console.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }


        private void btnServicesConsole_Click(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = false;
            string processPath = $@"{Environment.SystemDirectory}\services.msc";
            string processArguments = $"/computer:{GlobalVar.TargetComputerName}";

            try
            {
                Process.Start(processPath, processArguments);
                didTaskSucceed = true;
            }
            catch
            { }

            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Computer Management console.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }


        private void btnTaskScheduler_Click(object sender, RoutedEventArgs e)
        {
            bool didTaskSucceed = false;
            string processPath = $@"{Environment.SystemDirectory}\taskschd.msc";
            string processArguments = $"/computer:{GlobalVar.TargetComputerName}";

            try
            {
                Process.Start(processPath, processArguments);
                didTaskSucceed = true;
            }
            catch
            { }

            if (!didTaskSucceed)
            {
                // Get a reference to MainWindow along with the coordinates of the current user control.
                Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                var mainWindow = Window.GetWindow(this);

                // Setup a DialogResult which will build the confirmation dialog box.
                var dialog = new DialogResult();
                dialog.DialogTitle = "Error";
                dialog.DialogBody = "Failed to launch the Windows Task Scheduler.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;

                DialogWindow.DisplayDialog(mainWindow, dialog, controlPosition);
            }
        }

        private void btnExecuteRemoteCommand_Click(object sender, RoutedEventArgs e)
        {
            // Get a reference to MainWindow along with the coordinates of the current user control.
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));
            var mainWindow = Window.GetWindow(this);

            // Blur main window
            System.Windows.Media.Effects.BlurEffect objBlur = new System.Windows.Media.Effects.BlurEffect();
            objBlur.Radius = 4;
            this.Opacity = 0.85;
            this.Effect = objBlur;
            
            // Display dialog window.
            var remoteCommandWindow = new Dialog.RemoteCommandWindow(GlobalVar.TargetComputerName);
            remoteCommandWindow.WindowStartupLocation = WindowStartupLocation.CenterOwner;
            remoteCommandWindow.Owner = mainWindow;
            remoteCommandWindow.Left = controlPosition.X;
            remoteCommandWindow.Top = controlPosition.Y;
            remoteCommandWindow.ShowDialog();

            // Dialog acknowledged.  Remove blur from window.
            this.Effect = null;
            this.Opacity = 1;
        }
    }
}
