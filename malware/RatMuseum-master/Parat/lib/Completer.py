#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#
import glob
import readline


class auto_complete(object):  # Custom completer

    def __init__(self, options):
        self.options = sorted(options)

    def complete(self, text, state):

        if state == 0:  # on first trigger, build possible matches

            if text:  # cache matches (entries that start with entered text)

                try:

                    line = readline.get_line_buffer().split()
                    self.matches = [s for s in self.options if s and s.startswith(text)]
                    [self.matches.append(x) for x in glob.glob(text + '*')]

                except Exception as e:
                    pass#    print(e)


            else:  # no text entered, all matches possible
                self.matches = self.options[:]

        # return match indexed by state
        try:
            return self.matches[state] + " "

        except IndexError:
            return None
