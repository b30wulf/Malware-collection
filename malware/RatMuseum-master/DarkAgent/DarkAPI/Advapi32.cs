using System;
using System.Collections.Generic;
using System.Runtime.InteropServices;
using System.Text;

namespace DarkAPI
{
    public class Advapi32
    {
        public const UInt32 SE_PRIVILEGE_ENABLED_BY_DEFAULT = 0x00000001;
        public const UInt32 SE_PRIVILEGE_ENABLED = 0x00000002;
        public const UInt32 SE_PRIVILEGE_REMOVED = 0x00000004;
        public const UInt32 SE_PRIVILEGE_USED_FOR_ACCESS = 0x80000000;
        public const Int32 ANYSIZE_ARRAY = 1;
        public const UInt32 TOKEN_QUERY = 0x0008;
        public const UInt32 TOKEN_ADJUST_PRIVILEGES = 0x0020;
        public const string SE_SHUTDOWN_NAME = "SeShutdownPrivilege";
        public const uint FILE_SHARE_READ = 0x00000001;
        public const uint FILE_SHARE_WRITE = 0x00000002;
        public const uint FILE_SHARE_DELETE = 0x00000004;
        public const uint FILE_ATTRIBUTE_READONLY = 0x00000001;
        public const uint FILE_ATTRIBUTE_HIDDEN = 0x00000002;
        public const uint FILE_ATTRIBUTE_SYSTEM = 0x00000004;
        public const uint FILE_ATTRIBUTE_DIRECTORY = 0x00000010;
        public const uint FILE_ATTRIBUTE_ARCHIVE = 0x00000020;
        public const uint FILE_ATTRIBUTE_DEVICE = 0x00000040;
        public const uint FILE_ATTRIBUTE_NORMAL = 0x00000080;
        public const uint FILE_ATTRIBUTE_TEMPORARY = 0x00000100;
        public const uint FILE_ATTRIBUTE_SPARSE_FILE = 0x00000200;
        public const uint FILE_ATTRIBUTE_REPARSE_POINT = 0x00000400;
        public const uint FILE_ATTRIBUTE_COMPRESSED = 0x00000800;
        public const uint FILE_ATTRIBUTE_OFFLINE = 0x00001000;
        public const uint FILE_ATTRIBUTE_NOT_CONTENT_INDEXED = 0x00002000;
        public const uint FILE_ATTRIBUTE_ENCRYPTED = 0x00004000;
        public const uint GENERIC_READ = 0x80000000;
        public const uint GENERIC_WRITE = 0x40000000;
        public const uint GENERIC_EXECUTE = 0x20000000;
        public const uint GENERIC_ALL = 0x10000000;


        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool AbortSystemShutdown(string lpMachineName);

        [DllImport("advapi32", SetLastError = true)]
        public static extern bool AccessCheck(
          [MarshalAs(UnmanagedType.LPArray)]
          byte[] pSecurityDescriptor,
          IntPtr ClientToken,
          uint DesiredAccess,
          [In] ref GENERIC_MAPPING GenericMapping,
          IntPtr PrivilegeSet,
          ref uint PrivilegeSetLength,
          out uint GrantedAccess,
          out bool AccessStatus);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool AddAccessAllowedAce(IntPtr pAcl, uint dwAceRevision, ACCESS_MASK AccessMask, IntPtr pSid);


