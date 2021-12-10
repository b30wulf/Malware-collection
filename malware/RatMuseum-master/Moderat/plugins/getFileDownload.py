plugin_name = r"""getFileDownload"""
plugin_description = r"""HTTP File Downloader"""
plugin_type = r"""remote"""
plugin_source = r"""

url = 'URLHERE'
filename = 'FILENAME'
urllib.urlretrieve(url, filename)
"""
