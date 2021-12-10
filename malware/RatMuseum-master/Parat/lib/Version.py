#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from time import sleep
from random import randint, choice
from ParatPrint import pprint, gray
from conf import config_file

__author__   = 'micle'
__version__  = '1.0'
__date__     = 'May 16 2017'

config  = config_file()
status  = config.get('cmd', 'colors').lower()
Colored = True if status == "on" else False

def print_version():
    pprint(" Parat - %s\n" % (__version__))

def show_info():
    pprint("""\
 Author:             www.micle.ir | @micle_fm | mhd.ceh8@gmail
 Bleeding edge:      https://github.com/micle-fm\n""")


def print_banner(banner = 0):

    config = config_file()

    status = config.get('cmd', 'colors').lower()
    Colored = True if status == "on" else False

    # banner = randint(1,3)


    b = r"""

         ,%%%%%%%%%%%%%%%%%,
        ,,,%%%%%%%%%%%%%%%,,,
       ,,,,,%%%%%%%%%%%%%,,,,,
      ,,,,,,,%%%%%%%%%%%,,,,,,,           @@@@@@@@@    @@@@      @@@@@@@@@      @@@@   @@@@@@@@@@
     ,,,,,,,,,%%%%%%%%%,,,,,,,,,          @@@   @@@   @@@@@@     @@@   @@@     @@@@@@      @@
    ,,,,,,,,,,,%%%%%%%,,,,,,,,,,,         @@@   @@@  @@@  @@@    @@@   @@@    @@@   @@     @@
   ,,,,,,,,,,,,,%%%%%,,,,,,,,,,,,,        @@@@@@@@@ @@@    @@@   @@@@@@@@@   @@@    @@@    @@
  %%%%%,,,,,,,,,,%%%,,,,,,,,,,%%%%%       @@@       @@@@@@@@@@   @@@ @@@     @@@@@@@@@@    @@
 %%%%%%%%%%%,,,,,,%,,,,,,,%%%%%%%%%%      @@@       @@@@@@@@@@   @@@   @@@   @@@@@@@@@@    @@
%%%%%%%%%%%%%%%%,,,,,%%%%%%%%%%%%%%%%     @@@       @@@    @@@   @@@    @@@  @@@    @@@    @@
%%%%%%%%%%%%%%%%%%,%%%%%%%%%%%%%%%%%%
%%%%%%%%%%%%%%%%%,,,%%%%%%%%%%%%%%%%%     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 %%%%%%%%%%%%%,,,,%,,,,%%%%%%%%%%%%%      [..]       Backdoor creator for Remote Access       [..]
  %%%%%%%%,,,,,,,%%%,,,,,,,%%%%%%%%       [..]                 Version : {}                  [..]
   %%%%,,,,,,,,,%%%%%,,,,,,,,,%%%%        [..]               Date : {}               [..]
    ,,,,,,,,,,,%%%%%%%,,,,,,,,,,,         [..]        Created by : Xxxxxxxx Xxxx Xxxxx        [..]
     ,,,,,,,,,%%%%%%%%%,,,,,,,,,          [..]          Username : micle (micle_fm)           [..]
      ,,,,,,,%%%%%%%%%%%,,,,,,,           [..]             Website : www.micle.ir             [..]
       ,,,,,%%%%%%%%%%%%%,,,,,            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         ,,%%%%%%%%%%%%%%%,,
          %%%%%%%%%%%%%%%%%

"""


    if banner == 0:
                           #\033[1;31m -> BOLD
        b = b.replace("%", "\033[31m%\033[0m").replace("@", "\033[36m@\033[0m").replace("~", "\033[32m~\033[0m"
        ).replace(" [", "\033[32m [\033[0m").replace(".]", ".\033[32m]\033[0m").format(__version__, __date__)

        for line in b.split("\n"):

            line += '\n'

            if Colored:
                pprint(line); sleep(0.03)
            else:
                pprint(gray(line)); sleep(0.03)



    elif banner == 1:

        b = b.format(__version__, __date__)
        clear = "\x1b[0m"
    	colors = [31, 32, 33, 34, 35, 36]

        for line in b.split("\n"):

            if Colored:
                pprint("\x1b[%dm%s%s\n" % (choice(colors), line, clear)); sleep(0.03)
            else:
                pprint(gray(line + "\n")); sleep(0.03)
