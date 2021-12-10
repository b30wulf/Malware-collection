
class prtMod:

    """this is description for parat module. all things will included later..."""

   def __init__(self):
       self.__pmname__ = "Module Name"
       self.__params__ = {"option": 'value'}

   def __str__(self):
       return self.__doc__

   def run(self):
	   parat_module_main(self.__params__)



def parat_module_main(prams):

    print("%s Ran Succesfully. Received Prameters Are:" % self.name)
    [print(str(Key) + " : " + str(prams[Key])) for Key in prams.keys()]
