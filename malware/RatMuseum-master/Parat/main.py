#!/usr/bin/env python2
# -*- coding: UTF8 -*-
#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from sys import version_info
from lib.Completer import auto_complete
from core.Shell import start_loop
from core.Creds import ParatLogin


if version_info[0] != 2:
    current_version = '.'.join(map(str, list(version_info[0:3])))
    exit("Parat only support Python 2.x -> Found: %s" % current_version)



if __name__ == '__main__':

    # check parat login system
    success   = False
    login_obg = ParatLogin()
    is_login  = login_obg.check()


    if is_login:

        success = login_obg.prompt()

        if success:
            start_loop(login_obg)

    else:
        start_loop(login_obg)
