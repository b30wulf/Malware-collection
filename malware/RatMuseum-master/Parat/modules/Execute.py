#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from lib.ParatPrint import colorize, pprint
from lib.ParatEncrypt import Encode, Decode

class ParatWinShell:

    def __init__(self, conn, command, colors):

        self.colors  = colors
        self.conn    = conn
        self.command = command


    def start(self):

        def print_current_path():

            self.conn.send(Encode("ENTER"))
            response = Decode(self.conn.recv(4096))
            pprint(response)


        self.conn.send(Encode(self.command))
        pprint("\n")

        while True:

            try:

                prompt = Decode(self.conn.recv(4096))
                self.command = raw_input(prompt)

                if len(self.command) != 0:

                    if self.command != "exit":
                        self.conn.send(Encode(self.command))
                        response = Decode(self.conn.recv(4096))
                        pprint(response)

                    else:
                        self.conn.send(Encode(self.command))
                        pprint("\n"); break
                else:
                    print_current_path()

            except EOFError:
                print_current_path()

            except Exception as e:
                # import traceback; traceback.print_exc()
                pprint(
                    colorize(
                        str(e) + '\n',
                        colored=self.colors,
                        status="ERR"
                    ), 1)
                break
