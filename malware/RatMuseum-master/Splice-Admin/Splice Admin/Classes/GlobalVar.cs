namespace Splice_Admin.Classes
{
    public static class GlobalVar
    {
        private static string _targetComputerName;
        public static string TargetComputerName
        {
            get
            {
                return _targetComputerName;
            }
            set
            {
                _targetComputerName = value;
            }
        }

        private static string _alternateUsername;
        public static string AlternateUsername
        {
            get
            {
                return _alternateUsername;
            }
            set
            {
                _alternateUsername = value;
            }
        }

        private static string _alternatePassword;
        public static string AlternatePassword
        {
            get
            {
                return _alternatePassword;
            }
            set
            {
                _alternatePassword = value;
            }
        }

        private static string _alternateDomain;
        public static string AlternateDomain
        {
            get
            {
                return _alternateDomain;
            }
            set
            {
                _alternateDomain = value;
            }
        }

        private static bool _useAlternateCredentials;
        public static bool UseAlternateCredentials
        {
            get
            {
                return _useAlternateCredentials;
            }
            set
            {
                _useAlternateCredentials = value;
            }
        }
    }
}
