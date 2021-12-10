try
{
    Koadic.http.upload("~RFILE~", "data");
}
catch (e)
{
    Koadic.work.error(e);
}

Koadic.exit();
