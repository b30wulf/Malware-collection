using System;
using System.Diagnostics;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for HomeView.xaml
    /// </summary>
    public partial class HomeView : UserControl
    {
        public HomeView()
        {
            InitializeComponent();
            
            Version version = typeof(MainWindow).Assembly.GetName().Version;
            tbVersion.Text = $"Build: {version.Major}.{version.Minor.ToString("D4")}";
        }

        private void tbChangeLog_MouseDown(object sender, MouseButtonEventArgs e)
        {
            var wnd = new ChangeLogWindow();
            wnd.Show();
        }

        private void Hyperlink_RequestNavigate(object sender, System.Windows.Navigation.RequestNavigateEventArgs e)
        {
            try
            {
                Process.Start(new ProcessStartInfo(e.Uri.AbsoluteUri));
            }
            catch
            {
            }
            finally
            {
                e.Handled = true;
            }
        }
    }
}
