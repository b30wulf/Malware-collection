#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
import ConfigParser
from time import sleep
from string import ascii_letters
from random import choice
from lib.ParatPrint import colorize, pprint
from core.ParatError import *
from conf import config_file
from lib.ProcessThread import ParatProcessBar


config = config_file()

def tumultuous(conn, handler, colors, name):

    try:

        text = colorize(
            "Checking for data",
            colored=colors,
            status="INF"
        )
        process_bar = ParatProcessBar(text)
        process_bar.start_process()

        conn.settimeout(3)
        data = conn.recv(4096)
        rands = ''.join(choice(ascii_letters) for _ in range(5))
        handled_file  = "handled_%s_%s.log" % (name, rands)
        data_handler  = open(handled_file, "w")

        while data:
            if handler in data: break
            data_handler.write(data)
            data = conn.recv(4096)

        process_bar.Stop = True
        sleep(0.2)

        pprint(
            colorize(
                "Handler file: %s\n" % handled_file,
                colored=colors,
                status="SUC"
            )
        )
        data_handler.close()


    except Exception as e:

        if "[Errno 9]" in str(e):
            pass # <socket.error> Bad file descriptor

        elif "timed out" in str(e):
            process_bar.Stop = True
            sleep(0.2)
            pprint(
                colorize(
                    "No specify data!\n",
                    colored=colors,
                    status="SUC"
                ), 1)
        else:
            process_bar.Stop = True
            sleep(0.2)
            pprint(
                colorize(
                    str(e)+"\n",
                    colored=colors,
                    status="ERR"
                ), 1)
