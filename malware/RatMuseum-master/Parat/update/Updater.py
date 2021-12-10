#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from os.path import abspath, join, dirname
from lib.ParatPrint import colorize, pprint
from lib.Version import __version__
from ConfigParser import ConfigParser
import urllib2


def check_update():

    root_path = abspath(join(dirname(__file__)))

    parser = ConfigParser()
    path_to_config = join(root_path, "..", "conf", "config.ini")

    with open(path_to_config, 'r') as config:
        parser.readfp(config)
    config.close()

    color_mode  = parser.get('cmd', 'colors').lower()
    colored     = True if color_mode == "on" else False

    try:

        updateurl = 'https://raw.githubusercontent.com/micle-fm/Parat/master/conf/parat.version'
        request = urllib2.urlopen(updateurl)
        parat_version = str(request.read()).strip()
        request.close()

    except:

        path_to_version_file = join(root_path, "..", "conf", "parat.version")
        with open(path_to_version_file, 'r') as ver_file:
            parat_version = ver_file.read().strip()
        ver_file.close()

    if parat_version != __version__:
        pprint(
            colorize(
                    "Your templates are not synced with your parat version!" + \
                    "\n\t Update your parat using 'git clone https://github.com/micle-fm/parat' command.\n",
                    colored=colored,
                    status="WAR"
                ))
