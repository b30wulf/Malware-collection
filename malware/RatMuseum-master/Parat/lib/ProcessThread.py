#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import sys, random
from threading import Thread
from time import sleep
from lib.ParatPrint import colorize, pprint


class ParatProcessBar():

    Stop = False

    def __init__(self, message, key=None):

        self.message_text = message
        self.keyword = key



    def Run(self):

        print self.message_text + "...  ",
        sys.stdout.flush(); i = 0

        while not self.Stop:

            if (i%4) == 0: sys.stdout.write('\b/')
            elif (i%4) == 1: sys.stdout.write('\b-')
            elif (i%4) == 2: sys.stdout.write('\b\\')
            elif (i%4) == 3: sys.stdout.write('\b|')
            sys.stdout.flush(); sleep(0.2); i+=1

        print ""; self.Stop = False



    def start_process(self):

        self.Stop = False

        Process = Thread(target=self.Run)
        Process.start()

        if self.keyword is None:
            pass

        elif self.keyword == "#RELAXATION":
            sleep(random.random())
            self.Stop = True

        else:
            self.Stop = True
            error_message = "Invalid keyword for process bar: {}\n".format(self.keyword)
            pprint(colorize(error_message, status="ERR"), 1)
