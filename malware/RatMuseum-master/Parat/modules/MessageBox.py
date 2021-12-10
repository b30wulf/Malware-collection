#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import argparse
from lib.ParatPrint import colorize, pprint
from lib.ParatEncrypt import Encode, Decode


class ParatMsgBox:

    def __init__(self, conn, args, colors):

        self.helps  = ["-h", "--help"]
        self.conn   = conn
        self.args   = args
        self.colors = colors


    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.MessageBox",
            usage        = "msgbox -m/--message TEXT [-a/--argument VALUE]",
            description  = "advanced tool for display message box"
        )

        self.parser.add_argument(
            '-m', '--message',
            required     = True,
            help         = "your message to show"
        )
        self.parser.add_argument(
            '-t', '--title',
            default      = "HACKER",
            help         = "message box title"
        )
        self.parser.add_argument(
            '-i', '--icon',
            choices      = ["information", "warning", "critical", "None"],
            default      = "None",
            help         = "message box icon"
        )
        self.parser.add_argument(
            '-b', '--button',
            choices      = ["ok", "okcncl", "yesno", "yesnocncl", "okhelp"],
            default      = "ok",
            help         = "message box button(s)"
        )



    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.helps:
            self.parser.print_help()

        else:

            try:

                argument = self.parser.parse_args(self.args)

                finall_command = "{}<#>{}<#>{}<#>{}<#>{}".format(
                                        "msgbox",
                                        argument.title,
                                        argument.message,
                                        argument.icon,
                                        argument.button,
                                    )
                self.conn.send(Encode(finall_command))
                result = Decode(self.conn.recv(4096))

                if "error" in result:
                    pprint(colorize(result, colored=self.colors, status="ERR"))
                else:
                    pprint(colorize(result, colored=self.colors, status="SUC"))

            except:
                pass
