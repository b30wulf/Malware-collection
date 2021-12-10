#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from time import sleep
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode
from lib.ProcessThread import ParatProcessBar


class ParatWget:

    def __init__(self, conn, args, colors):

        self.conn     = conn
        self.args     = args
        self.colors   = colors


    def start(self):

        try:

            if len(self.args) == 0:
                pprint(
                    colorize(
                        "usage: wget http://google.com/file.any\n",
                        colored=self.colors,
                        status="INF"
                    ))
            else:
                text = colorize(
                    "downloading",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()

                url_address = self.args[0]

                if url_address.strip() != "":

                    start_download_from_url = "wget<#>" + url_address
                    self.conn.send(Encode(start_download_from_url))
                    response = str(Decode(self.conn.recv(4096)))

                    process_bar.Stop = True
                    sleep(0.2)
                    pprint(response) if self.colors else pprint(gray(response))

                else:
                    pprint(
                        colorize(
                            "No specified url.\n",
                            colored=self.colors,
                            status="ERR"
                        ))
        except:

            if process_bar:
                process_bar.Stop = True;
                sleep(0.2)

            pprint(
                colorize(
                    "Url error.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)
