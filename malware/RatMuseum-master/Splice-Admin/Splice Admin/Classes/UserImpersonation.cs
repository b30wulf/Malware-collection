using System;
using System.Runtime.InteropServices;
using System.Security.Principal;

namespace Splice_Admin.Classes
{
    //class UserImpersonation
    //{
    //    #region imports 
    //    //[DllImport("advapi32.dll", SetLastError = true)]
    //    //private static extern bool LogonUser(string lpszUsername, string lpszDomain, string lpszPassword, int dwLogonType, int dwLogonProvider, ref IntPtr phToken);

    //    [DllImport("advapi32.dll", SetLastError = true, CharSet = CharSet.Unicode)]
    //    public static extern bool LogonUser(String username, String domain, IntPtr password, int logonType, int logonProvider, ref IntPtr token);

    //    [DllImport("kernel32.dll", CharSet = CharSet.Auto, SetLastError = true)]
    //    public static extern bool CloseHandle(IntPtr handle);

    //    [DllImport("advapi32.dll", CharSet = CharSet.Auto, SetLastError = true)]
    //    public extern static bool DuplicateToken(IntPtr existingTokenHandle, int SECURITY_IMPERSONATION_LEVEL, ref IntPtr duplicateTokenHandle);
    //    #endregion

    //    #region logon consts 
    //    // logon types 
    //    public const int LOGON32_LOGON_INTERACTIVE = 2;
    //    public const int LOGON32_LOGON_NETWORK = 3;
    //    public const int LOGON32_LOGON_NEW_CREDENTIALS = 9;

    //    // logon providers 
    //    public const int LOGON32_PROVIDER_DEFAULT = 0;
    //    public const int LOGON32_PROVIDER_WINNT50 = 3;
    //    public const int LOGON32_PROVIDER_WINNT40 = 2;
    //    public const int LOGON32_PROVIDER_WINNT35 = 1;
    //    #endregion
    //}

    public class UserImpersonation : IDisposable
    {
        #region Declarations
        private readonly string username;
        private readonly string password;
        private readonly string domain;
        // this will hold the security context 
        // for reverting back to the client after
        // impersonation operations are complete
        private WindowsImpersonationContext impersonationContext;
        #endregion Declarations


        #region Constructors
        public UserImpersonation(string Username, string Domain, string Password)
        {
            username = Username;
            domain = Domain;
            password = Password;
        }
        #endregion Constructors


        #region Public Methods
        public static UserImpersonation Impersonate(string username, string domain, string password)
        {
            var imp = new UserImpersonation(username, domain, password);
            imp.Impersonate();
            return imp;
        }
        public void Impersonate()
        {
            impersonationContext = Logon().Impersonate();
        }
        public void Undo()
        {
            impersonationContext.Undo();
        }
        #endregion Public Methods


        #region Private Methods
        private WindowsIdentity Logon()
        {
            var handle = IntPtr.Zero;
            //var passwordPtr = IntPtr.Zero;

            //const int LOGON32_LOGON_NETWORK = 3;
            const int LOGON32_PROVIDER_DEFAULT = 0;
            const int SecurityImpersonation = 2;
            const int LOGON32_LOGON_INTERACTIVE = 2;
            //const int LOGON32_LOGON_NEW_CREDENTIALS = 9;

            // attempt to authenticate domain user account
            try
            {
                //passwordPtr = Marshal.SecureStringToGlobalAllocUnicode(password);
                if (!LogonUser(username, domain, password, LOGON32_LOGON_INTERACTIVE,
                    LOGON32_PROVIDER_DEFAULT, ref handle))
                    throw new Exception(
                        "User logon failed. Error Number: " +
                        Marshal.GetLastWin32Error());

                // ----------------------------------
                var dupHandle = IntPtr.Zero;
                if (!DuplicateToken(handle,
                    SecurityImpersonation,
                    ref dupHandle))
                    throw new Exception(
                        "Logon failed attemting to duplicate handle");
                // Logon Succeeded ! return new WindowsIdentity instance
                return (new WindowsIdentity(handle));
            }
            // close the open handle to the authenticated account
            finally
            {
                //Marshal.ZeroFreeGlobalAllocUnicode(passwordPtr);
                CloseHandle(handle);
            }
        }

        #region external Win32 API functions
        [DllImport("advapi32.dll", SetLastError = true)]
        private static extern bool LogonUser(string lpszUsername, string lpszDomain,
            string lpszPassword, int dwLogonType,
            int dwLogonProvider, ref IntPtr phToken);

        [DllImport("kernel32.dll", CharSet = CharSet.Auto)]
        private static extern bool CloseHandle(IntPtr handle);

        [DllImport("advapi32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern bool DuplicateToken(
            IntPtr ExistingTokenHandle,
            int SECURITY_IMPERSONATION_LEVEL,
            ref IntPtr DuplicateTokenHandle);
        #endregion external Win32 API functions
        #endregion Private Methods

        #region IDisposable
        private bool disposed;
        public void Dispose() { Dispose(true); }
        public void Dispose(bool isDisposing)
        {
            if (disposed) return;
            if (isDisposing) Undo();
            // -----------------
            disposed = true;
            GC.SuppressFinalize(this);
        }
        ~UserImpersonation() { Dispose(false); }

        #endregion IDisposable
    }
}
