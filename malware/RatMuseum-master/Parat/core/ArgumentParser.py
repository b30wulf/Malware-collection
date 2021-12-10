#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
import argparse
import threading
import hashlib
from lib.GetIP import get_lan_ip
from lib.ParatPrint import gray, colorize, pprint
from lib.ParatEncrypt import Encode
from lib.MiniUtils import disconnect_it


class ParatArgParse:

    def __init__(self, shell_self, wash):

        self.help_switch = ["--help", "-h"]
        self.shell_self = shell_self
        self.wash = wash


    def setting_method(self, args, password_db, config, conf_path, logger, colors):

        try:

            # build settings parser
            parser = argparse.ArgumentParser(
                prog         = "core.ArgumentParser",
                usage        = "setting --arg [value]",
                description  = "control parat main settings",
            )


            # add new arguments
            parser.add_argument(
                '-c', '--colors',
                choices      = ["off", "on"],
                help         = 'enable/disable parat colors'
            )
            parser.add_argument(
                '-v', '--verbose',
                choices      = ["off", "on"],
                help         = 'show errors details'
            )
            parser.add_argument(
                '-b', '--banner',
                choices      = ["off", "on"],
                help         = 'enable/disable parat banner'
            )
            parser.add_argument(
                '-k', '--creds',
                choices      = ["off", "on"],
                help         = 'enable/disable parat creds'
            )
            parser.add_argument(
                '-p', '--password',
                metavar      = 'PASS',
                help         = 'change login password'
            )
            parser.add_argument(
                '-r', '--reset',
                action       = "store_true",
                help         = 'remove commands history'
            )
            parser.add_argument(
                '-t', '--clear_logs',
                action       = "store_true",
                help         = 'remove logs history'
            )


            # control arguments
            if args is None or len(args) == 0 or args[0] in self.help_switch:
                parser.print_help()

            else:

                argument = parser.parse_args(args)


                if argument.colors:

                    config.set('cmd', 'colors', argument.colors)

                    with open(conf_path, 'wb') as confile:
                        config.write(confile)
                    confile.close()

                    logger.info("COLORS: " + argument.colors)

                    if argument.colors == "on":
                        pprint(colorize("Colors enabled.\n", colored=True, status="SUC"))
                    else:
                        pprint(colorize("Colors disabled.\n", colored=False, status="SUC"))



                if argument.verbose:

                    config.set('cmd', 'debug', argument.verbose)
                    with open(conf_path, 'wb') as confile:
                        config.write(confile)
                    confile.close()

                    logger.info("VERBOSE: " + argument.verbose)

                    if argument.verbose == "on":
                        pprint(colorize("Versbose mode enabled.\n", colored=colors, status="SUC"))
                    else:
                        pprint(colorize("Versbose mode disabled.\n", colored=colors, status="SUC"))




                if argument.banner:

                    config.set('cmd', 'display_banner', argument.banner)
                    with open(conf_path, 'wb') as confile:
                        config.write(confile)
                    confile.close()

                    logger.info("BANNER: " + argument.banner)

                    if argument.banner == "on":
                        pprint(colorize("Banner enabled.\n", colored=colors, status="SUC"))
                    else:
                        pprint(colorize("Banner disabled.\n", colored=colors, status="SUC"))



                if argument.creds:

                    config.set('base', 'creds', argument.creds)
                    with open(conf_path, 'wb') as confile:
                        config.write(confile)
                    confile.close()

                    logger.info("CREDS: " + argument.creds)

                    if argument.creds == "on":
                        pprint(colorize("Creds enabled.\n", colored=colors, status="SUC"))
                    else:
                        pprint(colorize("Creds disabled.\n", colored=colors, status="SUC"))



                if argument.password:

                    md5 = hashlib.md5()
                    md5.update(argument.password)
                    hash_password = md5.hexdigest()

                    password_db.execute("UPDATE admin SET password='%s' WHERE id=1" % hash_password)
                    password_db.commit()

                    logger.info("PASSWORD: changed to -> " + hash_password)
                    pprint(colorize("Password changed.\n", colored=colors, status="SUC"))



                if argument.reset:

                    os.system("echo  > .Parat_history")
                    logger.info("commands history reseted")

                    pprint(
                        colorize(
                            "Commands history cleaned successfully.\n",
                            colored=colors,
                            status="SUC"
                        ))


                if argument.clear_logs:

                    logs_path = conf_path.replace("config.ini", "logs/parat.log")

                    with open(logs_path, "w") as rlog: rlog.close()
                    logger.info("logs history reseted")

                    pprint(
                        colorize(
                            "logs history cleaned successfully.\n",
                            colored=colors,
                            status="SUC"
                        ))


        except: # Exception as error:
            pass # pprint(colorize("%s\n" % error, colored=colors, status="ERR"), 1)




    def listen_method(self, args, used_ports, ListenSync, logger, colored):

        try:

            # build listen parser
            parser = argparse.ArgumentParser(
                prog         = "core.ArgumentParser",
                usage        = "listen --arg [value]",
                description  = "set listenning ports",
            )

            # add new arguments
            parser.add_argument(
                '-s', '--show',
                action       = "store_true",
                help         = 'show active port(s)'
            )
            parser.add_argument(
                '-p', '--port',
                # choices      = range(1, 65535),
                help         = 'listen on specified port',
                type         = int
            )


            # control arguments
            if args is None or len(args) == 0 or args[0] in self.help_switch:
                parser.print_help()

            else:

                argument = parser.parse_args(args)


                if argument.show:

                    for t in threading.enumerate():

                        thread_name = t.getName()

                        # found listenning threads and get port(s)
                        if thread_name[:12] == "ListenThread":

                            listen_port = thread_name.split("_")[1]
                            pprint(
                                colorize(
                                    "Now listen on %s\n" % listen_port,
                                    colored=colored,
                                    status="INF"
                                ))
                        else:
                            pass


                if argument.port:

                    user_port = argument.port

                    # check for port is listenning or not
                    if used_ports.has_key(user_port):

                        if used_ports[user_port]:

                            if colored:
                                pprint(
                                    colorize(
                                        "Listenning on \033[96m%s\033[0m\n" % user_port,
                                        colored=colored,
                                        status="INF"
                                    ))
                            else:
                                pprint(
                                    gray(colorize(
                                        "Listenning on %s\n" % user_port,
                                        colored=colored,
                                        status="INF"
                                    )))
                        else:
                            pass
                    else:

                        if colored:
                            pprint(
                                colorize(
                                    "Start listen on \033[96m%s\033[0m\n" % user_port,
                                    colored=colored,
                                    status="INF"
                                ))
                        else:
                            pprint(
                                gray(colorize(
                                    "Listenning on %s\n" % user_port,
                                    colored=colored,
                                    status="INF"
                                )))

                        # if port not in use
                        used_ports[user_port] = 1

                        listen_thread = threading.Thread(
                            target=ListenSync.do_listen,
                            args=(
                                self.shell_self,
                                "SOCK_" + str(user_port),
                                user_port
                                ))

                        # do threading works
                        listen_thread.daemon = True
                        listen_thread.setName("ListenThread_" + str(user_port))
                        listen_thread.start()

                        logger.info("user: ListenThread_" + str(user_port))

        except: # Exception as error:
            pass # pprint(colorize("%s\n" % error, colored=colors, status="ERR"), 1)





    def generate_method(self, args, generator, config, port, logger, colors):

        try:

            platforms = generator.get_plats()
            arches    = generator.get_archs()

            # build settings parser
            parser = argparse.ArgumentParser(
                prog         = "core.ArgumentParser",
                usage        = "generate --arg [value]",
                description  = "generate server file for target"
            )

            # add new arguments
            parser.add_argument(
                '-s', '--show',
                action       = "store_true",
                help         = 'display setted values'
            )
            parser.add_argument(
                '-q', '--last',
                action       = "store_true",
                help         = 'generate server using last setted values'
            )
            parser.add_argument(
                '-i', '--host',
                metavar      = 'LHOST',
                help         = 'your specified ip/dns'
            )
            parser.add_argument(
                '-p', '--port',
                type         = int,
                metavar      = 'LPORT',
                help         = 'your specified port'
            )
            parser.add_argument(
                '-t', '--platform',
                choices      = platforms,
                help         = 'targets machine platform'
            )
            parser.add_argument(
                '-a', '--arch',
                choices      = arches,
                help         = 'targets machine archicture'
            )
            parser.add_argument(
                '-e', '--encode',
                action       = "store_true",
                help         = 'targets machine archicture'
            )
            parser.add_argument(
                '-o', '--output',
                help         = 'output file name'
            )
            parser.add_argument(
                '-d', '--path',
                help         = 'output file name'
            )
            parser.add_argument(
                '-v', '--scriptlet',
                metavar      = 'PY_FILE',
                help         = 'py-script that execute before start connection'
            )


            # control arguments
            if args is None or len(args) == 0 or args[0] in self.help_switch:
                parser.print_help()

            else:

                argument = parser.parse_args(args)

                FLAG = dict(
                    error = False,
                    host  = False,
                    port  = False,
                    show  = False
                )

                if argument.show:
                    FLAG['show'] = True
                    generator.show()

                if argument.host and argument.last or argument.port and argument.last:
                    FLAG['error'] = True

                if argument.last:

                    FLAG['host'], FLAG['port'] = True, True

                    osys = self.wash(config.get('base', 'current_platform'))
                    pprint(colorize("plat     : " + osys + "\n", colored=colors, status="SUC"))

                    arch_base = self.wash(config.get('base', 'current_arch'))
                    pprint(colorize("arch     : " + arch_base + "\n", colored=colors, status="SUC"))

                    lhost = self.wash(config.get('base', 'local_host'))
                    pprint(colorize("host     : " + lhost + "\n", colored=colors, status="SUC"))

                    lport = self.wash(config.get('base', 'local_port'))
                    pprint(colorize("port     : " + lport + "\n", colored=colors, status="SUC"))

                if argument.host and not argument.last:
                    generator.set_host(argument.host)
                    FLAG['host'] = True
                    pprint(colorize("host     : " + argument.host + '\n', colored=colors, status="SUC"))

                if argument.port and not argument.last:
                    generator.set_port(str(argument.port))
                    FLAG['port'] = True
                    pprint(colorize("port     : " + str(argument.port) + '\n', colored=colors, status="SUC"))

                if argument.platform:
                    generator.set_plat(argument.platform)
                    pprint(colorize("platform : " + argument.platform + '\n', colored=colors, status="SUC"))

                if argument.arch:
                    generator.set_arch(argument.arch)
                    pprint(colorize("arch     : " + argument.arch + '\n', colored=colors, status="SUC"))

                if argument.output:
                    generator.set_output(argument.output)
                    pprint(colorize("output   : " + argument.output + '\n', colored=colors, status="SUC"))

                if argument.path:
                    generator.set_path(argument.path)
                    pprint(colorize("path     : " + argument.path + '\n', colored=colors, status="SUC"))

                if argument.scriptlet:
                    generator.set_scriptlet(argument.scriptlet)
                    pprint(colorize("scriptlet: " + argument.scriptlet + '\n', colored=colors, status="SUC"))

                if argument.encode:
                    generator.set_encoding(argument.encode)
                    pprint(colorize("encoding : " + str(argument.encode) + '\n', colored=colors, status="SUC"))



                if not FLAG['error']:

                    if not FLAG['host'] and not FLAG['show']:
                        generator.set_host(get_lan_ip())
                        pprint(colorize("host     : " + get_lan_ip() + '\n', colored=colors, status="SUC"))

                    if not FLAG['port'] and not FLAG['show']:
                        generator.set_host(port)
                        pprint(colorize("port     : " + str(port) + '\n', colored=colors, status="SUC"))

                    generator.finally_generate()

                else:
                    pprint(
                        colorize(
                            "You can't use -d/--last and other one time\n",
                            colored=colors,
                            status="ERR"
                        ), 1)



        except: # Exception as error:
            pass # pprint(colorize("%s\n" % error, colored=colors, status="ERR"), 1)





    def sessions_method(self, args, connections, clients, client_counter, ClientShell, root_path, prompt_q, in_main_prompt, logger, colors):

        try:

            # build listen parser
            parser = argparse.ArgumentParser(
                prog         = "core.ArgumentParser",
                usage        = "sessions --arg [value]",
                description  = "control connected targets",
            )

            # add new arguments
            parser.add_argument(
                '-c', '--connect',
                metavar      = 'ID',
                type         = int,
                help         = 'control specified target'
            )
            parser.add_argument(
                '-r', '--remove',
                metavar      = 'ID',
                type         = int,
                help         = 'fully disconnect target'
            )
            parser.add_argument(
                '-l', '--list',
                action       = "store_true",
                help         = 'display information about sessions'
            )


            # control arguments
            if args is None or len(args) == 0 or args[0] in self.help_switch:
                parser.print_help()

            else:

                argument = parser.parse_args(args)


                if argument.connect:

                    try:

                        connection = connections[argument.connect][0]
                        ClientShell.ctrl_loop(self.shell_self, argument.connect, connection)

                    except KeyError:

                        pprint(
                            colorize(
                                "Client '%s' not found.\n" % argument.connect,
                                colored=colors,
                                status="ERR"
                            ), 1)


                if argument.remove:

                    try:

                        client_id = argument.remove
                        disconnect_it(
                            client_id,
                            connections,
                            clients,
                            root_path,
                            logger,
                            colors
                        )

                    except Exception as e:

                        pprint(
                            colorize(
                                "Client '%s' not found.\n" % argument.remove,
                                colored=colors,
                                status="ERR"
                            ), 1)


                if argument.list:

                    if client_counter != 0:

                        pprint(
                            colorize(
                                "\n  ID" + " "*6 + "Client" + " "*56 + "LPORT   RPORT\n",
                                colored=colors,
                                color="LRED"
                            ))
                        pprint(
                            colorize(
                                "  " + "="*4 + " "*4 + "="*56 + " "*6 + "="*5 + " "*3 + "="*5 + '\n',
                                colored=colors,
                                color="LRED"
                            ))

                        [pprint("  " + clients[cli]) for cli in clients]
                        pprint("\n")

                    else: # :client -> no:connection
                        pprint(
                            colorize(
                                "[-]",
                                colored=colors,
                                color="LBLUE"
                            ) + "not any zombie!\n")

        except: # Exception as error:
            pass # pprint(colorize("%s\n" % error, colored=colors, status="ERR"), 1)
