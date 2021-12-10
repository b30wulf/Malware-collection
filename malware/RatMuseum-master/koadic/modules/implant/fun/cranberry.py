import core.job
import core.implant
import uuid

class ThunderstruckJob(core.job.Job):
    def done(self):
        self.display()

    def display(self):
        self.results = "Completed"
        self.shell.print_plain(self.data)

class CranberryImplant(core.implant.Implant):

    NAME = "Thunderstruck"
    DESCRIPTION = "Opens hidden IE to the Thunderstruck YouTube video"
    AUTHORS = ["zerosum0x0"]

    def load(self):
        self.options.register("VIDEOURL", "https://www.youtube.com/watch?v=6Ejga4kJUts", "video to play")

    def run(self):

        payloads = {}
        payloads["vbs"] = self.loader.load_script("data/implant/fun/thunderstruck.vbs", self.options)
        #payloads["js"] = self.load_script("data/implant/manage/exec_cmd.js", self.options)

        self.dispatch(payloads, ThunderstruckJob)
