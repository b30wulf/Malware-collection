using Splice_Admin.Classes;
using System;
using System.ComponentModel;
using System.DirectoryServices;
using System.Security.Principal;
using System.Windows;
using System.Windows.Data;
using System.Windows.Input;
using System.Windows.Media;

namespace Splice_Admin.Views.Desktop.Dialog
{
    /// <summary>
    /// Interaction logic for UserDetailsWindow.xaml
    /// </summary>
    public partial class UserDetailsWindow : Window
    {
        public UserDetailsWindow(string samAccount)
        {
            InitializeComponent();

            SetUserDetails(SearchActiveDirectory(samAccount));
        }

        private void btnClose_Click(object sender, RoutedEventArgs e)
        {
            this.Close();
        }


        private void SetUserDetails(ActiveDirectoryUser user)
        {
            if (!string.IsNullOrEmpty(user.DistinguishedName))
            {
                tbFullName.Text = user.NameFull;
                txtAccount.Text = user.SamId;
                txtOU.Text = user.OrganizationalUnit;
                txtEmail.Text = user.EmailAddress;
                txtPhone.Text = user.OfficePhone;
                txtDepartment.Text = user.JobDepartment;
                txtTitle.Text = user.JobTitle;
                txtSID.Text = user.SID;

                lbGroups.ItemsSource = user.MemberOf;
                CollectionView view = (CollectionView)CollectionViewSource.GetDefaultView(lbGroups.ItemsSource);
                view.SortDescriptions.Add(new SortDescription("", ListSortDirection.Ascending));
            }
            else
            {
                //this.Show();
                //Point controlPosition = this.PointToScreen(new Point(0d, 0d));
                //this.Hide();

                //var error = new DialogResult();
                //error.DialogTitle = "Error";
                //error.DialogBody = "Could not locate user.";
                //error.DialogIconPath = "/Resources/error-48.png";
                //error.ButtonIconPath = "/Resources/checkmark-24.png";
                //error.ButtonText = "OK";
                //error.IsCancelVisible = false;
                
                //DialogWindow.DisplayDialog(this, error, new Point());
                //this.Close();
                tbFullName.Text = "Could not locate user.";
                lbGroups.Visibility = Visibility.Collapsed;
                imgIcon.Visibility = Visibility.Collapsed;
                txtAccount.Text = user.SamId;
            }
        }


        private ActiveDirectoryUser SearchActiveDirectory(string samAccount)
        {
            // Store the search results as a collection of Users.  This list will be returned.
            var user = new ActiveDirectoryUser();
            user.SamId = samAccount;

            string ldapPath = "LDAP://" + Environment.UserDomainName;
            string searchFilter =
                    "(&(objectCategory=person)(objectClass=user)" +
                    $"(sAMAccountName={samAccount}))";
            string[] searchProperties =
            {
                "givenName",
                "sn",
                "physicalDeliveryOfficeName",
                "telephoneNumber",
                "mail",
                "title",
                "department",
                "memberOf",
                "objectSid",
                "pwdLastSet",
                "distinguishedName"
            };

            try
            {
                using (
                    GlobalVar.UseAlternateCredentials
                    ? UserImpersonation.Impersonate(GlobalVar.AlternateUsername, GlobalVar.AlternateDomain, GlobalVar.AlternatePassword)
                    : null)
                using (DirectoryEntry parentEntry = new DirectoryEntry(ldapPath))
                using (DirectorySearcher directorySearcher = new DirectorySearcher(parentEntry, searchFilter, searchProperties))
                using (SearchResultCollection searchResultCollection = directorySearcher.FindAll())
                {
                    // Iterate through each search result.
                    foreach (SearchResult searchResult in searchResultCollection)
                    {
                        DirectoryEntry entry = new DirectoryEntry(searchResult.GetDirectoryEntry().Path);

                        user.NameFirst = (entry.Properties["givenName"].Value != null) ?
                            entry.Properties["givenName"].Value.ToString() : string.Empty;
                        user.NameLast = (entry.Properties["sn"].Value != null) ?
                            entry.Properties["sn"].Value.ToString() : string.Empty;
                        user.OfficeLocation = (entry.Properties["physicalDeliveryOfficeName"].Value != null)
                            ? entry.Properties["physicalDeliveryOfficeName"].Value.ToString() : string.Empty;
                        user.OfficePhone = (entry.Properties["telephoneNumber"].Value != null) ?
                            entry.Properties["telephoneNumber"].Value.ToString() : string.Empty;
                        user.JobDepartment = (entry.Properties["department"].Value != null) ?
                            entry.Properties["department"].Value.ToString() : string.Empty;
                        user.JobTitle = (entry.Properties["title"].Value != null) ?
                            entry.Properties["title"].Value.ToString() : string.Empty;
                        user.EmailAddress = (entry.Properties["mail"].Value != null) ?
                            entry.Properties["mail"].Value.ToString() : string.Empty;

                        if (entry.Properties["objectSid"].Value != null)
                            user.SID = new SecurityIdentifier((byte[])entry.Properties["objectSid"].Value, 0).ToString();

                        if (entry.Properties["memberOf"] != null)
                            user.MemberOf = entry.Properties["memberOf"];

                        //user.PasswordLastSet = (entry.Properties["pwdLastSet"].Value != null) ? entry.Properties["pwdLastSet"].Value.ToString() : string.Empty;

                        for (int i = 0; i < user.MemberOf.Count; ++i)
                        {
                            int startIndex = user.MemberOf[i].ToString().IndexOf("CN=", 0) + 3; //+3 for  length of "OU="
                            int endIndex = user.MemberOf[i].ToString().IndexOf(",", startIndex);
                            user.MemberOf[i] = user.MemberOf[i].ToString().Substring((startIndex), (endIndex - startIndex));
                        }

                        user.DistinguishedName = entry.Properties["distinguishedName"].Value.ToString();
                        string dn = entry.Properties["distinguishedName"].Value.ToString();
                        int dnStartIndex = dn.IndexOf(",", 1) + 4; //+3 for  length of "OU="
                        int dnEndIndex = dn.IndexOf(",", dnStartIndex);
                        user.OrganizationalUnit = dn.Substring((dnStartIndex), (dnEndIndex - dnStartIndex));
                    }
                }
            }
            catch
            { }

            return user;
        }

        private void Border_MouseDown(object sender, MouseButtonEventArgs e)
        {
            if (e.ChangedButton == MouseButton.Left)
                this.DragMove();
        }

        private void Window_Activated(object sender, EventArgs e)
        {
            TitleBar.Background = new LinearGradientBrush(Color.FromRgb(72, 113, 176), Color.FromRgb(81, 114, 164), new Point(0.5, 0), new Point(0.5, 1));
            TitleText.Foreground = Brushes.White;
        }

        private void Window_Deactivated(object sender, EventArgs e)
        {
            TitleBar.Background = SystemColors.GradientInactiveCaptionBrush;
            TitleText.Foreground = Brushes.DarkGray;
        }
    }



    class ActiveDirectoryUser
    {
        public string SamId { get; set; }
        public string NameFirst { get; set; }
        public string NameLast { get; set; }
        public string NameFull { get { return this.NameFirst + " " + this.NameLast; } }
        public string OfficeLocation { get; set; }
        public string OfficePhone { get; set; }
        public string JobDepartment { get; set; }
        public string JobTitle { get; set; }
        public string EmailAddress { get; set; }
        public string SID { get; set; }
        public string OrganizationalUnit { get; set; }
        public string PasswordLastSet { get; set; }
        public string DistinguishedName { get; set; }
        public PropertyValueCollection MemberOf { get; set; }
    }
}
