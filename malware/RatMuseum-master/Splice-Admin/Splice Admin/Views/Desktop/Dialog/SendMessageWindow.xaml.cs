using Splice_Admin.Classes;
using System;
using System.Management;
using System.Windows;
using System.Windows.Input;

namespace Splice_Admin.Views.Desktop.Dialog
{
    /// <summary>
    /// Interaction logic for SendMessageWindow.xaml
    /// </summary>
    public partial class SendMessageWindow : Window
    {
        private RemoteLogonSession _WindowsUser;

        public SendMessageWindow(RemoteLogonSession windowsUser)
        {
            InitializeComponent();

            // Set initial focus to text box.
            Loaded += (sender, e) =>
                MoveFocus(new TraversalRequest(FocusNavigationDirection.Next));

            _WindowsUser = windowsUser;
            tbBody.Text = $"Enter a message for {windowsUser.Username} on {RemoteLogonSession.ComputerName}.";
        }


        private void btnSendMessage_Click(object sender, RoutedEventArgs e)
        {
            string commandLine = $"msg {_WindowsUser.Username} /time:0 Message from {Environment.UserName}{Environment.NewLine}{Environment.NewLine}{txtMessage.Text}";

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{RemoteLogonSession.ComputerName}\root\CIMV2", options);

            // Connect to WMI and invoke the process creation method.
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
            }

            catch
            { }

            this.Close();
        }
    }
}
