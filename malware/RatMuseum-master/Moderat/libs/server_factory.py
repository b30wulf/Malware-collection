import string
import random
import ast

from twisted.internet.protocol import ServerFactory
from twisted.protocols.basic import LineReceiver


def id_generator(size=12, chars=string.ascii_uppercase + string.digits):
    return ''.join(random.choice(chars) for _ in range(size))


class ModeratServerProtocol(LineReceiver):

    delimiter = '[ENDOFMESSAGE]'
    MAX_LENGTH = 1024 * 1024 * 10  # 10MB

    # New Connection Made
    def connectionMade(self):
        self.factory.send_msg(self, 'connectSuccess', 'connectSuccess')

    def connectionLost(self, reason):
        # Delete Socket Entry
        for key, value in self.factory.moderat.directClients.items():
            if value['socket'] == self:
                del self.factory.moderat.directClients[key]
                self.factory.moderat.update_direct_table()

    def lineLengthExceeded(self, line):
        pass

    def lineReceived(self, line):
        try:
            data = ast.literal_eval(line)
        except SyntaxError:
            return

        if data['mode'] == 'clientInitializing':

            info = ast.literal_eval(data['payload'])

            self.factory.moderat.directClients[info['i']] = {
                'socket': self,
                'ip_address': self.transport.getPeer().host,
                'os_type': info['os_type'],
                'os': info['os'],
                'mark': info['mark']
            }

            self.factory.moderat.update_direct_table()
            self.factory.new_client(self.transport.getPeer().host)
        else:
            self.factory.received_msg(data)


class ModeratServerFactory(ServerFactory):
    protocol = ModeratServerProtocol

    def __init__(self, moderat):
        self.moderat = moderat
        self.moderat.directClients = {}

    def send_msg(self, client, message, mode, session_id='', module_id='',
                               end='[ENDOFMESSAGE]'):
        if self.moderat.directClients.has_key(client):
            self.moderat.directClients[client]['socket'].transport.write(str({
                'payload': message,
                'mode': mode,
                'from': 'server',
                'session_id': session_id,
                'module_id': module_id,
            }) + end)

    def received_msg(self, data):
        self.moderat.on_moderator_receive(data)

    def new_client(self, client_ip_address):
        self.moderat.tray.info(self.moderat.MString('TRAY_NEW_CLIENT'), client_ip_address)
