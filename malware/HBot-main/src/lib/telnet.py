import xtelnet

class Telnet:
    def __init__(self, ip: str, port: int, username: str, password: str):
        self.password = password
        self.username = username
        self.port = port
        self.ip = ip
    
        self.session = xtelnet.session()

    def run_command(self, command: str):
        try:
            return self.session.execute(command)
        except:
            return False

    def prompt(self):
        return self.session.prompt

    def disconnect(self):
        self.session.close()

    def connect(self, timeout= 5, callback= False):
        try:
            self.session.connect(self.ip, self.username, self.password, self.port, timeout)
            r = self.session.execute('ls')
            print(f'ls: {r}')
            return True
        except Exception as e:
            if callback:
                if 'Authentication Failed' not in str(e):
                    return 'error'
                else:
                    False
            else:
                False