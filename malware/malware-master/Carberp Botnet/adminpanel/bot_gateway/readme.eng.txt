Installation
Loading gateway to a server in the domain root folder (s) or IP.
Make a folder: chmod 777 *-R
Open the file includes / url.cfg
It is inserted domain or IP (if the admin has access to IP) admin.
For example, a domain admin blablabla.com, then the file you want to insert the line:
http://blablabla.com/
All setting at the gateway is complete.
Now it remains to adjust the admin to update the config on the gateway is a change in admin.
In most admin go to configuration section, there we have the "Links to the gateway"
In this field you insert the domain / s or IP gateway.
For example, we have a gateway with IP access such as 127.0.0.1
Then in the box, simply put 127.0.0.1 and click save.
If we have 2 or more gateways then you need to put a comma IP gateways or domains.
For example: 127.0.0.1,127.0.0.2, gw.com
We must note that the IP or domain
should be put without http:// characters / etc
You also need to take into account that, as the admin and the default gateway should be able to work without SSL.
Next to check if everything is configured correctly, go to the admin section "Bots" and there, under "configs"
And there, click "re-encrypt". And wait until process your request.
After  going to the server where the admin panel in the folder "cfg /" and  where the gateway to the folder "cfg /", and compare files.
At the gateway must be all the files that are in the admin with the extension. Psd. Tiff. Bmp and not any others.
It is necessary to take into account that the name that in the admin, which is the gateway the same.
If the files do not appear here or firewall where something is blocking or domains and IP set wrong.