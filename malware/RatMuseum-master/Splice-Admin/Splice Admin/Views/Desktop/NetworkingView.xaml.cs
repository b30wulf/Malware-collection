using Splice_Admin.Classes;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Management;
using System.Text;
using System.Text.RegularExpressions;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for NetworkingView.xaml
    /// </summary>
    public partial class NetworkingView : UserControl
    {
        public NetworkingView(string targetComputer)
        {
            InitializeComponent();

            RemoteNetworkAdapter.ComputerName = targetComputer;
            RefreshNetworkAdapters();
        }

        private void RefreshNetworkAdapters()
        {
            // Setup a background thread.
            var bgWorker = new BackgroundWorker();
            bgWorker.DoWork += new DoWorkEventHandler(bgThread_GetNetworkAdapters);
            bgWorker.RunWorkerCompleted += new RunWorkerCompletedEventHandler(bgThread_GetNetworkAdaptersCompleted);

            // Display overlay window indicating work is being performed.
            gridOverlay.Visibility = Visibility.Visible;

            // Retrieve event log in a new background thread.
            bgWorker.RunWorkerAsync();
        }

        private void bgThread_GetNetworkAdapters(object sender, DoWorkEventArgs e)
        {
            e.Result = RemoteNetworkAdapter.GetNetworkAdapters();
        }


        private void bgThread_GetNetworkAdaptersCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            var networkAdapters = e.Result as List<RemoteNetworkAdapter>;
            lvNetworkAdapters.ItemsSource = networkAdapters;
            gridOverlay.Visibility = Visibility.Collapsed;
        }

        private void TabItem_Selected(object sender, RoutedEventArgs e)
        {
            dgNetstat.ItemsSource = GetNetStatPorts();
        }





        // ===============================================
        // The Method That Parses The NetStat Output
        // And Returns A List Of Port Objects
        // ===============================================
        public static List<Port> GetNetStatPorts()
        {
            var ports = new List<Port>();
            string commandLine = "cmd /c netstat.exe -ano > ";
            string pathToNetstatOutput = $@"\\{RemoteNetworkAdapter.ComputerName}\C$\Windows\Temp";

            if (Directory.Exists(pathToNetstatOutput))
            {
                var rnd = new Random();
                var rndNumber = rnd.Next(0, 1000);
                pathToNetstatOutput += $@"\splc-{rndNumber}.txt";
                commandLine += $@"C:\Windows\Temp\splc-{rndNumber}.txt";
            }
            else
                return ports;

            // Setup WMI query.
            var options = new ConnectionOptions();
            if (GlobalVar.UseAlternateCredentials)
            {
                options.Username = GlobalVar.AlternateUsername;
                options.Password = GlobalVar.AlternatePassword;
                options.Authority = $"NTLMDOMAIN:{GlobalVar.AlternateDomain}";
            }
            var scope = new ManagementScope($@"\\{RemoteNetworkAdapter.ComputerName}\root\CIMV2", options);

            try
            {
                scope.Connect();
                var objectGetOptions = new ObjectGetOptions();
                var managementPath = new ManagementPath("Win32_Process");
                var managementClass = new ManagementClass(scope, managementPath, objectGetOptions);

                var startupOptions = new ManagementClass(@"\\.\root\CIMV2:Win32_ProcessStartup");
                startupOptions["ShowWindow"] = 0;

                var inParams = managementClass.GetMethodParameters("Create");
                inParams["CommandLine"] = commandLine;
                inParams["ProcessStartupInformation"] = startupOptions;
                var outParams = managementClass.InvokeMethod("Create", inParams, null);

                int returnValue;
                var isReturnValid = int.TryParse(outParams["ReturnValue"].ToString(), out returnValue);
                if (!isReturnValid || returnValue != 0)
                    throw new Exception();
            }
            catch (Exception ex)
            {
                // Do error handling.
                MessageBox.Show("Error: " + ex.Message);
                return ports;
            }

            for (int i = 0; i < 10; ++i)
            {
                System.Threading.Thread.Sleep(500);
                if (File.Exists(pathToNetstatOutput))
                    break;
            }

            try
            {
                using (var reader = File.OpenText(pathToNetstatOutput))
                {
                    string line;
                    while ((line = reader.ReadLine()) != null)
                    {
                        // Split it.
                        var tokens = Regex.Split(line, "\\s+");
                        if (tokens.Length > 4 && (tokens[1].Equals("UDP") || tokens[1].Equals("TCP")))
                        {
                            var port = new Port();
                            port.Protocol = tokens[1];
                            port.LocalPortNumber = tokens[2].Substring(tokens[2].LastIndexOf(':') + 1);
                            port.LocalAddress = tokens[2].Substring(0, tokens[2].LastIndexOf(':'));
                            port.LocalAddress = port.LocalAddress.TrimStart('[');
                            port.LocalAddress = port.LocalAddress.TrimEnd(']');
                            if (port.LocalAddress.Contains('%'))
                                port.LocalAddress = port.LocalAddress.Split('%')[0];

                            port.RemotePortNumber = tokens[3].Substring(tokens[3].LastIndexOf(':') + 1);
                            port.RemoteAddress = tokens[3].Substring(0, tokens[3].LastIndexOf(':'));
                            port.RemoteAddress = port.RemoteAddress.TrimStart('[');
                            port.RemoteAddress = port.RemoteAddress.TrimEnd(']');

                            port.State = port.Protocol == "TCP" ? tokens[4] : string.Empty;
                            port.ProcessId = port.Protocol == "TCP" ? tokens[5] : tokens[4];

                            if (port.State.Equals("LISTENING") &&
                                (port.RemoteAddress.Equals("0.0.0.0") || port.RemoteAddress.Equals("::")) &&
                                port.RemotePortNumber.Equals("0"))
                            {
                                port.RemoteAddress = "";
                                port.RemotePortNumber = "";
                            }

                            ports.Add(port);
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
                // Error.  Do nothing.
            }
            finally
            {
                File.Delete(pathToNetstatOutput);
            }

            return ports;
        }
    }

    // ===============================================
    // The Port Class We're Going To Create A List Of
    // ===============================================
    public class Port
    {
        public string Protocol { get; set; }
        public string LocalAddress { get; set; }
        public string RemoteAddress { get; set; }
        public string LocalPortNumber { get; set; }
        public string RemotePortNumber { get; set; }
        public string State { get; set; }
        public string ProcessId { get; set; }
        public string ProcessName { get; set; }

    }
}
