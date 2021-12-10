DESCRIPTION = "shows info about stagers"

def autocomplete(shell, line, text, state):
    pass

def help(shell):
    pass

def print_all_payloads(shell):
    if len(shell.stagers) == 0 or len([s for s in shell.stagers if not s.killed]) == 0:
        shell.print_error("No payloads yet.")
        return

    shell.print_plain("")

    formats = "\t{0:<5}{1:<16}{2:<8}{3:<20}"

    shell.print_plain(formats.format("ID", "IP", "PORT", "TYPE"))
    shell.print_plain(formats.format("----", "---------", "-----", "-------"))

    for stager in shell.stagers:
        if not stager.killed:
            payload = stager.get_payload().decode()
            shell.print_plain(formats.format(stager.payload_id, stager.hostname, stager.port, stager.module))

    shell.print_plain("")
    shell.print_plain('Use "listeners %s" to print a payload' % shell.colors.colorize("ID", [shell.colors.BOLD]))
    shell.print_plain('Use "listeners -k %s" to kill a payload' % shell.colors.colorize("ID", [shell.colors.BOLD]))
    shell.print_plain("")

def print_payload(shell, id):
    for stager in shell.stagers:
        if str(stager.payload_id) == id and not stager.killed:
            payload = stager.get_payload().decode()

            #shell.print_good("%s" % stager.options.get("URL"))
            shell.print_command("%s" % payload)

            #shell.print_plain("")
            return

    shell.print_error("No payload %s." % id)

def kill_listener(shell, id):
    for stager in shell.stagers:
        if str(stager.payload_id) == id and not stager.killed:
            if len(stager.sessions) > 0 and len([z for z in stager.sessions if not z.killed]) > 0:

                shell.print_warning("Warning: This listener still has live zombies attached:")
                shell.print_plain("   Zombie IDs: " + ", ".join([str(s.id) for s in stager.sessions]))
                shell.print_warning("If this listener dies, then they will die.")

                try:
                    import readline
                    old_prompt = shell.prompt
                    old_clean_prompt = shell.clean_prompt
                    readline.set_completer(None)
                    shell.prompt = "Continue? y/N: "
                    shell.clean_prompt = shell.prompt
                    option = shell.get_command(shell.prompt)

                    if option.lower() == 'y':
                        for session in stager.sessions:
                            # they die anyways, they shouldn't have to suffer
                            session.kill()

                        # this should work without problems right?
                        stager.http.shutdown()
                        stager.http.socket.close()
                        stager.http.server_close()
                        stager.killed = True

                        shell.print_good("Listener %s killed!" % id)
                        return
                    else:
                        return

                except KeyboardInterrupt:
                    shell.print_plain(shell.clean_prompt)
                    return
                finally:
                    shell.prompt = old_prompt
                    shell.clean_prompt = old_clean_prompt

            else:
                # this should work without problems right?
                stager.http.shutdown()
                stager.http.socket.close()
                stager.http.server_close()
                stager.killed = True

                shell.print_good("Listener %s killed!" % id)
                return

    shell.print_error("No payload %s." % id)


def execute(shell, cmd):

    splitted = cmd.split()

    if len(splitted) > 1:
        id = splitted[-1]
        if len(splitted) > 2:
            flag = splitted[1]
            if flag == "-k":
                kill_listener(shell, id)
                return
            else:
                shell.print_error("Unknown option '%s'" % flag)
                return
        else:
            print_payload(shell, id)
            return

    print_all_payloads(shell)
