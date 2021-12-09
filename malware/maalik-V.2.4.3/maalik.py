#!/usr/bin/python3

"""
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
"""

from pycore.core import *
from pycore.updates import *

def main():
    clear_screen()
    try:
        NotifyUpdates()
    except KeyboardInterrupt:
        pass
    
    Console()
    

main()
