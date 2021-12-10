# CrunchRAT
CrunchRAT currently supports the following features:
* File upload
* File download
* Command execution

It is currently single-threaded (only one task at a time), but multi-threading (or multi-tasking) is currently in the works. Additional features will be included at a later date.

## Server
The server-side of the RAT uses PHP and MySQL. The server-side of the RAT has been tested and works on the following:
* Ubuntu 15.10 (Desktop or Server edition)
* Ubuntu 16.04 (Desktop or Server edition)

Once the latest RAT code has been downloaded, there will be three directories:
* Client - Contains implant code (ignore for the this section)
* Server - Contains server code
* Setup - Contains setup files

### Dependencies Setup
1. Within the `Setup` directory, there are two dependencies setup shell scripts. If you are using Ubuntu 15.10 run `sh 15_10_dependencies.sh`, and if you're using Ubuntu 16.04 run `sh 16_04_dependencies.sh`. **Note: This needs to be run as root**. Failure to run with root privileges will result in an error.
2. When asked for a new MySQL root password, please choose one that is complex. This information is needed at a later step.

### HTTPS Setup
1. CrunchRAT uses a self-signed certificate to securely communicate between the server and implant. Run the `https_setup.sh` shell script with the `Setup` directory to automate the HTTPS setup. **Note: This needs to be run as root**. Failure to run with root privileges will result in an error. When asked to fill out the certificate information (Country Name, etc), please fill out all information. Snort rules already exist to alert on the dummy OpenSSL certificates. Don't be that guy that gets flagged by not filling out this information.

### Database Setup
1. Run the `database_setup.sh` shell script within the `Setup` directory to setup the MySQL database.
2. CrunchRAT creates a default RAT account with the `admin:changeme` credentials. Please log into the web end of the RAT and change the default password. Once logged into the web end of the RAT, go to `Account Management`--> `Change Password` to successfully change the default password to something more complex. Additional RAT users can be provisioned using `Account Management` --> `Add Users`.

### Miscellaneous Setup
1. Copy all files from the `Server` directory to the webroot.
2. You will want to create a `downloads` directory as well. **Note: It is absolutely critical that you don't put this folder in the webroot**. I typically create this directory in the `/home/<USERNAME>` directory. You will want to make sure that `www-data` can access this directory with the following command `sudo chown www-data:www-data downloads`. This directory will store all of the files downloaded from the infected system(s).
3. In the webroot, open the `config/config.php` file. This is the main RAT configuration file. Make sure that you update all of the variables (downloadsPath, dbUser, dbPass, etc) to match your environment.

## Client
CrunchRAT is written in C# for simplicity. The C# binary does not have a persistence mechanism in place, but plans to write a C++ stager are currently in the works.

Targeted Framework: .NET Framework 3.5 (enabled by default on Windows 7 systems)

1. Create a new console project in Visual Studio
2. Copy implant.cs code from `Client` directory and add it to the project.
3. Change `Output Type` to `Windows Application` (this will hide the command window) (`Project` --> `Properties` --> `Output Type`).
4. Make sure `Target Framework` is `.NET Framework 3.5`.
5. In the actual code, there will be a variable called `c2` - Change this variable to the IP address or domain name of the C2 server
6. Compile and your implant executable is ready to run.

## Thanks
Special thanks to the following people for helping me along the way:
* Michael Bailey
* Peter Kacherginsky
* Andy Rector
* Cole Hoven
* Erik Barzdukas
* Nick Gordon
* Chris Truncer
