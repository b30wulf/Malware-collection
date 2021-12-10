#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

class ParatModule(BaseException):

   def __init__(self, msg):

       self.msg = msg



class ParatDataType(BaseException):

   def __init__(self, msg):

       self.msg = msg



class ParatModuleExit(BaseException):

   def __init__(self, msg):

       self.msg = msg



class Network(BaseException):

   def __init__(self, msg):

       self.msg = msg



class Generate(Exception):

   def __init__(self, msg):

       self.msg = msg
