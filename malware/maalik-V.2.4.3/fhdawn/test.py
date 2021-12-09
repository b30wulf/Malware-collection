
"""
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
"""
import socket
import _thread
import sys
import time
from colorama import Fore, Style
import colorama
import os

colorama.init()

log = []

def _recv(sock):
    while(True):
        data = sock.recv(1024).decode()
        log.append(data)
        print("---------------\n\n" + data)

def _wait_for_msg():
    # Wait 20 seconds for message 
    del log[:]
    x = 0
    while(x != 20):
        try:
            if(len(log) > 0):
                break
            time.sleep(1)
            x += 1
        except KeyboardInterrupt:
            break

def main():

    def message(sock):

        def send(m):
            sock.send(m.encode())

        def sendb(m):
            sock.send(m)

        def DLLTransfer(mfile=None):
            if(mfile == None):
                mfile = input("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] DLL Path : ")
            
            try:
                with open(mfile, "rb") as sendfile:
                    data = sendfile.read()
                    bufferst = os.stat(mfile)
                    print("["+Style.BRIGHT + Fore.LIGHTGREEN_EX + "+" + Style.RESET_ALL + "] File opened " + mfile + " ("+str(bufferst.st_size) + " bytes)" )
                    
                    send("fdll") # Send File Receive trigger for client
                    trigger = "maalikloader" + ":" + str(bufferst.st_size) 
                    time.sleep(1)
                    send(trigger) # Send Trigger
                    sendb(data) # Send file
                    print("["+Style.BRIGHT + Fore.LIGHTBLUE_EX + "*" + Style.RESET_ALL + "] Uploading file.")
                    _wait_for_msg()
            except FileNotFoundError:
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] File not found!?")
            except Exception as e:
                print("["+Style.BRIGHT + Fore.RED + "X" + Style.RESET_ALL + "] Error : " + str(e))
        while(True):
            data = input("--> ")
            # if(len(data) > 0):
            if(data == "fdll"):
                DLLTransfer()
            else:
                send(data)
                #_wait_for_msg()
            

    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM, socket.IPPROTO_TCP)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    server.setsockopt(socket.SOL_SOCKET, socket.SO_KEEPALIVE, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPIDLE, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPINTVL, 1)
    server.setsockopt(socket.SOL_TCP, socket.TCP_KEEPCNT, 5)

    host = "0.0.0.0"
    port = 421

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

    while(True):
        client, addr = server.accept()
        _thread.start_new_thread(_recv, (client,))
        message(client)

main()