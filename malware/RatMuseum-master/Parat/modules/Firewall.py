#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import argparse
from lib.ParatPrint import pprint, gray
from lib.ParatEncrypt import Encode, Decode


class ParatFirewall:

    def __init__(self, conn, args, colors):

        self.helps = ["-h", "--help"]
        self.args = args
        self.conn = conn
        self.colors = colors


    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.Firewall",
            usage        = "firewall [--argumet]",
            description  = "simple command for control windows firewall"
        )

        self.parser.add_argument(
            '-a', '--active',
            action       = "store_true",
            help         = "enable firewall"
        )
        self.parser.add_argument(
            '-d', '--deactive',
            action       = "store_true",
            help         = "disable firewall"
        )
        self.parser.add_argument(
            '-s', '--status',
            action       = "store_true",
            help         = "display firewall status"
        )



    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.helps:
            self.parser.print_help()

        else:

            try:
                argument = self.parser.parse_args(self.args)

                if argument.active:
                    self.conn.send(Encode("firewall<#>active"))
                elif argument.deactive:
                    self.conn.send(Encode("firewall<#>deactive"))
                elif argument.status:
                    self.conn.send(Encode("firewall<#>status"))

                response = Decode(self.conn.recv(4096))
                pprint("\n%s\n" % response) if self.colors else pprint("\n%s\n" % gray(response))

            except Exception as e:
                print e
