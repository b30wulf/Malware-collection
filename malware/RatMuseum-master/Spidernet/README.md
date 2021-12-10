Proof of Concept of SSH Botnet C&C Using Python 

Original Concept:
http://raidersec.blogspot.com/2013/07/building-ssh-botnet-c-using-python-and.html

This tool is meant for academic use only.  It is not meant for use on systems the operator does not have consent to operate on.  The author of this code takes no responsibility for its use.  Please do not use this system publicly, unless you contact the author first.

      _________      .__    .___                          __   
     /   _____/_____ |__| __| _/___________  ____   _____/  |_ 
     \_____  \\____ \|  |/ __ |/ __ \_  __ \/    \_/ __ \   __\
     /        \  |_> >  / /_/ \  ___/|  | \/   |  \  ___/|  |  
    /_______  /   __/|__\____ |\___  >__|  |___|  /\___  >__|  
            \/|__|           \/    \/           \/     \/      
            
            

        
Usage: python spidernet.py
    
    #> python spidernet.py 
    [0] Connect Hosts
    [1] Update Hosts
    [2] List Hosts
    [3] Host Details
    [4] Run Command
    [5] Open Shell
    [6] Exit
    #> 0
    ----------Active Hosts-------------
    Host: 192.168.1.254:22 (root/password)
    Active: True
    -----------------------------------
    [0] Connect Hosts
    [1] Update Hosts
    [2] List Hosts
    [3] Host Details
    [4] Run Command
    [5] Open Shell
    [6] Exit
    #> 3
    -----------------------------------
    ----------Hosts Details------------
    [+] Host           : 192.168.1.254:22 (root/password)
     - Active          : True
     - R - Hostname    : testhost00
     - R - IP Address  : 192.168.1.254
     - R - User        : root
     - uname           : Linux testhost00 3.10.19+ #600 PREEMPT Sat Nov 16 20:34:43 GMT 2013 armv6l GNU/Linux
     - uptime          : 00:27:04 up 3 days,  4:12,  1 user,  load average: 1.20, 1.28, 1.16
    -----------------------------------
    [0] Connect Hosts
    [1] Update Hosts
    [2] List Hosts
    [3] Host Details
    [4] Run Command
    [5] Open Shell
    [6] Exit
    #> 4
    -----------------------------------
    Command: uname -a
    192.168.1.254 : Linux testhost00 3.10.19+ #600 PREEMPT Sat Nov 16 20:34:43 GMT 2013 armv6l GNU/Linux
    -----------------------------------
    [0] Connect Hosts
    [1] Update Hosts
    [2] List Hosts
    [3] Host Details
    [4] Run Command
    [5] Open Shell
    [6] Exit
    #> 
