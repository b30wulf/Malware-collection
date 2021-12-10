import core.stager

class RunDLL32JSStager(core.stager.Stager):

    NAME = "JScript rundll32.exe JavaScript Stager"
    DESCRIPTION = "Listens for new sessions, using JavaScript for payloads"
    AUTHORS = ['zerosum0x0']

    WORKLOAD = "js"

    def load(self):
        #self.options.set("SRVPORT", 9997)
        self.port = 9997

        self.stagetemplate = b"~SCRIPT~"
        self.stagecmd = self.loader.load_script("data/stager/js/rundll32_js/rundll32_js.cmd")
        self.forktemplate = self.stagetemplate
        self.forkcmd = self.stagecmd
