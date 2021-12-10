using Splice_Admin.Classes;
using System;
using System.Collections.Generic;
using System.Runtime.InteropServices;
namespace RemoteDesktopServicesAPI
{



    public class WtsApi
    {
        #region Dll Imports
        [DllImport("wtsapi32.dll", SetLastError = true)]
        public static extern bool WTSLogoffSession(
            IntPtr hServer,
            int SessionId,
            bool bWait);

        [DllImport("Wtsapi32.dll")]
        public static extern bool WTSQuerySessionInformation(
            IntPtr hServer,
            int SessionId,
            WTS_INFO_CLASS wtsInfoClass,
            out IntPtr ppBuffer,
            out uint pBytesReturned);

        [DllImport("wtsapi32.dll", SetLastError = true)]
        public static extern IntPtr WTSOpenServer(
            [MarshalAs(UnmanagedType.LPStr)] String pServerName);

        [DllImport("wtsapi32.dll")]
        public static extern void WTSCloseServer(
            IntPtr hServer);

        [DllImport("wtsapi32.dll", SetLastError = true)]
        public static extern Int32 WTSEnumerateSessions(
            IntPtr hServer,
            [MarshalAs(UnmanagedType.U4)] Int32 Reserved,
            [MarshalAs(UnmanagedType.U4)] Int32 Version,
            ref IntPtr ppSessionInfo,
            [MarshalAs(UnmanagedType.U4)] ref Int32 pCount);

        [DllImport("wtsapi32.dll")]
        static extern void WTSFreeMemory(
            IntPtr pMemory);

        [DllImport("kernel32.dll")]
        static extern uint WTSGetActiveConsoleSessionId();
        #endregion


        #region Structures
        //Structure for Terminal Service Client IP Address
        [StructLayout(LayoutKind.Sequential)]
        private struct WTS_CLIENT_ADDRESS
        {
            public int AddressFamily;
            [MarshalAs(UnmanagedType.ByValArray, SizeConst = 20)]
            public byte[] Address;
        }

        //Structure for Terminal Service Session Info
        [StructLayout(LayoutKind.Sequential)]
        private struct WTS_SESSION_INFO
        {
            public int SessionID;
            [MarshalAs(UnmanagedType.LPStr)]
            public string pWinStationName;
            public WTS_CONNECTSTATE_CLASS State;
        }
        #endregion


        #region Enumurations
        public enum WTS_CONNECTSTATE_CLASS
        {
            WTSActive,
            WTSConnected,
            WTSConnectQuery,
            WTSShadow,
            WTSDisconnected,
            WTSIdle,
            WTSListen,
            WTSReset,
            WTSDown,
            WTSInit
        }

        public enum WTS_INFO_CLASS
        {
            WTSInitialProgram,
            WTSApplicationName,
            WTSWorkingDirectory,
            WTSOEMId,
            WTSSessionId,
            WTSUserName,
            WTSWinStationName,
            WTSDomainName,
            WTSConnectState,
            WTSClientBuildNumber,
            WTSClientName,
            WTSClientDirectory,
            WTSClientProductId,
            WTSClientHardwareId,
            WTSClientAddress,
            WTSClientDisplay,
            WTSClientProtocolType,
            WTSIdleTime,
            WTSLogonTime,
            WTSIncomingBytes,
            WTSOutgoingBytes,
            WTSIncomingFrames,
            WTSOutgoingFrames,
            WTSClientInfo,
            WTSSessionInfo,
            WTSConfigInfo,
            WTSValidationInfo,
            WTSSessionAddressV4,
            WTSIsRemoteSession
        }
        #endregion



        public static List<RemoteLogonSession> GetWindowsUsers(IntPtr server)
        {
            var windowsUsers = new List<RemoteLogonSession>();

            IntPtr buffer = IntPtr.Zero;
            int count = 0;

            try
            {
                int retval = WTSEnumerateSessions(server, 0, 1, ref buffer, ref count);
                int dataSize = Marshal.SizeOf(typeof(WTS_SESSION_INFO));
                Int64 current = (int)buffer;

                if (retval != 0)
                {
                    for (int i = 0; i < count; i++)
                    {
                        var windowsUser = new RemoteLogonSession();
                        var bufferTwo = IntPtr.Zero;
                        uint bytesReturned = 0;

                        WTS_SESSION_INFO si = (WTS_SESSION_INFO)Marshal.PtrToStructure((IntPtr)current, typeof(WTS_SESSION_INFO));
                        current += dataSize;
                        windowsUser.SessionId = Convert.ToUInt32(si.SessionID);

                        try
                        {
                            // Get the username of the Terminal Services user.
                            if (WTSQuerySessionInformation(server, si.SessionID, WTS_INFO_CLASS.WTSUserName, out buffer, out bytesReturned) == true)
                                windowsUser.Username = Marshal.PtrToStringAnsi(buffer).Trim();
                            if (string.IsNullOrWhiteSpace(windowsUser.Username))
                                continue;

                            // Get the connection state of the Terminal Services user.
                            if (si.State == WTS_CONNECTSTATE_CLASS.WTSDisconnected)
                                windowsUser.IsDisconnected = true;

                            // Get the IP address of the Terminal Services user.
                            if (WTSQuerySessionInformation(server, si.SessionID, WTS_INFO_CLASS.WTSClientAddress, out buffer, out bytesReturned) == true)
                            {
                                var clientAddress = new WTS_CLIENT_ADDRESS();
                                clientAddress = (WTS_CLIENT_ADDRESS)Marshal.PtrToStructure(buffer, clientAddress.GetType());
                                windowsUser.IpAddress = clientAddress.Address[2] + "." + clientAddress.Address[3] + "." + clientAddress.Address[4] + "." + clientAddress.Address[5];
                            }

                            windowsUsers.Add(windowsUser);
                        }
                        finally
                        {
                            WTSFreeMemory(bufferTwo);
                        }
                    }
                }
            }
            finally
            {
                WTSFreeMemory(buffer);
            }


            return windowsUsers;
        }


