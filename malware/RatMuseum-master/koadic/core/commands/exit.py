DESCRIPTION = "exits the program"

def autocomplete(shell, line, text, state):
    return None

def help(shell):
    pass

def execute(shell, cmd):
    import sys
    sys.exit(0)
