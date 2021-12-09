CRIMEPACK 3.1.3

**** NEW INSTRUCTIONS IN 3.1.1 READ CAREFULLY ****


Installation instructions


Requirements:

Root access

Linux, PHP5, MySQL, libcurl, ioncube

Step 1. Install LAMP 

If you are on Debian, Write and you're set to go on that area

aptitude install php5 php5-gd php5-cgi php5-mysql mysql-server mysql-client php5-curl lynx php-pear ca-certificates xml-core apache2 libapache2-mod-php5

* You will need root access to run the /deny-ip.sh *


Step 2. *

 1. chmod +x deny-ip.sh
 2. /deny-ip.sh

* This is optional, but i'd advice you to do it to prevent analysers from finding your pack easily

Step 3.

Install webdav

a2enmod dav_fs
a2enmod dav
mkdir /var/www/webdav
edit /etc/apache2/sites-available/default (or whatever config you wish to edit)

Add:

Alias /webdav /var/www/webdav
<Location /webdav>
DAV On
</Location>

*******
HAVING WEBDAV IS A SECURITY RISK AND CAN ALLOW FILE UPLOADING (SHELLS!) 

REMEMBER TO SECURE THE WEBDAV DIR & CHOWN IT TO ROOT!

**** IF YOU GET YOUR PACK STOLEN, FOR EXAMPLE DUE TO LACK OF SECURITY WHEN HAVING WEBDAV OPEN, WILL RESULT IN YOU GETTING NO MORE SUPPORT, NO MORE UPDATES ****


*******

Move the 'data.jar' to your webdav folder

in the install.php, you will be asked to enter webdav address (ex. \\domain\webdav\data.jar)



Step 4. install ioncube loader

1. Check what architecture you got, command: 'arch' or 'uname -m' 

root@server:/# arch
x86_64

means we will get the x86-64 version of ionCube

http://www.ioncube.com/loaders.php

download the tar.gz, in my case

http://downloads2.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz

extract it to your www dir, and visit http://url.com/ioncube-loader-helper.php, follow the 'how to install using php.ini' instructions

Step 5.

Download the geoip file from http://ip-to-country.webhosting.info/downloads/ip-to-country.csv.zip

extract it to the crimepack dir

when done, chmod the crimepack dir to 777, and visit the 'install.php' file

Install password is 'password'

**** NOTE THAT I DO RECOMMEND RUNNING DEBIAN, IF YOU CHOOSE ANOTHER DISTRO YOU WILL NOT RECEIVE INSTALLATION SUPPORT ****

