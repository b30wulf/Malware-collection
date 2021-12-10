#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from lib.ParatPrint import pprint

suffix = "\x26" # &


def Encode(str):

    try:
        str = unicode(str, errors='ignore')
        cipher = ""
        for i in range(len(str)):
            cipher += chr(ord(str[i])^(ord("P")))
        cipher = cipher.encode('rot13').encode('hex')
        return cipher + suffix

    except Exception as e:
        pprint(str(e), 1)


def Decode(hex):

    try:
        hex = unicode(hex, errors='ignore')
        plain = ""
        cipher = hex.decode('hex').decode('rot13')
        for i in range(len(cipher)):
            plain += chr(ord(cipher[i])^(ord("P")))
        return plain

    except Exception as e:
        pprint(str(e), 1)
