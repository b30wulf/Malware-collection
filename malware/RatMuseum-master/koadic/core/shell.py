import os
import sys
import traceback
import threading

import core.loader
import core.colors
import core.job
import core.extant

''' Cmd is just a bad wrapper around readline with buggy input '''
class Shell(object):

    def __init__(self, banner, version):
        self.banner = banner
        self.version = version
        self.actions = core.loader.load_plugins("core/commands")
        self.plugins = core.loader.load_plugins("modules", True, self)
        self.stagers = []
        self.jobs = []
        self.state = "stager/js/mshta"
        self.colors = core.colors.Colors()
        self.extant = core.extant.Extant(self)
        self.verbose = False
        self.creds = {}
        self.creds_keys = []
        self.domain_info = {}
        self.sounds = {}
        self.rest_thread = ""
        self.continuesession = ""

    def run(self, autorun = []):
        self.main_thread_id = threading.current_thread().ident

        self.print_banner()

        while True:
            try:
                self.prompt = self.colors.get_prompt(self.state, True)
                self.clean_prompt = self.colors.get_prompt(self.state, False)

                cmd = ""
                while len(autorun) > 0:
                    cmd = autorun.pop(0).split("#")[0].strip()
                    if len(cmd) > 0:
                        break

                if len(cmd) == 0:
                    cmd = self.get_command(self.prompt, self.autocomplete)
                else:
                    print(self.clean_prompt + cmd)

                self.run_command(cmd)

            except KeyboardInterrupt:
                self.confirm_exit()
            except EOFError:
                self.run_command("exit")
            except Exception:
                self.print_plain(traceback.format_exc())

    def confirm_exit(self):
        sys.stdout.write(os.linesep)
        try:
            res = "n"
            res = self.get_command("Exit? y/N: ")
        except:
            sys.stdout.write(os.linesep)

        if res.strip().lower() == "y":
            self.run_command("exit")

    def run_command(self, cmd):
        if not cmd:
            return
        action = cmd.split()[0].lower()
        remap = {
            "?": "help",
            "exploit": "run",
            "execute": "run",
            "options": "info",
            "quit": "exit",
            "sessions": "zombies",
        }
        if action in self.actions:
            self.actions[action].execute(self, cmd)
        elif action in remap:
            cmd.replace(action, remap[action])
            self.actions[remap[action]].execute(self, cmd)
        else:
            try:
                self.print_error("Unrecognized command, you need 'help'.")

                #
                # bash lol:
                #os.system(cmd)
            except:
                pass

    def get_command(self, prompt, auto_complete_fn=None):
        try:
            if auto_complete_fn != None:
                import readline
                readline.set_completer_delims(' \t\n;')
                readline.parse_and_bind("tab: complete")
                readline.set_completer(auto_complete_fn)
        except:
            pass

        # python3 changes raw input name
        if sys.version_info[0] == 3:
            raw_input = input
        else:
            raw_input = __builtins__['raw_input']

        cmd = raw_input("%s" % prompt)
        return cmd.strip()

    def autocomplete(self, text, state):
        import readline
        line = readline.get_line_buffer()
        splitted = line.lstrip().split(" ")

        # if there is a space, delegate to the commands autocompleter
        if len(splitted) > 1:
            if splitted[0] in self.actions:
                if splitted[0] == "set" and splitted[1] == "MODULE" and len(splitted) < 4:
                    return self.actions["use"].autocomplete(self, line, text, state)
                return self.actions[splitted[0]].autocomplete(self, line, text, state)
            else:
                return None

        # no space, autocomplete will be the basic commands:
        options = [x + " " for x in self.actions if x.startswith(text)]
        try:
            return options[state]
        except:
            return None

    def print_banner(self):
        os.system("clear")

        implant_len = len([a for a in self.plugins
                           if a.startswith("implant")])
        stager_len = len([a for a in self.plugins
                          if a.startswith("stager")])
        print(self.banner % (self.version, stager_len, implant_len))

    def print_plain(self, text, redraw = False):
        sys.stdout.write("\033[1K\r" + text + os.linesep)
        sys.stdout.flush()

        if redraw or threading.current_thread().ident != self.main_thread_id:
            import readline
            #sys.stdout.write("\033[s")
            sys.stdout.write(self.clean_prompt + readline.get_line_buffer())
            #sys.stdout.write("\033[u\033[B")
            sys.stdout.flush()

    def print_text(self, sig, text, redraw = False):
        self.print_plain(sig + " " + text, redraw)

    def print_good(self, text, redraw = False):
        self.print_text(self.colors.good("[+]"), text, redraw)

    def print_warning(self, text, redraw = False):
        self.print_text(self.colors.warning("[!]"), text, redraw)

    def print_error(self, text, redraw = False):
        self.print_text(self.colors.error("[-]"), text, redraw)

    def print_status(self, text, redraw = False):
        self.print_text(self.colors.status("[*]"), text, redraw)

    def print_verbose(self, text, redraw = False):
        if self.verbose:
            self.print_text(self.colors.colorize("[v]", [self.colors.BOLD]), text, redraw)

    def print_help(self, text, redraw = False):
        self.print_text(self.colors.colorize("[?]", [self.colors.BOLD]), text, redraw)

    def print_command(self, text, redraw = False):
        self.print_text(self.colors.colorize("[>]", [self.colors.BOLD]), text, redraw)

    def print_hash(self, text, redraw = False):
        self.print_text(self.colors.colorize("[#]", [self.colors.BOLD]), text, redraw)

    def play_sound(self, enum):
        if enum in self.sounds:
            sound = self.sounds[enum]
            if type(sound) is list:
                import random
                sound = random.choice(sound)

            threading.Thread(target=self.play_audio_file, args=[sound]).start()

    def play_audio_file(self, audio_file):
        from playsound import playsound
        try:
            playsound(audio_file)
        except:
            if not os.path.isfile(audio_file):
                self.print_error('Could not play sound file %s. Check if path to file is correct.' % audio_file)
