try
{
    var computer = ".";
    var wmi = GetObject("winmgmts:{impersonationLevel=impersonate}!\\\\" + computer + "\\root\\cimv2");

    // https://msdn.microsoft.com/en-us/library/aa394189(v=vs.85).aspx
    var sessions = wmi.ExecQuery("Select * from Win32_LogonSession Where LogonType = 2 OR LogonType = 10");

    if (sessions.Count == 0)
    {
        Koadic.work.report("No interactive users found.")
    }
    else
    {
        for (var e = new Enumerator(sessions); !e.atEnd(); e.moveNext())
        {
            var session = e.item();

            var query = "";
            query += "Associators of {Win32_LogonSession.LogonId=" + session.LogonId;
            query += "} Where AssocClass=Win32_LoggedOnUser Role=Dependent";
            var users = wmi.ExecQuery(query);

            for (var f = new Enumerator(users); !f.atEnd(); f.moveNext())
            {
                var user = f.item();
                var info = "";
                info += user.Domain + "\n";
                info += user.SID + "\n";
                info += user.Caption + "\n";
                info += user.Description + "\n";
                info += user.LocalAccount + "\n";
                info += user.SIDType + "\n";
                info += user.Status + "\n";
                info += user.InstallDate + "\n";
                info += "--------\n";
                info += session.Caption + "\n";
                info += session.Description + "\n";
                info += session.InstallDate + "\n";
                info += session.Name + "\n";
                info += session.Status + "\n";
                info += session.StartTime + "\n";
                info += session.AuthenticationPackage + "\n";
                info += session.LogonId + "\n";
                info += session.LogonType + "\n";
                alert(info);
            }
        }
    }
} catch (e)
{
    Koadic.work.error(e);
}

Koadic.exit();