        [DllImport("advapi32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool AdjustTokenPrivileges(IntPtr TokenHandle,
           [MarshalAs(UnmanagedType.Bool)]bool DisableAllPrivileges,
           ref TOKEN_PRIVILEGES NewState,
           UInt32 BufferLengthInBytes,
           ref TOKEN_PRIVILEGES PreviousState,
           out UInt32 ReturnLengthInBytes);

        [DllImport("advapi32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool AdjustTokenPrivileges(IntPtr TokenHandle,
           [MarshalAs(UnmanagedType.Bool)]bool DisableAllPrivileges,
           ref TOKEN_PRIVILEGES NewState,
           UInt32 Zero,
           IntPtr Null1,
           IntPtr Null2);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool AllocateAndInitializeSid(
            ref SidIdentifierAuthority pIdentifierAuthority,
            byte nSubAuthorityCount,
            int dwSubAuthority0, int dwSubAuthority1,
            int dwSubAuthority2, int dwSubAuthority3,
            int dwSubAuthority4, int dwSubAuthority5,
            int dwSubAuthority6, int dwSubAuthority7,
            out IntPtr pSid);

        [DllImport("advapi32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern bool BackupEventLog(IntPtr hEventLog, string backupFile);


        [DllImport("advapi32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern void BuildExplicitAccessWithName(
            ref EXPLICIT_ACCESS pExplicitAccess,
            string pTrusteeName,
            uint AccessPermissions,
            uint AccessMode,
            uint Inheritance);

        [DllImport("advapi32.dll", CharSet=CharSet.Unicode, SetLastError=true)]
        public static extern Boolean ChangeServiceConfig(IntPtr hService, UInt32 nServiceType, UInt32 nStartType, UInt32 nErrorControl, String lpBinaryPathName, String lpLoadOrderGroup, IntPtr lpdwTagId, String lpDependencies, String lpServiceStartName, String lpPassword, String lpDisplayName);

        [DllImport("advapi32.dll", CharSet=CharSet.Unicode, SetLastError = true)]
        public static extern Boolean ChangeServiceConfig(IntPtr hService, UInt32 nServiceType, UInt32 nStartType, UInt32 nErrorControl, String lpBinaryPathName, String lpLoadOrderGroup, IntPtr lpdwTagId, [In] char[] lpDependencies, String lpServiceStartName, String lpPassword, String lpDisplayName);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool CheckTokenMembership(IntPtr TokenHandle, IntPtr SidToCheck, out bool IsMember);

        [DllImport("advapi32.dll", SetLastError = true, EntryPoint = "ClearEventLog")]
        [return: MarshalAs(UnmanagedType.U1)]
        public static extern bool ClearEventLog(
                                IntPtr hEventLog,
                                [MarshalAs(UnmanagedType.LPStr)] String lpBackupFileName);

        [DllImport("advapi32.dll", SetLastError = true, EntryPoint = "CloseEventLog")]
        [return: MarshalAs(UnmanagedType.U1)]
        public static extern bool CloseEventLog(IntPtr hEventLog);

        [DllImport("advapi32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool CloseServiceHandle(IntPtr hSCObject);

        [DllImport("advapi32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        public static extern bool ControlService(IntPtr hService, SERVICE_CONTROL dwControl, ref SERVICE_STATUS lpServiceStatus);

        [DllImport("advapi32", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern bool ConvertSidToStringSid([MarshalAs(UnmanagedType.LPArray)] byte[] pSID, out IntPtr ptrSid);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool ConvertStringSidToSid(string StringSid, out IntPtr ptrSid);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool CopySid(uint nDestinationSidLength, IntPtr pDestinationSid, IntPtr pSourceSid);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool CopySid(uint nDestinationSidLength, byte[] pDestinationSid, IntPtr pSourceSid);

        [DllImport("advapi32.dll", SetLastError = true, CharSet = CharSet.Unicode)]
        public static extern bool CreateProcessWithLogonW(
           String userName,
           String domain,
           String password,
           LogonFlags logonFlags,
           String applicationName,
           String commandLine,
           CreationFlags creationFlags,
           UInt32 environment,
           String currentDirectory,
           ref  STARTUPINFO startupInfo,
           out PROCESS_INFORMATION processInformation);


        [DllImport("advapi32.dll", SetLastError = true, CharSet = CharSet.Auto)]
        public static extern IntPtr CreateService(
            IntPtr hSCManager,
            string lpServiceName,
            string lpDisplayName,
            uint dwDesiredAccess,
            uint dwServiceType,
            uint dwStartType,
            uint dwErrorControl,
            string lpBinaryPathName,
            string lpLoadOrderGroup,
            string lpdwTagId,
            string lpDependencies,
            string lpServiceStartName,
            string lpPassword);

        [DllImport("advapi32.dll", SetLastError = true)]
        public static extern bool CreateWellKnownSid(
            WELL_KNOWN_SID_TYPE WellKnownSidType,
            IntPtr DomainSid,
            IntPtr pSid,
            ref uint cbSid);

        [DllImport("advapi32.dll", EntryPoint = "CredDeleteW", CharSet = CharSet.Unicode)]
        public static extern bool CredDelete(string target, int type, int flags);

        [DllImport("Advapi32.dll", SetLastError = true, EntryPoint = "CredWriteW", CharSet = CharSet.Unicode)]
        public static extern bool CredWrite([In] Credential userCredential, [In] UInt32 flags);





















        public class Credential
        {
            public UInt32 Flags;
            public CRED_TYPE Type;
            public string TargetName;
            public string Comment;
            public System.Runtime.InteropServices.ComTypes.FILETIME LastWritten;
            public byte[] CredentialBlob;
            public CRED_PERSIST Persist;
            public CREDENTIAL_ATTRIBUTE[] Attributes;
            public string TargetAlias;
            public string UserName;
        }

        public enum CRED_PERSIST : uint
        {
            SESSION = 1,
            LOCAL_MACHINE = 2,
            ENTERPRISE = 3,
        }

        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Auto)]
        public struct CREDENTIAL_ATTRIBUTE
        {
            string Keyword;
            uint Flags;
            uint ValueSize;
            IntPtr Value;
        }

        public enum CRED_TYPE : uint
        {
            GENERIC = 1,
            DOMAIN_PASSWORD = 2,
            DOMAIN_CERTIFICATE = 3,
            DOMAIN_VISIBLE_PASSWORD = 4,
            GENERIC_CERTIFICATE = 5,
            DOMAIN_EXTENDED = 6,
            MAXIMUM = 7,      // Maximum supported cred type
            MAXIMUM_EX = (MAXIMUM + 1000),  // Allow new applications to run on old OSes
        }

        [Flags]
        public enum CreateProcessFlags
        {
            CREATE_BREAKAWAY_FROM_JOB = 0x01000000,
            CREATE_DEFAULT_ERROR_MODE = 0x04000000,
            CREATE_NEW_CONSOLE = 0x00000010,
            CREATE_NEW_PROCESS_GROUP = 0x00000200,
            CREATE_NO_WINDOW = 0x08000000,
            CREATE_PROTECTED_PROCESS = 0x00040000,
            CREATE_PRESERVE_CODE_AUTHZ_LEVEL = 0x02000000,
            CREATE_SEPARATE_WOW_VDM = 0x00000800,
            CREATE_SHARED_WOW_VDM = 0x00001000,
            CREATE_SUSPENDED = 0x00000004,
            CREATE_UNICODE_ENVIRONMENT = 0x00000400,
            DEBUG_ONLY_THIS_PROCESS = 0x00000002,
            DEBUG_PROCESS = 0x00000001,
            DETACHED_PROCESS = 0x00000008,
            EXTENDED_STARTUPINFO_PRESENT = 0x00080000,
            INHERIT_PARENT_AFFINITY = 0x00010000
        }

        public enum WELL_KNOWN_SID_TYPE
        {
            WinNullSid = 0,
            WinWorldSid = 1,
            WinLocalSid = 2,
            WinCreatorOwnerSid = 3,
            WinCreatorGroupSid = 4,
            WinCreatorOwnerServerSid = 5,
            WinCreatorGroupServerSid = 6,
            WinNtAuthoritySid = 7,
            WinDialupSid = 8,
            WinNetworkSid = 9,
            WinBatchSid = 10,
            WinInteractiveSid = 11,
            WinServiceSid = 12,
            WinAnonymousSid = 13,
            WinProxySid = 14,
            WinEnterpriseControllersSid = 15,
            WinSelfSid = 16,
            WinAuthenticatedUserSid = 17,
            WinRestrictedCodeSid = 18,
            WinTerminalServerSid = 19,
            WinRemoteLogonIdSid = 20,
            WinLogonIdsSid = 21,
            WinLocalSystemSid = 22,
            WinLocalServiceSid = 23,
            WinNetworkServiceSid = 24,
            WinBuiltinDomainSid = 25,
            WinBuiltinAdministratorsSid = 26,
            WinBuiltinUsersSid = 27,
            WinBuiltinGuestsSid = 28,
            WinBuiltinPowerUsersSid = 29,
            WinBuiltinAccountOperatorsSid = 30,
            WinBuiltinSystemOperatorsSid = 31,
            WinBuiltinPrintOperatorsSid = 32,
            WinBuiltinBackupOperatorsSid = 33,
            WinBuiltinReplicatorSid = 34,
            WinBuiltinPreWindows2000CompatibleAccessSid = 35,
            WinBuiltinRemoteDesktopUsersSid = 36,
            WinBuiltinNetworkConfigurationOperatorsSid = 37,
            WinAccountAdministratorSid = 38,
            WinAccountGuestSid = 39,
            WinAccountKrbtgtSid = 40,
            WinAccountDomainAdminsSid = 41,
            WinAccountDomainUsersSid = 42,
            WinAccountDomainGuestsSid = 43,
            WinAccountComputersSid = 44,
            WinAccountControllersSid = 45,
            WinAccountCertAdminsSid = 46,
            WinAccountSchemaAdminsSid = 47,
            WinAccountEnterpriseAdminsSid = 48,
            WinAccountPolicyAdminsSid = 49,
            WinAccountRasAndIasServersSid = 50,
            WinNTLMAuthenticationSid = 51,
            WinDigestAuthenticationSid = 52,
            WinSChannelAuthenticationSid = 53,
            WinThisOrganizationSid = 54,
            WinOtherOrganizationSid = 55,
            WinBuiltinIncomingForestTrustBuildersSid = 56,
            WinBuiltinPerfMonitoringUsersSid = 57,
            WinBuiltinPerfLoggingUsersSid = 58,
            WinBuiltinAuthorizationAccessSid = 59,
            WinBuiltinTerminalServerLicenseServersSid = 60,
        }

        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Unicode)]
        public struct STARTUPINFO
        {
            public Int32 cb;
            public string lpReserved;
            public string lpDesktop;
            public string lpTitle;
            public Int32 dwX;
            public Int32 dwY;
            public Int32 dwXSize;
            public Int32 dwYSize;
            public Int32 dwXCountChars;
            public Int32 dwYCountChars;
            public Int32 dwFillAttribute;
            public Int32 dwFlags;
            public Int16 wShowWindow;
            public Int16 cbReserved2;
            public IntPtr lpReserved2;
            public IntPtr hStdInput;
            public IntPtr hStdOutput;
            public IntPtr hStdError;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct PROCESS_INFORMATION
        {
            public IntPtr hProcess;
            public IntPtr hThread;
            public int dwProcessId;
            public int dwThreadId;
        }

        [Flags]
        public enum CreationFlags
        {
            CREATE_SUSPENDED = 0x00000004,
            CREATE_NEW_CONSOLE = 0x00000010,
            CREATE_NEW_PROCESS_GROUP = 0x00000200,
            CREATE_UNICODE_ENVIRONMENT = 0x00000400,
            CREATE_SEPARATE_WOW_VDM = 0x00000800,
            CREATE_DEFAULT_ERROR_MODE = 0x04000000,
        }

        [Flags]
        public enum LogonFlags
        {
            LOGON_WITH_PROFILE = 0x00000001,
            LOGON_NETCREDENTIALS_ONLY = 0x00000002
        }

        [Flags]
        public enum SERVICE_TYPES : int
        {
            SERVICE_KERNEL_DRIVER = 0x00000001,
            SERVICE_FILE_SYSTEM_DRIVER = 0x00000002,
            SERVICE_WIN32_OWN_PROCESS = 0x00000010,
            SERVICE_WIN32_SHARE_PROCESS = 0x00000020,
            SERVICE_INTERACTIVE_PROCESS = 0x00000100
        }

        [StructLayout(LayoutKind.Sequential, Pack = 1)]
        public struct SERVICE_STATUS
        {
            public static readonly int SizeOf = Marshal.SizeOf(typeof(SERVICE_STATUS));
            public SERVICE_TYPES dwServiceType;
            public SERVICE_STATE dwCurrentState;
            public uint dwControlsAccepted;
            public uint dwWin32ExitCode;
            public uint dwServiceSpecificExitCode;
            public uint dwCheckPoint;
            public uint dwWaitHint;
        } 

        [Flags]
        public enum SERVICE_CONTROL : uint
        {
            STOP = 0x00000001,
            PAUSE = 0x00000002,
            CONTINUE = 0x00000003,
            INTERROGATE = 0x00000004,
            SHUTDOWN = 0x00000005,
            PARAMCHANGE = 0x00000006,
            NETBINDADD = 0x00000007,
            NETBINDREMOVE = 0x00000008,
            NETBINDENABLE = 0x00000009,
            NETBINDDISABLE = 0x0000000A,
            DEVICEEVENT = 0x0000000B,
            HARDWAREPROFILECHANGE = 0x0000000C,
            POWEREVENT = 0x0000000D,
            SESSIONCHANGE = 0x0000000E
        }

        public enum SERVICE_STATE : uint
        {
            SERVICE_STOPPED = 0x00000001,
            SERVICE_START_PENDING = 0x00000002,
            SERVICE_STOP_PENDING = 0x00000003,
            SERVICE_RUNNING = 0x00000004,
            SERVICE_CONTINUE_PENDING = 0x00000005,
            SERVICE_PAUSE_PENDING = 0x00000006,
            SERVICE_PAUSED = 0x00000007
        }

        [Flags]
        public enum SERVICE_ACCEPT : uint
        {
            STOP = 0x00000001,
            PAUSE_CONTINUE = 0x00000002,
            SHUTDOWN = 0x00000004,
            PARAMCHANGE = 0x00000008,
            NETBINDCHANGE = 0x00000010,
            HARDWAREPROFILECHANGE = 0x00000020,
            POWEREVENT = 0x00000040,
            SESSIONCHANGE = 0x00000080,
        }

        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Auto, Pack = 4)]
        public struct TRUSTEE
        {
            IntPtr pMultipleTrustee; // must be null
            int MultipleTrusteeOperation;
            int TrusteeForm;
            int TrusteeType;
            [MarshalAs(UnmanagedType.LPStr)]
            string ptstrName;
        }

        [StructLayout(LayoutKind.Sequential, CharSet = CharSet.Auto)]
        public struct EXPLICIT_ACCESS
        {
            uint grfAccessPermissions;
            uint grfAccessMode;
            uint grfInheritance;
            TRUSTEE Trustee;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct SidIdentifierAuthority
        {
            [MarshalAs(UnmanagedType.ByValArray, SizeConst = 6, ArraySubType = UnmanagedType.I1)]
            public byte[] Value;
        }

        public struct TOKEN_PRIVILEGES
        {
            public UInt32 PrivilegeCount;
            [MarshalAs(UnmanagedType.ByValArray, SizeConst = ANYSIZE_ARRAY)]
            public LUID_AND_ATTRIBUTES[] Privileges;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct LUID_AND_ATTRIBUTES
        {
            public LUID Luid;
            public UInt32 Attributes;
        }

        [StructLayout(LayoutKind.Sequential)]
        public struct LUID
        {
            public uint LowPart;
            public int HighPart;
        }

        public struct GENERIC_MAPPING
        {
            UInt32 GenericRead;
            UInt32 GenericWrite;
            UInt32 GenericExecute;
            UInt32 GenericAll;
        }

        [Flags]
        public enum ACCESS_MASK : uint
        {
            DELETE = 0x00010000,
            READ_CONTROL = 0x00020000,
            WRITE_DAC = 0x00040000,
            WRITE_OWNER = 0x00080000,
            SYNCHRONIZE = 0x00100000,
            STANDARD_RIGHTS_REQUIRED = 0x000f0000,
            STANDARD_RIGHTS_READ = 0x00020000,
            STANDARD_RIGHTS_WRITE = 0x00020000,
            STANDARD_RIGHTS_EXECUTE = 0x00020000,
            STANDARD_RIGHTS_ALL = 0x001f0000,
            SPECIFIC_RIGHTS_ALL = 0x0000ffff,
            ACCESS_SYSTEM_SECURITY = 0x01000000,
            MAXIMUM_ALLOWED = 0x02000000,
            GENERIC_READ = 0x80000000,
            GENERIC_WRITE = 0x40000000,
            GENERIC_EXECUTE = 0x20000000,
            GENERIC_ALL = 0x10000000,
            DESKTOP_READOBJECTS = 0x00000001,
            DESKTOP_CREATEWINDOW = 0x00000002,
            DESKTOP_CREATEMENU = 0x00000004,
            DESKTOP_HOOKCONTROL = 0x00000008,
            DESKTOP_JOURNALRECORD = 0x00000010,
            DESKTOP_JOURNALPLAYBACK = 0x00000020,
            DESKTOP_ENUMERATE = 0x00000040,
            DESKTOP_WRITEOBJECTS = 0x00000080,
            DESKTOP_SWITCHDESKTOP = 0x00000100,
            WINSTA_ENUMDESKTOPS = 0x00000001,
            WINSTA_READATTRIBUTES = 0x00000002,
            WINSTA_ACCESSCLIPBOARD = 0x00000004,
            WINSTA_CREATEDESKTOP = 0x00000008,
            WINSTA_WRITEATTRIBUTES = 0x00000010,
            WINSTA_ACCESSGLOBALATOMS = 0x00000020,
            WINSTA_EXITWINDOWS = 0x00000040,
            WINSTA_ENUMERATE = 0x00000100,
            WINSTA_READSCREEN = 0x00000200,
            WINSTA_ALL_ACCESS = 0x0000037f
        }
    }
}
