#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

import os
import ConfigParser
from lib.ParatPrint import colorize


def config_file(*args):

    try:

        if len(args) == 1: # get_path
            return os.path.abspath(os.path.join('conf', 'config.ini'))

        elif len(args) == 0:
            config = ConfigParser.ConfigParser()
            config.read(os.path.join('conf', 'config.ini'))
            return config

        else:
            raise Exception("Too much arguments expected.")

    except Exception as e:
        exit(colorize(str(e), colored=True, status="ERR"))


def path_to_db():

    conf = config_file()
    db_name = os.path.join('conf', conf.get('base', 'db_name'))
    return db_name








# ['OPTCRE',
#  'OPTCRE_NV',
#  'SECTCRE',
#  '_KEYCRE',
#  '__doc__',
#  '__init__',
#  '__module__',
#  '_boolean_states',
#  '_defaults',
#  '_dict',
#  '_get',
#  '_interpolate',
#  '_interpolation_replace',
#  '_optcre',
#  '_read',
#  '_sections',
#  'add_section',
#  'defaults',
#  'get',
#  'getboolean',
#  'getfloat',
#  'getint',
#  'has_option',
#  'has_section',
#  'items',
#  'options',
#  'optionxform',
#  'read',
#  'readfp',
#  'remove_option',
#  'remove_section',
#  'sections',
#  'set',
#  'write']
