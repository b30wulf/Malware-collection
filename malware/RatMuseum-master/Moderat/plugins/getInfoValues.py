plugin_name = r"""getInfoValues"""
plugin_description = r"""Get Info About Client Info.nfo file"""
plugin_type = r"""remote"""
plugin_source = r"""

values = open(os.path.join(os.path.dirname(sys.argv[0]), 'info.nfo'), 'r').read()

val = ast.literal_eval(values)

mprint = '''
# KEYLOGGER<br>
keylogger status = %s<br>
keylogger upload timer = %s<br>
<br># AUDIO RECORDING<br>
audio status = %s<br>
audio timer = %s<br>
<br># SCREENSHOTS<br>
screenshot status = %s<br>
screenshot upload timer = %s<br>
screenshot delay = %s<br>
<br># USB SPREADING<br>
usb spreading = %s<br>
''' % (val['kts'],
	val['kt'],
	val['ats'],
	val['at'],
	val['sts'],
	val['st'],
	val['std'],
	val['usp'],)
"""