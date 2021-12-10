plugin_name = r"""getPcInfo"""
plugin_description = r"""Get Info About Client PC"""
plugin_type = r"""remote"""
plugin_source = r"""

mprint = os.popen('systeminfo').read()

"""
