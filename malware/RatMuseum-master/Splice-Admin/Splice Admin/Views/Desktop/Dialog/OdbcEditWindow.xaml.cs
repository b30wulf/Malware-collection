using Microsoft.Win32;
using Splice_Admin.Classes;
using System;
using System.ServiceProcess;
using System.Windows;
using System.Windows.Input;

namespace Splice_Admin.Views.Desktop.Dialog
{
    /// <summary>
    /// Interaction logic for OdbcEditWindow.xaml
    /// </summary>
    public partial class OdbcEditWindow : Window
    {
        private RemoteOdbc _OdbcDsn;
        private RemoteOdbcValue _OdbcValue;

        public OdbcEditWindow(RemoteOdbc odbcDsn, RemoteOdbcValue odbcValue)
        {
            InitializeComponent();

            // Set initial focus to text box.
            Loaded += (sender, e) =>
                MoveFocus(new TraversalRequest(FocusNavigationDirection.Next));

            tbOdbcDsn.Text = odbcDsn.DataSourceName;
            tbOdbcSetting.Text = odbcValue.OdbcValueName;
            tbOdbcOriginalValue.Text = odbcValue.OdbcValueData;
            txtOdbcNewValue.Text = odbcValue.OdbcValueData;
            txtOdbcNewValue.SelectAll();

            _OdbcDsn = odbcDsn;
            _OdbcValue = odbcValue;
        }


        private void btnSave_Click(object sender, RoutedEventArgs e)
        {
            const string odbcRoot = @"SOFTWARE\ODBC\ODBC.INI";
            const string odbcRoot32bitOn64bit = @"SOFTWARE\Wow6432Node\ODBC\ODBC.INI";
            const string serviceName = "RemoteRegistry";
            bool isLocal = RemoteSystemInfo.TargetComputer.ToUpper() == Environment.MachineName.ToUpper() ? true : false;
            bool isServiceRunning = true;

            // If the target computer is remote, then start the Remote Registry service.
            using (
                GlobalVar.UseAlternateCredentials
                ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                : null)
            using (var sc = new ServiceController(serviceName, RemoteSystemInfo.TargetComputer))
            {
                try
                {
                    if (!isLocal && sc.Status != ServiceControllerStatus.Running)
                    {
                        isServiceRunning = false;
                        sc.Start();
                        sc.WaitForStatus(ServiceControllerStatus.Running);
                    }
                }
                catch { }

                try
                {
                    using (RegistryKey key = RegistryKey.OpenRemoteBaseKey(RegistryHive.LocalMachine, RemoteSystemInfo.TargetComputer))
                    {
                        if (_OdbcDsn.Is32bitOn64bit)
                        {
                            using (RegistryKey subKey = key.OpenSubKey($@"{odbcRoot32bitOn64bit}\{_OdbcDsn.DataSourceName}", true))
                            {
                                if (subKey != null)
                                    subKey.SetValue(_OdbcValue.OdbcValueName, txtOdbcNewValue.Text);
                            }
                        }
                        else
                        {
                            using (RegistryKey subKey = key.OpenSubKey($@"{odbcRoot}\{_OdbcDsn.DataSourceName}", true))
                            {
                                if (subKey != null)
                                    subKey.SetValue(_OdbcValue.OdbcValueName, txtOdbcNewValue.Text);
                            }
                        }
                    }
                }
                catch { }

                // Cleanup.
                if (!isLocal && !isServiceRunning)
                {
                    try
                    {
                        if (sc != null)
                            sc.Stop();
                    }

                    catch (Exception)
                    {
                    }
                }
            }

            this.Close();
        }
    }
}
