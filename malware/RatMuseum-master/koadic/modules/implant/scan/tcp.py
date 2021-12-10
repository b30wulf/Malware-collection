import core.job
import core.implant

class ScanTCPJob(core.job.Job):
    def done(self):
        self.display()

    def print_port(self, lines):
        status = lines[0]
        ip = lines[1]
        port = lines[2]
        if lines[3] != "0":
            errno = (int(lines[3]) + 2**32)
        errno = "%08x" % (int(lines[3]) + 2**32 if lines[3] != "0" else 0x0)

        formats = 'Zombie %d: Job %d (%s) {0:<20}{1:<10}{2:<20}{3:<10}' % (self.session.id, self.id, self.name)

        #color = []
        color = [self.shell.colors.RED]

        if status == "open":
            color = [self.shell.colors.GREEN]
            printer = self.shell.print_good
        elif status == "closed":
            printer = self.shell.print_status
        elif status == "unsupported":
            printer = self.shell.print_warning
        else:
            printer = self.shell.print_error

        status = self.shell.colors.colorize(status, color)
        #port = self.shell.colors.colorize(port, color)
        msg = formats.format(ip, port, status, errno)
        self.results += msg + "\n"
        printer(msg)


    def report(self, handler, data):
        if data != b"done":
            lines = data.decode().split("\n")
            self.print_port(lines)
            handler.reply(200)
            return

        super(ScanTCPJob, self).report(handler, data)

    def display(self):
        pass
        #self.shell.print_plain("PID Start Code: %s" % self.data)

class ScanTCPImplant(core.implant.Implant):

    NAME = "Scan TCP"
    DESCRIPTION = "Looks for open TCP ports."
    AUTHORS = ["RiskSense, Inc."]

    def load(self):
        self.options.register("RHOSTS", "", "name/IP of the remotes")
        self.options.register("RPORTS", "22,80,135,139,443,445,3389", "ports to scan")
        self.options.register("TIMEOUT", "2", "longer is more accurate")
        self.options.register("CHECKLIVE", "true", "check if host is up before checking ports", enum=["true", "false"])

    def job(self):
        return ScanTCPJob

    def run(self):
        options = self.options.copy()
        hosts = self.parse_ips(options.get("RHOSTS"))
        ports = self.parse_ports(options.get("RPORTS"))

        options.set("RHOSTS", self.make_js_array("ips", hosts))
        options.set("RPORTS", self.make_js_array("ports", ports))

        payloads = {}
        #payloads["vbs"] = self.load_script("data/implant/scan/tcp.vbs", options)
        payloads["js"] = self.loader.load_script("data/implant/scan/tcp.js", options)
        #print(payloads["js"])


        self.dispatch(payloads, self.job)
