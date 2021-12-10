#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import sys
from os import system


clrs = ['\033[1;34m',
        '\033[1;m',
        '\033[31m',
        '\033[0m',
        '\033[1;92m',
        '\033[01;m',
        '\033[94m',
        '\033[90m',
        '\033[91m',
        '\033[32m',
        '\033[92m',
        '\033[36m'
        ]


def colorize(input_text, color="", colored=True, status=""):

    if input_text is None:
        return ""

    if type(input_text) not in (str, unicode):
        input_text = str(input_text)

    STOP_COLOR = '\033[0m'


    if status:

        if status == "ERR":
            return "\r\033[91mERROR: \033[0m" + input_text if colored else "\rERROR: " + input_text

        elif status == "WAR":
            return "\r\033[93mWARNING: \033[0m" + input_text if colored else "\rWARNING: " + input_text

        elif status == "SUC":
            return "\r\033[32m[+]\033[0m" + input_text if colored else "\r[+]" + input_text

        elif status == "INF":
            return "\r\033[34m[*]\033[0m" + input_text if colored else "\r[*]" + input_text


    elif colored:

        if color == "LBLUE":
            return '\033[94m' + input_text + STOP_COLOR

        elif color == "LGREEN":
            return '\033[92m' + input_text + STOP_COLOR

        elif color == "LYELLOW":
            return '\033[93m' + input_text + STOP_COLOR

        elif color == "LRED":
            return '\033[91m' + input_text + STOP_COLOR

        elif color == "LCYAN":
            return '\033[96m' + input_text + STOP_COLOR

        elif color == "LVIOLET":
            return '\033[95m' + input_text + STOP_COLOR

        elif color == "BLUE":
            return '\033[34m' + input_text + STOP_COLOR

        elif color == "RED":
            return '\033[31m' + input_text + STOP_COLOR

        elif color == "GREEN":
            return '\033[32m' + input_text + STOP_COLOR

        elif color == "YELLOW":
            return '\033[33m' + input_text + STOP_COLOR

        elif color == "CYAN":
            return '\033[36m' + input_text + STOP_COLOR

        elif color == "GREY":
            return '\033[90m' + input_text + STOP_COLOR

    else:
        return input_text


def clear_screen():
    system('clear')



def pprint(text_msg, is_error=0):

    if is_error == 0:

        sys.stderr.write(text_msg)
        sys.stderr.flush()

    elif is_error == 1:

        sys.stdout.write(text_msg)
        sys.stdout.flush()

    else:

        ermsg = colorize("Invalid key for pprint(): {}\n".format(is_error), status="ERR")
        sys.stderr.write(ermsg)
        sys.stderr.flush()




gray = lambda y: y.replace("\033[1;34m","").replace("\033[1;m", "").replace("\033[31m", "").replace("\033[0m", ""
    ).replace("\033[1;92m", "").replace("\033[01;m", "").replace("\033[94m", "").replace("\033[90m", "").replace("\033[91m", ""
        ).replace("\033[32m", "").replace("\033[92m", "").replace("\033[36m", "")

# gray = lambda y: [y.replace(cl, "") for cl in clrs if cl in y]
