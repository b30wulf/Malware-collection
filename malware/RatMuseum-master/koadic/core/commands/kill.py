DESCRIPTION = "kill a job or all jobs"

def autocomplete(shell, line, text, state):
    pass

def help(shell):
    shell.print_plain("")
    shell.print_plain("Usage: kill [#/ALL]")
    shell.print_plain("")

def kill_zombie(shell, id):
    formats = "\t{0:<5}{1:<10}{2:<20}{3:<40}"

    for stager in shell.stagers:
        for session in stager.sessions:
            if id.lower() == "all":
                session.kill()
                continue

            if session.id == int(id):
                session.kill()

    shell.play_sound('KILL')
    shell.print_good("Zombie %s: Killed!" % id)

def execute(shell, cmd):

    splitted = cmd.split()

    if len(splitted) > 1:
        id = splitted[1]
        kill_zombie(shell, id)
        return

    help(shell)
