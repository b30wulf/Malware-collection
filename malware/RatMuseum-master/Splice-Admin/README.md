Splice Admin
============
Splice Admin is a remote Windows administration tool.  It allows you to retrieve information and interact with remote machines on your network.


###### (Latest version is v2017.1213, released on December 13, 2017)
### [Click here to download the latest .exe](https://github.com/R-Smith/Splice-Admin/releases/download/v2017.1213/SpliceAdm.exe)
### [Click here to download the source](https://github.com/R-Smith/Splice-Admin/archive/master.zip)

##### Notes
* There is no installer.  Just run the .exe.
* .NET 4.0 or greater is required on the machine running Splice Admin.
* Most features require WMI to be running on the target computer.
* Local administrative rights are required on the target computer.

[![Join the chat at https://gitter.im/Splice-Admin/Lobby](https://badges.gitter.im/Splice-Admin/Lobby.svg)](https://gitter.im/Splice-Admin/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)


### Changes for v2017.1213
* New section: Networking.  This section includes a new remote netstat feature which gives the ability to view all TCP/UDP listening and established connections on the target computer.
* Details for network adapters was moved from the System Info section to the new Networking section.

**Check out the new feature under Networking -> Netstat.**  This is just like running the netstat command, but on a remote computer.  View all established connections as well as which ports that are open and which process is listening on a given port.  I've been sitting on this feature for a while and finally decided to push it out.  It'll eventually improve to include process names and paths, resolving IPs to hostnames, filtering, refreshing, and better sorting.


### Changes for v2017.1107
* Tasks -> Group Policy Update: GPupdate was only updating the computer policy on remote computers.  It now updates the user policy as well.  To achieve this, Splice Admin does several tricks.  1) Create a temporary .VBS file to run gpupdate.exe in a hidden window.  2) Get a list of logged in users.  3) For each user, create a scheduled task that executes the .VBS script (this will execute in the context of each logged in user).  4)  Run the task and then remove the temporary file and task.
* Bulk Query:  You can now use wildcards when searching for files.   For example: C:\Windows\\*.exe


### Older changes
* Bug fix: Corrected LDAP queries used in the Bulk Query view.
* The Processes, Services, and Applications views now include a filter feature.  Type in a search phrase and the results are automatically filtered as you type.
* Bug fix: In the Bulk Query view, targeting 'All domain computers' no longer causes a crash.
* Bulk query:  Added the ability to search for registry values.  You can either retrieve a specific value, or you can search for a key and all values within that key will be returned.  For example, searching for hklm\software\microsoft\windows\currentversion\run, would return all startup applications for every computer in your target list.  Target operating system must be Vista/2008 or higher at this time.
* Completely revamped the bulk query feature.  Rather than a simple 'match' or 'no match', searches now include detailed results.  For example, if searching for installed applications and your search phrase is 'adobe', you will get a list which includes the full title of every application with 'adobe' in the name, as well as the version number, for every computer in your target list.   When searching services, search results will include the startup type and the current status of the service.
* Bulk query:  The pre-defined target computer groups now exclude disabled computers.
* Bulk query:  New option to only include computers that have been active in the past 30 days.
* Bulk query:  Added the ability to search for running processes.
* Applications:  Remote applications were previously retrieved through the Remote Registry service.  The registry values are now retrieved through WMI which results in faster data retrieval.  The application will fall back to using the Remote Registry service if the target OS does not support the WMI registry provider.
* Bulk query:  Searching for applications now also goes through the WMI registry provider with a fallback to remote registry.  This results in faster application searches.
* Added: Tasks -> Windows Remote Assistance.  A shortcut to connect to the target computer using the Windows Remote Assistance tool.  This will only work if your environment is configured to allow unsolicited remote assistance.


Features
========
All features apply to remote Windows machines on your network.
* System Information
  * Get computer name, operating system, and uptime.
  * Get PC manufacturer, model, CPU and memory information.
  * Show connected USB devices.
  * Retrieve detailed settings for all connected network adapters.
  * Retrieve and edit system ODBC DSNs.
* Processes
  * Show all running processes along with their PID, owner, and path.
  * Color coding helps you spot user processes versus system processes.
  * Terminate any process (accessed via right-click).
* Services
  * Show all runing services along with their current status and startup type.
  * Color coding helps you spot different startup type and statuses.
  * Start and stop any service (accessed via right-click).
* Storage
  * Display all connected storage devices and their capacity information.
* Applications
  * Show all installed applications.
* Updates
  * Display all installed updates along with their installation date (if available) and KB article links.
  * Show if there is a pending required reboot.
  * Display update configuration (automatic or manual), the date and time updates were last checked (XP - 8.1), and the date and time updates were last installed (XP - 8.1).
  * Uninstall any update (accessed via right-click, Vista/2008 or higher).
* Users
  * Show all logged on users along with their login date/time, IP address (if available), and session ID.
  * Send a message to **any** user and logoff any user (accessed via right-click).
  * Retrieve basic details about the user from Active Directory (accessed via right-click).
  * Show a history of all logons and logoffs along with their date/time, IP address (if available), and whether it's an interactive or RDP connection.  Vista/2008 or higher.  Logon history depends on event logs so if the target isn't logging this information, you won't see it.
* Misc
  * Reboot / shutdown target computer.
  * Force a group policy update.
  * Execute any command or script on the target computer.  You do not get the output, the command is simply executed.
  * Launch various MMCs targeting the selected computer (Event viewer, computer management, services).


Screenshots
===========
##### Overview
![Overview](https://github.com/R-Smith/supporting-docs/raw/master/Splice-Admin/spliceadm-overview.png?raw=true "Overview")

##### Processes
![Processes](https://github.com/R-Smith/supporting-docs/raw/master/Splice-Admin/spliceadm-processes.png?raw=true "Processes")

##### Storage
![Storage](https://github.com/R-Smith/supporting-docs/raw/master/Splice-Admin/spliceadm-storage.png?raw=true "Storage")
