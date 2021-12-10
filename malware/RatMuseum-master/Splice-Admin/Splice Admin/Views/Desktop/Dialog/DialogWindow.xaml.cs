using Splice_Admin.Classes;
using System;
using System.Windows;
using System.Windows.Media.Imaging;

namespace Splice_Admin.Views.Desktop.Dialog
{
    /// <summary>
    /// Interaction logic for DialogWindow.xaml
    /// </summary>
    public partial class DialogWindow : Window
    {
        public DialogWindow(DialogResult dialog)
        {
            InitializeComponent();

            tbTitle.Text = (dialog.DialogTitle != null) ? dialog.DialogTitle : string.Empty;
            tbBody.Text = (dialog.DialogBody != null) ? dialog.DialogBody : string.Empty;
            btnConfirm.Content = (dialog.ButtonText != null) ? dialog.ButtonText : "OK";
            image.Source = new BitmapImage(new Uri(dialog.DialogIconPath, UriKind.Relative));
            btnConfirm.Tag = new BitmapImage(new Uri(dialog.ButtonIconPath, UriKind.Relative));

            if (!dialog.IsCancelVisible)
            {
                btnCancel.Visibility = Visibility.Collapsed;
                btnCancel.IsEnabled = false;
            }
        }


        public DialogWindow(DialogResult dialog, bool waitDialog)
        {
            InitializeComponent();

            tbTitle.Text = (dialog.DialogTitle != null) ? dialog.DialogTitle : string.Empty;
            tbBody.Text = (dialog.DialogBody != null) ? dialog.DialogBody : string.Empty;
            btnConfirm.Content = (dialog.ButtonText != null) ? dialog.ButtonText : "OK";
            image.Source = new BitmapImage(new Uri(dialog.DialogIconPath, UriKind.Relative));
            btnConfirm.Tag = new BitmapImage(new Uri(dialog.ButtonIconPath, UriKind.Relative));

            if (!dialog.IsCancelVisible)
            {
                btnCancel.Visibility = Visibility.Collapsed;
                btnCancel.IsEnabled = false;
            }
        }


        public static bool? DisplayDialog(Window mainWindow, DialogResult dialog, Point position)
        {
            // Blur main window
            System.Windows.Media.Effects.BlurEffect objBlur = new System.Windows.Media.Effects.BlurEffect();
            objBlur.Radius = 4;
            mainWindow.Opacity = 0.85;
            mainWindow.Effect = objBlur;

            // Display dialog window.
            var dialogWindow = new DialogWindow(dialog);
            dialogWindow.WindowStartupLocation = WindowStartupLocation.CenterOwner;
            dialogWindow.Owner = mainWindow;
            dialogWindow.Left = position.X;
            dialogWindow.Top = position.Y;
            bool? result = dialogWindow.ShowDialog();

            // Dialog acknowledged.  Remove blur from window.
            mainWindow.Effect = null;
            mainWindow.Opacity = 1;

            return result;
        }


        private void btnConfirm_Click(object sender, RoutedEventArgs e)
        {
            DialogResult = true;
        }
    }
}
