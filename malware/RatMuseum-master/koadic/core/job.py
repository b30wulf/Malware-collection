from core.mappings import mappings
import string
import threading
import uuid


class Job(object):
    CREATED = 0
    RECEIVED = 2
    RUNNING = 3
    COMPLETE = 4
    FAILED = 5

    JOB_ID = 0
    JOB_ID_LOCK = threading.Lock()

    def __init__(self, shell, session_id, name, script, options):
        self.fork32Bit = False
        self.completed = Job.CREATED
        self.script = script
        self.shell = shell
        self.options = options
        self.session_id = session_id
        self.name = name
        self.errno = ""
        self.data = b""
        self.unsafe_data = b""
        self.key = uuid.uuid4().hex
        self.results = ""
        self.ip = ""

        if self.session_id != -1:
            self.session = [session for stager in self.shell.stagers for session in stager.sessions if session.id == self.session_id][0]
            self.ip = self.session.ip
            self.computer = self.session.computer

        with Job.JOB_ID_LOCK:
            self.id = Job.JOB_ID
            Job.JOB_ID += 1

        if self.create() != False:
            self.create = True
            self.shell.print_status("Zombie %d: Job %d (%s) created." % (
                self.session_id, self.id, self.name))
        else:
            self.create = False

    def create(self):
        pass

    def receive(self):
        #self.shell.print_status("Zombie %d: Job %d (%s) received." % (self.session.id, self.id, self.name))
        self.completed = Job.RECEIVED

    def payload(self):
        #self.shell.print_status("Zombie %d: Job %d (%s) running." % (self.session.id, self.id, self.name))
        self.completed = Job.RUNNING
        return self.script

    def error(self, errno, errdesc, errname, data):
        self.errno = str(errno)
        self.errdesc = errdesc
        self.errname = errname
        self.completed = Job.FAILED
        self.sanitize_data(data)

        self.print_error()

    def print_error(self):
        self.shell.play_sound('FAIL')
        self.shell.print_error("Zombie %d: Job %d (%s) failed!" % (
            self.session_id, self.id, self.name))
        self.shell.print_error("%s (%08x): %s " % (
            self.errname, int(self.errno) + 2**32, self.errdesc))

    def sanitize_data(self, data):
        # clean up unprintable characters from data
        self.data = b""
        for i in range(0, len(data)):
            try:
                if data[i:i + 1].decode() in string.printable:
                    self.data += data[i:i + 1]
            except:
                pass
        self.data = self.data.decode()

        #self.data = "".join(i for i in data.decode() if i in string.printable)

    def report(self, handler, data, sanitize=True):
        #self.errno = str(errno)
        self.completed = Job.COMPLETE

        self.unsafe_data = data

        if (sanitize):
            self.sanitize_data(data)
        else:
            self.data = ""

        if handler:
            handler.reply(202)

        self.shell.play_sound('SUCCESS')
        self.shell.print_good("Zombie %d: Job %d (%s) completed." % (
            self.session_id, self.id, self.name))

        self.done()

    def status_string(self):
        if self.completed == Job.COMPLETE:
            return "Complete"
        if self.completed == Job.CREATED:
            return "Created"
        if self.completed == Job.RECEIVED:
            return "Received"
        if self.completed == Job.RUNNING:
            return "Running"
        if self.completed == Job.FAILED:
            return "Failed"

    def done(self):
        pass

    def display(self):
        pass

    def print_status(self, message):
        self.shell.print_status("Zombie %d: Job %d (%s) %s" % (
            self.session_id, self.id, self.name, message))

    def print_good(self, message):
        self.shell.print_good("Zombie %d: Job %d (%s) %s" % (
            self.session_id, self.id, self.name, message))

    def print_warning(self, message):
        self.shell.print_warning("Zombie %d: Job %d (%s) %s" % (
            self.session_id, self.id, self.name, message))


    def decode_downloaded_data(self, data, encoder, text=False):
        slash_char = chr(92).encode()
        zero_char = chr(0x30).encode()
        null_char = chr(0).encode()
        mapping = mappings

        b_list = []
        escape_flag = False
        special_char = {
            '0': null_char,
            '\\': slash_char
        }

        append = b_list.append

        for i in data.decode('utf-8'):
            # Decide on slash char
            if escape_flag:
                escape_flag = False
                append(special_char[i])
                continue

            if i == '\\' and not text:
                # EAT the slash
                escape_flag = True
            else:
                # collisions will go here
                if i == '€' and encoder == "1251":
                    append(b'\x88')
                    continue

                try:
                    append(mapping[ord(i)])
                except:
                    print("ENCODING ERROR: "+str(ord(i))+" <- Please add a mapping to core/mappings.py with \"chr("+str(ord(i))+").encode('windows-"+encoder+"')\"")

        return b"".join(b_list)
