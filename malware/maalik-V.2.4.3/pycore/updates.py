"""
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
"""

import requests
from colorama import Fore, Style
from .other import clear_screen
import time

def current_version():
    with open("VERSION.md", "r") as versionf:
        data = versionf.read()
    return data


def get_version():
    try:
        status = requests.get("https://raw.githubusercontent.com/quantumcored/maalik/master/VERSION.md") # Change this link later when repository is public
        data = status.content
        return data.decode()
    except Exception as e:
        return "Error checking for update : " + str(e)


def NotifyUpdates():
    x = get_version()
    if(x.startswith("Error")):
        print(x)
        time.sleep(2)
        pass
    else:
        if(x in current_version()):
            # print(Style.BRIGHT + Fore.GREEN + "[ OK ] " + Style.RESET_ALL + " Maalik is at latest version.")
            pass
        else:
            t = 0
            while(True):
                print(Style.BRIGHT + Fore.LIGHTBLUE_EX + "[ Update Available ] New Maalik Version is available.")
                print(x)
                print("\n-------------\n[+] Starting in 10 Seconds.. {x}.".format(x = t))
                time.sleep(1)
                t += 1
                if(t >= 10):
                    break
                clear_screen()
