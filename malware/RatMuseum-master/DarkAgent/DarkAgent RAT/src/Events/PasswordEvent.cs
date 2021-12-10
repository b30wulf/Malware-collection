using System;
using System.Collections.Generic;
using System.Text;
using DarkAgent_RAT.src.Objects;

namespace DarkAgent_RAT.src.Events
{
    public delegate void GetPasswordHandler(object o, PasswordEventArgs e);

    public class PasswordEventArgs : EventArgs
    {
        public readonly PasswordInfo passwordInfo;
        public readonly string RemoteIP;

        public PasswordEventArgs(PasswordInfo PasswordInfo, string ip)
        {
            this.passwordInfo = PasswordInfo;
            this.RemoteIP = ip;
        }
    }

    public class PasswordEvent
    {
        public static event GetPasswordHandler passwords;
        public static void OnGetPasswords(PasswordEventArgs e)
        {
            if (passwords != null)
                passwords(new object(), e);
        }
    }
}