        public static bool IsUserLoggedOn(IntPtr server, string queryUserName)
        {
            IntPtr buffer = IntPtr.Zero;
            int count = 0;
            bool isUserLoggedOn = false;

            try
            {
                int retval = WTSEnumerateSessions(server, 0, 1, ref buffer, ref count);
                int dataSize = Marshal.SizeOf(typeof(WTS_SESSION_INFO));
                Int64 current = (int)buffer;

                if (retval != 0)
                {
                    for (int i = 0; i < count; i++)
                    {
                        var bufferTwo = IntPtr.Zero;
                        uint bytesReturned = 0;

                        WTS_SESSION_INFO si = (WTS_SESSION_INFO)Marshal.PtrToStructure((IntPtr)current, typeof(WTS_SESSION_INFO));
                        current += dataSize;
                        //windowsUser.SessionId = Convert.ToUInt32(si.SessionID);

                        try
                        {
                            string loggedOnUser = string.Empty;

                            // Get the username of the Terminal Services user.
                            if (WTSQuerySessionInformation(server, si.SessionID, WTS_INFO_CLASS.WTSUserName, out buffer, out bytesReturned) == true)
                                loggedOnUser = Marshal.PtrToStringAnsi(buffer).Trim();
                            if (loggedOnUser.Equals(queryUserName, StringComparison.OrdinalIgnoreCase))
                            {
                                isUserLoggedOn = true;
                                break;
                            }
                        }
                        finally
                        {
                            WTSFreeMemory(bufferTwo);
                        }
                    }
                }
            }
            finally
            {
                WTSFreeMemory(buffer);
            }


            return isUserLoggedOn;
        }

        public static bool LogOffUser(IntPtr Server, int SessionId, string Username)
        {
            bool returnValue = false;
            IntPtr buffer = IntPtr.Zero;
            uint bytesReturned = 0;

            try
            {
                // Get the username of the Terminal Services user.
                if (WTSQuerySessionInformation(Server, SessionId, WTS_INFO_CLASS.WTSUserName, out buffer, out bytesReturned) == true)
                {
                    string user = Marshal.PtrToStringAnsi(buffer).Trim();
                    if (user.Trim().ToUpper() == Username.Trim().ToUpper())
                        returnValue = WTSLogoffSession(Server, SessionId, false);
                }
            }
            finally
            {
                WTSFreeMemory(buffer);
            }

            return returnValue;
        }


        public static List<int> GetSessionIDs(IntPtr server)
        {
            List<int> sessionIds = new List<int>();
            IntPtr buffer = IntPtr.Zero;
            int count = 0;
            int retval = WTSEnumerateSessions(server, 0, 1, ref buffer, ref count);
            int dataSize = Marshal.SizeOf(typeof(WTS_SESSION_INFO));
            Int64 current = (int)buffer;

            if (retval != 0)
            {
                for (int i = 0; i < count; i++)
                {
                    WTS_SESSION_INFO si = (WTS_SESSION_INFO)Marshal.PtrToStructure((IntPtr)current, typeof(WTS_SESSION_INFO));
                    current += dataSize;
                    sessionIds.Add(si.SessionID);
                }
                WTSFreeMemory(buffer);
            }
            return sessionIds;
        }

        //public static bool LogOffUser(string userName, IntPtr server)
        //{

        //    userName = userName.Trim().ToUpper();
        //    List<int> sessions = GetSessionIDs(server);
        //    Dictionary<string, int> userSessionDictionary = GetUserSessionDictionary(server, sessions);
        //    if (userSessionDictionary.ContainsKey(userName))
        //        return WTSLogoffSession(server, userSessionDictionary[userName], true);
        //    else
        //        return false;
        //}

        public static Dictionary<string, int> GetUserSessionDictionary(IntPtr server, List<int> sessions)
        {
            Dictionary<string, int> userSession = new Dictionary<string, int>();

            foreach (var sessionId in sessions)
            {
                string uName = GetUserName(sessionId, server);
                if (!string.IsNullOrWhiteSpace(uName))
                    userSession.Add(uName, sessionId);
            }
            return userSession;
        }

        public static string GetUserName(int sessionId, IntPtr server)
        {
            IntPtr buffer = IntPtr.Zero;
            uint count = 0;
            string userName = string.Empty;
            try
            {
                WTSQuerySessionInformation(server, sessionId, WTS_INFO_CLASS.WTSUserName, out buffer, out count);
                userName = Marshal.PtrToStringAnsi(buffer).ToUpper().Trim();
            }
            finally
            {
                WTSFreeMemory(buffer);
            }
            return userName;
        }
    }
}