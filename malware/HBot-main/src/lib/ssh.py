import paramiko

class Ssh:
    def __init__(self, ip: str, port: int, username: str, password: str):
        self.password = password
        self.username = username
        self.port = port
        self.ip = ip
    
        self.session = paramiko.SSHClient()
        self.session.set_missing_host_key_policy(paramiko.AutoAddPolicy())

    def run_command(self, command: str):
        try:
            stdin, stdout, stderr = self.session.exec_command(command)
            return str(stdout.read()).split('b\'')[1]
        except:
            return False

    def disconnect(self):
        self.session.close()

    def connect(self, timeout= 5, callback: bool= False):
        try:
            self.session.connect(self.ip, port = self.port, username= self.username, password= self.password, timeout= timeout)
            return True
        except Exception as e:
            if callback:
                if 'Authentication failed' not in str(e):
                    return 'error'

            return False