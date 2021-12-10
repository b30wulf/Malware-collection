plugin_name = r"""getWebcamName"""
plugin_description = r"""Get Name of Webcamera"""
plugin_type = r"""remote"""

plugin_source = """
try:
    import vidcap
    cam = vidcap.new_Dev(0, 0)
    mprint = str(cam.getdisplayname())
    del cam
except:
    mprint = 'NoDevice'
"""
