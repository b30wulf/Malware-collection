#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
import sys
import sqlite3
import hashlib
import signal
import zlib

from time import sleep
from getpass import getpass
from conf import config_file, path_to_db
from lib.ParatPrint import colorize, pprint



class ParatLogin:

    def __init__(self):

        self.status      = True if config_file().get("base", "creds").lower() == "on" else False
        self.colors      = True if config_file().get("cmd", "colors").lower() == "on" else False
        self.login_md5   = hashlib.md5()
        self.create_md5  = hashlib.md5()


    def check(self):
        try:
            self.status = True if config_file().get("base", "creds").lower() == "on" else False
            return self.status
        except Exception as e:
            return self.status



    def reverse_count(self, n):

        signal.signal(signal.SIGINT, signal.SIG_IGN)

        for i in range(n, 0, -1):

            sys.stdout.write("\rEnter after: %ss" % i)
            sys.stdout.flush()
            sleep(1)

        sys.stdout.write("\n")



    def encrypt(self, pfile):


        if pfile.endswith(".py") or \
          pfile.endswith(".pyc") or \
          pfile.endswith(".ini") or \
          pfile.endswith(".sqlite") or \
          "/docs/" in pfile or \
          pfile.endswith(".Parat_history") or \
          pfile.endswith("COPYING") or \
          pfile.endswith("pass.network") or \
          pfile.endswith("telgram.service") or \
          pfile.endswith(".encrypted"):
            pass


        else:

            with open(pfile, "rb") as old_file:

                with open(pfile + ".encrypted", "wb") as new_file:
                    new_file.write(zlib.compress(old_file.read(), 9).encode("base64"))
            os.remove(pfile)




    def decrypt(self, pfile):

        if pfile.endswith(".encrypted"):

            with open(pfile, "rb") as old_file:

                with open(pfile.replace(".encrypted", ""), "wb") as new_file:
                    new_file.write(zlib.decompress(old_file.read().decode("base64")))

            os.remove(pfile)



    def prompt(self):

        conn = sqlite3.connect(path_to_db(), check_same_thread=False)
        conn.execute('CREATE TABLE if not exists "admin" ("ID"  INT  PRIMARY KEY  NOT NULL, "password"  NOT NULL)')

        cur = conn.cursor()
        cur.execute("SELECT * FROM admin")
        rows = cur.fetchone()

        if not rows:

            try:
                while True:

                    create = raw_input(colorize("No password found. Do you want to create now(Y/n)?", colored=self.colors, status="WAR"))
                    create = create.lower().strip()

                    if create == "yes" or create == "y" or create == "":

                        passwd = getpass("Please enter your password: ")
                        self.create_md5.update(passwd)

                        cur.execute("INSERT INTO admin (ID, password) VALUES (1, '{}')".format(self.create_md5.hexdigest()))
                        conn.commit()

                        pprint(colorize("Updated successfully!\n", colored=self.colors, status="SUC"))
                        pprint(colorize("You can change your password later.\n", colored=self.colors, status="INF"))
                        self.reverse_count(9)

                        return True

                    elif create == "no" or create == "n":
                        return True

                    else:
                        pass

            except:
                pass

        else:

            try:

                passwd = getpass()
                self.login_md5.update(passwd)
                password_hash = self.login_md5.hexdigest()

                if rows[1] == password_hash:
                    pprint(
                        colorize(
                            " Login success!\n",
                            colored=self.colors,
                            status="SUC"
                    ))
                    for path, folders, files in os.walk(os.path.abspath('')):
                        [self.decrypt('/'.join([path, f])) for f in files]

                    return True

                else:
                    pprint(
                        colorize(
                            "Invalid password!\n",
                            colored=self.colors,
                            status="ERR"
                    ), 1)
                    for path, folders, files in os.walk(os.path.abspath('')):
                        [self.encrypt('/'.join([path, f])) for f in files]

            except KeyboardInterrupt:
                pprint("\nInterrupted!\n")

            except Exception:
                pass

        return False
        conn.close()
