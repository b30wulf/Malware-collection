using Splice_Admin.Classes;
using System.Windows;
using System.Windows.Controls;

namespace Splice_Admin.Views.Desktop
{
    /// <summary>
    /// Interaction logic for AlternateCredentialsView.xaml
    /// </summary>
    public partial class AlternateCredentialsView : UserControl
    {
        private object _PreviousView;
        private ContentControl _ContentControl;

        public AlternateCredentialsView(ContentControl contentControl, object previousView)
        {
            InitializeComponent();

            _ContentControl = contentControl;
            _PreviousView = previousView;

            SetInitialViewSate();
        }

        private void SetInitialViewSate()
        {
            if (GlobalVar.UseAlternateCredentials)
            {
                radioAlternate.IsChecked = true;
                radioCurrent.IsChecked = false;
            }
            else
            {
                radioCurrent.IsChecked = true;
                radioAlternate.IsChecked = false;
            }

            tbUsername.Text = GlobalVar.AlternateUsername;
            pbPassword.Password = GlobalVar.AlternatePassword;
            tbDomain.Text = GlobalVar.AlternateDomain;
        }

        private void btnSave_Click(object sender, RoutedEventArgs e)
        {
            if (radioAlternate.IsChecked == true)
            {
                GlobalVar.UseAlternateCredentials = true;
                GlobalVar.AlternateUsername = tbUsername.Text;
                GlobalVar.AlternatePassword = pbPassword.Password;
                GlobalVar.AlternateDomain = tbDomain.Text;
            }
            else
            {
                GlobalVar.UseAlternateCredentials = false;
                GlobalVar.AlternateUsername = string.Empty;
                GlobalVar.AlternatePassword = string.Empty;
                GlobalVar.AlternateDomain = string.Empty;
            }

            CloseCurrentView();
        }

        private void btnCancel_Click(object sender, RoutedEventArgs e)
        {
            CloseCurrentView();
        }

        private void CloseCurrentView()
        {
            _ContentControl.Content = _PreviousView;
        }
    }
}
