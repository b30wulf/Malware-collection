from colorama import Fore, Style, init; init()
from terminaltables import DoubleTable
import os, threading, random

class Color:
    def __init__(self):
        # Styles
        self.invisible = '\033[30;40;196m'
        self.underline = '\033[4m'
        self.bright =  '\033[1;4m'
        self.reset =   '\033[0m'

        # Colors
        self.white   = self.rgb(255, 255, 255)
        self.magenta = self.rgb(249, 53, 248) 
        self.yellow  = self.rgb(216, 235, 52)
        self.orange  = self.rgb(255, 99, 71)
        self.blue_m  = self.rgb(88, 5, 255)
        self.green   = self.rgb(0, 255, 0)
        self.red     = self.rgb(255, 0, 0)

    def rgb(self, r: int, g: int, b: int):
        return '\033[38;2;<r>;<g>;<b>m'.replace('<r>', str(r)).replace('<g>', str(g)).replace('<b>', str(b))

    def fade(self, text: str):
        final = ''
        i= 0
        
        for char in text:
            i+= 4
            final += f'{self.rgb(200, 60, i)}{char}' # Original ~> 200, 60, i
        
        return final

class Console:
    def __init__(self):
        self.locker = threading.Semaphore(value= 1)

    def clear(self):
        os.system('cls' if os.name == 'nt' else 'clear')

    def __lock_print(self, text: str, past: str, color: Fore):
        self.locker.acquire()
        print(f'[{color}{past}{Fore.RESET}] {text}.')
        self.locker.release()

    def __format_text(self, text: str):
        return text.replace('->', f'{Fore.LIGHTCYAN_EX}->{Fore.RESET}').replace(':', f'{Fore.YELLOW}:{Fore.RESET}').replace('Error', f'{Fore.RED}Error{Fore.RESET}').replace('Invalid', f'{Fore.MAGENTA}Invalid{Fore.RESET}').replace('"', f'{Fore.LIGHTBLUE_EX}"{Fore.RESET}').replace('killed', f'{Fore.LIGHTRED_EX}killed{Fore.RESET}')

    def loader_banner(self):
        self.clear()
        print(Style.BRIGHT + Fore.WHITE + '''
         _    _ _                     _           
        | |  | | |                   | |          
        | |__| | |     ___   __ _  __| | ___ _ __ 
        |  __  | |    / _ \ / _` |/ _` |/ _ \ '__|
        | |  | | |___| (_) | (_| | (_| |  __/ |   
        |_|  |_|______\___/ \__,_|\__,_|\___|_|    github.com/Its-Vichy/HBot
        ''' + Style.RESET_ALL + Fore.RESET)
    
    def scanner_banner(self):
        self.clear()
        print(Style.BRIGHT + Fore.WHITE + '''
         _    _  _____                                 
        | |  | |/ ____|                                
        | |__| | (___   ___ __ _ _ __  _ __   ___ _ __ 
        |  __  |\___ \ / __/ _` | '_ \| '_ \ / _ \ '__|
        | |  | |____) | (_| (_| | | | | | | |  __/ |   
        |_|  |_|_____/ \___\__,_|_| |_|_| |_|\___|_|    github.com/Its-Vichy/HBot
        ''' + Style.RESET_ALL + Fore.RESET)
    
    def cnc_banner(self):
        self.clear()
        print(Style.BRIGHT + Fore.WHITE + '''
         _    _ ____        _   
        | |  | |  _ \      | |  
        | |__| | |_) | ___ | |_ 
        |  __  |  _ < / _ \| __|
        | |  | | |_) | (_) | |_ 
        |_|  |_|____/ \___/ \__|    github.com/Its-Vichy/HBot
        ''' + Style.RESET_ALL + Fore.RESET)
    
    def print_success(self, text: str):
        self.__lock_print(self.__format_text(text), 'SUCCESS', Fore.LIGHTGREEN_EX)
    
    def print_info(self, text: str):
        self.__lock_print(self.__format_text(text), 'INFO', Fore.LIGHTYELLOW_EX)
    
    def get_simple_table(self, name: str, emoji: str, items: list):
        data = ''

        for item in sorted(items, key=len, reverse= True):
            data += f'{item}\n'

        return DoubleTable([[f'{emoji} {name} ({len(items)}) {emoji}'], [data.strip()]]).table
    
    def get_custom_table(self, headers: list, row: list):
        return DoubleTable([headers, row]).table

    def raw_fade(self, text):
        data  = []

        for line in text.split('\n'):
            data.append('  ' + Color().fade(line.split('\n')[0]))
                    
        return data
    
    def bulk_fade(self, items: list):
        i= 0
        for item in items:
            items[i] = Color().fade(item)
            i+=1
        
        return items