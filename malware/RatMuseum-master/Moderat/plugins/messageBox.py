plugin_name = r"""messageBox"""
plugin_description = r"""messageBox"""
plugin_type = r"""remote"""
plugin_source = r"""

# coding=utf-8
TITLE = 'Title'
MESSAGE = 'Message'

MB_OK = 0x0
MB_OKCXL = 0x01
MB_YESNOCXL = 0x03
MB_YESNO = 0x04
MB_HELP = 0x4000
ICON_EXLAIM=0x30
ICON_INFO = 0x40
ICON_STOP = 0x10
MB_SYSTEMMODAL = 0x1000

result = ctypes.windll.user32.MessageBoxA(0, MESSAGE, TITLE, ICON_EXLAIM | MB_OK | MB_SYSTEMMODAL)
"""