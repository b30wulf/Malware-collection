                      The ionCube Loader 
                      ------------------

This package contains:

* the latest available Loaders for the platform(s) selected

* a Loader Wizard script to assist with Loader installation (loader-wizard.php)

* the License document for use of the Loader and encoded files (LICENSE)


INSTALLATION
------------

** Installing to a remote SHARED server

1. Upload the contents of this package to a directory/folder called ioncube
   within your main web scripts area. 

2. Launch the Loader Wizard script in your browser. For example:
     http://yourdomain/ioncube/loader-wizard.php

If the wizard is not found, carefully check the location where you uploaded
the Loaders and the wizard script to on your server.


** Installing to a remote UNIX/LINUX DEDICATED or VPS server

1. Upload the contents of this package to /usr/local/ioncube

2. Copy the loader-wizard.php script to the root web directory of a 
   configured domain on the server

2. Launch the Loader Wizard script in your browser. For example:
     http://yourdomain/loader-wizard.php


** Installing to a remote WINDOWS DEDICATED or VPS server

1. Upload the contents of this package to C:\windows\system32

2. Copy the loader-wizard.php script to the root web folder of a 
   configured domain on the server

2. Launch the Loader Wizard script in your browser. For example:
     http://yourdomain/loader-wizard.php


XCACHE and XDEBUG
-----------------

If you wish to enable *both* XCache and Xdebug whilst running the Loader, please
use XCache as an ordinary extension rather than a Zend engine extension. That
is, install XCache using the following line in the ini file and with xcache.so
in the extensions directory:  

extension = xcache.so

If you are only running XCache then it can be installed as a Zend engine 
extension when the Loader is also installed.


Copyright (c) 2002-2011 ionCube Ltd.                  Last revised 21-Jan-2011
