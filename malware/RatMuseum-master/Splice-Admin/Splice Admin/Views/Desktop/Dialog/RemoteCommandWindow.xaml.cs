using Microsoft.Win32;
using Splice_Admin.Classes;
using System;
using System.ComponentModel;
using System.Management;
using System.Windows;
using System.Windows.Input;

namespace Splice_Admin.Views.Desktop.Dialog
{
    /// <summary>
    /// Interaction logic for RemoteCommandWindow.xaml
    /// </summary>
    public partial class RemoteCommandWindow : Window
    {
        private string _TargetComputer;

        public RemoteCommandWindow(string targetComputer)
        {
            InitializeComponent();

            // Set initial focus to text box.
            Loaded += (sender, e) =>
                MoveFocus(new TraversalRequest(FocusNavigationDirection.Next));

            _TargetComputer = targetComputer;
            tbPath.Text = $"Enter the full command line, including any optional arguments, that you would like to run on {targetComputer}:";
        }


        private void bgThread_ExecuteRemoteCommand(object sender, DoWorkEventArgs e)
        {
            // Background thread to shutdown the target computer.
            // The result contains a DialogResult.
            e.Result = ExecuteRemoteCommand(e.Argument as RemoteCommand);
        }


        private void bgThread_ExecuteRemoteCommandCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            // Background thread completed.
            // Get a reference to MainWindow along with the coordinates of the current user control.
            Point controlPosition = this.PointToScreen(new Point(0d, 0d));

            // Display DialogWindow using the MainWindow reference, the background thread results, and the control coordinates.
            this.IsEnabled = true;
            gridOverlay.Visibility = Visibility.Collapsed;
            this.Hide();
            DialogWindow.DisplayDialog(this, e.Result as DialogResult, controlPosition);
            this.Close();
        }


        private void btnBrowseLocalFilesystem_Click(object sender, RoutedEventArgs e)
        {
            var dialog = new OpenFileDialog();
            dialog.DefaultExt = ".exe";
            var result = dialog.ShowDialog();

            if (result == true)
            {
                txtPath.Text = dialog.FileName;
            }
        }



        private DialogResult ExecuteRemoteCommand(RemoteCommand remoteCommand)
        {
            // TaskGpupdate() uses WMI to execute the GPUpdate command.
            // It returns a DialogResult which will be used to display the results.
            var dialog = new DialogResult();
            bool didTaskSucceed = false;
            string commandLine = remoteCommand.CommandLine;

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{remoteCommand.TargetComputer}\root\CIMV2", options);

            try
            {
                scope.Connect();
                var objectGetOptions = new ObjectGetOptions();
                var managementPath = new ManagementPath("Win32_Process");
                var managementClass = new ManagementClass(scope, managementPath, objectGetOptions);

                ManagementBaseObject inParams = managementClass.GetMethodParameters("Create");
                inParams["CommandLine"] = commandLine;
                ManagementBaseObject outParams = managementClass.InvokeMethod("Create", inParams, null);

                int returnValue;
                bool isReturnValid = int.TryParse(outParams["ReturnValue"].ToString(), out returnValue);
                if (!isReturnValid || returnValue != 0)
                    throw new Exception();

                didTaskSucceed = true;
            }
            catch
            { }

            if (didTaskSucceed)
            {
                // GPUpdate was successful.  Build DialogResult to reflect success.
                dialog.DialogTitle = "Success";
                dialog.DialogBody = $"Successfully executed the command {remoteCommand.CommandLine} on {remoteCommand.TargetComputer}.";
                dialog.DialogIconPath = "/Resources/success-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }
            else
            {
                // Failed to run GPUpdate.  Build DialogResult to reflect failure.
                dialog.DialogTitle = "Error";
                dialog.DialogBody = $"Failed to run the command {remoteCommand.CommandLine} on {remoteCommand.TargetComputer}.";
                dialog.DialogIconPath = "/Resources/error-48.png";
                dialog.ButtonIconPath = "/Resources/checkmark-24.png";
                dialog.ButtonText = "OK";
                dialog.IsCancelVisible = false;
            }

            return dialog;
        }


        private void btnExecute_Click(object sender, RoutedEventArgs e)
        {
            // User confirmed they want to terminate the selected process.
            // Setup a background thread.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_ExecuteRemoteCommand);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_ExecuteRemoteCommandCompleted);

            // Launch background thread to terminate the selected process.
            tbOverlay.Text = $"Executing command on {_TargetComputer}...";
            gridOverlay.Visibility = Visibility.Visible;
            this.IsEnabled = false;
            bgWorker.RunWorkerAsync(new RemoteCommand { TargetComputer = _TargetComputer, CommandLine = txtPath.Text });
        }
    }

    class RemoteCommand
    {
        public string TargetComputer { get; set; }
        public string CommandLine { get; set; }
    }
}
