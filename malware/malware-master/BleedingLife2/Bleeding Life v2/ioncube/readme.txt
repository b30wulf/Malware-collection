                      The ionCube Loader - Version 3.1
                      --------------------------------

This package contains the latest available Loaders for the PHP versions and
operating systems that you selected.

In the package you should have:

* Loader(s)

* The Loader installation helper script (ioncube-loader-helper.php)

* A file encoded with the ionCube encoder, needed by the helper script (ioncube-encoded-file.php)

* License for use of the Loader and encoded files (LICENSE)


Installation
------------

* INSTALLING FOR RUN-TIME LOADING

The run-time loading feature is the easiest way to run encoded files, and
lets encoded files locate and install the correct Loader when needed. 

Provided that run-time loading is supported on the target system, encoded
scripts and Loaders can be bundled together without the user having to install
any Loader or update their system configuration, and without having to
know about the PHP version or operating system used.

For run-time loading to work, a directory called 'ioncube' containing the
Loaders (e.g. this directory) should be placed in or above the top
directory of encoded files. For example, if you have encoded files in
or below '/var/www/htdocs/', you might place the 'ioncube' directory
in '/var/www/htdocs' or '/var/www'.  If you have an application or library
to distribute, you could place the ioncube directory within the top directory
of your project or library.

It's not working - why?
-----------------------

If encoded files fail to run with run-time loading, you can test this by
using the helper PHP script 'ioncube-loader-helper.php' that's included in this package.

1. Copy the 'ioncube-loader-helper.php' and 'ioncube-encoded-file.php' PHP scripts
   to a directory where you expect encoded files to be working.

2. Access the 'ioncube-loader-helper.php' script from a web server or with a 
   PHP cli or cgi executable.

3. Choose the 'Run-time loading installation instructions' option.

4. The script will try to locate and install the required Loader, and will
   produce output as it runs.

5. Once complete the script will either report that run-time loading is working, 
   will provide instructions for how to correct any issue with the server configuration, 
   or will report that Loaders must be installed in the php.ini file.


* INSTALLING IN PHP.INI

Installing in the php.ini file is also simple, and offers the best
performance for encoded scripts. It is also required for systems that use
safe mode, or if PHP has been built with thread support, e.g. on Windows.

The ' php.ini installation instructions' option on the installation helper PHP 
script is provided to assist with this. Access the script from a web server 
or a PHP cli or cgi executable and it should tell you which Loader to install, 
which file to edit and what you need to add  (it's just a one line change).


Copyright (c) 2002-2006 ionCube Ltd.                  Last revised 11-Jan-2006
