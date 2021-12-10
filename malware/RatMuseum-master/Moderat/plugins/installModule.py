plugin_name = r"""installModule"""
plugin_description = r"""install python module from url"""
r_source = r"""

### SETTINGS ###
whl_url = r'<whl url>'
### SETTINGS ###

import urllib
import sys
import os
import zipfile

site_packages = os.path.join(os.path.dirname(sys.argv[0]), 'packages')
if not os.path.exists(site_packages):
    os.makedirs(site_packages)
open(os.path.join(site_packages, '__init__.py'), 'w').close()
sys.path.insert(1, site_packages)
try:
    urllib.urlretrieve(whl_url, "pip.whl")
    fh = open('pip.whl', 'rb')
    z = zipfile.ZipFile(fh)
    for name in z.namelist():
        z.extract(name, site_packages)
    fh.close()
except Exception as e:
    mprint = e

sys.path.insert(1, os.path.join(os.path.dirname(sys.argv[0]), 'packages'))"""
