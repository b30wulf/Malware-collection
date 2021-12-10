import core.implant

class EnumUsersJob(core.job.Job):

    def report(self, handler, data, sanitize = False):
        pass

    def done(self):
        #self.display()
        pass

    def display(self):
        pass

class EnumUsersImplant(core.implant.Implant):

    NAME = "Enum Users"
    DESCRIPTION = "Enumerates user sessions on the target system."
    AUTHORS = ["zerosum0x0"]

    def load(self):
        pass

    def job(self):
        return EnumUsersJob

    def run(self):
        payloads = {}
        payloads["js"] = self.loader.load_script("data/implant/gather/enum_users.js", self.options)

        self.dispatch(payloads, self.job)
