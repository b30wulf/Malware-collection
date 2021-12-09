import os
import colorama
from shutil import which

colorama.init()
def banner():
    banner_one =  colorama.Fore.LIGHTGREEN_EX + r"""
    Black Vision Remote Access                   
    """ + colorama.Style.RESET_ALL

    return banner_one

def clear():
    if(os.name == "nt"):
        os.system("cls")
    else:
        os.system("clear")

def exeCheck(path):
    print("[~] Checking if "+str(path)+" is installed.")
    p = which(path)
    if(p):
        print("[+] Found " + str(p))
        return True
    else :
        print("[-] Not Installed.")
        return False






