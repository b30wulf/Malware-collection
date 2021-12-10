import socket, time, threading, functools, http.server, socketserver

class FileServer(threading.Thread):
    def __init__(self, port: int):
        # Todo: Disable logs and put them to file, if anyone know how to make that, make pull requests^^
        self.handler = functools.partial(http.server.SimpleHTTPRequestHandler, directory= '../bin/')
        threading.Thread.__init__(self)
        self.port = port
   
    def run(self):
        with socketserver.TCPServer(('0.0.0.0', self.port), self.handler) as httpd:
            httpd.serve_forever()

class Port:
    def __init__(self):
        pass

    def check_open(self, ip: str, port: int, timeout: float= 1):
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.settimeout(timeout)
        
        try:
            sock.connect((ip, port))
            return True
        except:
            return False

class Network:
    def __init__(self, socket_session: socket.socket, database=None):
        self.sock = socket_session
        self.db = database

    def __add_packet(self, n: int, upload: bool, len: int):
        if self.db != None:
            if upload == True:
                self.db.send_len += len
                self.db.send_packet += n
            else:
                self.db.recv_len += len
                self.db.recv_packet += n

    def close_socket(self):
        self.sock.close()

    def send_packet(self, content: str):
        try:
            self.sock.send(content.encode('utf-8'))
            self.__add_packet(1, True, len(content))
            return True
        except:
            return False
    
    def bulk_send_packet(self, packets: list):
        res = None
        _len = 0

        for packet in packets:
            packet = f'\r\033[0m{packet}\n'
            
            _len += len(packet)
            res = self.send_packet(packet)
        
        self.__add_packet(len(packets), True, _len)
        return res
    
    def raw_packet_list_send(self, packets):
        res = None
        _len = 0
        for packet in packets:
            res = self.send_packet(packet)
            _len += len(packet)
            time.sleep(0.5)
        
        self.__add_packet(len(packets), True, _len)
        return res

    def recv_packet(self):
        # Optimization ?
        data = None
        while True:
            try:
                data = self.sock.recv(1024).decode('utf-8').strip().split('\n')[0]
                if data not in ['\n', '', 'None', None, False, 'False']:
                    break

            except Exception:
                data = False
                break

        try: # because "TypeError: object of type 'bool' has no len()" when killing client
            self.__add_packet(1, False, len(data))
        except:
            pass
        return data